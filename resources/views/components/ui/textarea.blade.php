@props([
    'label' => null,
    'error' => null,
    'hint'  => null,
    'rows'  => 4,
])

@php
$textareaClasses = 'w-full rounded-xl border bg-white px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 transition-all outline-none focus:ring-2 resize-y '
    . ($error
        ? 'border-red-300 focus:border-red-400 focus:ring-red-100'
        : 'border-slate-200 focus:border-blue-500 focus:ring-blue-100');
@endphp

<div class="w-full">
    @if($label)
        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">
            {{ $label }}
            @if(isset($attributes['required']))
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <textarea rows="{{ $rows }}" {{ $attributes->merge(['class' => $textareaClasses]) }}>{{ $slot }}</textarea>

    @if($error)
        <p class="mt-1 text-[11.5px] text-red-600">{{ $error }}</p>
    @elseif($hint)
        <p class="mt-1 text-[11.5px] text-slate-500">{{ $hint }}</p>
    @endif
</div>
