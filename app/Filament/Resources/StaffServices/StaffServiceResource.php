<?php

namespace App\Filament\Resources\StaffServices;

use App\Filament\Resources\StaffServices\Pages\CreateStaffService;
use App\Filament\Resources\StaffServices\Pages\EditStaffService;
use App\Filament\Resources\StaffServices\Pages\ListStaffServices;
use App\Filament\Resources\StaffServices\Schemas\StaffServiceForm;
use App\Filament\Resources\StaffServices\Tables\StaffServicesTable;
use App\Models\StaffService;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StaffServiceResource extends Resource
{
    protected static ?string $model = StaffService::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return StaffServiceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StaffServicesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStaffServices::route('/'),
            'create' => CreateStaffService::route('/create'),
            'edit' => EditStaffService::route('/{record}/edit'),
        ];
    }
}
