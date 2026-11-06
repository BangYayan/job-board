<?php

namespace App\Observers;

use App\Models\JobApplication;
use Illuminate\Support\Facades\Cache;

class JobApplicationObserver
{
    /**
     * Handle the JobApplication "created" event.
     */
    public function created(JobApplication $jobApplication): void
    {
        Cache::forget("job:{$jobApplication->job_id}");
    }

    /**
     * Handle the JobApplication "deleted" event.
     */
    public function deleted(JobApplication $jobApplication): void
    {
        Cache::forget("job:{$jobApplication->job_id}");
    }
}

