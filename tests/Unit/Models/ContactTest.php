<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(CategorySeeder::class);
    }

    /** @test */
    public function お問い合わせはカテゴリに属する()
    {
        // Arrange
        $category = Category::factory()->create();
        $contact = Contact::factory()->create(['category_id' => $category->id]);

        // Act
        $result = $contact->category;

        // Assert
        $this->assertEquals($category->id, $result->id);
    }

    /** @test */
    public function お問い合わせは複数のタグと同期できる()
    {
        // Arrange
        $contact = Contact::factory()->create();
        $tags = Tag::factory()->count(2)->create();

        // Act
        $contact->tags()->sync($tags->pluck('id'));

        // Assert
        $this->assertCount(2, $contact->tags);
    }
}
