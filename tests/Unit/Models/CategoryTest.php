<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function カテゴリは複数のお問い合わせを持つ()
    {
        // Arrange
        $category = Category::factory()->create();
        Contact::factory()->count(3)->create(['category_id' => $category->id]);

        // Act
        $contacts = $category->contacts;

        // Assert
        $this->assertCount(3, $contacts);
    }
}
