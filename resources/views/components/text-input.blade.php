<div class="relative" x-data="{ value: @js(old($name, $value ?? '')) }">
    @if ($type !== 'textarea')
        @if ($type !== 'password')
            <button type="button" 
                class="absolute top-0 right-0 flex items-center justify-center h-full p-1.5"
                x-show="value"
                @click="value = ''; $nextTick(() => {
                    const form = {{ $formId ? "document.getElementById('{$formId}')" : "\$el.closest('form')" }};
                    if (form && form.method.toUpperCase() === 'GET') form.submit();
                })">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3.5 text-slate-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        @endif

        <input 
            type="{{ $type }}"
            placeholder="{{ $placeholder }}" 
            x-model="value"
            name="{{ $name }}"  
            id="{{ $name }}"
            {{ $attributes->class([
                'w-full px-2.5 py-1.5 text-sm ring-1 rounded-md placeholder:text-slate-400 border-0 focus:ring-2',
                'pr-8' => $type !== 'password',
                'ring-slate-300' => !$errors->has($name),
                'ring-red-400' => $errors->has($name),
            ]) }}
        />
    @else
        <textarea
            placeholder="{{ $placeholder }}"
            x-model="value"
            name="{{ $name }}"
            id="{{ $name }}"
            {{ $attributes->class([
                'w-full px-2.5 py-1.5 text-sm ring-1 rounded-md placeholder:text-slate-400 border-0 focus:ring-2',
                'ring-slate-300' => !$errors->has($name),
                'ring-red-400' => $errors->has($name),
            ]) }}>{{ old($name, $value ?? '') }}</textarea>
    @endif

    @error($name)
        <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
    @enderror
</div>
