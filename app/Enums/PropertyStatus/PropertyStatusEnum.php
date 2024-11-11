<?php

namespace App\Enums\PropertyStatus;

use Spatie\Enum\Enum;

/**
 * @method static self Ready()
 * @method static self OffPlan()
 */
class PropertyStatusEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'Ready' => 'READY',
            'OffPlan' => 'OFF_PLAN',
        ];
    }
}
