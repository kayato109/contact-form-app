<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactUpdateApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 正常に更新できる()
    {
        $contact = Contact::factory()->create();
        $category = Category::factory()->create();

        $response = $this->putJson("/api/v1/contacts/{$contact->id}", [
            'first_name' => '更新後',
            'last_name' => '太郎',
            'gender' => 1,
            'email' => 'new@example.com',
            'tel' => '09012345678',
            'address' => '東京都',
            'building' => '',
            'category_id' => $category->id,
            'detail' => '更新内容',
            'tag_ids' => [],
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.email', 'new@example.com');
    }

    /** @test */
    public function 存在しないIDは404が返る()
    {
        $response = $this->putJson('/api/v1/contacts/999', []);

        $response->assertStatus(404);
    }

    /** @test */
    public function バリデーションエラー時は422が返る()
    {
        $contact = Contact::factory()->create();

        $response = $this->putJson("/api/v1/contacts/{$contact->id}", [
            'email' => 'invalid',
        ]);

        $response->assertStatus(422);
    }
}
