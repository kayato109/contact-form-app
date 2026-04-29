<?php

namespace Tests\Unit\Models;

use App\Models\Contact;
use App\Models\Tag;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(CategorySeeder::class);
    }

    /** @test */
    public function タグは複数のお問い合わせに紐づく()
    {
        // Arrange
        $tag = Tag::factory()->create();
        $contacts = Contact::factory()->count(2)->create();

        // Act
        $tag->contacts()->sync($contacts->pluck('id'));

        // Assert
        $this->assertCount(2, $tag->contacts);
    }
}
