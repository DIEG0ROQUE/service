<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Datos y Foto - ITO</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 p-6 md:p-12">
    <div class="max-w-2xl mx-auto">
        <a href="{{ route('estudiante.dashboard') }}" class="text-indigo-700 font-bold mb-6 inline-block">← Volver al
            Panel</a>

        <div class="bg-white p-8 rounded-[2rem] shadow-xl border border-gray-200">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Actualizar Información</h1>

            <form action="{{ route('tarjeton.update', $tarjeton->id) }}" method="POST" enctype="multipart/form-data"
                class="space-y-8">
                @csrf
                @method('PUT')

                <div>
                    <h2 class="text-sm font-black text-[#1a3a63] uppercase border-b-2 border-[#1a3a63] pb-2 mb-4">1.
                        Datos Personales</h2>

                    <div class="bg-indigo-50 p-6 rounded-2xl border-2 border-dashed border-indigo-200 mb-6">
                        <label class="block text-xs font-bold text-indigo-700 uppercase mb-3 text-center">📸 Actualizar
                            Fotografía de Perfil</label>
                        <input type="file" name="foto" accept="image/*"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nombre Completo</label>
                            <input type="text" name="nombre_completo" value="{{ $user->nombre_completo }}" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1a3a63] outline-none">
                        </div>

                        @if (isset($user->carrera))
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Carrera</label>
                                <select name="adscripcion" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1a3a63] outline-none">
                                    <option value="{{ $user->carrera }}" selected>{{ $user->carrera }}</option>
                                    <option value="Ingeniería en Sistemas Computacionales">Ingeniería en Sistemas
                                        Computacionales</option>
                                    <option value="Ingeniería Industrial">Ingeniería Industrial</option>
                                    <option value="Ingeniería Mecánica">Ingeniería Mecánica</option>
                                    <option value="Ingeniería Civil">Ingeniería Civil</option>
                                    <option value="Ingeniería Eléctrica">Ingeniería Eléctrica</option>
                                    <option value="Ingeniería Electrónica">Ingeniería Electrónica</option>
                                    <option value="Ingeniería Química">Ingeniería Química</option>
                                    <option value="Ingeniería en Gestión Empresarial">Ingeniería en Gestión Empresarial
                                    </option>
                                    <option value="Licenciatura en Administración">Licenciatura en Administración
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Número de
                                    Control</label>
                                <input type="text" name="numero_id" value="{{ $user->numero_control }}" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1a3a63] outline-none">
                            </div>
                        @else
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Departamento</label>
                                <select name="adscripcion" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1a3a63] outline-none">
                                    <option value="{{ $user->departamento_adscripcion }}" selected>
                                        {{ $user->departamento_adscripcion }}</option>
                                    <option value="Administración">Administración</option>
                                    <option value="Académico">Académico</option>
                                    <option value="Mantenimiento">Mantenimiento</option>
                                    <option value="Recursos Humanos">Recursos Humanos</option>
                                    <option value="Servicios Escolares">Servicios Escolares</option>
                                    <option value="Comunicación y Difusión">Comunicación y Difusión</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Número de
                                    Empleado</label>
                                <input type="text" name="numero_id" value="{{ $user->numero_empleado }}" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1a3a63] outline-none">
                            </div>
                        @endif
                    </div>
                </div>

                <div>
                    <h2 class="text-sm font-black text-[#1a3a63] uppercase border-b-2 border-[#1a3a63] pb-2 mb-4 mt-8">
                        2. Datos del Vehículo</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Marca del
                                Vehículo</label>
                            <input type="text" name="marca" value="{{ $tarjeton->marca }}" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1a3a63] outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Modelo (Año)</label>
                            <input type="text" name="modelo" value="{{ $tarjeton->modelo }}" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1a3a63] outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Placas</label>
                            <input type="text" name="placas" value="{{ $tarjeton->placas }}" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1a3a63] outline-none uppercase">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Color</label>
                            <input type="text" name="color" value="{{ $tarjeton->color }}" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1a3a63] outline-none">
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit"
                        class="w-full bg-[#1a3a63] text-white py-4 rounded-2xl font-black text-lg shadow-lg hover:bg-[#0f2a4a] transition-all uppercase tracking-widest">
                        Guardar Todos los Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
