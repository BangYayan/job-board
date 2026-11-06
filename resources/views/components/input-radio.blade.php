@foreach ($values as $val)
    <label class="flex items-center" {{ $attributes->only('class') }}>
        <input type="radio" name="{{ $name }}" value="{{ $val }}"
            @checked(old($name, $value ?? request($name) ?? '') === $val) />
        <span class="ml-2 capitalize">
            @if ($val != '')
                {{ $val }}
            @else
                {{ $default }}
            @endif
        </span>
    </label>    
@endforeach
