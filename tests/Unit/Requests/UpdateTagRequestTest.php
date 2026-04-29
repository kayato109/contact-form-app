<?php

namespace Tests\Unit\Requests;

use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class UpdateTagRequestTest extends TestCase
{
    use RefreshDatabase;

    private function validate(array $data, Tag $tag)
    {
        $request = new UpdateTagRequest;

        // Route パラメータをセット
        $request->setRouteResolver(function () use ($tag) {
            return new class($tag)
            {
                public function __construct(public $tag) {}

                public function parameter($key)
                {
                    return $this->tag;
                }
            };
        });

        return Validator::make($data, $request->rules());
    }

    /** @test */
    public function 自身のタグ名はそのまま更新可能()
    {
        // Arrange
        $tag = Tag::factory()->create(['name' => '既存']);

        // Act
        $validator = $this->validate(['name' => '既存'], $tag);

        // Assert
        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function 他のタグ名に変更すると重複エラーになる()
    {
        // Arrange
        Tag::factory()->create(['name' => '他のタグ']);
        $tag = Tag::factory()->create(['name' => '元のタグ']);

        // Act
        $validator = $this->validate(['name' => '他のタグ'], $tag);

        // Assert
        $this->assertTrue($validator->fails());
    }
}
