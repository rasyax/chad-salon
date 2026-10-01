<?php

namespace Database\Seeders;

use App\Enum\RoleEnum;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staff = [
            ['name' => 'Alex Morgan', 'phone' => '555-0101', 'position' => 'Master Stylist'],
            ['name' => 'Sam Taylor', 'phone' => '555-0102', 'position' => 'Color Specialist'],
            ['name' => 'Jordan Lee', 'phone' => '555-0103', 'position' => 'Nail Technician'],
            ['name' => 'Casey Reyes', 'phone' => '555-0104', 'position' => 'Massage Therapist'],
        ];

        $services = [
            'Classic Manicure' => ['Nail Art'],
            'Gel Polish' => ['Nail Art', 'Hair Treatment'],
            'Haircut & Style' => ['Hair Treatment'],
            'Hair Color' => ['Hair Treatment'],
            'Swedish Massage' => ['Massage'],
            'Deep Tissue Massage' => ['Massage'],
            'Express Facial' => ['Facial'],
            'Everyday Makeup' => ['Makeup'],
            'Brow Wax' => ['Waxing'],
        ];

        foreach ($staff as $attrs) {
            $user = User::query()->firstOrCreate(
                ['email' => strtolower(str_replace(' ', '.', $attrs['name'])).'@salon.test'],
                [
                    'name' => $attrs['name'],
                    'email' => strtolower(str_replace(' ', '.', $attrs['name'])).'@salon.test',
                    'password' => Hash::make('password'),
                    'phone' => $attrs['phone'],
                    'role' => RoleEnum::Staff,
                ],
            );

            Staff::query()->updateOrCreate(
                ['name' => $attrs['name']],
                [
                    'user_id' => $user->id,
                    'phone' => $attrs['phone'],
                    'position' => $attrs['position'],
                    'is_active' => true,
                ],
            );
        }
    }
}
