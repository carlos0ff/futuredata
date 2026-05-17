{{-- ═══ FLASH ALERTS ═══ --}}
@if(session('success'))
<div
    x-data="{ show: true }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 -translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-2"
    class="mx-4 mt-4 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 sm:mx-6"
>
    <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M20 6 9 17l-5-5"/>
    </svg>
    <p class="flex-1 text-[13px] font-medium text-emerald-800">{{ session('success') }}</p>
    <button @click="show = false" class="shrink-0 text-emerald-500 transition hover:text-emerald-700" aria-label="Fechar">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
</div>
@endif

@if(session('error'))
<div
    x-data="{ show: true }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 -translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-2"
    class="mx-4 mt-4 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 sm:mx-6"
>
    <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
    </svg>
    <p class="flex-1 text-[13px] font-medium text-red-800">{{ session('error') }}</p>
    <button @click="show = false" class="shrink-0 text-red-400 transition hover:text-red-600" aria-label="Fechar">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
</div>
@endif

@if(session('warning'))
<div
    x-data="{ show: true }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 -translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-2"
    class="mx-4 mt-4 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 sm:mx-6"
>
    <svg class="mt-0.5 h-4 w-4 shrink-0 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
        <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
    </svg>
    <p class="flex-1 text-[13px] font-medium text-amber-800">{{ session('warning') }}</p>
    <button @click="show = false" class="shrink-0 text-amber-400 transition hover:text-amber-600" aria-label="Fechar">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
</div>
@endif

@if($errors->any())
<div
    x-data="{ show: true }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 -translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-2"
    class="mx-4 mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 sm:mx-6"
>
    <div class="flex items-start gap-3">
        <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
        </svg>
        <div class="flex-1">
            <p class="text-[13px] font-semibold text-red-800">Corrija os erros abaixo para continuar:</p>
            <ul class="mt-1.5 space-y-0.5 text-[12.5px] text-red-700">
                @foreach($errors->all() as $error)
                    <li class="flex items-center gap-1.5">
                        <span class="h-1 w-1 rounded-full bg-red-400 shrink-0"></span>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
        <button @click="show = false" class="shrink-0 text-red-400 transition hover:text-red-600" aria-label="Fechar">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
        </button>
    </div>
</div>
@endif
