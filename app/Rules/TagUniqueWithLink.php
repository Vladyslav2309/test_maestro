<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Tag;

class TagUniqueWithLink implements Rule
{
    protected ?string $newsLink = null;

    public function __construct(protected ?int $currentTagId = null)
    {
    }

    public function passes($attribute, $value)
    {
        $tag = Tag::where('name', $value)
            ->when($this->currentTagId, fn($q) => $q->where('id', '!=', $this->currentTagId))
            ->first();

        if ($tag && $tag->news) {
            $this->newsLink = route('filament.admin.resources.news.edit', $tag->news);
            return false;
        }

        return true;
    }

    public function message()
    {
        if ($this->newsLink) {
            return "Тег вже існує в іншій новині: {$this->newsLink}";
        }

        return "Тег вже існує в іншій новині.";
    }
}
