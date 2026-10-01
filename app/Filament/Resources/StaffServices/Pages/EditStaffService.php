<?php

namespace App\Filament\Resources\StaffServices\Pages;

use App\Filament\Resources\StaffServices\StaffServiceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStaffService extends EditRecord
{
    protected static string $resource = StaffServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
