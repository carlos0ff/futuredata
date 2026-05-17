<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Login - Organizze</title>

   
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

</head>
<body class="bg-gray-100">

    <div class="w-full min-h-screen flex flex-col items-center justify-center pt-8 pb-12 px-4 sm:px-6">

        <!-- Logo -->
        <div class="mb-8 sm:mb-10">
            <a href="/" class="flex items-center justify-center">
                <img src="{{ asset('images/futuredata.png') }}" class="h-12 sm:h-12 w-auto object-contain" alt="Future Data e tecnologia" />
            </a>
        </div>

        <!-- Card -->
        <div class="w-full max-w-md bg-white shadow-xl rounded-lg px-6 py-10 sm:px-10 sm:py-12">
            <h2 class="text-[#2E312D] text-2xl sm:text-3xl font-bold text-center mb-8">Acesse sua conta </h2>

            <!-- Error -->
            <div id="errorMessage" class=" mb-4">
                <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                        <p id="errorText" class="text-sm text-red-600">E-mail ou senha inválidos</p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form id="loginForm" class="flex flex-col gap-5" autocomplete="off">

                <!-- Email -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-[#4D4D4D] font-medium text-sm">Seu e-mail</label>
                    <input id="email" type="email" placeholder="Digite seu e-mail" autocomplete="email" class="h-12 w-full rounded-lg border border-gray-300 bg-white px-4 text-gray-800 placeholder-gray-400 transition-all " />
                </div>

                <!-- Password -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-[#4D4D4D] font-medium text-sm">Sua senha</label>
                    <input id="password" type="password" placeholder="Digite sua senha" autocomplete="current-password" class="h-12 w-full rounded-lg border border-gray-300 bg-white px-4 text-gray-800 placeholder-gray-400 transition-all  " />
                    <a href="/auth/recuperar" class="mt-1 text-sm text-gray-500 hover:text-[#C80000] transition-colors self-end" >Esqueci minha senha</a>
                </div>

                <!-- Remember -->
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input id="remember" type="checkbox" class="w-4 h-4 accent-[#C80000] rounded"/>
                    <span class="text-sm text-gray-600">
                        Lembrar de mim
                    </span>
                </label>

                <!-- Submit -->
                <button type="submit" class="mt-1 h-12 w-full rounded-lg bg-[#FB0101] font-semibold text-white transition-all hover:bg-[#C80000] focus:outline-none focus:ring-2 focus:ring-[#16C64F]/40 disabled:opacity-60 disabled:cursor-not-allowed" >
                    Entrar
                </button>
            </form>
        </div>

    </div>

</body>
</html>