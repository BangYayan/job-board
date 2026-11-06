<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'min_salary', 'max_salary', 'experience', 'category']);

        $version = Cache::rememberForever('jobs_list_cache_version', fn () => 1);
        $cacheKey = 'jobs_list:v' . $version . ':' . md5(http_build_query($filters));

        $jobIds = Cache::remember($cacheKey, now()->addMinutes(60), function () use ($filters) {
            return Job::filter($filters)
                ->latest()
                ->pluck('id')
                ->all();
        });

        $jobs = Job::with('employer')
            ->whereIn('id', $jobIds)
            ->latest()
            ->get();

        return view('jobs.index', ['jobs' => $jobs]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Job $job): View
    {
        $job->load([
            'employer.jobs' => fn ($query) => $query->where('id', '!=', $job->id),
        ]);

        return view('jobs.show', ['job' => $job]);
    }
}
