<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Staff>
 */
class StaffFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => null,
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'position' => 'Stylist',
            'is_active' => true,
        ];
    }

    public function active(): static
    {
        return $this->state(['is_active' => true]);
    }

    public function withService(Service $service): static
    {
        return $this->hasAttached($service);
    }
}
