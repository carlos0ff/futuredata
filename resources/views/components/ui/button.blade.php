@props([
    'variant' => 'primary',
    'size'    => 'md',
    'href'    => null,
    'type'    => 'button',
    'iconLeft'  => null,
    'iconRight' => null,
])

@php
$variants = [
    'primary'   => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500/30 shadow-sm',
    'secondary' => 'border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 focus:ring-slate-300/30 shadow-sm',
    'danger'    => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500/30 shadow-sm',
    'ghost'     => 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 focus:ring-slate-300/30',
    'success'   => 'bg-emerald-600 text-white hover:bg-emerald-700 focus:ring-emerald-500/30 shadow-sm',
];

$sizes = [
    'xs' => 'h-7 gap-1.5 rounded-lg px-2.5 text-[11.5px]',
    'sm' => 'h-8 gap-2 rounded-lg px-3 text-[12.5px]',
    'md' => 'h-9 gap-2 rounded-xl px-4 text-[13.5px]',
    'lg' => 'h-10 gap-2 rounded-xl px-5 text-sm',
    'xl' => 'h-12 gap-2.5 rounded-xl px-6 text-base',
];

$base = 'inline-flex items-center justify-center font-semibold transition-all duration-150 focus:outline-none focus:ring-2 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer';
$classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($iconLeft)
            <svg class="h-[15px] w-[15px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">{!! $iconLeft !!}</svg>
        @endif
        {{ $slot }}
        @if($iconRight)
            <svg class="h-[15px] w-[15px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">{!! $iconRight !!}</svg>
        @endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($iconLeft)
            <svg class="h-[15px] w-[15px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">{!! $iconLeft !!}</svg>
        @endif
        {{ $slot }}
        @if($iconRight)
            <svg class="h-[15px] w-[15px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">{!! $iconRight !!}</svg>
        @endif
    </button>
@endif
