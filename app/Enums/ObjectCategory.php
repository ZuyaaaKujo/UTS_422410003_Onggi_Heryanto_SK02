<?php

namespace App\Enums;

enum ObjectCategory: string
{
    case Planet = 'planet';
    case Star = 'star';
    case Galaxy = 'galaxy';
    case Nebula = 'nebula';
    case NaturalSatellite = 'natural_satellite';
    case StarCluster = 'star_cluster';
    case Phenomenon = 'phenomenon';

    public function label(): string
    {
        return match ($this) {
            self::Planet => 'Planet',
            self::Star => 'Bintang',
            self::Galaxy => 'Galaksi',
            self::Nebula => 'Nebula',
            self::NaturalSatellite => 'Satelit Alami',
            self::StarCluster => 'Gugus Bintang',
            self::Phenomenon => 'Fenomena Astronomi',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Planet => 'warning',
            self::Star => 'info',
            self::Galaxy => 'violet',
            self::Nebula => 'pink',
            self::NaturalSatellite => 'secondary',
            self::StarCluster => 'primary',
            self::Phenomenon => 'success',
        };
    }

    /** Gambar fallback lokal per kategori (tidak bergantung pada internet). */
    public function fallbackImage(): string
    {
        return asset('assets/images/objects/' . $this->value . '.svg');
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($c) => [$c->value => $c->label()])->all();
    }
}
