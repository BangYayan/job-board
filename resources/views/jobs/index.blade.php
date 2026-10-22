<x-layout>
    @foreach ( $jobs as $job )
        <x-card class="mb-4">
            <div class="mb-4 flex justify-between">
                <h2 class="font-medium text-lg">{{ $job->title }}</h2>
                <div class="text-slate-500">${{ number_format($job->salary) }}</div>
            </div>
            <div class="flex justify-between mb-4 text-sm text-slate-500">
                <div class="flex gap-x-4 items-center">
                    <div>Company Name</div>
                    <div>{{ $job->location }}</div>
                </div>
                <div class="flex gap-x-2 text-xs items-center">
                    <x-tag>{{ Str::ucfirst($job->location) }}</x-tag>
                    <x-tag>{{ Str::ucfirst($job->category) }}</x-tag>
                </div>
            </div>
            <div class="text-sm whitespace-pre-line text-slate-500 mb-4">{{ $job->description }}</div>
            <a href="{{ route('jobs.show', $job) }}" >View Details</a>
        </x-card>
    @endforeach
</x-layout>