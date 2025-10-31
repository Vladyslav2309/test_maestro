<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class News extends Model implements HasMedia
{


    use InteractsWithMedia;
    use HasSlug;
    use HasTranslations;


    protected $fillable = [
        'title',
        'slug',
        'content',
        'is_status',
        'created_at'
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('webp')
            ->format('webp')->nonQueued();
    }

    public function getImageAttribute(): ?string
    {
        return $this->getFirstMediaUrl('image', 'webp');
    }


    public function getSlugOptions(): SlugOptions
    {
        $slugOptions = SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate()
            ->usingLanguage('uk');
        return $slugOptions;
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }
    public function validateTags(array $tags): array
    {
        $duplicates = [];

        foreach ($tags as $tag) {
            if (Tag::where('name', $tag['name'])->exists()) {
                $duplicates[] = $tag['name'];
            }
        }

        return $duplicates;
    }

}
