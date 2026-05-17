{{-- Paginação --}}
@if($ordens->hasPages())
<div class="mt-4 flex items-center justify-between border-t border-slate-200 pt-4">
    <p class="text-[12px] text-slate-400">
        {{ $ordens->firstItem() }}–{{ $ordens->lastItem() }} de {{ $ordens->total() }}
    </p>

    <div class="flex gap-1">

        @if(!$ordens->onFirstPage())
        <a href="{{ $ordens->previousPageUrl() }}"
           class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:bg-slate-50">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="m15 18-6-6 6-6"/>
            </svg>
        </a>
        @endif

        @if($ordens->hasMorePages())
        <a href="{{ $ordens->nextPageUrl() }}"
           class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:bg-slate-50">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="m9 18 6-6-6-6"/>
            </svg>
        </a>
        @endif

    </div>
</div>
@endif

@endif {{-- fim isEmpty --}}
@endif {{-- fim $cliente --}}

@endsection