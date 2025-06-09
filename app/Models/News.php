<?php

namespace App\Models;

use App\Traits\AutoResourceCodeGeneration;
use App\Traits\HasExcerptGeneration;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory, Sluggable, AutoResourceCodeGeneration, HasExcerptGeneration;

    protected $fillable = [
        "news_code", "title", "thumbnail", "content",
        "excerpt", "status", "user_id", "published_at",
        "archived_at"
    ];

    protected $casts = [
        "published_at" => "datetime",
        "archived_at" => "datetime"
    ];

    public function newsCategory() {
        return $this->belongsToMany(NewsCategory::class, 'news_category_pivots');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function sluggable(): array
    {
        return [
            "slug" => [
                "source" => "title",
                "unique" => true,
                'onUpdate' => true
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public function getResourceCodeConfig(): array {
        return [
            "column" => "news_code",
            "prefix" => "NWS"
        ];
    }
}
