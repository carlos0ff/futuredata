@props([
    'cancelHref'  => null,
    'submitLabel' => 'Salvar alterações',
])

<div class="flex items-center justify-end gap-3 pt-2">
    @if($cancelHref)
        <a
            href="{{ $cancelHref }}"
            class="inline-flex h-9 items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-[13.5px] font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
        >
            Cancelar
        </a>
    @endif

    <button
        type="submit"
        class="inline-flex h-9 items-center gap-2 rounded-xl bg-blue-600 px-4 text-[13.5px] font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/30 disabled:opacity-50"
    >
        <svg class="h-[15px] w-[15px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20 6 9 17l-5-5"/>
        </svg>
        {{ $submitLabel }}
    </button>
</div>
