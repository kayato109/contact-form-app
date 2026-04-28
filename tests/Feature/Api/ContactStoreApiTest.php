<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactStoreApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 正常に作成できる()
    {
        $category = Category::factory()->create();
        $tag = Tag::factory()->create();

        $data = [
            'first_name' => '山田',
            'last_name' => '太郎',
            'gender' => 1,
            'email' => 'test@example.com',
            'tel' => '09012345678',
            'address' => '東京都',
            'building' => '',
            'category_id' => $category->id,
            'detail' => '内容',
            'tag_ids' => [$tag->id],
        ];

        $response = $this->postJson('/api/v1/contacts', $data);

        $response->assertStatus(201)
            ->assertJsonPath('data.email', 'test@example.com');
    }

    /** @test */
    public function バリデーションエラー時は422が返る()
    {
        $response = $this->postJson('/api/v1/contacts', [
            'first_name' => '',
        ]);

        $response->assertStatus(422);
    }
}
