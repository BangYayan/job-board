<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobRequest;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MyJobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $jobs = $request->user()->employer->jobs()
            ->with(['employer', 'jobApplications.user'])
            ->latest()
            ->get();

        return view('my_job.index', ['jobs' => $jobs]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('create', Job::class);

        return view('my_job.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JobRequest $request): RedirectResponse
    {
        Gate::authorize('create', Job::class);

        $request->user()->employer->jobs()->create($request->validated());

        return redirect()->route('my-jobs.index')
            ->with('success', 'Job created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Job $myJob): View
    {
        Gate::authorize('update', $myJob);

        return view('my_job.edit', ['job' => $myJob]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JobRequest $request, Job $myJob): RedirectResponse
    {
        Gate::authorize('update', $myJob);

        $myJob->update($request->validated());

        return redirect()->route('my-jobs.index')
            ->with('success', 'Job updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Job $myJob): RedirectResponse
    {
        Gate::authorize('delete', $myJob);

        $myJob->delete();

        return redirect()->route('my-jobs.index')
            ->with('success', 'Job deleted successfully!');
    }

    /**
     * Download an applicant's CV for a specific job.
     */
    public function downloadCv(Job $job, JobApplication $application): StreamedResponse
    {
        abort_unless(
            $job->employer->user_id === auth()->id() && $application->job_id === $job->id,
            403
        );

        if (! $application->cv_path || ! Storage::exists($application->cv_path)) {
            abort(404, 'CV file not found.');
        }

        return Storage::download($application->cv_path);
    }
}

