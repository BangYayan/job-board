<x-layout>
    <x-bread-crumbs class="mb-4"
        :links="['Jobs' => route('jobs.index'), 'My Jobs' => '#']"
    />

    <div class="mb-8 text-right">
        <x-link-button :href="route('my-jobs.create')">Post a New Job</x-link-button>
    </div>

    @forelse ($jobs as $job)
        <x-card class="mb-4">
            <div class="mb-4 flex justify-between">
                <div>
                    <div class="text-lg font-medium">
                        <a href="{{ route('jobs.show', $job) }}" class="hover:underline">{{ $job->title }}</a>
                    </div>
                    <div class="text-sm text-slate-500">
                        {{ $job->employer->company_name }} · {{ $job->location }}
                    </div>
                </div>

                <div class="text-slate-500 font-semibold">
                    ${{ number_format($job->salary) }}
                </div>
            </div>

            <div class="flex items-center justify-between text-xs text-slate-500 mb-4 pb-4 border-b border-slate-200">
                <div class="flex gap-x-2">
                    <x-tag>{{ $job->experience }}</x-tag>
                    <x-tag>{{ $job->category }}</x-tag>
                </div>

                <div class="flex items-center space-x-2">
                    <x-link-button :href="route('my-jobs.edit', $job)">Edit</x-link-button>
                    <form action="{{ route('my-jobs.destroy', $job) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <x-button class="border-red-300 text-red-600 hover:bg-red-50">Delete</x-button>
                    </form>
                </div>
            </div>

            <div class="mt-4">
                <h3 class="mb-3 text-sm font-semibold text-slate-700">
                    Applications ({{ $job->jobApplications->count() }})
                </h3>

                @forelse ($job->jobApplications as $application)
                    <div class="mb-3 flex items-center justify-between rounded-md bg-slate-50 p-3 text-xs text-slate-600">
                        <div>
                            <div class="font-medium text-slate-800">{{ $application->user->name }}</div>
                            <div class="text-slate-400">Applied {{ $application->created_at->diffForHumans() }}</div>
                            <div class="mt-1">Asking Salary: <span class="font-semibold text-slate-700">${{ number_format($application->expected_salary) }}</span></div>
                        </div>

                        <div>
                            @if ($application->cv_path)
                                <a href="{{ route('my-jobs.cv', ['job' => $job, 'application' => $application]) }}"
                                    class="rounded-md border border-slate-300 bg-white px-2.5 py-1 font-semibold text-slate-700 shadow-sm hover:bg-slate-100">
                                    Download CV
                                </a>
                            @else
                                <span class="text-slate-400">No CV</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-xs text-slate-400">No applications received yet.</div>
                @endforelse
            </div>
        </x-card>
    @empty
        <x-card class="text-center py-12">
            <div class="text-lg font-medium text-slate-600">No jobs posted yet</div>
            <div class="mt-2 text-sm text-slate-400">Get started by creating your first job posting.</div>
            <div class="mt-4">
                <x-link-button :href="route('my-jobs.create')">Post a Job</x-link-button>
            </div>
        </x-card>
    @endforelse
</x-layout>

