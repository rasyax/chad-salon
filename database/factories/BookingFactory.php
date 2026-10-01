<?php

namespace Database\Factories;

use App\Enum\BookingStatus;
use App\Enum\RoleEnum;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startsAt = now()->addDay()->startOfHour();

        return [
            'user_id' => User::factory()->state(['role' => RoleEnum::Customer]),
            'service_id' => Service::factory(),
            'staff_id' => Staff::factory(),
            'starts_at' => $startsAt,
            'ends_at' => $startsAt->copy()->addMinutes(60),
            'status' => BookingStatus::Pending,
        ];
    }
}
