<select name="{{ $name }}" {{ $attributes->only('class') }} class="w-full border-0 ring-1 ring-slate-300 px-2.5 py-1.5 text-sm rounded-md focus:ring-2 shadow-sm" >
    @foreach ($values as $value)
        <option value="{{ $value }}" @selected((request($name) ?? '') === $value)>
            {{ $value != '' ? $value : $default }}
        </option>
    @endforeach
</select>