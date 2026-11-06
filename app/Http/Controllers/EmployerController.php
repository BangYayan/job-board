<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployerController extends Controller
{
    /**
     * Show the form for creating a new employer profile.
     */
    public function create(Request $request): View|RedirectResponse
    {
        if ($request->user()->employer !== null) {
            return redirect()->route('my-jobs.index');
        }

        return view('employer.create');
    }

    /**
     * Store a newly created employer profile in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        abort_if($request->user()->employer !== null, 403);

        $validated = $request->validate([
            'company_name' => ['required', 'string', 'min:3', 'max:255', 'unique:employers,company_name'],
        ]);

        $request->user()->employer()->create($validated);

        return redirect()->route('my-jobs.index')
            ->with('success', 'Your employer account has been created!');
    }
}

