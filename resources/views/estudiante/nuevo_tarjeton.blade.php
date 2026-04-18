<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Nuevo Tarjetón - ITO</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
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

                <div x-data="buscadorVehiculos()" class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Año del Vehículo</label>
                        <select x-model="anio" @change="buscarModelos()"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 text-gray-700 font-bold">
                            <option value="">Selecciona el año...</option>
                            @for ($i = date('Y') + 1; $i >= 1990; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Marca</label>
                        <input type="text" name="marca" x-model="marca" @change="buscarModelos()"
                            list="lista-marcas" required placeholder="Ej. Nissan"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 uppercase">

                        <datalist id="lista-marcas">
                            <option value="Nissan"></option>
                            <option value="Volkswagen"></option>
                            <option value="Chevrolet"></option>
                            <option value="Ford"></option>
                            <option value="Toyota"></option>
                            <option value="Honda"></option>
                            <option value="Mazda"></option>
                            <option value="Kia"></option>
                            <option value="Hyundai"></option>
                            <option value="Seat"></option>
                            <option value="Peugeot"></option>
                            <option value="Suzuki"></option>
                        </datalist>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">
                            Modelo <span x-show="cargando"
                                class="text-indigo-500 ml-2 animate-pulse text-[10px]">Cargando...</span>
                        </label>
                        <input type="text" name="modelo" x-model="modelo" list="lista-modelos" required
                            :placeholder="cargando ? 'Buscando en catálogo...' : 'Ej. Versa'"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 uppercase"
                            :disabled="cargando">

                        <datalist id="lista-modelos">
                            <template x-for="mod in modelosAPI" :key="mod.Model_Name">
                                <option :value="mod.Model_Name"></option>
                            </template>
                        </datalist>
                        <p x-show="modelosAPI.length > 0" class="text-[9px] text-green-600 font-bold mt-1">✓ Modelos
                            cargados. Haz clic arriba para elegir.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Placas</label>
                        <input type="text" name="placas" required placeholder="TKM-123-A"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 uppercase">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Color</label>
                        <input type="text" name="color" required placeholder="Blanco"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 uppercase">
                    </div>
                </div>
                <hr class="my-6 border-gray-200 w-1/2">

                <h3 class="text-[12px] font-black text-red-600 uppercase tracking-widest mb-4">Información de Emergencia
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Nombre del Contacto</label>
                        <input type="text" name="contacto_emergencia_nombre" required
                            placeholder="Ej. María Inés Pérez"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Teléfono de
                            Emergencia</label>
                        <input type="tel" name="contacto_emergencia_telefono" required placeholder="9511234567"
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

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('buscadorVehiculos', () => ({
                marca: '',
                anio: '',
                modelo: '',
                modelosAPI: [],
                cargando: false,

                async buscarModelos() {
                    // Si el usuario ya seleccionó año y escribió al menos 2 letras de la marca
                    if (this.marca.length >= 2 && this.anio !== '') {
                        this.cargando = true;
                        this.modelosAPI = [];
                        this.modelo = ''; // Se limpia el modelo anterior por si cambian de marca

                        try {
                            // Primera búsqueda: Como vehículo de pasajeros (Passenger Car)
                            const response = await fetch(
                                `https://vpic.nhtsa.dot.gov/api/vehicles/GetModelsForMakeYear/make/${this.marca}/modelyear/${this.anio}/vehicleType/passenger car?format=json`
                            );
                            const data = await response.json();

                            if (data.Results && data.Results.length > 0) {
                                this.modelosAPI = data.Results;
                            } else {
                                // Búsqueda secundaria (General): Por si es camioneta, SUV o pickup
                                const responseAll = await fetch(
                                    `https://vpic.nhtsa.dot.gov/api/vehicles/GetModelsForMakeYear/make/${this.marca}/modelyear/${this.anio}?format=json`
                                );
                                const dataAll = await responseAll.json();
                                this.modelosAPI = dataAll.Results || [];
                            }
                        } catch (error) {
                            console.error("Error al obtener datos de la API NHTSA:", error);
                        } finally {
                            this.cargando = false;
                        }
                    } else {
                        // Si borran la marca o el año, vaciamos la lista
                        this.modelosAPI = [];
                    }
                }
            }))
        })
    </script>
</body>

</html>
