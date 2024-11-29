<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Property;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClearDuplicatedAmenitiesEntriesForProjectsAndProperties extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $project = Project::withTrashed()->findOrFail(2633);
//        $amenities = $project->amenities->pluck('id')->unique()->toArray();
//
//        $this->command->info(count($amenities)."aa");

        /** @var Project $project */
        foreach (Project::withTrashed()->cursor() as $project) {
            $amenities = $project->amenities->pluck('id')->unique()->toArray();
            $project->amenities()->detach();
            $project->amenities()->attach($amenities);
        }

        /** @var Property $project */
        foreach (Property::withTrashed()->cursor() as $property) {
            $amenities = $property->amenities->pluck('id')->unique()->toArray();
            $property->amenities()->detach();
            $property->amenities()->sync($amenities);
        }
    }
}
