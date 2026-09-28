<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enum\RoleEnum;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make("name")->required()->placeholder('Ada Lovelace'),
            TextInput::make("email")
                ->label("Email address")
                ->placeholder('adalovelace@example.com')
                ->email()
                ->required(),
            TextInput::make("phone")
                ->label("Phone number")
                ->tel()
                ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/'),
            Select::make("role")->options(RoleEnum::class)->required(),
            DateTimePicker::make("email_verified_at"),
            TextInput::make("password")->password()->required(),
        ]);
    }
}
