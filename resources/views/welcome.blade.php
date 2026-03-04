<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ITO</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center p-4" x-data="{ tab: 'estudiante' }">

        <div class="mb-4">
            <img src="{{ asset('logo.png') }}" alt="Logo ITO" class="h-24 w-auto">
        </div>

        <h2 class="text-gray-600 text-lg mb-6">Instituto Tecnológico de Oaxaca</h2>

        <div class="bg-white p-8 rounded-3xl shadow-lg border border-gray-100 w-full max-w-md">

            @if ($errors->has('error'))
                <div class="mb-4 p-3 rounded-xl bg-red-50 text-red-700 text-sm font-medium border border-red-100">
                    {{ $errors->first('error') }}
                </div>
            @endif

            <div class="flex bg-gray-100 rounded-xl p-1 mb-8">
                <button @click="tab = 'estudiante'"
                    :class="tab === 'estudiante' ? 'bg-indigo-700 text-white shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                    class="flex-1 py-2 text-sm font-semibold rounded-lg transition-all duration-200">
                    Estudiantes
                </button>
                <button @click="tab = 'personal'"
                    :class="tab === 'personal' ? 'bg-green-700 text-white shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                    class="flex-1 py-2 text-sm font-semibold rounded-lg transition-all duration-200">
                    Personal
                </button>
            </div>

            <h3 class="text-gray-800 font-bold text-lg mb-4 text-left">Usuario</h3>

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf

                <input type="hidden" name="tipo" :value="tab">

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1"
                        x-text="tab === 'estudiante' ? 'Número de control' : 'Número de empleado'"></label>
                    <input type="text" name="numero_id" required
                        class="w-full px-4 py-3 bg-blue-50/50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:border-transparent outline-none transition-all"
                        placeholder="Ingresa tu ID">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Contraseña</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-3 bg-blue-50/50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:border-transparent outline-none transition-all"
                        placeholder="••••••">
                </div>

                <div class="pt-2">
                    <button type="submit"
                        :class="tab === 'estudiante' ? 'bg-indigo-700 hover:bg-indigo-800 shadow-indigo-200' :
                            'bg-green-700 hover:bg-green-800 shadow-green-200'"
                        class="w-full text-white py-4 rounded-xl font-bold text-lg shadow-xl transition-all active:scale-95 transform">
                        Iniciar sesión
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-6 text-center">
            <p class="text-gray-500 text-sm">
                ¿No tienes una cuenta?
                <a href="{{ route('register') }}" class="font-bold transition-colors"
                    :class="tab === 'estudiante' ? 'text-indigo-700 hover:text-indigo-900' :
                        'text-green-700 hover:text-green-900'">
                    Regístrate aquí
                </a>
            </p>
        </div>

        <div class="mt-8 text-center">
            <p class="text-gray-400 text-xs mt-4">© 2026 Instituto Tecnológico de Oaxaca. Todos los derechos reservados.
            </p>
        </div>
    </div>
</body>

</html>
