<?php

namespace App\Models;

use App\Traits\AutoResourceCodeGeneration;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsCategory extends Model
{
    use HasFactory, Sluggable, AutoResourceCodeGeneration;

    protected $fillable = [
        "news_category_code",
        "name",
    ];

    public function news() {
        return $this->belongsToMany(News::class, 'news_category_pivots');
    }

    /**
     * @inheritDoc
     */
    public function sluggable(): array {
        return [
            "slug" => [
                "source" => "name"
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public function getResourceCodeConfig(): array {
        return [
            "column" => "news_category_code",
            "prefix" => "NCAT"
        ];
    }

}
