<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Contact>
 */
class ContactFactory extends Factory
{
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
                default => $faker->firstName(),
            },
            'gender' => $gender,
            'email' => $faker->unique()->safeEmail(),
            'tel' => $faker->randomElement([
                $faker->regexify('0[1-9]\d{8}'),   // 10桁（固定電話）
                $faker->regexify('0[789]0\d{8}'),  // 11桁（携帯）
            ]),
            'address' => $faker->prefecture().$faker->city().' '.$faker->streetAddress(),
            'building' => $faker->optional()->secondaryAddress(),
            'detail' => $faker->realText(120),

            // 既存 categories からランダムに選ぶ
            // ※ NULL だとテストで NOT NULL 制約エラーになるため
            'category_id' => Category::inRandomOrder()->value('id'),
        ];
    }
}
