<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Autenticação') — Future Data</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

    @stack('styles')
</head>
<body class="min-h-screen [font-family:'DM_Sans',sans-serif] antialiased">

{{-- Background gradient --}}
<div class="fixed inset-0 -z-10 bg-[#0d0f16]">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,rgba(59,130,246,0.12)_0%,transparent_60%)]"></div>
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_bottom_right,rgba(99,102,241,0.08)_0%,transparent_60%)]"></div>
    {{-- Subtle grid --}}
    <div class="absolute inset-0 opacity-[0.015]" style="background-image: linear-gradient(rgba(255,255,255,.5) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.5) 1px, transparent 1px); background-size: 40px 40px;"></div>
</div>

{{-- Content --}}
<div class="flex min-h-screen flex-col items-center justify-center px-4 py-12">
    @yield('content')
</div>

@stack('scripts')
</body>
</html>
