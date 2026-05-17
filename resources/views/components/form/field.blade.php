@props([
    'label'    => null,
    'for'      => null,
    'error'    => null,
    'required' => false,
    'hint'     => null,
])

<div class="w-full">
    @if($label)
        <label
            @if($for) for="{{ $for }}" @endif
            class="mb-1.5 block text-[12.5px] font-semibold text-slate-700"
        >
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    {{ $slot }}

    @if($error)
        <p class="mt-1 flex items-center gap-1 text-[11.5px] text-red-600">
            <svg class="h-3 w-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
            </svg>
            {{ $error }}
        </p>
    @elseif($hint)
        <p class="mt-1 text-[11.5px] text-slate-500">{{ $hint }}</p>
    @endif
</div>
