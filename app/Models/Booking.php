<?php

namespace App\Models;

use App\Enum\BookingStatus;
use Database\Factories\BookingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

#[Fillable(['user_id', 'service_id', 'staff_id', 'starts_at', 'ends_at', 'status'])]
class Booking extends Model
{
    /** @use HasFactory<BookingFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'status' => BookingStatus::class,
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * @param  Builder<Booking>  $query
     * @return Builder<Booking>
     */
    public function scopeOccupyingStaff(Builder $query, int $staffId, Carbon $startsAt, Carbon $endsAt): Builder
    {
        return $query
            ->where('staff_id', $staffId)
            ->where('status', '!=', BookingStatus::Cancelled)
            ->where('starts_at', '<', $endsAt)
            ->where('ends_at', '>', $startsAt);
    }

    /**
     * @param  array{user_id: int, service_id: int, staff_id: int, starts_at: mixed, status?: BookingStatus|string}  $attributes
     */
    public static function schedule(array $attributes, ?self $existing = null): self
    {
        return DB::transaction(function () use ($attributes, $existing): self {
            $service = Service::query()->findOrFail($attributes['service_id']);
            $staff = Staff::query()->lockForUpdate()->findOrFail($attributes['staff_id']);
            $startsAt = Carbon::parse($attributes['starts_at']);
            $endsAt = $startsAt->copy()->addMinutes($service->duration_minutes);

            $offersService = $staff->is_active
                && $staff->services()->whereKey($service->id)->exists();

            $overlaps = self::query()
                ->occupyingStaff($staff->id, $startsAt, $endsAt)
                ->when($existing, fn (Builder $query) => $query->whereKeyNot($existing->id))
                ->lockForUpdate()
                ->exists();

            if (! $offersService || $overlaps) {
                throw ValidationException::withMessages([
                    'starts_at' => $overlaps
                        ? 'That staff member is already booked for this time.'
                        : 'The selected staff member does not offer this service.',
                ]);
            }

            $booking = $existing ?? new self;
            $booking->fill([
                ...$attributes,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'status' => $attributes['status'] ?? BookingStatus::Pending,
            ]);
            $booking->save();

            return $booking;
        });
    }
}
