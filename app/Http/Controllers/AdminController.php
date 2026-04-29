<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexContactRequest;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;

class AdminController extends Controller
{
    public function index(IndexContactRequest $request)
    {
        $contacts = Contact::with(['category', 'tags'])
            ->filter($request->validated())
            ->paginate(7)
            ->withQueryString();

        return view('admin.index', [
            'contacts' => $contacts,
            'categories' => Category::all(),
            'tags' => Tag::all(),
        ]);
    }

    public function show(Contact $contact)
    {
        return view('admin.show', [
            'contact' => $contact->load(['category', 'tags']),
        ]);
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect('/admin')->with('success', 'お問い合わせを削除しました');
    }
}
