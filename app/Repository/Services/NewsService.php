<?php 

namespace App\Repository\Services;

use App\Models\News;
use App\Helpers\CodeGeneration;
use Illuminate\Support\Facades\Storage;
use App\Repository\Interfaces\NewsInterface;
use Cviebrock\EloquentSluggable\Services\SlugService;

class NewsService implements NewsInterface
{
    public function getAll()
    {
        $news = News::with('user', 'categories')->get();
        return response()->json([
            'success' => true,
            'data' => $news
        ], 200);
    }

    public function getById($id)
    {
        $news = News::with('user', 'categories')->findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $news
        ], 200);
    }

    public function create(array $data)
    {
        $codeGeneration = new CodeGeneration(News::class, "news_code", "NWS");
        $imagePath = $data['image'] instanceof \Illuminate\Http\UploadedFile ? $data['image']->store('news_images', 'public') : null;

        $data['news_code'] = $codeGeneration->getGeneratedCode();
        $data['slug'] = SlugService::createSlug(News::class, 'slug', $data['title']);
        $data['image'] = $imagePath;

        $news = News::create($data);

        if (!empty($data['categories'])) {
            $news->categories()->attach($data['categories']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berita berhasil ditambahkan',
            'data' => $news->load('categories')
        ], 201);
    }

    public function update(array $data, $id)
    {
        $news = News::findOrFail($id);

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }

            $data['image'] = $data['image']->store('news_images', 'public');
        } else {
            unset($data['image']);
        }

        if (isset($data['title']) && $data['title'] !== $news->title) {
            $data['slug'] = SlugService::createSlug(News::class, 'slug', $data['title']);
        }

        $news->update($data);

        if (!empty($data['categories'])) {
            $news->categories()->sync($data['categories']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berita berhasil diedit',
            'data' => $news->load('categories')
        ], 201);
    }

    public function delete($id)
    {
        $news = News::findOrFail($id);

        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }

        $news->categories()->detach();
        $news->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berita berhasil dihapus'
        ], 201);
    }
}