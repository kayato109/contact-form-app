<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactPageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function フォームページが表示されカテゴリとタグが渡される()
    {
        // Arrange
        $category = Category::factory()->create(['content' => 'カテゴリA']);
        $tag = Tag::factory()->create(['name' => 'タグA']);

        // Act
        $response = $this->get('/');

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('contact.index');
        $response->assertSee('カテゴリA');
        $response->assertSee('タグA');
    }

    /** @test */
    public function サンクスページが表示される()
    {
        $response = $this->get('/thanks');

        $response->assertStatus(200);
        $response->assertViewIs('contact.thanks');
    }
}
