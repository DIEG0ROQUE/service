<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Institucional - ITO</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* Oculta el ojo nativo en navegadores Edge y Chrome */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center p-4" x-data="{
        tab: 'estudiante',
        showP1: false,
        showP2: false,
        pwd: '',
        pwdConfirm: '',
        get isMatch() { return this.pwd === this.pwdConfirm && this.pwd.length > 0; },
        get isValidLength() { return this.pwd.length >= 8; },
        get hasUpper() { return /[A-Z]/.test(this.pwd); },
        get hasNumber() { return /[0-9]/.test(this.pwd); },
        get hasSymbol() { return /[!@#$%^&*(),.?\':{}|<>]/.test(this.pwd); },
        get isFormValid() { return this.isMatch && this.isValidLength && this.hasUpper && this.hasNumber && this.hasSymbol; }
    }">

        <div class="mb-4 text-center">
            <img src="{{ asset('logo.png') }}" alt="Logo ITO" class="h-20 w-auto mx-auto">
            <h2 class="text-gray-600 text-lg mt-2 font-bold">Crea tu cuenta institucional</h2>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-lg border border-gray-100 w-full max-w-lg">

            <div class="flex bg-gray-100 rounded-xl p-1 mb-6">
                <button type="button" @click="tab = 'estudiante'"
                    :class="tab === 'estudiante' ? 'bg-[#1a3a63] text-white shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                    class="flex-1 py-2 text-sm font-bold rounded-lg transition-all">Estudiantes</button>
                <button type="button" @click="tab = 'personal'"
                    :class="tab === 'personal' ? 'bg-green-700 text-white shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                    class="flex-1 py-2 text-sm font-bold rounded-lg transition-all">Personal</button>
            </div>

            <form action="{{ route('register.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf

                <input type="hidden" name="tipo" :value="tab">

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nombre Completo</label>
                    <input type="text" name="nombre_completo" required
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1a3a63] outline-none transition-all"
                        placeholder="Empezando por apellidos...">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1"
                        x-text="tab === 'estudiante' ? 'Carrera' : 'Departamento de Adscripción'"></label>

                    <select name="adscripcion" x-show="tab === 'estudiante'" :required="tab === 'estudiante'"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1a3a63] outline-none transition-all">
                        <option value="" disabled selected>Selecciona tu carrera</option>
                        <option value="Ingeniería en Sistemas Computacionales">Ingeniería en Sistemas Computacionales
                        </option>
                        <option value="Ingeniería Industrial">Ingeniería Industrial</option>
                        <option value="Ingeniería Mecánica">Ingeniería Mecánica</option>
                        <option value="Ingeniería Civil">Ingeniería Civil</option>
                        <option value="Ingeniería Eléctrica">Ingeniería Eléctrica</option>
                        <option value="Ingeniería Electrónica">Ingeniería Electrónica</option>
                        <option value="Ingeniería Química">Ingeniería Química</option>
                        <option value="Ingeniería en Gestión Empresarial">Ingeniería en Gestión Empresarial</option>
                        <option value="Licenciatura en Administración">Licenciatura en Administración</option>
                    </select>

                    <select name="adscripcion" x-show="tab === 'personal'" :required="tab === 'personal'"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-700 outline-none transition-all">
                        <option value="" disabled selected>Selecciona tu departamento</option>
                        <option value="Administración">Administración</option>
                        <option value="Académico">Académico</option>
                        <option value="Mantenimiento">Mantenimiento</option>
                        <option value="Recursos Humanos">Recursos Humanos</option>
                        <option value="Servicios Escolares">Servicios Escolares</option>
                        <option value="Comunicación y Difusión">Comunicación y Difusión</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1"
                        x-text="tab === 'estudiante' ? 'Número de Control' : 'Número de Empleado'"></label>
                    <input type="text" name="numero_id" required
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1a3a63] outline-none transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Correo Electrónico</label>
                    <input type="text" name="correo_electronico" placeholder="Correo o Usuario" required ...>
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1a3a63] outline-none transition-all"
                    placeholder="ejemplo@itoaxaca.edu.mx">
                </div>

                <div class="md:col-span-2 mt-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Contraseña</label>
                    <div class="relative">
                        <input :type="showP1 ? 'text' : 'password'" name="password" required x-model="pwd"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1a3a63] outline-none transition-all pr-12"
                            placeholder="Crea una contraseña segura">
                        <button type="button" @click="showP1 = !showP1"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-gray-700">
                            <svg x-show="!showP1" class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                            <svg x-show="showP1" class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.543 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>

                    <div class="bg-gray-50 p-3 rounded-lg mt-2 border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-500 uppercase mb-1">Tu contraseña debe tener:</p>
                        <ul class="text-[11px] font-semibold space-y-1">
                            <li :class="isValidLength ? 'text-green-600' : 'text-gray-400'"><span
                                    x-text="isValidLength ? '✓' : '○'"></span> Mínimo 8 caracteres</li>
                            <li :class="hasUpper ? 'text-green-600' : 'text-gray-400'"><span
                                    x-text="hasUpper ? '✓' : '○'"></span> Al menos una letra mayúscula</li>
                            <li :class="hasNumber ? 'text-green-600' : 'text-gray-400'"><span
                                    x-text="hasNumber ? '✓' : '○'"></span> Al menos un número</li>
                            <li :class="hasSymbol ? 'text-green-600' : 'text-gray-400'"><span
                                    x-text="hasSymbol ? '✓' : '○'"></span> Al menos un símbolo (ej. @$!%*?&)</li>
                        </ul>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Verificar Contraseña</label>
                    <div class="relative">
                        <input :type="showP2 ? 'text' : 'password'" name="password_confirmation" required
                            x-model="pwdConfirm"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1a3a63] outline-none transition-all pr-12"
                            placeholder="Repite tu contraseña">
                        <button type="button" @click="showP2 = !showP2"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-gray-700">
                            <svg x-show="!showP2" class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                            <svg x-show="showP2" class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.543 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <p x-show="pwdConfirm.length > 0" class="text-xs font-bold mt-2 transition-all"
                        :class="isMatch ? 'text-green-600' : 'text-red-500'">
                        <span
                            x-text="isMatch ? '✓ Las contraseñas coinciden' : '✗ Las contraseñas no coinciden'"></span>
                    </p>
                </div>

                <div class="md:col-span-2 pt-4">
                    <button type="submit" :disabled="!isFormValid"
                        :class="isFormValid ? (tab === 'estudiante' ? 'bg-[#1a3a63] hover:bg-[#0f2a4a] shadow-lg' :
                            'bg-green-700 hover:bg-green-800 shadow-lg') : 'bg-gray-300 cursor-not-allowed'"
                        class="w-full text-white py-4 rounded-xl font-black text-lg transition-all uppercase tracking-widest">
                        Crear Cuenta
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center border-t border-gray-100 pt-6">
                <p class="text-sm text-gray-500 font-bold">¿Ya tienes cuenta?</p>
                <a href="{{ route('login') }}"
                    class="text-[#1a3a63] text-sm font-black hover:underline mt-1 inline-block uppercase">Inicia sesión
                    aquí</a>
            </div>
        </div>
    </div>
</body>

</html>
