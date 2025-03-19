<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsCategoryPivot extends Model
{
    use HasFactory;

    protected $table = 'news_category';

    protected $fillable = [
        'news_id',
        'news_category_id'
    ];
}
