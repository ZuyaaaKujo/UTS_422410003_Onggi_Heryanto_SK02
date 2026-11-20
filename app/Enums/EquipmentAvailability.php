<?php

namespace App\Enums;

enum EquipmentAvailability: string
{
    case Available = 'available';
    case InUse = 'in_use';
    case Maintenance = 'maintenance';

    public function label(): string
    {
        return match ($this) {
            self::Available => 'Tersedia',
            self::InUse => 'Sedang Digunakan',
            self::Maintenance => 'Perawatan',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Available => 'success',
            self::InUse => 'info',
            self::Maintenance => 'warning',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($c) => [$c->value => $c->label()])->all();
    }
}
