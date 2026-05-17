{{--
    Breadcrumbs partial.
    Usage: @include('layouts.partials.breadcrumbs', ['items' => [
        ['label' => 'Ordens de Serviço', 'href' => '/ordens'],
        ['label' => 'OS #12458'],
    ]])
--}}
@if(!empty($items))
<nav aria-label="Breadcrumb" class="flex items-center gap-1.5 text-[13px] text-slate-500">
    @foreach($items as $index => $item)
        @if(!$loop->last)
            @if(!empty($item['href']))
                <a href="{{ $item['href'] }}" class="truncate max-w-[160px] text-slate-500 transition hover:text-slate-900">{{ $item['label'] }}</a>
            @else
                <span class="truncate max-w-[160px]">{{ $item['label'] }}</span>
            @endif
            <svg class="h-3 w-3 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/>
            </svg>
        @else
            <span class="truncate max-w-[200px] font-semibold text-slate-900">{{ $item['label'] }}</span>
        @endif
    @endforeach
</nav>
@endif
