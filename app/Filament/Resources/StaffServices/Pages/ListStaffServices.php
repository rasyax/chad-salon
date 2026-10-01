<?php

namespace App\Filament\Resources\StaffServices\Pages;

use App\Filament\Resources\StaffServices\StaffServiceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStaffServices extends ListRecords
{
    protected static string $resource = StaffServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
