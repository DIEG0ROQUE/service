<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Acceso Seguridad - ITO</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        #reader {
            border: none !important;
            background: #000;
            border-radius: 1rem;
            overflow: hidden;
            width: 100%;
        }

        #reader button {
            background-color: #1a3a63 !important;
            color: white !important;
            padding: 12px !important;
            border-radius: 0.75rem !important;
            font-weight: 900 !important;
            border: none !important;
            width: 100% !important;
            margin-top: 10px !important;
            text-transform: uppercase !important;
        }

        #reader a {
            display: none !important;
        }

        #reader video {
            object-fit: cover !important;
            border-radius: 1rem !important;
        }

        body {
            padding-bottom: 80px;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans antialiased min-h-screen flex flex-col" x-data="guardiaApp()">

    <div class="bg-gray-900 text-white p-4 shadow-md flex justify-between items-center shrink-0 fixed top-0 w-full z-40">
        <div class="font-black tracking-widest uppercase text-sm flex items-center gap-2">
            <span>🛡️</span> Caseta ITO
        </div>
        <a href="{{ route('login') }}"
            class="text-xs font-bold hover:text-gray-300 uppercase bg-white/20 px-3 py-1.5 rounded-lg">Salir</a>
    </div>

    <div class="mt-16 p-4 w-full max-w-lg mx-auto flex-grow">

        <div x-show="tab === 'escaner'" x-transition>
            <div x-show="!mostrarDatos" class="w-full transition-all flex flex-col items-center justify-center">
                <div class="text-center mb-4">
                    <h2 class="text-xl font-black text-gray-800 uppercase">Verificar Tarjetón</h2>
                </div>
                <div class="bg-white p-3 rounded-[1.5rem] shadow-xl border border-gray-200 w-full relative">
                    <div id="reader"></div>
                </div>
                <div x-show="errorMsg" style="display: none;"
                    class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl text-center font-bold text-sm"
                    x-text="errorMsg"></div>
            </div>

            <div x-show="mostrarDatos" style="display: none;"
                class="w-full bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-200 mt-2">
                <div class="p-4 text-center transition-all"
                    :class="datos.tarjeton.activo == 1 ? 'bg-green-600' : 'bg-red-600'">
                    <p class="text-white font-black text-xl tracking-widest uppercase"
                        x-text="datos.tarjeton.activo == 1 ? '✅ ACCESO PERMITIDO' : '🚫 ACCESO DENEGADO'"></p>
                    <p class="text-white/80 text-[10px] font-bold uppercase mt-1"
                        x-text="datos.tarjeton.activo == 1 ? 'Tarjetón Activo' : 'Tarjetón sin sellar o inactivo'"></p>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="flex items-center gap-3 mb-5 border-b border-gray-100 pb-5">
                        <div
                            class="w-16 h-20 bg-gray-100 rounded-lg overflow-hidden border-2 border-gray-300 shrink-0 flex items-center justify-center">
                            <template x-if="datos.foto"><img :src="'/storage/' + datos.foto"
                                    class="w-full h-full object-cover"></template>
                            <template x-if="!datos.foto"><span
                                    class="text-[9px] text-gray-400 font-bold uppercase text-center">Sin<br>Foto</span></template>
                        </div>
                        <div class="flex-1 overflow-hidden">
                            <p class="text-[9px] font-black text-gray-500 uppercase" x-text="datos.tipo"></p>
                            <p class="text-base font-black text-gray-800 uppercase leading-tight mb-1 truncate"
                                x-text="datos.nombre"></p>
                            <p class="text-xs font-black text-[#1a3a63] mt-1" x-text="'ID: ' + datos.identificador"></p>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-2xl grid grid-cols-2 gap-3 mb-6 border border-gray-100">
                        <div class="col-span-2 flex justify-between items-end border-b border-gray-200 pb-2">
                            <div>
                                <p class="text-[9px] font-bold text-gray-500 uppercase">Placas</p>
                                <p class="font-black text-gray-800 uppercase text-xl leading-none"
                                    x-text="datos.tarjeton.placas"></p>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] font-bold text-gray-500 uppercase">Vigencia</p>
                                <p class="font-black text-xs"
                                    :class="datos.tarjeton.activo == 1 ? 'text-green-600' : 'text-gray-400'"
                                    x-text="datos.vigencia"></p>
                            </div>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-gray-500 uppercase">Marca/Modelo</p>
                            <p class="font-bold text-gray-800 text-xs uppercase"
                                x-text="datos.tarjeton.marca + ' ' + datos.tarjeton.modelo"></p>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-gray-500 uppercase">Color</p>
                            <p class="font-bold text-gray-800 text-xs uppercase" x-text="datos.tarjeton.color"></p>
                        </div>
                    </div>
                    <button @click="limpiarDatos()"
                        class="w-full py-4 bg-gray-200 text-gray-800 rounded-xl font-black uppercase hover:bg-gray-300 transition-all text-sm tracking-widest shadow-sm">Terminar
                        Revisión</button>
                </div>
            </div>
        </div>

        <div x-show="tab === 'placas'" style="display: none;" x-transition>
            <div class="text-center mb-6">
                <h2 class="text-xl font-black text-gray-800 uppercase">Buscar Placa</h2>
            </div>
            <div class="bg-white p-6 rounded-[2rem] shadow-lg border border-gray-200 w-full mb-4">
                <input type="text" x-model="placaSearch" placeholder="Ej. ABC-123"
                    class="w-full text-center text-2xl font-black uppercase tracking-widest border-2 border-gray-300 rounded-xl py-4 mb-4 outline-none focus:border-gray-900">
                <button @click="buscarPlacaEnBD()" :disabled="buscando"
                    class="w-full py-4 bg-gray-900 text-white rounded-xl font-black uppercase tracking-widest flex justify-center items-center gap-2">
                    <span x-text="buscando ? 'Buscando...' : '🔍 Buscar'"></span>
                </button>
            </div>
            <div x-show="errorMsg" style="display: none;"
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl text-center font-bold text-sm"
                x-text="errorMsg"></div>
        </div>

        <div x-show="tab === 'visitas'" style="display: none;" x-transition>
            <div class="text-center mb-6">
                <h2 class="text-xl font-black text-gray-800 uppercase">Registro Externo</h2>
            </div>
            @if (session('success'))
                <div
                    class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl text-center font-bold text-sm">
                    {{ session('success') }}</div>
            @endif

            <form action="{{ route('guardia.registrar.visita') }}" method="POST" enctype="multipart/form-data"
                class="bg-white p-6 rounded-[2rem] shadow-lg border border-gray-200 space-y-4">
                @csrf
                <div><label
                        class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Conductor</label><input
                        type="text" name="nombre_conductor" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold outline-none">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label
                            class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Placas</label><input
                            type="text" name="placa" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-black uppercase outline-none">
                    </div>
                    <div><label
                            class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Motivo</label><select
                            name="motivo" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold outline-none">
                            <option value="Proveedor">Proveedor</option>
                            <option value="Trámite Escolar">Trámite</option>
                            <option value="Visita">Visita</option>
                            <option value="Otro">Otro</option>
                        </select></div>
                </div>
                <div><label
                        class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Notas</label><input
                        type="text" name="nota"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold outline-none">
                </div>

                <div class="relative overflow-hidden">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Evidencia
                        (Foto)</label>
                    <label
                        class="flex flex-col items-center justify-center w-full h-24 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50">
                        <span class="text-2xl mb-1">📸</span>
                        <p class="text-xs font-bold text-gray-500 uppercase">Tocar para cámara</p>
                        <input type="file" name="foto" accept="image/*" capture="environment" class="hidden"
                            required />
                    </label>
                </div>
                <button type="submit"
                    class="w-full py-4 mt-4 bg-gray-900 text-white rounded-xl font-black uppercase tracking-widest shadow-lg">Guardar
                    Registro</button>
            </form>
        </div>
    </div>

    <div
        class="fixed bottom-0 w-full bg-white border-t border-gray-200 shadow-[0_-5px_15px_rgba(0,0,0,0.05)] z-50 px-6 py-3 flex justify-between items-center">
        <button @click="cambiarTab('escaner')" class="flex flex-col items-center gap-1 transition-all"
            :class="tab === 'escaner' ? 'text-gray-900 scale-110' : 'text-gray-400'"><span
                class="text-2xl">📷</span><span
                class="text-[9px] font-black uppercase tracking-widest">Escáner</span></button>
        <button @click="cambiarTab('placas')" class="flex flex-col items-center gap-1 transition-all"
            :class="tab === 'placas' ? 'text-gray-900 scale-110' : 'text-gray-400'"><span
                class="text-2xl">🔍</span><span
                class="text-[9px] font-black uppercase tracking-widest">Buscar</span></button>
        <button @click="cambiarTab('visitas')" class="flex flex-col items-center gap-1 transition-all"
            :class="tab === 'visitas' ? 'text-gray-900 scale-110' : 'text-gray-400'"><span
                class="text-2xl">📝</span><span
                class="text-[9px] font-black uppercase tracking-widest">Visitas</span></button>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('guardiaApp', () => ({
                tab: 'escaner',
                mostrarDatos: false,
                buscando: false,
                placaSearch: '',
                errorMsg: '',
                datos: {
                    tarjeton: {},
                    vigencia: '',
                    nombre: '',
                    identificador: '',
                    adscripcion: '',
                    tipo: '',
                    foto: null
                },
                html5QrcodeScanner: null,
                init() {
                    this.iniciarCamara();
                },
                cambiarTab(nuevaTab) {
                    this.tab = nuevaTab;
                    this.limpiarDatos();
                    if (nuevaTab === 'escaner') setTimeout(() => this.iniciarCamara(), 100);
                    else if (this.html5QrcodeScanner) this.html5QrcodeScanner.clear();
                },
                iniciarCamara() {
                    if (!document.getElementById('reader')) return;
                    this.html5QrcodeScanner = new Html5QrcodeScanner("reader", {
                        fps: 10,
                        qrbox: {
                            width: 250,
                            height: 100
                        }
                    }, false);
                    this.html5QrcodeScanner.render(this.onScanSuccess.bind(this));
                },
                async onScanSuccess(decodedText) {
                    try {
                        let beep = new Audio(
                            'https://www.soundjay.com/buttons/sounds/beep-07a.mp3');
                        beep.play().catch(e => {});
                    } catch (e) {}
                    // Reutilizamos la ruta del admin para solo LEER los datos
                    try {
                        let res = await fetch('/admin/escaner/buscar', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content'),
                                'ngrok-skip-browser-warning': 'true'
                            },
                            body: JSON.stringify({
                                folio: decodedText
                            })
                        });
                        let data = await res.json();
                        if (data.success) {
                            this.datos = data;
                            this.mostrarDatos = true;
                            this.html5QrcodeScanner.clear();
                        } else {
                            this.errorMsg = data.message;
                            setTimeout(() => this.errorMsg = '', 3000);
                        }
                    } catch (e) {
                        this.errorMsg = "Error de red";
                    }
                },
                async buscarPlacaEnBD() {
                    if (!this.placaSearch) return;
                    this.buscando = true;
                    this.errorMsg = '';
                    try {
                        let res = await fetch('/guardia/buscar-placa', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content'),
                                'ngrok-skip-browser-warning': 'true'
                            },
                            body: JSON.stringify({
                                placa: this.placaSearch
                            })
                        });
                        let data = await res.json();
                        if (data.success) {
                            this.datos = data;
                            this.mostrarDatos = true;
                            this.tab = 'escaner';
                        } else {
                            this.errorMsg = data.message;
                            setTimeout(() => this.errorMsg = '', 3000);
                        }
                    } catch (e) {
                        this.errorMsg = "Error de conexión";
                    } finally {
                        this.buscando = false;
                    }
                },
                limpiarDatos() {
                    this.mostrarDatos = false;
                    this.placaSearch = '';
                    this.errorMsg = '';
                    this.datos = {
                        tarjeton: {}
                    };
                    if (this.tab === 'escaner') this.iniciarCamara();
                }
            }))
        })
    </script>
</body>

</html>
