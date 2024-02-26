<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\{
    Property,
    Amenity,
    Accommodation,
    Category,
    CompletionStatus,
    Community,
    Developer,
    Feature,
    OfferType,
    Agent,
    PropertyBedroom,
    PropertyDetail,
    Subcommunity,
    Project,
    TagCategory,
    MetaDetail,
    ProjectAmenity,
    Highlight,
    ProjectDetail,
    User
};
use Carbon\Carbon;
use PDF;

class StoreProjectBrochure implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $projectId;

    public function __construct($projectId)
    {
        $this->projectId = $projectId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("StoreProjectBrochure-start-time: " . Carbon::now());
        Log::info("project-id-start: " . $this->projectId);
        try {
            $project = Project::find($this->projectId);
            if ($project) {
                $minBed = $project->subProjects->min('bedrooms');
                $maxBed = $project->subProjects->max('bedrooms');
                if ($minBed != $maxBed) {
                    if ($maxBed === "Studio") {
                        $bedroom = $maxBed . "-" . $minBed;
                    } else {
                        $bedroom = $minBed . "-" . $maxBed;
                    }
                } else {
                    $bedroom = $minBed;
                }
                $area_unit = 'sq ft';

                $starting_price = 0;
                $dateStr = $project->completion_date;
                $month = date("n", strtotime($dateStr));
                $yearQuarter = ceil($month / 3);

                view()->share([
                    'project' => $project,
                    'area_unit' => $area_unit,
                    'starting_price' => count($project->subProjects) > 0 ? $project->subProjects->where('starting_price', $project->subProjects->min('starting_price'))->first()->starting_price : 0,
                    'bedrooms' => $bedroom,
                    'handOver' => "Q" . $yearQuarter . " " . date("Y", strtotime($dateStr)),
                    'communityName' => $project->mainCommunity ? $project->mainCommunity->name : '',

                ]);
                $pdf = PDF::loadView('pdf.projectBrochure');
                //return $pdf->stream();
                //return $pdf->download($project->title.' Brochure.pdf');

                // $pdfContent = $this->generateBrochure($project);
                $pdfContent = $pdf->output();


                $project->clearMediaCollection('brochures');

                $project->addMediaFromString($pdfContent)
                    ->usingFileName($project->title . '-brochure.pdf')
                    ->toMediaCollection('brochures', 'projectFiles');

                $project->save();

                $project->brochure_link = $project->brochure;
                $project->updated_brochure = 1;
                $project->save();
            }
            Log::info("Project Brchure has been updated successfully.");
        } catch (\Exception $error) {
            Log::info("project-error" . $error->getMessage());
        }

        Log::info("project-id-end: " . $this->projectId);
        Log::info("StoreProjectBrochure-end-time: " . Carbon::now());
    }
}
