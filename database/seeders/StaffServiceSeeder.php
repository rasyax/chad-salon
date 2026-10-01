<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Staff;
use Illuminate\Database\Seeder;

class StaffServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $assignments = [
            'Alex Morgan' => ['Classic Manicure', 'Gel Polish', 'Haircut & Style', 'Hair Color'],
            'Sam Taylor' => ['Gel Polish', 'Haircut & Style', 'Hair Color'],
            'Jordan Lee' => ['Classic Manicure', 'Gel Polish', 'Nail Art Design'],
            'Casey Reyes' => ['Swedish Massage', 'Deep Tissue Massage', 'Express Facial'],
        ];

        foreach ($assignments as $staffName => $serviceNames) {
            $staff = Staff::query()->where('name', $staffName)->first();

            if ($staff === null) {
                continue;
            }

            $serviceIds = Service::query()
                ->whereIn('name', $serviceNames)
                ->pluck('id')
                ->all();

            $staff->services()->syncWithoutDetaching($serviceIds);
        }
    }
}
