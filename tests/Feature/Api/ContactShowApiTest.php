<?php

namespace Tests\Feature\Api;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactShowApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 詳細が取得できる()
    {
        $contact = Contact::factory()->create();

        $response = $this->getJson("/api/v1/contacts/{$contact->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $contact->id);
    }

    /** @test */
    public function 存在しないIDは404が返る()
    {
        $response = $this->getJson('/api/v1/contacts/999');

        $response->assertStatus(404);
    }
}
