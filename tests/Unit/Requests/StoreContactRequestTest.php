<?php

namespace Tests\Unit\Requests;

use App\Http\Requests\StoreContactRequest;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class StoreContactRequestTest extends TestCase
{
    use RefreshDatabase;
    private function validate(array $data)
    {
        $request = new StoreContactRequest();
        return Validator::make($data, $request->rules());
    }

    private function validData(array $override = [])
    {
        $category = Category::factory()->create();
        $tag1 = Tag::factory()->create();
        $tag2 = Tag::factory()->create();

        return array_merge([
            'first_name' => '山田',
            'last_name' => '太郎',
            'gender' => 1,
            'email' => 'test@example.com',
            'tel' => '09012345678',
            'address' => '東京都',
            'building' => 'ビル101',
            'category_id' => $category->id,
            'detail' => 'お問い合わせ内容',
            'tag_ids' => [$tag1->id, $tag2->id],
        ], $override);
    }

    /** @test */
    public function 有効な入力はバリデーションを通過する()
    {
        // Arrange
        $data = $this->validData();

        // Act
        $validator = $this->validate($data);

        // Assert
        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function 不正な電話番号形式はバリデーションエラーになる()
    {
        // Arrange
        $data = $this->validData(['tel' => 'abcde']);

        // Act
        $validator = $this->validate($data);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('tel', $validator->errors()->toArray());
    }
}
