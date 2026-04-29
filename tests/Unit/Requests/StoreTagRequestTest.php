<?php

namespace Tests\Unit\Requests;

use App\Http\Requests\StoreTagRequest;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class StoreTagRequestTest extends TestCase
{
    use RefreshDatabase;

    private function validate(array $data)
    {
        $request = new StoreTagRequest;

        return Validator::make($data, $request->rules());
    }

    /** @test */
    public function 有効なタグ名はバリデーションを通過する()
    {
        // Arrange
        $data = ['name' => '重要'];

        // Act
        $validator = $this->validate($data);

        // Assert
        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function タグ名が空だとバリデーションエラーになる()
    {
        // Arrange
        $data = ['name' => ''];

        // Act
        $validator = $this->validate($data);

        // Assert
        $this->assertTrue($validator->fails());
    }

    /** @test */
    public function タグ名が重複するとバリデーションエラーになる()
    {
        // Arrange
        Tag::factory()->create(['name' => '重複']);
        $data = ['name' => '重複'];

        // Act
        $validator = $this->validate($data);

        // Assert
        $this->assertTrue($validator->fails());
    }
}
