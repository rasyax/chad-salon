<?php

namespace App\Enum;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum RoleEnum: string implements HasLabel
{
    case Admin = "admin";
    case Customer = "customer";
    case Staff = "staff";

    public function getLabel(): string | Htmlable | null
    {
        return match ($this) {
            self::Admin => "Admin",
            self::Customer => "Customer",
            self::Staff => "Staff",
        };
    }
}
