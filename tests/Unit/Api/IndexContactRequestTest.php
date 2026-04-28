<?php

namespace Tests\Unit\Api;

use App\Http\Requests\Api\V1\IndexContactRequest;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class IndexContactRequestTest extends TestCase
{
    use RefreshDatabase;

    private function validate(array $data)
    {
        $request = new IndexContactRequest();
        return Validator::make($data, $request->rules());
    }

    /** @test */
    public function 有効な検索条件はバリデーションを通過する()
    {
        $category = Category::factory()->create();

        $data = [
            'keyword' => '山田',
            'gender' => 1,
            'category_id' => $category->id,
            'date' => '2024-01-01',
            'per_page' => 20,
        ];

        $validator = $this->validate($data);

        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function 不正な性別値はバリデーションエラーになる()
    {
        $validator = $this->validate(['gender' => 999]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('gender', $validator->errors()->toArray());
    }
}
