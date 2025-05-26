<?php

namespace App\Repository\Services;

use App\Libraries\CodeGeneration;
use App\Models\NewsCategory;
use App\Repository\Interfaces\NewsCategoryInterface;

class NewsCategoryService implements NewsCategoryInterface
{
    public function getAll() {
        $newsCategories = NewsCategory::all();
        return response()->json([
            'success' => true,
            'data' => $newsCategories
        ], 200);
    }

    public function getById($id) {
        $newsCategory = NewsCategory::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $newsCategory
        ], 200);
    }

    public function create(array $data) {
        $codeGeneration = new CodeGeneration(NewsCategory::class, "news_category_code", "NCA");

        $data['news_category_code'] = $codeGeneration->getGeneratedResourceCode();
        $newsCategory = NewsCategory::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Kategori Berita berhasil ditambahkan',
            'data' => $newsCategory
        ], 201);
    }

    public function update(array $data, $id) {
        NewsCategory::findOrFail($id)->update($data);
        return response()->json([
            'success' => true,
            'message' => 'Kategori Berita berhasil diedit'
        ], 201);
    }

    public function delete($id){
        NewsCategory::findOrFail($id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Kategori Berita berhasil dihapus'
        ], 201);
    }
}