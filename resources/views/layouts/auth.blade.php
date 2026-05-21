<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Login - Organizze</title>

    <!-- TailwindCSS CDN -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

</head>
<body class="bg-gray-100">

{{-- Background gradient --}}
<div class="fixed inset-0 -z-10 bg-[#07111f]">

    {{-- Glow azul premium --}}
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,rgba(14,165,233,0.18)_0%,transparent_60%)]"></div>

    {{-- Glow roxo/indigo --}}
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_bottom_right,rgba(99,102,241,0.12)_0%,transparent_65%)]"></div>

    {{-- Glow central suave --}}
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(59,130,246,0.08)_0%,transparent_70%)]"></div>

    {{-- Grid elegante --}}
    <div 
        class="absolute inset-0 opacity-[0.03]"
        style="
            background-image:
                linear-gradient(rgba(255,255,255,.4) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.4) 1px, transparent 1px);
            background-size: 42px 42px;
        ">
    </div>

    {{-- Noise overlay --}}
    <div class="absolute inset-0 opacity-[0.015] mix-blend-soft-light"
        style="background-image: url('https://www.transparenttextures.com/patterns/asfalt-light.png');">
    </div>

</div>

{{-- Content --}}
<div class="w-full min-h-screen flex flex-col items-center justify-center pt-8 pb-12 px-4 sm:px-6">
    @yield('content')
</div>

@stack('scripts')

</body>
</html>
