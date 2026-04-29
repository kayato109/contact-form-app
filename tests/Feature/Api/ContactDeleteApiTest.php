<?php

namespace Tests\Feature\Api;

use App\Models\Contact;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactDeleteApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(CategorySeeder::class);
    }

    /** @test */
    public function 正常に削除できる()
    {
        $contact = Contact::factory()->create();

        $response = $this->deleteJson("/api/v1/contacts/{$contact->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }

    /** @test */
    public function 存在しない_i_dは404が返る()
    {
        $response = $this->deleteJson('/api/v1/contacts/999');

        $response->assertStatus(404);
    }
}
