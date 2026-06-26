@extends('layouts.app')
@section('title', 'Meu Perfil')

@section('breadcrumbs')
    @include('layouts.partials.breadcrumbs', ['items' => [['label' => 'Meu Perfil']]])
@endsection

@section('content')

@php
    $user = auth()->user();
    $roleColor = match($user->role) {
        'gerente'   => 'primary',
        'admin'     => 'danger',
        'tecnico'   => 'info',
        'atendente' => 'default',
        default     => 'default',
    };
@endphp

{{-- ═══════════════════════════════════════════════
     PROFILE HERO
════════════════════════════════════════════════ --}}
<div class="relative mb-6 overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 shadow-sm">

    {{-- Subtle dot-grid overlay --}}
    <div class="pointer-events-none absolute inset-0 opacity-[0.035]"
         style="background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:24px 24px"></div>
    {{-- Glow accent --}}
    <div class="pointer-events-none absolute -top-24 -right-24 h-64 w-64 rounded-full bg-blue-600/20 blur-3xl"></div>

    <div class="relative flex flex-col gap-5 p-6 sm:flex-row sm:items-center sm:justify-between sm:p-8">

        {{-- LEFT: avatar + identity --}}
        <div class="flex items-center gap-5">
            {{-- Avatar XL --}}
            <div class="relative shrink-0">
                <div class="flex h-[72px] w-[72px] items-center justify-center rounded-2xl
                            bg-gradient-to-br from-blue-400 via-blue-600 to-indigo-700
                            text-[22px] font-bold tracking-wide text-white
                            shadow-lg shadow-blue-900/40 ring-4 ring-white/10">
                    {{ $user->iniciais }}
                </div>
                <span class="absolute -bottom-1 -right-1 h-4 w-4 rounded-full
                             border-2 border-slate-800 bg-emerald-400"
                      title="Online"></span>
            </div>

            {{-- Name + meta --}}
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <h1 class="text-[19px] font-bold leading-tight text-white">{{ $user->name }}</h1>
                    <x-ui.badge variant="{{ $roleColor }}">{{ $user->role_label }}</x-ui.badge>
                </div>
                <p class="mt-1 flex items-center gap-1.5 text-[13px] text-slate-400">
                    <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect width="20" height="16" x="2" y="4" rx="2"/>
                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                    </svg>
                    {{ $user->email }}
                </p>
                <p class="mt-1 flex items-center gap-1.5 text-[12px] text-slate-500">
                    <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect width="18" height="18" x="3" y="4" rx="2"/>
                        <line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/>
                        <line x1="3" x2="21" y1="10" y2="10"/>
                    </svg>
                    Membro desde {{ $user->created_at->translatedFormat('d \d\e F \d\e Y') }}
                </p>
            </div>
        </div>

        {{-- RIGHT: stat pills --}}
        <div class="flex flex-wrap items-center gap-2.5 sm:flex-col sm:items-end">
            <div class="flex items-center gap-2 rounded-xl border border-white/10
                        bg-white/5 px-3.5 py-2 backdrop-blur-sm">
                <span class="relative flex h-2 w-2">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-60"></span>
                    <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-400"></span>
                </span>
                <span class="text-[12.5px] font-medium text-slate-200">Conta Ativa</span>
            </div>

            <div class="rounded-xl border border-white/10 bg-white/5 px-3.5 py-2 backdrop-blur-sm">
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Nível de acesso</p>
                <p class="mt-0.5 text-[13px] font-semibold text-slate-200">{{ $user->role_label }}</p>
            </div>

            <div class="rounded-xl border border-white/10 bg-white/5 px-3.5 py-2 backdrop-blur-sm">
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">ID do usuário</p>
                <p class="mt-0.5 font-mono text-[13px] font-semibold text-slate-300">
                    #{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}
                </p>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════
     MAIN GRID  (2 col desktop / 1 col mobile)
════════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 gap-5 lg:grid-cols-2">

    {{-- ───────────────────────────────────────
         1. INFORMAÇÕES PESSOAIS
    ─────────────────────────────────────────── --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        {{-- Card header --}}
        <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-blue-50">
                <svg class="h-4 w-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </div>
            <div>
                <h2 class="text-[14px] font-bold text-slate-900">Informações Pessoais</h2>
                <p class="text-[12px] text-slate-500">Atualize seu nome de exibição no sistema.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('app.perfil.atualizar') }}"
              x-data="{ loading: false }"
              @submit="loading = true"
              novalidate
              class="p-6 space-y-5">
            @csrf @method('PUT')

            {{-- Nome --}}
            <x-ui.input
                name="name"
                label="Nome completo"
                type="text"
                autocomplete="name"
                :value="old('name', auth()->user()->name)"
                :error="$errors->first('name')"
                iconLeft='<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>'
                required
            />

            {{-- E-mail (somente leitura) --}}
            <div>
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">
                    E-mail
                </label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <rect width="20" height="16" x="2" y="4" rx="2"/>
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                        </svg>
                    </div>
                    <input type="email"
                           value="{{ auth()->user()->email }}"
                           readonly
                           aria-label="E-mail (somente leitura)"
                           class="h-10 w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-100
                                  pl-9 pr-3 text-sm text-slate-400 outline-none" />
                </div>
                <p class="mt-1.5 flex items-center gap-1 text-[11.5px] text-slate-400">
                    <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/>
                    </svg>
                    O e-mail não pode ser alterado.
                </p>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-1">
                <x-ui.button type="submit" variant="primary" x-bind:disabled="loading">
                    <svg x-show="loading" style="display:none"
                         class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                    </svg>
                    <span x-text="loading ? 'Salvando…' : 'Salvar Alterações'">Salvar Alterações</span>
                </x-ui.button>
            </div>
        </form>
    </div>

    {{-- ───────────────────────────────────────
         2. SEGURANÇA
    ─────────────────────────────────────────── --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-amber-50">
                <svg class="h-4 w-4 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/>
                </svg>
            </div>
            <div>
                <h2 class="text-[14px] font-bold text-slate-900">Segurança</h2>
                <p class="text-[12px] text-slate-500">Atualize sua senha para manter sua conta protegida.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('app.perfil.senha') }}"
              x-data="{
                  loading:  false,
                  show:     { current: false, novo: false, confirm: false },
                  password: '',
                  get strength() {
                      const p = this.password;
                      let s = 0;
                      if (p.length >= 8)            s++;
                      if (/[0-9]/.test(p))          s++;
                      if (/[A-Z]/.test(p))          s++;
                      if (/[^a-zA-Z0-9]/.test(p))  s++;
                      return s;
                  },
                  get strengthLabel()     { return ['','Fraca','Média','Forte','Muito Forte'][this.strength] ?? '' },
                  get strengthBarColor()  { return ['bg-slate-200','bg-red-500','bg-amber-400','bg-blue-500','bg-emerald-500'][this.strength] ?? 'bg-slate-200' },
                  get strengthTextColor() { return ['text-slate-400','text-red-600','text-amber-600','text-blue-600','text-emerald-600'][this.strength] ?? 'text-slate-400' },
              }"
              @submit="loading = true"
              novalidate
              class="p-6 space-y-5">
            @csrf @method('PUT')

            {{-- Senha atual --}}
            <div>
                <label for="current_password"
                       class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">
                    Senha atual
                </label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </div>
                    <input id="current_password"
                           name="current_password"
                           :type="show.current ? 'text' : 'password'"
                           autocomplete="current-password"
                           aria-label="Senha atual"
                           class="h-10 w-full rounded-xl border bg-white pl-9 pr-10 text-sm text-slate-900 outline-none transition
                                  focus:ring-2 {{ $errors->has('current_password') ? 'border-red-300 focus:border-red-400 focus:ring-red-100' : 'border-slate-200 focus:border-blue-500 focus:ring-blue-100' }}" />
                    <button type="button"
                            @click="show.current = !show.current"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 transition hover:text-slate-600"
                            :aria-label="show.current ? 'Ocultar senha' : 'Mostrar senha'">
                        <svg x-show="!show.current" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg x-show="show.current" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none">
                            <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                            <line x1="2" x2="22" y1="2" y2="22"/>
                        </svg>
                    </button>
                </div>
                @error('current_password')
                    <p class="mt-1 text-[11.5px] text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nova senha + indicador de força --}}
            <div>
                <label for="password"
                       class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">
                    Nova senha
                </label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/>
                        </svg>
                    </div>
                    <input id="password"
                           name="password"
                           :type="show.novo ? 'text' : 'password'"
                           autocomplete="new-password"
                           aria-label="Nova senha"
                           x-model="password"
                           class="h-10 w-full rounded-xl border bg-white pl-9 pr-10 text-sm text-slate-900 outline-none transition
                                  focus:ring-2 {{ $errors->has('password') ? 'border-red-300 focus:border-red-400 focus:ring-red-100' : 'border-slate-200 focus:border-blue-500 focus:ring-blue-100' }}" />
                    <button type="button"
                            @click="show.novo = !show.novo"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 transition hover:text-slate-600"
                            :aria-label="show.novo ? 'Ocultar senha' : 'Mostrar senha'">
                        <svg x-show="!show.novo" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg x-show="show.novo" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none">
                            <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                            <line x1="2" x2="22" y1="2" y2="22"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1 text-[11.5px] text-red-600">{{ $message }}</p>
                @enderror

                {{-- Força da senha --}}
                <div x-show="password.length > 0" x-transition style="display:none" class="mt-3 space-y-2.5">

                    {{-- Barra --}}
                    <div>
                        <div class="mb-1.5 flex items-center justify-between">
                            <span class="text-[11px] font-medium text-slate-500">Força da senha</span>
                            <span class="text-[11px] font-bold transition-colors" :class="strengthTextColor" x-text="strengthLabel"></span>
                        </div>
                        <div class="flex gap-1">
                            <template x-for="i in 4" :key="i">
                                <div class="h-1 flex-1 rounded-full transition-all duration-300"
                                     :class="i <= strength ? strengthBarColor : 'bg-slate-200'"></div>
                            </template>
                        </div>
                    </div>

                    {{-- Checklist --}}
                    <div class="grid grid-cols-2 gap-1.5">
                        @php
                        $checks = [
                            ['expr' => 'password.length >= 8',           'label' => '8 caracteres'],
                            ['expr' => '/[0-9]/.test(password)',          'label' => 'Número'],
                            ['expr' => '/[A-Z]/.test(password)',          'label' => 'Letra maiúscula'],
                            ['expr' => '/[^a-zA-Z0-9]/.test(password)',  'label' => 'Caractere especial'],
                        ];
                        @endphp
                        @foreach($checks as $check)
                        <div class="flex items-center gap-1.5 text-[11.5px] transition-colors"
                             :class="{{ $check['expr'] }} ? 'text-emerald-600' : 'text-slate-400'">
                            <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 6 9 17l-5-5"/>
                            </svg>
                            {{ $check['label'] }}
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Confirmar nova senha --}}
            <div>
                <label for="password_confirmation"
                       class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">
                    Confirmar nova senha
                </label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/>
                        </svg>
                    </div>
                    <input id="password_confirmation"
                           name="password_confirmation"
                           :type="show.confirm ? 'text' : 'password'"
                           autocomplete="new-password"
                           aria-label="Confirmar nova senha"
                           class="h-10 w-full rounded-xl border border-slate-200 bg-white pl-9 pr-10 text-sm text-slate-900
                                  outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100" />
                    <button type="button"
                            @click="show.confirm = !show.confirm"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 transition hover:text-slate-600"
                            :aria-label="show.confirm ? 'Ocultar senha' : 'Mostrar senha'">
                        <svg x-show="!show.confirm" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg x-show="show.confirm" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none">
                            <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                            <line x1="2" x2="22" y1="2" y2="22"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-1">
                <x-ui.button type="submit" variant="primary"
                             x-bind:disabled="loading || password.length < 8">
                    <svg x-show="loading" style="display:none"
                         class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                    </svg>
                    <span x-text="loading ? 'Atualizando…' : 'Atualizar Senha'">Atualizar Senha</span>
                </x-ui.button>
            </div>
        </form>
    </div>

</div>{{-- /main grid --}}

{{-- ═══════════════════════════════════════════════
     INFORMAÇÕES DA CONTA (full-width)
════════════════════════════════════════════════ --}}
<div class="mt-5 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

    <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4">
        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-slate-100">
            <svg class="h-4 w-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 16v-4M12 8h.01"/>
            </svg>
        </div>
        <div>
            <h2 class="text-[14px] font-bold text-slate-900">Informações da Conta</h2>
            <p class="text-[12px] text-slate-500">Dados do sistema — somente leitura.</p>
        </div>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 xl:grid-cols-6">

            @php
            $accountItems = [
                [
                    'icon'  => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
                    'label' => 'ID do Usuário',
                    'value' => '#' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                    'mono'  => true,
                ],
                [
                    'icon'  => '<path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>',
                    'label' => 'Perfil',
                    'value' => $user->role_label,
                    'mono'  => false,
                ],
                [
                    'icon'  => '<circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/>',
                    'label' => 'Status',
                    'value' => 'Ativo',
                    'color' => 'text-emerald-600',
                    'mono'  => false,
                ],
                [
                    'icon'  => '<rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>',
                    'label' => 'E-mail',
                    'value' => $user->email,
                    'small' => true,
                    'mono'  => false,
                ],
                [
                    'icon'  => '<rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/>',
                    'label' => 'Membro desde',
                    'value' => $user->created_at->format('d/m/Y'),
                    'mono'  => false,
                ],
                [
                    'icon'  => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
                    'label' => 'Última atividade',
                    'value' => $user->updated_at->diffForHumans(),
                    'mono'  => false,
                ],
            ];
            @endphp

            @foreach($accountItems as $item)
            <div class="rounded-xl bg-slate-50 px-3.5 py-3">
                <div class="mb-1.5 flex items-center gap-1.5">
                    <svg class="h-3 w-3 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2">{!! $item['icon'] !!}</svg>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">
                        {{ $item['label'] }}
                    </p>
                </div>
                <p class="truncate font-semibold
                          {{ $item['mono']  ?? false ? 'font-mono'    : '' }}
                          {{ $item['small'] ?? false ? 'text-[11.5px]' : 'text-[13px]' }}
                          {{ $item['color'] ?? 'text-slate-800' }}">
                    {{ $item['value'] }}
                </p>
            </div>
            @endforeach

        </div>
    </div>
</div>

@endsection
