<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // 1=男性, 2=女性, 3=その他
        $gender = fake()->randomElement([1, 2, 3]);

        return [
            'last_name' => fake()->lastName(),
            'first_name' => $gender === 1
                ? fake()->firstNameMale()
                : ($gender === 2
                    ? fake()->firstNameFemale()
                    : fake()->firstName()), // その他はどちらでもOK
            'gender' => $gender,
            'email' => fake()->unique()->safeEmail(),
            'tel' => fake()->randomElement([
                fake()->regexify('0[1-9]\d{8}'),   // 10桁（固定電話）
                fake()->regexify('0[789]0\d{8}'),  // 11桁（携帯）
            ]),
            'address' => fake()->prefecture() . fake()->city() . ' ' . fake()->streetAddress(),
            'building' => fake()->optional()->secondaryAddress(),
            'detail' => fake()->realText(120),
            'category_id' => null,
        ];

    }
}
