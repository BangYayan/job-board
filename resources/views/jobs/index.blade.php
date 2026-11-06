<x-layout>
    <x-bread-crumbs class="mb-4" :links="['Jobs' => route('jobs.index')]"></x-bread-crumbs>
    <x-card class="mb-4 text-sm">
        <form action="{{ route('jobs.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <div class="mb-2 font-semibold">Search</div>
                    <x-text-input name="search" placeholder="Search jobs..." value="{{ request('search') }}"/>
                </div>
                <div>
                    <div class="mb-2 font-semibold">Salary</div>
                    <div class="flex gap-x-2">
                        <x-text-input name="min_salary" placeholder="From" value="{{ request('min_salary') }}"/>
                        <x-text-input name="max_salary" placeholder="To" value="{{ request('max_salary') }}"/>
                    </div>
                </div>
                <div>
                    <div class="mb-2 font-semibold">Experience</div>
                    <x-input-radio  name="experience" :values="array_merge([''], App\Models\Job::$experiences)" default="All" />
                </div>
                <div>
                    <div class="mb-2 font-semibold">Category</div>
                    <x-select-input name="category" :values="array_merge([''], App\Models\Job::$categories)" default="All" />
                </div>
            </div>
            <x-button class="w-full cursor-pointer">Filter</x-button>
        </form>
    </x-card>
    @foreach ( $jobs as $job )
        <x-job-card :job="$job">
            <x-link-button href="{{ route('jobs.show', $job) }}">View Details</x-link-button>
        </x-job-card>
    @endforeach
</x-layout>