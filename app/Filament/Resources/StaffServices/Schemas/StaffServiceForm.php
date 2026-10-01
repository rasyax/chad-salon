<?php

namespace App\Filament\Resources\StaffServices\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class StaffServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('staff_id')
                ->relationship('staff', 'name')
                ->searchable()
                ->preload()
                ->required()
                ->label('Staff'),
            Select::make('service_id')
                ->relationship('service', 'name')
                ->searchable()
                ->preload()
                ->required()
                ->label('Service'),
        ]);
    }
}
