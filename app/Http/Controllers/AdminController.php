<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Requests\IndexContactRequest;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index(IndexContactRequest $request)
    {
        $query = Contact::with(['category', 'tags'])->orderBy('created_at', 'desc');

        // 名前 or メール（部分一致）
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('first_name', 'like', "%{$keyword}%")
                    ->orWhere('last_name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%");
            });
        }

        // 性別
        if ($request->filled('gender') && $request->gender != '0') {
            $query->where('gender', $request->gender);
        }

        // カテゴリ
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 日付
        if ($request->filled('date')) {
            // 入力された日付(JST)を UTC に変換
            $start = Carbon::parse($request->date, 'Asia/Tokyo')->startOfDay()->timezone('UTC');
            $end = Carbon::parse($request->date, 'Asia/Tokyo')->endOfDay()->timezone('UTC');

            $query->whereBetween('created_at', [$start, $end]);
        }

        $contacts = $query->paginate(7)->withQueryString();
        $categories = Category::all();
        $tags = Tag::all();

        return view('admin.index', compact('contacts', 'categories', 'tags'));
    }
    public function show(Contact $contact)
    {
        $contact->load(['category', 'tags']);
        return view('admin.show', compact('contact'));
    }
}
