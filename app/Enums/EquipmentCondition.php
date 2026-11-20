<?php

namespace App\Enums;

enum EquipmentCondition: string
{
    case Excellent = 'excellent';
    case Good = 'good';
    case Fair = 'fair';
    case NeedsRepair = 'needs_repair';

    public function label(): string
    {
        return match ($this) {
            self::Excellent => 'Sangat Baik',
            self::Good => 'Baik',
            self::Fair => 'Cukup',
            self::NeedsRepair => 'Perlu Perbaikan',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Excellent => 'success',
            self::Good => 'info',
            self::Fair => 'warning',
            self::NeedsRepair => 'danger',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($c) => [$c->value => $c->label()])->all();
    }
}
