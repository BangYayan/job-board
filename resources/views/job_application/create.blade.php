<x-layout>
    <x-bread-crumbs class="mb-4"
        :links="[
            'Jobs' => route('jobs.index'),
            $job->title => route('jobs.show', $job),
            'Apply' => '#',
        ]"
    />

    <x-job-card :job="$job" />

    <x-card>
        <h2 class="mb-4 text-lg font-medium">Your Job Application</h2>

        <form action="{{ route('job.application.store', $job) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="expected_salary" class="mb-2 block text-sm font-medium text-slate-900">
                    Expected Salary ($)
                </label>
                <x-text-input type="number" name="expected_salary" placeholder="e.g. 75000" />
            </div>

            <div class="mb-6">
                <label for="cv" class="mb-2 block text-sm font-medium text-slate-900">
                    Upload CV (PDF, max 2MB)
                </label>
                <input type="file" name="cv" id="cv"
                    class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-slate-100 hover:file:bg-slate-200 cursor-pointer" />
                @error('cv')
                    <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                @enderror
            </div>

            <x-button class="w-full">Submit Application</x-button>
        </form>
    </x-card>
</x-layout>

