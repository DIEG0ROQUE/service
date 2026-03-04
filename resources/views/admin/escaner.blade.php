<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Escáner de Tarjetones - ITO</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100 font-sans antialiased min-h-screen flex flex-col">

    <div class="bg-[#1a3a63] text-white p-4 shadow-md flex justify-between items-center">
        <div class="font-black tracking-widest uppercase">Panel Admin ITO</div>
        <a href="{{ route('login') }}" class="text-xs font-bold hover:text-gray-300">Salir</a>
    </div>

    <div class="flex-grow flex flex-col items-center p-6" x-data="escanerData()">

        <div class="text-center mb-6">
            <h2 class="text-2xl font-black text-gray-800 uppercase">Validación Rápida</h2>
            <p class="text-gray-500 text-sm font-bold">Apunta la cámara al código de barras del tarjetón</p>
        </div>

        <div class="bg-white p-4 rounded-3xl shadow-lg border border-gray-200 w-full max-w-md">
            <div id="reader" class="w-full rounded-2xl overflow-hidden bg-white min-h-[300px]"></div>
        </div>

        <div x-show="mensaje" class="mt-8 w-full max-w-md p-6 rounded-2xl shadow-lg transition-all text-center border-2"
            :class="exito ? 'bg-green-50 border-green-400 text-green-800' : 'bg-red-50 border-red-400 text-red-800'"
            style="display: none;">
            <p class="text-2xl font-black uppercase" x-text="mensaje"></p>
            <p class="text-sm font-bold mt-2 opacity-70" x-show="folioEscaneado">Folio: <span
                    x-text="folioEscaneado"></span></p>
        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('escanerData', () => ({
                mensaje: '',
                exito: false,
                folioEscaneado: '',
                procesando: false,

                init() {
                    // Inicializar la cámara
                    const html5QrcodeScanner = new Html5QrcodeScanner(
                        "reader", {
                            fps: 10,
                            qrbox: {
                                width: 250,
                                height: 100
                            }
                        },
                        /* verbose= */
                        false
                    );

                    html5QrcodeScanner.render(this.onScanSuccess.bind(this));
                },

                onScanSuccess(decodedText, decodedResult) {
                    // Evitar escaneos dobles si ya estamos procesando uno
                    if (this.procesando) return;
                    this.procesando = true;

                    // Reproducir sonido de "beep" (opcional, ayuda mucho en la vida real)
                    let beep = new Audio('https://www.soundjay.com/buttons/sounds/beep-07a.mp3');
                    beep.play();

                    // Mandar a validar a Laravel
                    fetch('{{ route('admin.validar') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: JSON.stringify({
                                folio: decodedText
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            this.mensaje = data.message;
                            this.exito = data.success;
                            this.folioEscaneado = data.folio || decodedText;

                            // Esperar 3 segundos antes de permitir escanear otro
                            setTimeout(() => {
                                this.procesando = false;
                                this.mensaje = '';
                            }, 3000);
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            this.procesando = false;
                        });
                }
            }))
        })
    </script>
</body>

</html>
