<?php

namespace App\Enums\RevalidationHandler;

use Spatie\Enum\Enum;

/**
 * @method static self Community()
 * @method static self Property()
 * @method static self Careers()
 * @method static self Developers()
 * @method static self Faq()
 * @method static self Home()
 * @method static self Managements()
 * @method static self Medias()
 * @method static self Projects()
 * @method static self NearbyProjects()
 * @method static self Meta()
 * @method static self Agents()
 * @method static self Testimonials()
 * @method static self Media()
 */
class TagEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'Community' => 'communities',
            'Property' => 'properties',
            'Careers' => 'careers',
            'Developers' => 'developers',
            'Faq' => 'faq',
            'Home' => 'home',
            'Managements' => 'managements',
            'Medias' => 'medias',
            'Projects' => 'projects',
            'NearbyProjects' => 'nearby-projects',
            'Meta' => 'meta',
            'Agents' => 'agents',
            'Testimonials' => 'testimonials',
            'Media' => 'media',
        ];
    }
}
