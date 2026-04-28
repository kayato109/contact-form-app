<?php

namespace Tests\Unit\Api;

use App\Http\Requests\Api\V1\StoreContactRequest;
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

    private function validData()
    {
        $category = Category::factory()->create();
        $tag = Tag::factory()->create();

        return [
            'first_name' => '山田',
            'last_name' => '太郎',
            'gender' => 1,
            'email' => 'test@example.com',
            'tel' => '09012345678',
            'address' => '東京都',
            'building' => '',
            'category_id' => $category->id,
            'detail' => '内容',
            'tag_ids' => [$tag->id],
        ];
    }

    /** @test */
    public function 有効な入力はバリデーションを通過する()
    {
        $validator = $this->validate($this->validData());
        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function 不正な電話番号はエラーになる()
    {
        $data = $this->validData();
        $data['tel'] = 'abc';

        $validator = $this->validate($data);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('tel', $validator->errors()->toArray());
    }
}
