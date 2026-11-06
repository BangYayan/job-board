<x-layout>
    <x-bread-crumbs class="mb-4"
        :links="['Jobs' => route('jobs.index'), $job->title => '#']"
    />

    <x-job-card :job="$job">
        <div class="text-sm whitespace-pre-line text-slate-500 mb-4">{{ $job->description }}</div>

        @can('apply', $job)
            <x-link-button :href="route('job.application.create', $job)">Apply</x-link-button>
        @else
            @auth
                @if ($job->hasUserApplied(auth()->user()))
                    <div class="text-center text-sm font-medium text-slate-500">
                        You have already applied to this job
                    </div>
                @elseif ($job->employer && $job->employer->user_id === auth()->id())
                    <div class="text-center text-sm font-medium text-slate-500">
                        This is your job listing
                    </div>
                @endif
            @else
                <x-link-button :href="route('login')">Sign in to Apply</x-link-button>
            @endauth
        @endcan
    </x-job-card>

    <x-card class="mb-4">
        <h2 class="mb-4 text-lg font-medium">More from {{ $job->employer?->company_name ?? 'this Employer' }}</h2>
        <div class="text-sm text-slate-500 space-y-4">
            @forelse ($job->employer?->jobs?->where('id', '!=', $job->id) ?? [] as $otherJob)
                <div class="flex justify-between items-center border-b border-slate-100 pb-3 last:border-b-0 last:pb-0">
                    <div>
                        <div class="font-medium text-slate-700">
                            <a href="{{ route('jobs.show', $otherJob) }}" class="hover:underline">
                                {{ $otherJob->title }}
                            </a>
                        </div>
                        <div class="text-xs text-slate-400">
                            {{ $otherJob->created_at->diffForHumans() }}
                        </div>
                    </div>
                    <div class="text-xs font-semibold text-slate-600">
                        ${{ number_format($otherJob->salary) }}
                    </div>
                </div>
            @empty
                <div class="text-slate-400">No other jobs listed yet.</div>
            @endforelse
        </div>
    </x-card>
</x-layout>
