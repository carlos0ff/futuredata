@props([
    'title'       => 'Nenhum item encontrado',
    'description' => 'Não há dados para exibir no momento.',
    'actionLabel' => null,
    'actionHref'  => null,
    'icon'        => null,
])

<div class="flex flex-col items-center justify-center py-16 text-center">
    <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl border border-slate-200 bg-slate-50">
        @if($icon)
            <svg class="h-7 w-7 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                {!! $icon !!}
            </svg>
        @else
            <svg class="h-7 w-7 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 0 2-2h2a2 2 0 0 0 2 2"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6M9 16h4"/>
            </svg>
        @endif
    </div>
    <h3 class="text-[15px] font-bold text-slate-900">{{ $title }}</h3>
    <p class="mt-1.5 max-w-sm text-[13px] text-slate-500">{{ $description }}</p>
    @if($actionLabel && $actionHref)
        <a
            href="{{ $actionHref }}"
            class="mt-5 inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-[13.5px] font-semibold text-white shadow-sm transition hover:bg-blue-700"
        >
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
            </svg>
            {{ $actionLabel }}
        </a>
    @endif
    {{ $slot }}
</div>
