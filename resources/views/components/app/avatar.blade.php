@props([
    'initials' => '??',
    'size'     => 'md',
])

@php
$sizes = [
    'sm' => 'h-8 w-8 text-[11px]',
    'md' => 'h-10 w-10 text-[13px]',
    'lg' => 'h-12 w-12 text-[15px]',
    'xl' => 'h-14 w-14 text-[18px]',
];

// All variants listed statically so Tailwind doesn't purge them
$gradients = [
    'from-blue-400 to-indigo-600',
    'from-emerald-400 to-teal-600',
    'from-violet-400 to-purple-600',
    'from-rose-400 to-pink-600',
    'from-amber-400 to-orange-500',
    'from-cyan-400 to-blue-500',
];

$idx = abs(crc32((string) $initials)) % count($gradients);
$gradient = $gradients[$idx];
$sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<div {{ $attributes->merge(['class' => "flex shrink-0 items-center justify-center rounded-full bg-gradient-to-br {$gradient} {$sizeClass} font-bold text-white shadow-sm"]) }}>
    {{ $initials }}
</div>
