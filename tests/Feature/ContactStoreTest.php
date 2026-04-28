<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactStoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function バリデーション成功でお問い合わせが保存されタグも同期される()
    {
        $category = Category::factory()->create();
        $tag1 = Tag::factory()->create();
        $tag2 = Tag::factory()->create();

        $data = [
            'first_name' => '山田',
            'last_name' => '太郎',
            'gender' => 1,
            'email' => 'test@example.com',
            'tel' => '09012345678',
            'address' => '東京都',
            'building' => '',
            'category_id' => $category->id,
            'detail' => '問い合わせ内容',
            'tag_ids' => [$tag1->id, $tag2->id],
        ];

        $response = $this->post('/contacts', $data);

        $response->assertRedirect('/thanks');

        $this->assertDatabaseHas('contacts', [
            'email' => 'test@example.com',
        ]);

        $contact = Contact::first();
        $this->assertCount(2, $contact->tags);
    }

    /** @test */
    public function バリデーションエラー時は保存されない()
    {
        $response = $this->post('/contacts', [
            'first_name' => '', // 必須エラー
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['first_name']);
        $this->assertDatabaseCount('contacts', 0);
    }
}
