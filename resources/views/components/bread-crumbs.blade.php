@props(['links'])

<nav {{ $attributes->only('class') }}>
    <ul class="flex gap-x-2 text-slate-500">
        <li><a href="/">Home</a></li>
        @foreach ($links as $label => $url)
            <li>→</li>
            <li><a href="{{ $url }}">{{ $label }}</a></li>
        @endforeach
    </ul>
</nav>