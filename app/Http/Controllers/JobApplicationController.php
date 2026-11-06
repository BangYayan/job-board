<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class JobApplicationController extends Controller
{
    /**
     * Show the form for creating a new job application.
     */
    public function create(Job $job): View
    {
        Gate::authorize('apply', $job);

        return view('job_application.create', ['job' => $job]);
    }

    /**
     * Store a newly created job application in storage.
     */
    public function store(Job $job, Request $request): RedirectResponse
    {
        Gate::authorize('apply', $job);

        $validated = $request->validate([
            'expected_salary' => ['required', 'numeric', 'min:1', 'max:1000000'],
            'cv' => ['required', 'file', 'mimes:pdf', 'max:2048'],
        ]);

        $file = $request->file('cv');
        $path = $file->store('cvs', 'local');

        $job->jobApplications()->create([
            'user_id' => $request->user()->id,
            'expected_salary' => (int) $validated['expected_salary'],
            'cv_path' => $path,
        ]);

        return redirect()->route('jobs.show', $job)
            ->with('success', 'Job application submitted successfully.');
    }
}

