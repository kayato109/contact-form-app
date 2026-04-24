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
        $faker = \Faker\Factory::create('ja_JP');

        // 1=男性, 2=女性, 3=その他
        $gender = $faker->randomElement([1, 2, 3]);

        return [
            'first_name' => $faker->lastName(),
            'last_name' => match ($gender) {
                1 => $faker->firstNameMale(),
                2 => $faker->firstNameFemale(),
                default => $faker->firstName(),// その他はどちらでもOK
            },
            'gender' => $gender,
            'email' => $faker->unique()->safeEmail(),
            'tel' => $faker->randomElement([
                $faker->regexify('0[1-9]\d{8}'),   // 10桁（固定電話）
                $faker->regexify('0[789]0\d{8}'),  // 11桁（携帯）
            ]),
            'address' => $faker->prefecture() . $faker->city() . ' ' . $faker->streetAddress(),
            'building' => $faker->optional()->secondaryAddress(),
            'detail' => $faker->realText(120),
            'category_id' => null, // Seeder 側で割り当てる
        ];

    }
}
