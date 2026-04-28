<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Category;
use App\Models\Tag;
use App\Http\Requests\IndexContactRequest;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index(IndexContactRequest $request)
    {
        $validated = $request->validated();

        $query = Contact::with(['category', 'tags'])
            ->filter($validated)
            ->orderBy('created_at', 'desc');

        // 日付だけ JST → UTC の変換が必要（API と挙動が違う）
        if (!empty($validated['date'])) {
            $start = Carbon::parse($validated['date'], 'Asia/Tokyo')->startOfDay()->timezone('UTC');
            $end = Carbon::parse($validated['date'], 'Asia/Tokyo')->endOfDay()->timezone('UTC');
            $query->whereBetween('created_at', [$start, $end]);
        }

        $contacts = $query->paginate(7)->withQueryString();
        $categories = Category::all();
        $tags = Tag::all();

        return view('admin.index', compact('contacts', 'categories', 'tags'));
    }

    public function show(Contact $contact)
    {
        return view('admin.show', [
            'contact' => $contact->load(['category', 'tags'])
        ]);
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect('/admin')->with('success', 'お問い合わせを削除しました');
    }
}
