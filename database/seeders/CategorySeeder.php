<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Nail Art', 'description' => 'Manicure, gel polish, nail extensions, and decorative nail designs.'],
            ['name' => 'Hair Treatment', 'description' => 'Haircuts, coloring, keratin, and restorative hair care.'],
            ['name' => 'Facial', 'description' => 'Cleansing facials, peels, and skin-renewal treatments.'],
            ['name' => 'Massage', 'description' => 'Relaxation, deep tissue, and body massage sessions.'],
            ['name' => 'Makeup', 'description' => 'Everyday, bridal, and event makeup application.'],
            ['name' => 'Waxing', 'description' => 'Hair removal for face and body.'],
            ['name' => 'Spa', 'description' => 'Body scrubs, wraps, and full spa packages.'],
        ];

        foreach ($categories as $category) {
            Category::query()->updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                $category,
            );
        }
    }
}
