@props([
    'title'       => null,
    'description' => null,
    'noPadding'   => false,
])

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm']) }}>

    @if($title || isset($actions))
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-3.5">
            <div>
                @if($title)
                    <h3 class="text-[13.5px] font-bold text-slate-900">{{ $title }}</h3>
                @endif
                @if($description)
                    <p class="mt-0.5 text-[12px] text-slate-500">{{ $description }}</p>
                @endif
            </div>
            @if(isset($actions))
                <div class="flex items-center gap-2">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif

    <div @class(['px-5 py-4' => !$noPadding])>
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="border-t border-slate-100 px-5 py-3">
            {{ $footer }}
        </div>
    @endif
</div>
