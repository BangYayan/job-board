<?php

namespace App\Observers;

use App\Models\Job;
use Illuminate\Support\Facades\Cache;

class JobObserver
{
    /**
     * Handle the Job "saved" event (covers created and updated).
     */
    public function saved(Job $job): void
    {
        $this->invalidateCache($job);
    }

    /**
     * Handle the Job "deleted" event.
     */
    public function deleted(Job $job): void
    {
        $this->invalidateCache($job);
    }

    /**
     * Handle the Job "restored" event.
     */
    public function restored(Job $job): void
    {
        $this->invalidateCache($job);
    }

    /**
     * Invalidate both individual job cache and the list cache version.
     */
    protected function invalidateCache(Job $job): void
    {
        Cache::forget("job:{$job->id}");

        if (Cache::has('jobs_list_cache_version')) {
            Cache::increment('jobs_list_cache_version');
        } else {
            Cache::forever('jobs_list_cache_version', 2);
        }
    }
}

