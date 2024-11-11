<?php

namespace App\Enums\PropertyType;

use Spatie\Enum\Enum;

/**
 * @method static self Apartment()
 * @method static self Villa()
 * @method static self TownHouse()
 */
class PropertyTypeEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'Apartment' => 'APARTMENT',
            'Villa' => 'VILLA',
            'TownHouse' => 'TOWNHOUSE',
        ];
    }
}
