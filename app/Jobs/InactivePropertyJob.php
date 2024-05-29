<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\{
    Property,
    Project
};
use DB;

class InactivePropertyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("InactivePropertyJob-start-time: " . Carbon::now());
        DB::beginTransaction();
        try {
            $projects = Project::active()->mainProject()->pluck('id')->toArray();
            foreach ($projects as $project) {
                $subprojects = Project::where('is_parent_project', 0)->where('parent_project_id', $project)->pluck('id')->toArray();
                foreach ($subprojects  as $subproject) {
                    Log::info('project_id' . $project);
                    Log::info('sub_project_id' . $subproject);

                    $lowestPricePropertyId = Property::where('project_id', $project)
                        ->where('sub_project_id', $subproject)
                        ->orderBy('price')
                        ->active()
                        ->approved()
                        ->value('id');

                    Log::info("lowest priece property-" . $lowestPricePropertyId);

                    Property::where('project_id', $project)
                        ->where('sub_project_id', $subproject)
                        ->active()
                        ->approved()
                        ->where('id', '!=', $lowestPricePropertyId)
                        ->update(['status' => 'Inactive']);

                    Log::info("other lowest priece property-");
                }
            }


            // $withotProjects = Project::active()->mainProject()->whereNull('permit_number')->pluck('id')->toArray();

            // foreach ($withotProjects as $project) {

            //     Property::where('project_id', $project)
            //         ->active()
            //         ->approved()
            //         ->where('id', '!=', $lowestPricePropertyId)
            //         ->update(['status' => 'Inactive']);
            //     Log::info("other lowest priece property-");
            // }

            DB::commit();
        } catch (\Exception $error) {
            Log::info("Property-error" . $error->getMessage());
        }

        Log::info("InactivePropertyJob-end-time: " . Carbon::now());
    }
}
