<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - ITO</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center p-4" x-data="{ tab: 'estudiante' }">

        <div class="mb-4 text-center">
            <img src="{{ asset('logo.png') }}" alt="Logo ITO" class="h-20 w-auto mx-auto">
            <h2 class="text-gray-600 text-lg mt-2">Crea tu cuenta institucional</h2>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-lg border border-gray-100 w-full max-w-lg">

            <div class="flex bg-gray-100 rounded-xl p-1 mb-6">
                <button @click="tab = 'estudiante'"
                    :class="tab === 'estudiante' ? 'bg-indigo-700 text-white shadow-sm' : 'text-gray-500'"
                    class="flex-1 py-2 text-sm font-semibold rounded-lg transition-all">Estudiantes</button>
                <button @click="tab = 'personal'"
                    :class="tab === 'personal' ? 'bg-green-700 text-white shadow-sm' : 'text-gray-500'"
                    class="flex-1 py-2 text-sm font-semibold rounded-lg transition-all">Personal</button>
            </div>

            <form action="{{ route('register.store') }}" method="POST" enctype="multipart/form-data"
                class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf

                <input type="hidden" name="tipo" :value="tab">

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nombre Completo</label>
                    <input type="text" name="nombre_completo" required
                        class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 outline-none"
                        placeholder="Juan Pérez López">
                </div>

                <div class="md:col-span-2 bg-indigo-50 p-4 rounded-2xl border-2 border-dashed border-indigo-200">
                    <label class="block text-xs font-bold text-indigo-700 uppercase mb-2">📸 Fotografía para el
                        Tarjetón</label>
                    <div class="flex items-center justify-center w-full">
                        <label for="foto"
                            class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-white hover:bg-gray-50 transition-all">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-4 text-indigo-500" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                </svg>
                                <p class="mb-2 text-sm text-gray-500 font-semibold text-center">Haz clic para
                                    seleccionar tu foto</p>
                                <p class="text-xs text-gray-400 uppercase">JPG o PNG (Máx. 2MB)</p>
                            </div>
                            <input type="file" name="foto" id="foto" accept="image/*" required
                                class="hidden" />
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1"
                        x-text="tab === 'estudiante' ? 'Carrera' : 'Departamento'"></label>

                    <select name="adscripcion" x-show="tab === 'estudiante'" :required="tab === 'estudiante'"
                        class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 outline-none">
                        <option value="" disabled selected>Selecciona tu carrera</option>
                        <option value="Ingeniería en Sistemas Computacionales">Ingeniería en Sistemas Computacionales
                        </option>
                        <option value="Ingeniería Civil">Ingeniería Civil</option>
                        <option value="Ingeniería Industrial">Ingeniería Industrial</option>
                    </select>

                    <select name="adscripcion" x-show="tab === 'personal'" :required="tab === 'personal'"
                        class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 outline-none">
                        <option value="" disabled selected>Selecciona tu departamento</option>
                        <option value="Académico">Académico</option>
                        <option value="Administración">Administración</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1"
                        x-text="tab === 'estudiante' ? 'Número de Control' : 'Número de Empleado'"></label>
                    <input type="text" name="numero_id" required
                        class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 outline-none">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Correo Electrónico</label>
                    <input type="email" name="correo_electronico" required
                        class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 outline-none">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Contraseña</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 outline-none">
                </div>

                <div class="md:col-span-2 pt-4">
                    <button type="submit"
                        :class="tab === 'estudiante' ? 'bg-indigo-700 hover:bg-indigo-800' : 'bg-green-700 hover:bg-green-800'"
                        class="w-full text-white py-3 rounded-xl font-bold text-lg shadow-lg transition-all">
                        Registrarme
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-gray-400 text-sm hover:underline">¿Ya tienes cuenta? Inicia
                    sesión</a>
            </div>
        </div>
    </div>
</body>

</html>
