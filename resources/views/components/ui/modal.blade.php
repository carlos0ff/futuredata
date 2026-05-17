@props([
    'name'  => 'modal',
    'title' => null,
    'size'  => 'md',
])

@php
$sizes = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-lg',
    'lg' => 'max-w-2xl',
    'xl' => 'max-w-4xl',
];
$panelSize = $sizes[$size] ?? $sizes['md'];
@endphp

{{--
    Usage example:
    <div x-data="{ showModal: false }">
        <x-ui.modal name="showModal" title="Confirmar ação">
            <p>Conteúdo do modal</p>
        </x-ui.modal>
        <button @click="showModal = true">Abrir modal</button>
    </div>
--}}
<div
    x-show="{{ $name }}"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @keydown.escape.window="{{ $name }} = false"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    style="display:none"
>
    {{-- Backdrop --}}
    <div
        class="absolute inset-0 bg-black/50 backdrop-blur-sm"
        @click="{{ $name }} = false"
    ></div>

    {{-- Panel --}}
    <div
        x-show="{{ $name }}"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-2"
        class="relative w-full {{ $panelSize }} overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl"
    >
        @if($title)
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <h3 class="text-[15px] font-bold text-slate-900">{{ $title }}</h3>
                <button
                    @click="{{ $name }} = false"
                    class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                >
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6 6 18M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        <div class="px-6 py-5">
            {{ $slot }}
        </div>

        @if(isset($footer))
            <div class="border-t border-slate-100 px-6 py-4">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>
