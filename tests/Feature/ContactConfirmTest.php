<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactConfirmTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function バリデーション成功で確認画面が表示される()
    {
        // Arrange
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
            'detail' => '問い合わせ内容',
            'tag_ids' => [$tag->id],
        ];

        // Act
        $response = $this->post('/contacts/confirm', $data);

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('contact.confirm');
        $response->assertSee('太郎');
        $response->assertSee($category->name);
    }

    /** @test */
    public function バリデーションエラー時は元の画面へリダイレクトされる()
    {
        $response = $this->post('/contacts/confirm', [
            'first_name' => '', // 必須エラー
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['first_name']);
    }
}
