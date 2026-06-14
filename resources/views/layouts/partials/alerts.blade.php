{{-- ═══ TOASTS ═══ --}}
@php
$toasts = [];
if(session('success')) $toasts[] = ['type' => 'success', 'message' => session('success')];
if(session('error'))   $toasts[] = ['type' => 'error',   'message' => session('error')];
if(session('warning')) $toasts[] = ['type' => 'warning', 'message' => session('warning')];
if($errors->any())     $toasts[] = ['type' => 'error',   'message' => 'Corrija os erros abaixo para continuar.', 'list' => $errors->all()];
@endphp

@if(!empty($toasts))
<div class="pointer-events-none fixed top-4 right-4 z-[100] flex w-full max-w-sm flex-col gap-2.5 sm:top-5 sm:right-5">
    @foreach($toasts as $toast)
    @php
    $styles = [
        'success' => ['border' => 'border-emerald-200', 'icon' => 'bg-emerald-100 text-emerald-600', 'bar' => 'bg-emerald-500', 'title' => 'text-emerald-900'],
        'error'   => ['border' => 'border-red-200',     'icon' => 'bg-red-100 text-red-600',         'bar' => 'bg-red-500',     'title' => 'text-red-900'],
        'warning' => ['border' => 'border-amber-200',   'icon' => 'bg-amber-100 text-amber-600',     'bar' => 'bg-amber-500',   'title' => 'text-amber-900'],
    ][$toast['type']];
    @endphp
    <div
        x-data="{ show: false }"
        x-init="setTimeout(() => show = true, 30); setTimeout(() => show = false, 5000)"
        x-show="show"
        @click="show = false"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-x-4"
        x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 translate-x-4"
        class="pointer-events-auto relative overflow-hidden rounded-xl border {{ $styles['border'] }} bg-white shadow-lg shadow-black/5"
        style="display:none"
    >
        <div class="absolute inset-y-0 left-0 w-1 {{ $styles['bar'] }}"></div>
        <div class="flex items-start gap-3 px-4 py-3 pl-5">
            <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full {{ $styles['icon'] }}">
                @if($toast['type'] === 'success')
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6 9 17l-5-5"/></svg>
                @elseif($toast['type'] === 'error')
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                @else
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                @endif
            </span>
            <div class="flex-1 min-w-0">
                <p class="text-[13px] font-semibold leading-snug {{ $styles['title'] }}">{{ $toast['message'] }}</p>
                @if(!empty($toast['list']))
                <ul class="mt-1.5 space-y-0.5 text-[12px] text-slate-500">
                    @foreach($toast['list'] as $error)
                    <li class="flex items-start gap-1.5">
                        <span class="mt-1.5 h-1 w-1 shrink-0 rounded-full bg-slate-400"></span>
                        {{ $error }}
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>
            <button @click.stop="show = false" class="shrink-0 text-slate-300 transition hover:text-slate-500" aria-label="Fechar">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>
    </div>
    @endforeach
</div>
@endif
