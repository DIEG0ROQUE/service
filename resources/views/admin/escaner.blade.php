<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Validación de Accesos - ITO</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* DOMANDO LA LIBRERÍA DE LA CÁMARA PARA QUE SE VEA COMO APP NATIVA */
        #reader {
            border: none !important;
            background: #000;
            border-radius: 1rem;
            overflow: hidden;
            width: 100%;
        }

        /* Botones feos de la librería */
        #reader button {
            background-color: #1a3a63 !important;
            color: white !important;
            padding: 12px 20px !important;
            border-radius: 0.75rem !important;
            font-weight: 900 !important;
            border: none !important;
            width: 100% !important;
            margin-top: 10px !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
        }

        /* Enlaces feos de la librería */
        #reader a {
            color: #4f46e5 !important;
            text-decoration: none !important;
            font-weight: bold;
            display: inline-block;
            margin-top: 10px;
        }

        /* Ocultar elementos innecesarios */
        #reader__dashboard_section_swaplink {
            display: none !important;
        }

        /* Ajustar el video al 100% del contenedor */
        #reader video {
            object-fit: cover !important;
            border-radius: 1rem !important;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans antialiased min-h-screen flex flex-col">

    <div class="bg-[#1a3a63] text-white p-4 shadow-md flex justify-between items-center shrink-0">
        <div class="font-black tracking-widest uppercase text-sm flex items-center gap-2">
            <span>📱</span> Validación ITO
        </div>
        <a href="{{ route('login') }}"
            class="text-xs font-bold hover:text-gray-300 uppercase bg-white/10 px-3 py-1.5 rounded-lg">Salir</a>
    </div>

    <div class="flex-grow flex flex-col items-center p-4 w-full max-w-lg mx-auto" x-data="escanerData()">

        <div x-show="!mostrarDatos" class="w-full transition-all flex flex-col items-center justify-center mt-2">
            <div class="text-center mb-4">
                <h2 class="text-xl font-black text-gray-800 uppercase">Escanear Tarjetón</h2>
                <p class="text-gray-500 text-xs font-bold mt-1">Apunta al código de barras o QR</p>
            </div>

            <div class="bg-white p-3 rounded-[1.5rem] shadow-xl border border-gray-200 w-full relative">
                <div id="reader"></div>

                <div x-show="procesando" style="display: none;"
                    class="absolute inset-0 bg-white/80 backdrop-blur-sm z-50 flex flex-col items-center justify-center rounded-[1.5rem]">
                    <div
                        class="animate-spin rounded-full h-12 w-12 border-4 border-[#1a3a63] border-t-transparent mb-3">
                    </div>
                    <p class="font-black text-[#1a3a63] uppercase tracking-widest text-sm">Validando...</p>
                </div>
            </div>

            <div x-show="errorMsg" style="display: none;"
                class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl text-center font-bold w-full text-sm"
                x-text="errorMsg"></div>
        </div>

        <div x-show="mostrarDatos" style="display: none;"
            class="w-full bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-200 mt-2">

            <div class="p-4 text-center transition-all"
                :class="datos.tarjeton.activo == 1 ? 'bg-green-600' : 'bg-amber-500'">
                <p class="text-white font-black text-xl tracking-widest uppercase"
                    x-text="datos.tarjeton.activo == 1 ? '✅ ESTATUS: ACTIVO' : '⚠️ INACTIVO'"></p>
            </div>

            <div class="p-4 sm:p-6">
                <div class="flex items-center gap-3 mb-5 border-b border-gray-100 pb-5">
                    <div
                        class="w-16 h-20 sm:w-20 sm:h-24 bg-gray-100 rounded-lg overflow-hidden border-2 border-gray-300 shrink-0 flex items-center justify-center shadow-inner">
                        <template x-if="datos.foto">
                            <img :src="'/storage/' + datos.foto" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!datos.foto">
                            <span
                                class="text-[9px] text-gray-400 font-bold uppercase text-center leading-none">Sin<br>Foto</span>
                        </template>
                    </div>
                    <div class="flex-1 overflow-hidden">
                        <p class="text-[9px] font-black text-[#1a3a63] uppercase" x-text="datos.tipo"></p>
                        <p class="text-base sm:text-lg font-black text-gray-800 uppercase leading-tight mb-1 truncate"
                            x-text="datos.nombre"></p>
                        <p class="text-[10px] sm:text-xs font-bold text-gray-500 uppercase truncate"
                            x-text="datos.adscripcion"></p>
                        <p class="text-xs sm:text-sm font-black text-gray-800 mt-1"
                            x-text="'ID: ' + datos.identificador"></p>
                    </div>
                </div>

                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Datos del Vehículo</h3>
                <div class="bg-gray-50 p-3 rounded-2xl grid grid-cols-2 gap-3 mb-6 border border-gray-100">
                    <div class="col-span-2 flex justify-between items-end border-b border-gray-200 pb-2">
                        <div>
                            <p class="text-[9px] font-bold text-gray-500 uppercase">Placas</p>
                            <p class="font-black text-[#1a3a63] uppercase text-xl leading-none tracking-wider"
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
                        <p class="text-[9px] font-bold text-gray-500 uppercase">Marca / Modelo</p>
                        <p class="font-bold text-gray-800 text-xs uppercase"
                            x-text="datos.tarjeton.marca + ' ' + datos.tarjeton.modelo"></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-gray-500 uppercase">Color / Folio</p>
                        <p class="font-bold text-gray-800 text-xs uppercase"
                            x-text="datos.tarjeton.color + ' - ' + datos.tarjeton.folio"></p>
                    </div>
                </div>

                <div class="space-y-3">
                    <button @click="cambiarEstatus()" :disabled="cambiando"
                        class="w-full py-3.5 rounded-xl font-black text-white uppercase tracking-widest shadow-lg transition-all flex justify-center items-center gap-2"
                        :class="datos.tarjeton.activo == 1 ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700'">
                        <span x-show="cambiando"
                            class="animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent"></span>
                        <span
                            x-text="cambiando ? 'Procesando...' : (datos.tarjeton.activo == 1 ? 'Desactivar Tarjetón' : 'Aprobar / Sellar')"></span>
                    </button>

                    <button @click="escanearOtro()" :disabled="cambiando"
                        class="w-full py-3 bg-gray-100 text-gray-600 rounded-xl font-bold uppercase hover:bg-gray-200 transition-all text-sm">
                        Volver a Escanear
                    </button>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('escanerData', () => ({
                mostrarDatos: false,
                procesando: false, // Para el overlay de la cámara
                cambiando: false, // Para el spinner del botón
                errorMsg: '',
                datos: {
                    tarjeton: {
                        id: null,
                        activo: 0,
                        placas: '',
                        folio: '',
                        marca: '',
                        modelo: '',
                        color: ''
                    },
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

                iniciarCamara() {
                    this.html5QrcodeScanner = new Html5QrcodeScanner(
                        "reader", {
                            fps: 10,
                            qrbox: {
                                width: 250,
                                height: 100
                            }
                        },
                        false
                    );
                    this.html5QrcodeScanner.render(this.onScanSuccess.bind(this));
                },

                async onScanSuccess(decodedText, decodedResult) {
                    if (this.procesando) return;
                    this.procesando = true;
                    this.errorMsg = '';

                    // Sonido anti-bloqueo
                    try {
                        let beep = new Audio(
                            'https://www.soundjay.com/buttons/sounds/beep-07a.mp3');
                        beep.play().catch(e => {});
                    } catch (e) {}

                    try {
                        let res = await fetch('/admin/escaner/buscar', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
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
                            setTimeout(() => {
                                this.errorMsg = '';
                            }, 3000);
                        }
                    } catch (err) {
                        this.errorMsg = 'Error de conexión con el servidor.';
                    } finally {
                        this.procesando = false;
                    }
                },

                async cambiarEstatus() {
                    if (this.cambiando) return;
                    this.cambiando = true; // Activa el spinner del botón

                    try {
                        let res = await fetch('/admin/escaner/toggle', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                                'ngrok-skip-browser-warning': 'true'
                            },
                            // CAMBIO AQUÍ: Ahora enviamos el folio que escaneamos originalmente
                            body: JSON.stringify({
                                folio: this.datos.tarjeton.folio
                            })
                        });

                        let data = await res.json();

                        if (data.success) {
                            // Actualizamos las variables locales al instante
                            this.datos.tarjeton.activo = data.estado;
                            this.datos.vigencia = data.vigencia;
                        } else {
                            // Ahora el mensaje de error será más específico
                            alert("Error: " + (data.message ||
                                "Hubo un error al actualizar el estatus."));
                        }
                    } catch (err) {
                        alert("Error de red. Intenta de nuevo.");
                    } finally {
                        this.cambiando = false; // Apaga el spinner del botón
                    }
                },

                escanearOtro() {
                    this.mostrarDatos = false;
                    this.procesando = false;
                    this.cambiando = false;
                    this.datos = {
                        tarjeton: {
                            id: null,
                            activo: 0
                        }
                    }; // Reset de seguridad
                    this.iniciarCamara();
                }
            }))
        })
    </script>
</body>

</html>
