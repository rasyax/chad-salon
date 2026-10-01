<?php

namespace Database\Seeders;

use App\Enum\BookingStatus;
use App\Models\Booking;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::query()->where('role', 'admin')->latest()->first()
            ?? User::query()->latest()->first();

        $staffService = Staff::query()->where('is_active', true)
            ->whereHas('services')
            ->inRandomOrder()
            ->first();

        if ($staffService === null || $admin === null) {
            return;
        }

        $service = $staffService->services()->inRandomOrder()->first();

        if ($service === null) {
            return;
        }

        $startsAt = now()->addDay()->setTime(14, 0);

        Booking::query()->updateOrCreate(
            ['starts_at' => $startsAt, 'staff_id' => $staffService->id],
            [
                'user_id' => $admin->id,
                'service_id' => $service->id,
                'ends_at' => $startsAt->copy()->addMinutes($service->duration_minutes),
                'status' => BookingStatus::Confirmed,
            ],
        );
    }
}
