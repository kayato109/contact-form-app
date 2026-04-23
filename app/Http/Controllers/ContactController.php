<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreContactRequest;
use App\Models\Category;
use App\Models\Tag;

class ContactController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('contact.index', compact('categories', 'tags'));
    }

    public function confirm(StoreContactRequest $request)
    {
        // バリデーション済みデータ
        $validated = $request->validated();

        // カテゴリ名取得
        $category = Category::find($validated['category_id']);

        // タグ名取得（複数）
        $tags = collect();
        if (!empty($validated['tag_ids'])) {
            $tags = Tag::whereIn('id', $validated['tag_ids'])->get();
        }

        return view('contact.confirm', [
            'validated' => $validated,
            'category' => $category,
            'tags' => $tags,
        ]);
    }
}
