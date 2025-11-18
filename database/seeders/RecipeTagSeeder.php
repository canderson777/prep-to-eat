<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RecipeTag;
use Illuminate\Support\Str;

class RecipeTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            ['name' => 'Plant-Forward', 'icon' => '🌿'],
            ['name' => 'Anti-Inflammatory', 'icon' => '💧'],
            ['name' => 'Gluten-Free', 'icon' => '🌾'],
            ['name' => 'High-Protein', 'icon' => '💪'],
            ['name' => 'Heart Healthy', 'icon' => '❤️'],
            ['name' => 'Brain Boosting', 'icon' => '🧠'],
            ['name' => 'Gut Health', 'icon' => '🌿'],
            ['name' => 'Quick & Easy', 'icon' => '⏱️'],
            ['name' => 'Meal Prep Friendly', 'icon' => '🍱'],
            ['name' => 'Vegan / Vegetarian', 'icon' => '🌱'],
            ['name' => 'Weight Management', 'icon' => '⚖️'],
            ['name' => 'Balanced Energy', 'icon' => '⚡'],
            ['name' => 'Vegan', 'icon' => '🌱'],
            ['name' => 'Vegetarian', 'icon' => '🥗'],
            ['name' => 'Pescatarian', 'icon' => '🐟'],
            ['name' => 'Dairy-Free', 'icon' => '🥛'],
            ['name' => 'Soy-Free', 'icon' => '🫘'],
            ['name' => 'Nut-Free', 'icon' => '🥜'],
            ['name' => 'Sugar Conscious / Low Glycemic', 'icon' => '🍯'],
        ];

        foreach ($tags as $tag) {
            RecipeTag::firstOrCreate(
                ['slug' => Str::slug($tag['name'])],
                [
                    'name' => $tag['name'],
                    'icon' => $tag['icon'],
                ]
            );
        }
    }
}
