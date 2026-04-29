<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Contact;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminContactDetailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(CategorySeeder::class);
    }

    /** @test */
    public function 管理画面でお問い合わせ詳細が表示される()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $contact = Contact::factory()->create(['category_id' => $category->id]);

        $response = $this->actingAs($user)->get("/admin/contacts/{$contact->id}");

        $response->assertStatus(200);
        $response->assertViewIs('admin.show');
        $response->assertSee($contact->first_name);
        $response->assertSee($category->name);
    }

    /** @test */
    public function 管理画面でお問い合わせを削除できる()
    {
        $user = User::factory()->create();
        $contact = Contact::factory()->create();

        $response = $this->actingAs($user)->delete("/admin/contacts/{$contact->id}");

        $response->assertRedirect('/admin');
        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }
}
