@props([
    'field' => '',
])

@error($field)
    <p class="mt-1 flex items-center gap-1 text-[11.5px] text-red-600">
        <svg class="h-3 w-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
        </svg>
        {{ $message }}
    </p>
@enderror
