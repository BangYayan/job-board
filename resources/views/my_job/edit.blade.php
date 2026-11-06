<x-layout>
    <x-bread-crumbs class="mb-4"
        :links="[
            'Jobs' => route('jobs.index'),
            'My Jobs' => route('my-jobs.index'),
            'Edit ' . $job->title => '#',
        ]"
    />

    <x-card class="mb-8">
        <h1 class="mb-6 text-2xl font-semibold text-slate-800">Edit Job Listing</h1>

        <form action="{{ route('my-jobs.update', $job) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4 grid grid-cols-2 gap-4">
                <div>
                    <label for="title" class="mb-2 block text-sm font-medium text-slate-900">Job Title</label>
                    <x-text-input name="title" :value="$job->title" placeholder="Senior Backend Developer" />
                </div>

                <div>
                    <label for="location" class="mb-2 block text-sm font-medium text-slate-900">Location</label>
                    <x-text-input name="location" :value="$job->location" placeholder="Remote / New York" />
                </div>

                <div class="col-span-2">
                    <label for="salary" class="mb-2 block text-sm font-medium text-slate-900">Salary ($)</label>
                    <x-text-input type="number" name="salary" :value="$job->salary" placeholder="85000" />
                </div>

                <div class="col-span-2">
                    <label for="description" class="mb-2 block text-sm font-medium text-slate-900">Job Description</label>
                    <x-text-input type="textarea" name="description" :value="$job->description" placeholder="Describe the role..." />
                </div>

                <div>
                    <label for="experience" class="mb-2 block text-sm font-medium text-slate-900">Experience Level</label>
                    <x-input-radio name="experience" :values="App\Models\Job::$experiences" :value="$job->experience" />
                    @error('experience')
                        <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="category" class="mb-2 block text-sm font-medium text-slate-900">Category</label>
                    <x-select-input name="category" :values="App\Models\Job::$categories" :value="$job->category" default="Select a Category" />
                    @error('category')
                        <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <x-button class="w-full">Save Changes</x-button>
        </form>
    </x-card>
</x-layout>

