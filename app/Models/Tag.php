<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'news_id'
    ];
    public function news()
    {
        return $this->belongsTo(News::class);
    }
}
