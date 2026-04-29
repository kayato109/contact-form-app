<?php

namespace Tests\Unit\Requests;

use App\Http\Requests\ExportContactRequest;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ExportCsvRequestTest extends TestCase
{
    use RefreshDatabase;

    private function validate(array $data)
    {
        $request = new ExportContactRequest;

        return Validator::make($data, $request->rules());
    }

    /** @test */
    public function 有効なフィルタ条件はバリデーションを通過する()
    {
        $category = Category::factory()->create();

        $data = [
            'keyword' => '山田',
            'gender' => 1,
            'category_id' => $category->id,
            'date' => '2024-01-01',
        ];

        $validator = $this->validate($data);

        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function 不正な性別値はエラーになる()
    {
        $validator = $this->validate(['gender' => 999]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('gender', $validator->errors()->toArray());
    }

    /** @test */
    public function 存在しないカテゴリ_i_dはエラーになる()
    {
        $validator = $this->validate(['category_id' => 999]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('category_id', $validator->errors()->toArray());
    }
}
