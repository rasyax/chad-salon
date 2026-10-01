<?php

namespace App\Filament\Resources\Bookings\Tables;

use App\Enum\BookingStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')->label('Customer')->searchable(),
                TextColumn::make('service.name')->label('Service')->searchable(),
                TextColumn::make('staff.name')->label('Staff')->searchable(),
                TextColumn::make('starts_at')->dateTime()->sortable(),
                TextColumn::make('ends_at')->dateTime()->sortable(),
                TextColumn::make('status')->badge(),
            ])
            ->defaultSort('starts_at', 'desc')
            ->filters([
                SelectFilter::make('status')->options(BookingStatus::class),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
