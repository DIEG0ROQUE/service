<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Nuevo Tarjetón - ITO</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 p-6">
    <div class="max-w-2xl mx-auto">
        <a href="{{ route('estudiante.dashboard') }}" class="text-indigo-700 font-bold mb-6 inline-block">← Volver al
            panel</a>

        <div class="bg-white p-8 rounded-[2rem] shadow-xl border border-gray-100">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Registro de Vehículo</h1>
            <p class="text-gray-500 mb-8">Ingresa los datos del auto para el que solicitas el tarjetón de acceso.</p>


            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700">
                    <ul class="list-disc ml-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <form action="{{ route('tarjeton.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Marca</label>
                        <input type="text" name="marca" required placeholder="Ej. Nissan"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Modelo</label>
                        <input type="text" name="modelo" required placeholder="Ej. Versa"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Placas</label>
                        <input type="text" name="placas" required placeholder="TKM-123-A"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Color</label>
                        <input type="text" name="color" required placeholder="Gris Plata"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-indigo-700 text-white py-4 rounded-2xl font-bold text-lg shadow-lg hover:bg-indigo-800 transition-all">
                        Enviar Solicitud de Tarjetón
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
