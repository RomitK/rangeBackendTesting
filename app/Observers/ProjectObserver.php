<?php

namespace App\Observers;

use App\Models\Project;
use Illuminate\Support\Facades\Cache;

class ProjectObserver
{

    public function updated(Project $project)
    {
        Cache::forget('homeProjects');
    }
}
