<x-layout>
    <x-bread-crumbs class="mb-4"
        :links="['Jobs' => route('jobs.index'), 'My Jobs' => route('my-jobs.index'), 'Post a Job' => '#']"
    />

    <x-card class="mb-8">
        <h1 class="mb-6 text-2xl font-semibold text-slate-800">Post a New Job</h1>

        <form action="{{ route('my-jobs.store') }}" method="POST">
            @csrf

            <div class="mb-4 grid grid-cols-2 gap-4">
                <div>
                    <label for="title" class="mb-2 block text-sm font-medium text-slate-900">Job Title</label>
                    <x-text-input name="title" placeholder="Senior Backend Developer" />
                </div>

                <div>
                    <label for="location" class="mb-2 block text-sm font-medium text-slate-900">Location</label>
                    <x-text-input name="location" placeholder="Remote / New York" />
                </div>

                <div class="col-span-2">
                    <label for="salary" class="mb-2 block text-sm font-medium text-slate-900">Salary ($)</label>
                    <x-text-input type="number" name="salary" placeholder="85000" />
                </div>

                <div class="col-span-2">
                    <label for="description" class="mb-2 block text-sm font-medium text-slate-900">Job Description</label>
                    <x-text-input type="textarea" name="description" placeholder="Describe the role, responsibilities, and qualifications..." />
                </div>

                <div>
                    <label for="experience" class="mb-2 block text-sm font-medium text-slate-900">Experience Level</label>
                    <x-input-radio name="experience" :values="App\Models\Job::$experiences" />
                    @error('experience')
                        <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="category" class="mb-2 block text-sm font-medium text-slate-900">Category</label>
                    <x-select-input name="category" :values="App\Models\Job::$categories" default="Select a Category" />
                    @error('category')
                        <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <x-button class="w-full">Create Job</x-button>
        </form>
    </x-card>
</x-layout>

