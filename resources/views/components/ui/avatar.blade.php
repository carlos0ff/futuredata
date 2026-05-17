@props([
    'initials' => 'U',
    'src'      => null,
    'size'     => 'md',
    'color'    => 'blue',
    'online'   => false,
])

@php
$sizes = [
    'xs' => ['wrapper' => 'h-6 w-6', 'text' => 'text-[9px]', 'indicator' => 'h-[7px] w-[7px] border-[1.5px]'],
    'sm' => ['wrapper' => 'h-7 w-7', 'text' => 'text-[10px]', 'indicator' => 'h-[8px] w-[8px] border-[1.5px]'],
    'md' => ['wrapper' => 'h-9 w-9', 'text' => 'text-[12px]', 'indicator' => 'h-[10px] w-[10px] border-2'],
    'lg' => ['wrapper' => 'h-11 w-11', 'text' => 'text-[13px]', 'indicator' => 'h-3 w-3 border-2'],
];

$colors = [
    'blue'    => 'from-blue-400 via-blue-600 to-indigo-700',
    'emerald' => 'from-emerald-400 to-emerald-600',
    'purple'  => 'from-purple-400 to-purple-600',
    'rose'    => 'from-rose-400 to-pink-600',
    'amber'   => 'from-amber-400 to-orange-500',
    'cyan'    => 'from-cyan-400 to-blue-500',
];

$s = $sizes[$size] ?? $sizes['md'];
$c = $colors[$color] ?? $colors['blue'];
@endphp

<div {{ $attributes->merge(['class' => 'relative inline-flex shrink-0']) }}>
    @if($src)
        <img
            src="{{ $src }}"
            alt="{{ $initials }}"
            class="{{ $s['wrapper'] }} rounded-full object-cover ring-2 ring-white/10"
        >
    @else
        <div class="{{ $s['wrapper'] }} flex items-center justify-center rounded-full bg-gradient-to-br {{ $c }} {{ $s['text'] }} font-bold tracking-wide text-white ring-2 ring-white/10">
            {{ strtoupper(substr($initials, 0, 2)) }}
        </div>
    @endif

    @if($online)
        <span class="absolute -bottom-0.5 -right-0.5 {{ $s['indicator'] }} rounded-full border-white bg-emerald-400"></span>
    @endif
</div>
