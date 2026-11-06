<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Job Board</title>
    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])
</head>
<body class="mx-auto mt-10 max-w-2xl bg-slate-200 text-slate-700 pb-16">
    <nav class="mb-8 flex justify-between text-lg font-medium">
        <ul class="flex space-x-2">
            <li>
                <a href="{{ route('jobs.index') }}">Home</a>
            </li>
        </ul>

        <ul class="flex space-x-4">
            @auth
                <li>
                    <a href="{{ route('my-job-applications.index') }}">
                        {{ auth()->user()->name ?? 'User' }}: Applications
                    </a>
                </li>
                <li>
                    <a href="{{ route('my-jobs.index') }}">My Jobs</a>
                </li>
                <li>
                    <form action="{{ route('auth.destroy', 'auth') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="hover:underline">Logout</button>
                    </form>
                </li>
            @else
                <li>
                    <a href="{{ route('login') }}" class="hover:underline">Sign in</a>
                </li>
            @endauth
        </ul>
    </nav>

    @if (session('success'))
        <div role="alert" class="my-8 rounded-md border-l-4 border-green-300 bg-green-100 p-4 text-green-700">
            <p class="font-bold">Success!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div role="alert" class="my-8 rounded-md border-l-4 border-red-300 bg-red-100 p-4 text-red-700">
            <p class="font-bold">Error!</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    {{ $slot }}
</body>
</html>
