<?php

namespace App\Actions;

use App\Enums\RevalidationHandler\TagEnum;
use App\Models\Career;
use App\Models\Community;
use App\Models\Developer;
use App\Models\PageTag;
use App\Models\Project;
use App\Models\Property;

class RevalidateDataAction
{
    public function execute():array
    {
        $data = [
            'agents' => [
                'tag' => TagEnum::Agents()->value ,
                'slug_available' => false
            ],
            'articles' => [
                'tag' => TagEnum::Media()->value ,
                'slug_available' => false
            ],
            'careers' => [
                'tag' => TagEnum::Careers()->value,
                'slug_available' => true
            ],
            'community' => [
                'tag' => TagEnum::Community()->value,
                'slug_available' => true
            ],
            'developers' => [
                'tag' => TagEnum::Property()->value ,
                'slug_available' => true
            ],
            'faq' => [
                'tag' => TagEnum::Faq()->value ,
                'slug_available' => false
            ],
            'meta' => [
                'tag' => TagEnum::Meta()->value ,
                'slug_available' => true
            ],
            'projects' => [
                'tag' => TagEnum::Projects()->value ,
                'slug_available' => true
            ],
            'property' => [
                'tag' => TagEnum::Property()->value,
                'slug_available' => true
            ],
            'testimonials' => [
                'tag' => TagEnum::Testimonials()->value ,
                'slug_available' => false
            ]
        ];

        return $data ;
    }
}
