<?php

namespace Tests\Feature;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagCrudTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 未ログインユーザーはタグ編集画面にアクセスできない()
    {
        $tag = Tag::factory()->create();

        $response = $this->get("/admin/tags/{$tag->id}/edit");

        $response->assertRedirect('/login');
    }

    /** @test */
    public function ログインユーザーはタグ編集画面を表示できる()
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['name' => 'タグA']);

        $response = $this->actingAs($user)->get("/admin/tags/{$tag->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('admin.tags.edit');
        $response->assertSee('タグA');
    }

    /** @test */
    public function ログインユーザーはタグを新規作成できる()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/admin/tags', [
            'name' => '新規タグ',
        ]);

        $response->assertRedirect('/admin');
        $this->assertDatabaseHas('tags', ['name' => '新規タグ']);
    }

    /** @test */
    public function ログインユーザーはタグを更新できる()
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['name' => '旧タグ']);

        $response = $this->actingAs($user)->put("/admin/tags/{$tag->id}", [
            'name' => '更新後タグ',
        ]);

        $response->assertRedirect('/admin');
        $this->assertDatabaseHas('tags', ['name' => '更新後タグ']);
    }

    /** @test */
    public function ログインユーザーはタグを削除できる()
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create();

        $response = $this->actingAs($user)->delete("/admin/tags/{$tag->id}");

        $response->assertRedirect('/admin');
        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
    }

    /** @test */
    public function 未ログインユーザーはタグ作成できない()
    {
        $response = $this->post('/admin/tags', ['name' => 'タグ']);

        $response->assertRedirect('/login');
        $this->assertDatabaseCount('tags', 0);
    }
}
