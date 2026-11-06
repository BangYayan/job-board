<x-card class="mb-4">
    <div class="mb-4 flex justify-between">
            <h2 class="font-medium text-lg">{{ $job->title }}</h2>
            <div class="text-slate-500">${{ number_format($job->salary) }}</div>
    </div>
    <div class="flex justify-between mb-4 text-sm text-slate-500">
        <div class="flex gap-x-4 items-center">
            <div>{{ $job->employer?->company_name ?? 'Company Name' }}</div>
            <div>{{ $job->location }}</div>
        </div>
        <div class="flex gap-x-2 text-xs items-center">
            <x-tag>
                <a href="{{ route('jobs.index', ['experience' => $job->experience]) }}"> {{ $job->experience }} </a>
            </x-tag>
            <x-tag>
                <a href="{{ route('jobs.index', ['category' => $job->category]) }}"> {{ $job->category }} </a>
            </x-tag>
        </div>
    </div>
    {{ $slot }}
</x-card>