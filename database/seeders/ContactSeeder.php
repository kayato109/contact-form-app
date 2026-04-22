<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact;
use App\Models\Category;
use App\Models\Tag;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::pluck('id')->toArray();
        $tags = Tag::all();

        Contact::factory(20)->make()->each(function ($contact) use ($categories, $tags) {

            // category_id を Seeder 側で割り当てる
            $contact->category_id = fake()->randomElement($categories);
            $contact->save();

            // タグを1〜3件ランダムに付与
            $contact->tags()->attach(
                $tags->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}
