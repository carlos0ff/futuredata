@props([
    'label'    => null,
    'error'    => null,
    'hint'     => null,
    'iconLeft' => null,
])

@php
$inputClasses = 'h-10 w-full rounded-xl border bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 transition-all outline-none focus:ring-2 '
    . ($error
        ? 'border-red-300 focus:border-red-400 focus:ring-red-100'
        : 'border-slate-200 focus:border-blue-500 focus:ring-blue-100');

if ($iconLeft) {
    $inputClasses .= ' pl-9';
}
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

    <div class="relative">
        @if($iconLeft)
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">{!! $iconLeft !!}</svg>
            </div>
        @endif

        <input {{ $attributes->merge(['class' => $inputClasses]) }}>
    </div>

    @if($error)
        <p class="mt-1 text-[11.5px] text-red-600">{{ $error }}</p>
    @elseif($hint)
        <p class="mt-1 text-[11.5px] text-slate-500">{{ $hint }}</p>
    @endif
</div>
