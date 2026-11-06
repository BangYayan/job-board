<x-layout>
    <x-bread-crumbs class="mb-4"
    :links="['Jobs' => route('jobs.index'), $job->title => '#']"/>
    <x-job-card :job="$job">
        <div class="text-sm whitespace-pre-line text-slate-500 mb-4">{{ $job->description }}</div>
    </x-job-card>

    <x-card class="mb-4">
        <h2 class="mb-4 text-lg font-medium">More {{ $job->employer->company_name }}</h2>
        <div class="text-sm text-slate-500">
            @foreach ($job->employer->jobs as $job)
            <div class="flex justify-between space-y-4">
                <div>
                    <div class="text-slate-700">
                        <a href="{{ route('jobs.show', $job) }}">{{ $job->title }}</a>
                    </div>
                    <div class="text-xs">
                        {{ $job->created_at->diffForHumans() }}
                    </div>
                </div>
                <div class="text-xs">
                    ${{ number_format($job->salary) }}
                </div>                
            </div>
            @endforeach
        </div>
    </x-card>
</x-layout>