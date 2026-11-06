<x-layout>
    <x-bread-crumbs class="mb-4"
        :links="['Jobs' => route('jobs.index'), 'My Job Applications' => '#']"
    />

    @forelse ($applications as $application)
        <x-job-card :job="$application->job">
            <div class="flex items-center justify-between text-xs text-slate-500 pt-2 border-t border-slate-200">
                <div>
                    <div>
                        Applied {{ $application->created_at->diffForHumans() }}
                    </div>
                    <div>
                        Other {{ max(0, $application->job->job_applications_count - 1) }}
                        {{ Str::plural('applicant', max(0, $application->job->job_applications_count - 1)) }}
                    </div>
                    <div>
                        Your asking salary: <span class="font-semibold text-slate-700">${{ number_format($application->expected_salary) }}</span>
                    </div>
                    <div>
                        Average asking salary: <span class="font-semibold text-slate-700">${{ number_format($application->job->job_applications_avg_expected_salary) }}</span>
                    </div>
                </div>

                <div>
                    <form action="{{ route('my-job-applications.destroy', $application) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <x-button class="border-red-300 text-red-600 hover:bg-red-50">
                            Cancel
                        </x-button>
                    </form>
                </div>
            </div>
        </x-job-card>
    @empty
        <x-card class="text-center py-12">
            <div class="text-lg font-medium text-slate-600">No job applications yet</div>
            <div class="mt-2 text-sm text-slate-400">Find your next role and track your applications here.</div>
            <div class="mt-4">
                <x-link-button :href="route('jobs.index')">Browse Jobs</x-link-button>
            </div>
        </x-card>
    @endforelse
</x-layout>

