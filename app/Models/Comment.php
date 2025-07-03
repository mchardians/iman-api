<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ["news_id", "user_id", "content", "parent_id"];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function news() {
        return $this->belongsTo(News::class);
    }

    public function reply() {
        return $this->hasMany(Comment::class, 'parent_id')->with('user');
    }

    public function parent() {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
}
