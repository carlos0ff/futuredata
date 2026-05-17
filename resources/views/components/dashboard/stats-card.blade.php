@props([
    'title'      => 'Métrica',
    'value'      => '0',
    'change'     => null,
    'changeType' => 'neutral',
    'color'      => 'blue',
    'icon'       => null,
])

@php
$colors = [
    'blue'    => ['bg' => 'bg-blue-50', 'icon' => 'text-blue-600', 'ring' => 'ring-blue-100'],
    'emerald' => ['bg' => 'bg-emerald-50', 'icon' => 'text-emerald-600', 'ring' => 'ring-emerald-100'],
    'amber'   => ['bg' => 'bg-amber-50', 'icon' => 'text-amber-600', 'ring' => 'ring-amber-100'],
    'purple'  => ['bg' => 'bg-purple-50', 'icon' => 'text-purple-600', 'ring' => 'ring-purple-100'],
    'rose'    => ['bg' => 'bg-rose-50', 'icon' => 'text-rose-600', 'ring' => 'ring-rose-100'],
];

$c = $colors[$color] ?? $colors['blue'];

$changeColors = [
    'up'      => 'text-emerald-600 bg-emerald-50',
    'down'    => 'text-red-600 bg-red-50',
    'neutral' => 'text-slate-500 bg-slate-100',
];

$changeBg = $changeColors[$changeType] ?? $changeColors['neutral'];
@endphp

<div {{ $attributes->merge(['class' => 'rounded-xl border border-slate-200 bg-white p-5 shadow-sm']) }}>
    <div class="flex items-start justify-between gap-4">
        <div class="flex-1 min-w-0">
            <p class="text-[12.5px] font-semibold uppercase tracking-[0.06em] text-slate-500">{{ $title }}</p>
            <p class="mt-2 text-[28px] font-bold leading-none tracking-tight text-slate-900">{{ $value }}</p>
            @if($change)
                <div class="mt-3 inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11.5px] font-semibold {{ $changeBg }}">
                    @if($changeType === 'up')
                        <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7M17 7H7M17 7v10"/>
                        </svg>
                    @elseif($changeType === 'down')
                        <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7 17 17M17 17H7M17 17V7"/>
                        </svg>
                    @else
                        <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14"/>
                        </svg>
                    @endif
                    {{ $change }}
                </div>
            @endif
        </div>

        @if($icon)
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl {{ $c['bg'] }} ring-1 {{ $c['ring'] }}">
                <svg class="h-5 w-5 {{ $c['icon'] }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    {!! $icon !!}
                </svg>
            </div>
        @endif
    </div>
</div>
