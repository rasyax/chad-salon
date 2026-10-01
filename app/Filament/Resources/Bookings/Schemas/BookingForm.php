<?php

namespace App\Filament\Resources\Bookings\Schemas;

use App\Enum\BookingStatus;
use App\Enum\RoleEnum;
use App\Models\Service;
use App\Models\Staff;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('user_id')
                ->label('Customer')
                ->relationship(
                    'customer',
                    'name',
                    fn (Builder $query) => $query->where('role', RoleEnum::Customer),
                )
                ->searchable()
                ->preload()
                ->required(),
            Select::make('service_id')
                ->label('Service')
                ->relationship('service', 'name')
                ->searchable()
                ->preload()
                ->live()
                ->afterStateUpdated(function (Set $set, ?string $state, Get $get): void {
                    $set('staff_id', null);
                    self::fillEndsAt($set, $state, $get('starts_at'));
                })
                ->required(),
            Select::make('staff_id')
                ->label('Staff')
                ->options(function (Get $get): array {
                    if (blank($get('service_id'))) {
                        return [];
                    }

                    return Staff::query()
                        ->where('is_active', true)
                        ->whereHas('services', fn (Builder $query) => $query->whereKey($get('service_id')))
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->all();
                })
                ->searchable()
                ->preload()
                ->required(),
            DateTimePicker::make('starts_at')
                ->label('Starts at')
                ->seconds(false)
                ->native(false)
                ->live()
                ->afterStateUpdated(function (Set $set, ?string $state, Get $get): void {
                    self::fillEndsAt($set, $get('service_id'), $state);
                })
                ->required(),
            DateTimePicker::make('ends_at')
                ->label('Ends at')
                ->seconds(false)
                ->disabled()
                ->dehydrated(false)
                ->required(),
            Select::make('status')
                ->options(BookingStatus::class)
                ->default(BookingStatus::Pending)
                ->required(),
        ]);
    }

    private static function fillEndsAt(Set $set, mixed $serviceId, mixed $startsAt): void
    {
        $duration = filled($serviceId)
            ? Service::query()->whereKey($serviceId)->value('duration_minutes')
            : null;

        $set(
            'ends_at',
            filled($duration) && filled($startsAt)
                ? Carbon::parse($startsAt)->addMinutes($duration)
                : null,
        );
    }
}
