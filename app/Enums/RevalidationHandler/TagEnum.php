<?php

namespace App\Enums\RevalidationHandler;

use Spatie\Enum\Enum;

/**
 * @method static self Community()
 * @method static self Property()
 */
class TagEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'Community' => 'community',
            'Property' => 'property',
        ];
    }
}
