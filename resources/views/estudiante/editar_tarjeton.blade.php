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
                class="space-y-6">
                @csrf
                @method('PUT')

                <div class="bg-indigo-50 p-6 rounded-2xl border-2 border-dashed border-indigo-200">
                    <label class="block text-xs font-bold text-indigo-700 uppercase mb-3 text-center">📸 Actualizar
                        Fotografía de Perfil</label>
                    <input type="file" name="foto" accept="image/*"
                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Marca del Vehículo</label>
                        <input type="text" name="marca" value="{{ $tarjeton->marca }}" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Modelo (Año)</label>
                        <input type="text" name="modelo" value="{{ $tarjeton->modelo }}" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Placas</label>
                        <input type="text" name="placas" value="{{ $tarjeton->placas }}" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Color</label>
                        <input type="text" name="color" value="{{ $tarjeton->color }}" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit"
                        class="w-full bg-[#1a3a63] text-white py-4 rounded-2xl font-bold text-lg shadow-lg hover:bg-indigo-900 transition-all uppercase tracking-widest">
                        Guardar Cambios y Foto
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
