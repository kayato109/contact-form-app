<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\ExportContactRequest;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact.index', [
            'categories' => Category::all(),
            'tags' => Tag::all(),
        ]);
    }

    public function confirm(StoreContactRequest $request)
    {
        $validated = $request->validated();

        return view('contact.confirm', [
            'validated' => $validated,
            'category' => Category::find($validated['category_id']),
            'tags' => Tag::whereIn('id', $validated['tag_ids'] ?? [])->get(),
        ]);
    }

    public function store(StoreContactRequest $request)
    {
        $validated = $request->validated();

        $contact = Contact::create($validated);
        $contact->tags()->sync($validated['tag_ids'] ?? []);

        return redirect('/thanks');
    }

    public function thanks()
    {
        return view('contact.thanks');
    }

    public function export(ExportContactRequest $request)
    {
        $validated = $request->validated();

        $contacts = Contact::with('category')
            ->filter($validated)
            ->orderBy('created_at', 'desc')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="contacts.csv"',
        ];

        $callback = function () use ($contacts) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF"); // BOM

            fputcsv($file, [
                'ID',
                '氏名',
                '性別',
                'メール',
                '電話',
                '住所',
                '建物',
                'カテゴリ',
                '内容',
                '作成日時'
            ]);

            foreach ($contacts as $contact) {
                fputcsv($file, $this->makeCsvRow($contact));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    protected function makeCsvRow(Contact $contact)
    {
        return [
            $contact->id,
            $contact->full_name,
            $contact->gender_label,
            $contact->email,
            $contact->tel,
            $contact->address,
            $contact->building,
            $contact->category_name,
            $contact->detail,
            $contact->created_at->format('Y-m-d H:i:s'),
        ];
    }

}
