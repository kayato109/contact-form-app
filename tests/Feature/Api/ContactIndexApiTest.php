<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactIndexApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 一覧が取得できる()
    {
        Contact::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/contacts');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function 検索条件が適用される()
    {
        Contact::factory()->create(['first_name' => '山田']);
        Contact::factory()->create(['first_name' => '佐藤']);

        $response = $this->getJson('/api/v1/contacts?keyword=山田');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    /** @test */
    public function バリデーションエラー時は422が返る()
    {
        $response = $this->getJson('/api/v1/contacts?gender=999');

        $response->assertStatus(422);
    }
}
