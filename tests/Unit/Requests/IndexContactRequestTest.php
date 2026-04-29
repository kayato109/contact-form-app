<?php

namespace Tests\Unit\Requests;

use App\Http\Requests\IndexContactRequest;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class IndexContactRequestTest extends TestCase
{
    use RefreshDatabase;

    private function validate(array $data)
    {
        $request = new IndexContactRequest;

        return Validator::make($data, $request->rules());
    }

    /** @test */
    public function 有効な検索条件はバリデーションを通過する()
    {
        // Arrange
        $category = Category::factory()->create();

        $data = [
            'keyword' => '山田',
            'gender' => '1',
            'category_id' => $category->id,
            'date' => '2024-01-01',
        ];

        // Act
        $validator = $this->validate($data);

        // Assert
        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function 不正な性別値はバリデーションエラーになる()
    {
        // Arrange
        $data = ['gender' => '999'];

        // Act
        $validator = $this->validate($data);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('gender', $validator->errors()->toArray());
    }
}
