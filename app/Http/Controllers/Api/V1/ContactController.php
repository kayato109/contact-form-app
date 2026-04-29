<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\IndexContactRequest;
use App\Http\Requests\Api\V1\StoreContactRequest;
use App\Http\Requests\Api\V1\UpdateContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ContactController extends Controller
{
    public function index(IndexContactRequest $request): AnonymousResourceCollection
    {
        $validated = $request->validated();
        $perPage = $validated['per_page'] ?? 20;

        $contacts = Contact::with(['category', 'tags'])
            ->filter($validated)
            ->latest()
            ->paginate($perPage);

        return ContactResource::collection($contacts);
    }

    public function show(Contact $contact): ContactResource
    {
        return new ContactResource(
            $contact->load(['category', 'tags'])
        );
    }

    public function store(StoreContactRequest $request)
    {
        $validated = $request->validated();
        $tagIds = $validated['tag_ids'] ?? [];
        unset($validated['tag_ids']);

        $contact = Contact::create($validated);
        $contact->tags()->sync($tagIds);

        return (new ContactResource(
            $contact->load(['category', 'tags'])
        ))->response()->setStatusCode(201);
    }

    public function update(UpdateContactRequest $request, Contact $contact)
    {
        $validated = $request->validated();
        $tagIds = $validated['tag_ids'] ?? [];
        unset($validated['tag_ids']);

        $contact->update($validated);
        $contact->tags()->sync($tagIds);

        return (new ContactResource(
            $contact->load(['category', 'tags'])
        ))->response()->setStatusCode(200);
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return response()->json(null, 204);
    }
}
