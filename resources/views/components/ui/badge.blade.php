@props([
    'variant' => 'default',
    'dot'     => false,
    'pulse'   => false,
])

@php
$variants = [
    'default' => 'border-slate-200 bg-slate-100 text-slate-700',
    'primary' => 'border-blue-200 bg-blue-50 text-blue-700',
    'success' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
    'warning' => 'border-amber-200 bg-amber-50 text-amber-700',
    'danger'  => 'border-red-200 bg-red-50 text-red-700',
    'info'    => 'border-cyan-200 bg-cyan-50 text-cyan-700',
];

$dotColors = [
    'default' => 'bg-slate-400',
    'primary' => 'bg-blue-500',
    'success' => 'bg-emerald-500',
    'warning' => 'bg-amber-500',
    'danger'  => 'bg-red-500',
    'info'    => 'bg-cyan-500',
];

$classes = 'inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11.5px] font-semibold leading-none '
    . ($variants[$variant] ?? $variants['default']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    @if($dot)
        <span class="relative flex h-1.5 w-1.5 shrink-0">
            @if($pulse)
                <span class="absolute inline-flex h-full w-full animate-ping rounded-full {{ $dotColors[$variant] ?? 'bg-slate-400' }} opacity-75"></span>
            @endif
            <span class="relative inline-flex h-1.5 w-1.5 rounded-full {{ $dotColors[$variant] ?? 'bg-slate-400' }}"></span>
        </span>
    @endif
    {{ $slot }}
</span>
