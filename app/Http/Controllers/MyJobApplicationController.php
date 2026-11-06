<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MyJobApplicationController extends Controller
{
    /**
     * Display a listing of the user's job applications.
     */
    public function index(Request $request): View
    {
        $applications = $request->user()->jobApplications()
            ->with([
                'job' => fn ($query) => $query->with('employer')
                    ->withCount('jobApplications')
                    ->withAvg('jobApplications', 'expected_salary'),
            ])
            ->latest()
            ->get();

        return view('my_job_application.index', ['applications' => $applications]);
    }

    /**
     * Remove the specified job application from storage.
     */
    public function destroy(JobApplication $myJobApplication): RedirectResponse
    {
        abort_if($myJobApplication->user_id !== auth()->id(), 403);

        if ($myJobApplication->cv_path) {
            Storage::delete($myJobApplication->cv_path);
        }

        $myJobApplication->delete();

        return back()->with('success', 'Job application cancelled successfully.');
    }
}

