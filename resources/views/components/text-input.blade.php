<div class="relative" x-data="{ value: @js($value ?? '') }">
    
        <button type="button" 
        class="absolute top-0 right-0 flex items-center justify-center h-full p-1.5"
        x-show="value"
        @click="value = ''; $nextTick(() => $el.closest('form').submit())">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3.5 text-slate-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>        

    <input 
    placeholder="{{ $placeholder }}" 
    x-model="value"
    name="{{ $name }}"  
    id="{{ $name }}"
    class="w-full px-2.5 py-1.5 text-sm ring-1 ring-slate-300 rounded-md placeholder:text-slate-400 border-0 focus:ring-2 pr-8"/>
</div>