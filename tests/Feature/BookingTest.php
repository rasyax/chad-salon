<?php

use App\Enum\BookingStatus;
use App\Enum\RoleEnum;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

function bookablePair(): array
{
    $service = Service::factory()->create(['duration_minutes' => 60]);
    $staff = Staff::factory()->create();
    $staff->services()->attach($service);

    return [$service, $staff];
}

it('schedules a booking that ends after the service duration', function () {
    [$service, $staff] = bookablePair();
    $customer = User::factory()->create(['role' => RoleEnum::Customer]);
    $startsAt = '2026-10-01 10:00:00';

    $booking = Booking::schedule([
        'user_id' => $customer->id,
        'service_id' => $service->id,
        'staff_id' => $staff->id,
        'starts_at' => $startsAt,
    ]);

    expect($booking->ends_at->toDateTimeString())->toBe('2026-10-01 11:00:00')
        ->and($booking->status)->toBe(BookingStatus::Pending);

    $this->assertDatabaseHas('bookings', [
        'id' => $booking->id,
        'user_id' => $customer->id,
        'ends_at' => '2026-10-01 11:00:00',
        'status' => BookingStatus::Pending->value,
    ]);
});

it('rejects a booking that overlaps the same staff', function () {
    [$service, $staff] = bookablePair();
    $customer = User::factory()->create();

    Booking::factory()->create([
        'service_id' => $service->id,
        'staff_id' => $staff->id,
        'starts_at' => '2026-10-01 10:00:00',
        'ends_at' => '2026-10-01 11:00:00',
        'status' => BookingStatus::Confirmed,
    ]);

    expect(fn () => Booking::schedule([
        'user_id' => $customer->id,
        'service_id' => $service->id,
        'staff_id' => $staff->id,
        'starts_at' => '2026-10-01 10:30:00',
    ]))->toThrow(ValidationException::class);

    expect(Booking::query()->count())->toBe(1);
});

it('allows a booking in a slot another staff member already holds', function () {
    [$service, $busy] = bookablePair();
    $free = Staff::factory()->create();
    $free->services()->attach($service);

    Booking::factory()->create([
        'service_id' => $service->id,
        'staff_id' => $busy->id,
        'starts_at' => '2026-10-01 10:00:00',
        'ends_at' => '2026-10-01 11:00:00',
    ]);

    $booking = Booking::schedule([
        'user_id' => User::factory()->create()->id,
        'service_id' => $service->id,
        'staff_id' => $free->id,
        'starts_at' => '2026-10-01 10:00:00',
    ]);

    expect($booking->staff_id)->toBe($free->id);
});

it('allows a booking in a slot that was cancelled', function () {
    [$service, $staff] = bookablePair();

    Booking::factory()->create([
        'service_id' => $service->id,
        'staff_id' => $staff->id,
        'starts_at' => '2026-10-01 10:00:00',
        'ends_at' => '2026-10-01 11:00:00',
        'status' => BookingStatus::Cancelled,
    ]);

    $booking = Booking::schedule([
        'user_id' => User::factory()->create()->id,
        'service_id' => $service->id,
        'staff_id' => $staff->id,
        'starts_at' => '2026-10-01 10:00:00',
    ]);

    expect($booking->status)->toBe(BookingStatus::Pending);
});

it('rejects an inactive staff member', function () {
    [$service, $staff] = bookablePair();
    $staff->update(['is_active' => false]);

    expect(fn () => Booking::schedule([
        'user_id' => User::factory()->create()->id,
        'service_id' => $service->id,
        'staff_id' => $staff->id,
        'starts_at' => '2026-10-01 10:00:00',
    ]))->toThrow(ValidationException::class);

    $this->assertDatabaseCount('bookings', 0);
});

it('rejects a staff member who does not offer the service', function () {
    $service = Service::factory()->create();
    $staff = Staff::factory()->create();

    expect(fn () => Booking::schedule([
        'user_id' => User::factory()->create()->id,
        'service_id' => $service->id,
        'staff_id' => $staff->id,
        'starts_at' => '2026-10-01 10:00:00',
    ]))->toThrow(ValidationException::class);

    $this->assertDatabaseCount('bookings', 0);
});

it('keeps its own slot when the booking is rescheduled onto itself', function () {
    [$service, $staff] = bookablePair();
    $booking = Booking::factory()->create([
        'service_id' => $service->id,
        'staff_id' => $staff->id,
        'starts_at' => '2026-10-01 10:00:00',
        'ends_at' => '2026-10-01 11:00:00',
    ]);

    $updated = Booking::schedule([
        'user_id' => $booking->user_id,
        'service_id' => $service->id,
        'staff_id' => $staff->id,
        'starts_at' => '2026-10-01 10:15:00',
        'status' => BookingStatus::Confirmed,
    ], $booking);

    expect($updated->starts_at->toDateTimeString())->toBe('2026-10-01 10:15:00')
        ->and($updated->ends_at->toDateTimeString())->toBe('2026-10-01 11:15:00')
        ->and($updated->status)->toBe(BookingStatus::Confirmed);

    $this->assertDatabaseCount('bookings', 1);
});
