<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required(),
            Select::make('category_id')
                ->label('Category')
                ->relationship('category', 'name')
                ->required(),
            TextInput::make('price')->required()->numeric()->prefix('$'),
            TextInput::make('duration_minutes')->required()->numeric(),
            Textarea::make('description')->required()->columnSpanFull(),
        ]);
    }
}
