<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            'nail-art' => [
                ['name' => 'Classic Manicure', 'price' => 25, 'duration_minutes' => 45, 'description' => 'Nail shaping, cuticle care, and a polish of your choice.'],
                ['name' => 'Gel Polish', 'price' => 40, 'duration_minutes' => 60, 'description' => 'Long-lasting gel color with a glossy or matte finish.'],
                ['name' => 'Nail Art Design', 'price' => 55, 'duration_minutes' => 75, 'description' => 'Custom hand-painted or embellished nail designs.'],
            ],
            'hair-treatment' => [
                ['name' => 'Haircut & Style', 'price' => 45, 'duration_minutes' => 60, 'description' => 'Cut, wash, and blow-dry styled to suit you.'],
                ['name' => 'Hair Color', 'price' => 90, 'duration_minutes' => 120, 'description' => 'Full color, highlights, or balayage with a gloss finish.'],
                ['name' => 'Keratin Treatment', 'price' => 150, 'duration_minutes' => 150, 'description' => 'Smoothing treatment that reduces frizz and adds shine.'],
            ],
            'facial' => [
                ['name' => 'Express Facial', 'price' => 50, 'duration_minutes' => 30, 'description' => 'Cleanse, exfoliate, and moisturize for a quick refresh.'],
                ['name' => 'Deep Cleansing Facial', 'price' => 85, 'duration_minutes' => 75, 'description' => 'Extraction, mask, and hydration for congested skin.'],
            ],
            'massage' => [
                ['name' => 'Swedish Massage', 'price' => 80, 'duration_minutes' => 60, 'description' => 'Light to medium pressure for relaxation.'],
                ['name' => 'Deep Tissue Massage', 'price' => 95, 'duration_minutes' => 60, 'description' => 'Firm pressure targeting tight muscles and knots.'],
            ],
            'makeup' => [
                ['name' => 'Everyday Makeup', 'price' => 55, 'duration_minutes' => 45, 'description' => 'Natural daytime makeup for work or events.'],
                ['name' => 'Bridal Makeup', 'price' => 150, 'duration_minutes' => 90, 'description' => 'Long-wear bridal look, including lashes.'],
            ],
            'waxing' => [
                ['name' => 'Brow Wax', 'price' => 15, 'duration_minutes' => 15, 'description' => 'Shape and tidy the eyebrows.'],
                ['name' => 'Full Leg Wax', 'price' => 60, 'duration_minutes' => 45, 'description' => 'Hair removal from thigh to ankle.'],
            ],
            'spa' => [
                ['name' => 'Body Scrub', 'price' => 70, 'duration_minutes' => 45, 'description' => 'Exfoliating scrub followed by a hydrating body lotion.'],
                ['name' => 'Spa Package', 'price' => 180, 'duration_minutes' => 150, 'description' => 'Facial, massage, and body scrub in one visit.'],
            ],
        ];

        foreach ($services as $slug => $items) {
            $categoryId = Category::query()->where('slug', $slug)->value('id');

            foreach ($items as $item) {
                Service::query()->updateOrCreate(
                    [
                        'category_id' => $categoryId,
                        'name' => $item['name'],
                    ],
                    $item,
                );
            }
        }
    }
}
