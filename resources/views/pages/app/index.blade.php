@extends('layouts.app')
@section('title', 'Plataforma')

@section('breadcrumbs')
@include('layouts.partials.breadcrumbs', ['items' => [['label' => 'Plataforma']]])
@endsection

@section('content')

{{-- ── Header ───────────────────────────────────────────── --}}
<div class="mb-8 flex flex-col gap-1">
    <div class="flex items-center gap-2.5">
        <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-purple-500/10">
            <svg class="h-4 w-4 text-purple-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
            </svg>
        </div>
        <h1 class="text-[22px] font-bold tracking-tight text-slate-900">Gerenciamento da Plataforma</h1>
    </div>
    <p class="ml-[42px] text-[13px] text-slate-500">Controle de roles, permissões e usuários do sistema.</p>
</div>

{{-- ── Stats cards ──────────────────────────────────────── --}}
<div class="mb-8 grid grid-cols-2 gap-4 sm:grid-cols-4">
    @php
        $statsCards = [
            ['label' => 'Roles',        'value' => $stats['total_roles'],       'icon' => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z', 'color' => 'purple'],
            ['label' => 'Permissões',   'value' => $stats['total_permissions'], 'icon' => 'M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z', 'color' => 'blue'],
            ['label' => 'Usuários',     'value' => $stats['total_users'],       'icon' => 'M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z', 'color' => 'emerald'],
            ['label' => 'Grupos',       'value' => $stats['total_grupos'],      'icon' => 'M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z', 'color' => 'amber'],
        ];
        $colorMap = [
            'purple'  => ['card' => 'border-purple-100  bg-purple-50/50',  'icon' => 'bg-purple-100 text-purple-600',  'val' => 'text-purple-700'],
            'blue'    => ['card' => 'border-blue-100    bg-blue-50/50',    'icon' => 'bg-blue-100 text-blue-600',      'val' => 'text-blue-700'],
            'emerald' => ['card' => 'border-emerald-100 bg-emerald-50/50', 'icon' => 'bg-emerald-100 text-emerald-600','val' => 'text-emerald-700'],
            'amber'   => ['card' => 'border-amber-100   bg-amber-50/50',   'icon' => 'bg-amber-100 text-amber-600',   'val' => 'text-amber-700'],
        ];
    @endphp

    @foreach($statsCards as $card)
    @php $c = $colorMap[$card['color']]; @endphp
    <div class="flex items-center gap-4 rounded-2xl border {{ $c['card'] }} p-5">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $c['icon'] }}">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}"/>
            </svg>
        </div>
        <div>
            <p class="text-[11.5px] font-semibold uppercase tracking-wider text-slate-500">{{ $card['label'] }}</p>
            <p class="text-[28px] font-bold leading-none {{ $c['val'] }}">{{ $card['value'] }}</p>
        </div>
    </div>
    @endforeach
</div>

{{-- ── Grid principal ───────────────────────────────────── --}}
<div class="grid grid-cols-1 gap-6 xl:grid-cols-[1fr_360px]">

    {{-- ── Roles & Permissões ─────────────────────────── --}}
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-[15px] font-bold text-slate-900">Roles e Permissões</h2>
            <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-[11.5px] font-semibold text-slate-600">{{ $roles->count() }} roles</span>
        </div>

        @php
            $roleBadges = [
                'dev'       => 'bg-purple-100 text-purple-700 border border-purple-200',
                'admin'     => 'bg-blue-100 text-blue-700 border border-blue-200',
                'tecnico'   => 'bg-emerald-100 text-emerald-700 border border-emerald-200',
                'atendente' => 'bg-amber-100 text-amber-700 border border-amber-200',
                'cliente'   => 'bg-slate-100 text-slate-600 border border-slate-200',
            ];
            $roleCards = [
                'dev'       => 'border-purple-200/60  bg-white hover:border-purple-300/80',
                'admin'     => 'border-blue-200/60    bg-white hover:border-blue-300/80',
                'tecnico'   => 'border-emerald-200/60 bg-white hover:border-emerald-300/80',
                'atendente' => 'border-amber-200/60   bg-white hover:border-amber-300/80',
                'cliente'   => 'border-slate-200      bg-white hover:border-slate-300',
            ];
            $roleStripes = [
                'dev'       => 'bg-purple-500',
                'admin'     => 'bg-blue-500',
                'tecnico'   => 'bg-emerald-500',
                'atendente' => 'bg-amber-500',
                'cliente'   => 'bg-slate-400',
            ];
            $permGroupColors = [
                'sistema'    => 'bg-purple-50 text-purple-700 border border-purple-200/60',
                'ordens'     => 'bg-blue-50 text-blue-700 border border-blue-200/60',
                'clientes'   => 'bg-emerald-50 text-emerald-700 border border-emerald-200/60',
                'relatorios' => 'bg-amber-50 text-amber-700 border border-amber-200/60',
                'portal'     => 'bg-slate-100 text-slate-600 border border-slate-200',
            ];
        @endphp

        @foreach($roles as $role)
        @php
            $badgeClass = $roleBadges[$role->slug]   ?? $roleBadges['cliente'];
            $cardClass  = $roleCards[$role->slug]     ?? $roleCards['cliente'];
            $stripe     = $roleStripes[$role->slug]   ?? $roleStripes['cliente'];
        @endphp
        <div class="group overflow-hidden rounded-2xl border {{ $cardClass }} transition-all duration-200 shadow-sm hover:shadow-md">
            <div class="flex items-start gap-4 p-5">
                {{-- Stripe colorida --}}
                <div class="mt-0.5 h-12 w-1 shrink-0 rounded-full {{ $stripe }}"></div>

                <div class="min-w-0 flex-1">
                    {{-- Header do role --}}
                    <div class="mb-3 flex flex-wrap items-center gap-2.5">
                        <h3 class="text-[14.5px] font-bold text-slate-900">{{ $role->name }}</h3>
                        <span class="rounded-full px-2.5 py-0.5 text-[11px] font-bold {{ $badgeClass }}">
                            Nível {{ $role->level }}
                        </span>
                        <span class="ml-auto rounded-full bg-slate-100 px-2.5 py-0.5 text-[11.5px] font-semibold text-slate-500">
                            {{ $role->users_count }} {{ $role->users_count === 1 ? 'usuário' : 'usuários' }}
                        </span>
                    </div>

                    <p class="mb-3.5 text-[12.5px] text-slate-500">{{ $role->description }}</p>

                    {{-- Permissões --}}
                    @if($role->permissions->isEmpty())
                    <p class="text-[12px] italic text-slate-400">Sem permissões atribuídas.</p>
                    @else
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($role->permissions as $perm)
                        @php $pgColor = $permGroupColors[$perm->group] ?? $permGroupColors['portal']; @endphp
                        <span class="inline-flex items-center gap-1 rounded-lg px-2 py-0.5 text-[11px] font-medium {{ $pgColor }}">
                            {{ $perm->name }}
                        </span>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ── Coluna direita ──────────────────────────────── --}}
    <div class="space-y-5">

        {{-- Usuários recentes --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-3.5">
                <h3 class="text-[13.5px] font-bold text-slate-900">Usuários</h3>
                <span class="text-[12px] font-medium text-slate-400">{{ $stats['total_users'] }} total</span>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($ultimosUsuarios as $user)
                <div class="flex items-center gap-3 px-5 py-3">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-400 to-indigo-600 text-[11px] font-bold text-white">
                        {{ $user->iniciais }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-[13px] font-semibold text-slate-800">{{ $user->name }}</p>
                        <p class="truncate text-[11.5px] text-slate-400">{{ $user->email }}</p>
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        @php
                            $uc = match($user->role) {
                                'gerente' => 'bg-blue-100 text-blue-700',
                                'tecnico' => 'bg-emerald-100 text-emerald-700',
                                default   => 'bg-slate-100 text-slate-600',
                            };
                        @endphp
                        <span class="rounded-full px-2 py-0.5 text-[10.5px] font-bold {{ $uc }}">
                            {{ $user->roleLabel }}
                        </span>
                        @if($user->roles->isNotEmpty())
                        <div class="flex gap-1">
                            @foreach($user->roles->take(2) as $r)
                            @php $rb = $roleBadges[$r->slug] ?? $roleBadges['cliente']; @endphp
                            <span class="rounded px-1.5 py-0.5 text-[9.5px] font-semibold {{ $rb }}">{{ $r->name }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <p class="text-[13px] text-slate-400">Nenhum usuário encontrado.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Permissões por grupo --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-3.5">
                <h3 class="text-[13.5px] font-bold text-slate-900">Permissões por Grupo</h3>
            </div>
            <div class="divide-y divide-slate-50">
                @foreach($permissoesPorGrupo as $grupo => $perms)
                @php
                    $groupInfo = \App\Models\Permission::GROUPS[$grupo] ?? ['label' => ucfirst($grupo), 'color' => 'text-slate-500'];
                    $gpColor   = $permGroupColors[$grupo] ?? 'bg-slate-100 text-slate-600 border border-slate-200';
                @endphp
                <div class="px-5 py-3.5">
                    <div class="mb-2 flex items-center justify-between">
                        <span class="rounded-lg px-2 py-0.5 text-[11px] font-bold {{ $gpColor }}">
                            {{ $groupInfo['label'] }}
                        </span>
                        <span class="text-[11.5px] text-slate-400">{{ $perms->count() }} permissões</span>
                    </div>
                    <div class="space-y-1">
                        @foreach($perms as $perm)
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2 min-w-0">
                                <div class="h-1 w-1 shrink-0 rounded-full bg-slate-300"></div>
                                <p class="truncate text-[12.5px] font-medium text-slate-700">{{ $perm->name }}</p>
                            </div>
                            <code class="shrink-0 rounded bg-slate-100 px-1.5 py-0.5 text-[10px] font-mono text-slate-500">
                                {{ $perm->slug }}
                            </code>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Status do sistema --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-3.5">
                <h3 class="text-[13.5px] font-bold text-slate-900">Status do Sistema</h3>
            </div>
            <div class="space-y-3 p-5">
                @foreach([
                    ['label' => 'RBAC',              'status' => 'Ativo',       'ok' => true],
                    ['label' => 'Middleware de Role', 'status' => 'Configurado', 'ok' => true],
                    ['label' => 'Permissões',         'status' => $stats['total_permissions'] . ' carregadas', 'ok' => true],
                    ['label' => 'Roles',              'status' => $stats['total_roles'] . ' definidas', 'ok' => true],
                    ['label' => 'Autenticação',       'status' => 'Auth Guard OK','ok' => true],
                ] as $item)
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <div class="h-2 w-2 rounded-full {{ $item['ok'] ? 'bg-emerald-500' : 'bg-red-500' }} {{ $item['ok'] ? 'shadow-sm shadow-emerald-500/50' : '' }}"></div>
                        <span class="text-[13px] font-medium text-slate-700">{{ $item['label'] }}</span>
                    </div>
                    <span class="text-[12px] {{ $item['ok'] ? 'text-emerald-600' : 'text-red-500' }} font-semibold">
                        {{ $item['status'] }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection
