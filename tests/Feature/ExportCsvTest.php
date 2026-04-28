<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExportCsvTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 未ログインユーザーはCSVをダウンロードできない()
    {
        $response = $this->get('/contacts/export');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function ログインユーザーはCSVをダウンロードできる()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        Contact::factory()->create([
            'first_name' => '山田',
            'last_name' => '太郎',
            'category_id' => $category->id,
        ]);

        $response = $this->actingAs($user)->get('/contacts/export');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        // CSV 内容確認
        $response->assertSee('太郎');
        $response->assertSee($category->name);
    }

    /** @test */
    public function フィルタ条件が適用される()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        Contact::factory()->create([
            'first_name' => '山田',
            'last_name' => '太郎',
            'category_id' => $category->id,
        ]);

        Contact::factory()->create([
            'first_name' => '佐藤',
            'last_name' => '花子',
            'category_id' => $category->id,
        ]);

        $response = $this->actingAs($user)->get('/contacts/export?keyword=太郎');

        $response->assertStatus(200);
        $response->assertSee('太郎');
        $response->assertDontSee('花子');
    }
}
