<?php

namespace App\Filament\Resources\Staff\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class StaffForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make("name")->required(),
            Select::make("user_id")
                ->relationship(
                    "user",
                    "name",
                    fn(Builder $query) => $query->where("role", "staff"),
                )
                ->nullable()
                ->searchable()
                ->preload()
                ->label("User staff account (Optional)"),
            TextInput::make("phone")->tel()->required(),
            TextInput::make("position")->required(),
            Toggle::make("is_active")->required(),
        ]);
    }
}
