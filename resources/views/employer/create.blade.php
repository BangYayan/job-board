<x-layout>
    <x-card class="py-8 px-16">
        <h1 class="mb-6 text-2xl font-semibold text-slate-800">
            Create Employer Profile
        </h1>
        <p class="mb-8 text-sm text-slate-500">
            Provide your company details so you can start posting jobs and reviewing applicants.
        </p>

        <form action="{{ route('employer.store') }}" method="POST">
            @csrf

            <div class="mb-8">
                <label for="company_name" class="mb-2 block text-sm font-medium text-slate-900">
                    Company Name
                </label>
                <x-text-input name="company_name" placeholder="Acme Inc." />
            </div>

            <x-button class="w-full">Create Profile</x-button>
        </form>
    </x-card>
</x-layout>

