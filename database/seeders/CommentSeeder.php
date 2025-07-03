<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parentComments = Comment::factory(10)->create();

        foreach ($parentComments as $comment) {
            Comment::factory()->count(rand(1,2))
            ->create([
                "news_id" => $comment->news_id,
                "user_id" => $comment->user_id,
                "parent_id" => $comment->id
            ]);
        }
    }
}
