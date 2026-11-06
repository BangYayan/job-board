@foreach ($values as $value)
    <label class="flex items-center" {{ $attributes->only('class') }}>
        <input type="radio" name="{{ $name }}" value="{{ $value }}" @checked((request($name) ?? '') === $value) />
        <span class="ml-2 capitalize">
            @if ($value != '')
                {{ $value }}
            @else
                {{ $default }}
            @endif
        </span>
    </label>    
@endforeach
