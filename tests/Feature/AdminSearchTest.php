<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 管理画面で検索とページネーションが機能する()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        // 10件作成（7件で1ページ）
        Contact::factory()->count(10)->create([
            'category_id' => $category->id,
            'gender' => 1,
        ]);

        $response = $this->actingAs($user)->get('/admin?gender=1');

        $response->assertStatus(200);
        $response->assertViewIs('admin.index');

        // ページネーションが効いている（7件）
        $this->assertCount(7, $response->viewData('contacts'));
    }
}
