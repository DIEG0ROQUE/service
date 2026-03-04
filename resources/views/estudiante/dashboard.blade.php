<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Tarjetón ITO - {{ $user->nombre_completo }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Arial:wght@400;700&display=swap');

        /* Marca de agua institucional para el reverso */
        .watermark {
            background-image: url('https://upload.wikimedia.org/wikipedia/commons/4/47/Logo_del_TecNM.png');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 80%;
            opacity: 0.05;
            position: absolute;
            inset: 0;
            z-index: 0;
        }

        /* Dimensiones exactas del tarjetón para simetría */
        .tarjeton-card {
            width: 320px;
            height: 540px;
            background: white;
            border-radius: 2.5rem;
            overflow: hidden;
            position: relative;
            display: flex;
            flex-direction: column;
            page-break-inside: avoid;
        }

        /* Ajuste de nitidez para el código de barras SVG */
        .barcode-svg svg {
            width: 100%;
            height: 60px;
        }

        /* CONFIGURACIÓN PARA IMPRESIÓN PDF */
        @media print {

            /* ESTA REGLA OBLIGA A IMPRIMIR TODOS LOS COLORES Y FONDOS */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            body {
                background: white !important;
                padding: 0 !important;
            }

            .print\:hidden {
                display: none !important;
            }

            .print\:shadow-none {
                box-shadow: none !important;
                border: none !important;
            }

            .container-main {
                display: block !important;
            }

            /* Mantenemos el tamaño y forzamos bordes nítidos para recortar */
            .tarjeton-card {
                width: 320px !important;
                height: 550px !important;
                border: 1px solid #000 !important;
                margin: 10px;
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body class="bg-gray-100 font-sans antialiased p-4 md:p-10">

    <div class="max-w-7xl mx-auto container-main">
        <div class="flex flex-col lg:flex-row gap-10">

            <div
                class="flex-1 bg-white p-6 md:p-8 rounded-[2.5rem] shadow-sm border border-gray-200 print:shadow-none print:border-none print:p-0">
                <div class="flex justify-between items-center mb-8 border-b pb-4 print:hidden">
                    <div>
                        <h2 class="text-xl font-black text-gray-800 uppercase italic leading-none">Vista de Impresión
                        </h2>
                        <p class="text-xs text-gray-400 font-bold mt-1 uppercase">Asegúrate de que el código de barras
                            sea visible</p>
                    </div>
                    <button onclick="window.print()"
                        class="bg-indigo-700 text-white px-6 py-3 rounded-2xl font-black shadow-lg hover:bg-indigo-800 transition-all flex items-center gap-2">
                        <span>🖨️</span> GENERAR PDF
                    </button>
                </div>

                <div
                    class="flex flex-wrap justify-center gap-6 bg-gray-50 p-6 rounded-[2rem] border-2 border-dashed border-gray-200 print:bg-white print:border-none print:p-0">

                    <!-- ============================================ -->
                    <!-- TARJETA FRONTAL -->
                    <!-- ============================================ -->

                    <div class="tarjeton-card border-2 border-gray-300">
                        <!-- PERCHA FRONTAL (GRIS OSCURO) -->
                        <div class="h-[130px] shrink-0 bg-[#999] relative border-b border-gray-400">
                            <div
                                class="absolute top-[30px] left-1/2 -translate-x-1/2 w-[90px] h-[90px] bg-white rounded-full border-2 border-gray-400 z-20">
                            </div>
                        </div>

                        <div class="p-5 flex-grow relative z-10 flex flex-col">
                            <div class="flex justify-between items-center mb-4">
                                <img src="http://127.0.0.1:8000/logo tecnm2.png" class="h-8">
                                <img src="{{ asset('logo.png') }}" class="h-10">
                            </div>

                            <div class="flex gap-3 mb-4">
                                <div class="flex-1">
                                    <p class="text-[10px] font-black text-[#1a3a63] uppercase">Nombre:</p>
                                    <p class="text-[13px] font-black text-gray-800 leading-tight mb-2 uppercase">
                                        {{ $user->nombre_completo }}</p>

                                    <p class="text-[10px] font-black text-[#1a3a63] uppercase">Carrera:</p>
                                    <p class="text-[11px] font-bold text-gray-700 mb-2 uppercase">{{ $user->carrera }}
                                    </p>

                                    <p class="text-[10px] font-black text-[#1a3a63] uppercase">No. de control:</p>
                                    <p class="text-lg font-black text-gray-900 uppercase tracking-tighter">
                                        {{ $user->numero_control }}</p>
                                </div>

                                <div
                                    class="w-20 h-28 border-2 border-[#1a3a63] bg-white overflow-hidden shadow-sm flex items-center justify-center">
                                    @if ($user->foto)
                                        <img src="{{ asset('storage/' . $user->foto) }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div
                                            class="text-[8px] font-bold text-gray-400 text-center uppercase rotate-90 leading-none">
                                            FOTO Y SELLO<br>INSTITUCIONAL</div>
                                    @endif
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-y-2 border-t border-gray-200 pt-3">
                                <div>
                                    <p class="text-[10px] font-black text-[#1a3a63] uppercase">Vehículo</p>
                                    <p class="text-xs font-bold uppercase">{{ $tarjeton->marca }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-[#1a3a63] uppercase">Color</p>
                                    <p class="text-xs font-bold uppercase">{{ $tarjeton->color }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-[#1a3a63] uppercase">Modelo</p>
                                    <p class="text-xs font-bold uppercase">{{ $tarjeton->modelo }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-[#1a3a63] uppercase">Placas</p>
                                    <p class="text-xs font-black text-indigo-800 uppercase">{{ $tarjeton->placas }}</p>
                                </div>
                            </div>

                            <div
                                class="bg-[#1a3a63] text-white text-center py-3 font-black text-xl tracking-[0.3em] uppercase mt-auto -mx-5 -mb-5 shrink-0">
                                Estudiante
                            </div>
                        </div>
                    </div>

                    <!-- ============================================ -->
                    <!-- TARJETA TRASERA -->
                    <!-- ============================================ -->
                    <div class="tarjeton-card border-2 border-gray-300">
                        <div class="watermark"></div>

                        <div class="h-[130px] shrink-0 bg-[#e5e5e5] relative border-b border-gray-300">
                            <div
                                class="absolute top-[30px] left-1/2 -translate-x-1/2 w-[90px] h-[90px] bg-white rounded-full border-2 border-gray-400 z-20">
                            </div>
                        </div>

                        <div class="p-5 flex-grow relative z-10 flex flex-col bg-white">
                            <div class="h-2 shrink-0"></div>

                            <div class="flex justify-center mb-1 barcode-svg shrink-0">
                                {!! $barcode !!}
                            </div>
                            <p class="text-[9px] font-mono font-bold mb-3 text-gray-600 text-center uppercase shrink-0">
                                {{ $tarjeton->folio }}
                            </p>

                            <div
                                class="flex-grow flex flex-col justify-center border-t border-gray-200 pt-2 pb-2 text-center">
                                <div class="space-y-2.5">
                                    <div>
                                        <p class="text-[9px] font-black text-gray-800 uppercase leading-none">
                                            EMERGENCIAS</p>
                                        <p class="text-[10px] font-bold text-gray-600">911</p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-gray-800 uppercase leading-none">BOMBEROS
                                        </p>
                                        <p class="text-[8px] font-bold text-gray-600 uppercase">Estación Central: 54 92
                                            197</p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-gray-800 uppercase leading-none">CRUZ ROJA
                                        </p>
                                        <p class="text-[8px] font-bold text-gray-600 uppercase">065, 51 6 44 55, 51 6 40
                                            03</p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-gray-800 uppercase leading-none">POLICÍA
                                            MUNICIPAL</p>
                                        <p class="text-[8px] font-bold text-gray-600 uppercase">51 4 45 25, 51 6 04 00
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-gray-800 uppercase leading-none">DIRECCIÓN
                                            DE TRÁNSITO</p>
                                        <p class="text-[8px] font-bold text-gray-600 uppercase">57 25 800, 57 25 801</p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-gray-800 uppercase leading-none">HOSPITAL
                                            DEL ISSSTE</p>
                                        <p class="text-[7px] font-bold text-gray-600 uppercase leading-tight">51 5 33
                                            11, 51 5 35 00, 51 5 39 02</p>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="bg-[#1a3a63] text-white text-center py-3 font-black text-xl tracking-[0.3em] uppercase mt-auto -mx-5 -mb-5 shrink-0">
                                Estudiante
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- ============================================ -->
            <!-- PANEL LATERAL DERECHO -->
            <!-- ============================================ -->
            <div class="w-full lg:w-96 space-y-6 print:hidden">

                <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-200">
                    <h3 class="text-xs font-black text-gray-400 uppercase mb-4 tracking-widest">Estatus del
                        Trámite</h3>
                    @if ($tarjeton->activo)
                        <div class="flex items-center gap-4 bg-green-50 p-4 rounded-2xl border border-green-200">
                            <div
                                class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center text-white text-xl">
                                ✓</div>
                            <div>
                                <p class="text-green-700 font-black text-lg leading-none">ACTIVO</p>
                                <p class="text-green-600 text-[10px] font-bold mt-1 uppercase">Vigencia:
                                    {{ \Carbon\Carbon::parse($tarjeton->vigencia)->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-4 bg-amber-50 p-4 rounded-2xl border border-amber-200">
                            <div
                                class="w-12 h-12 bg-amber-500 rounded-full flex items-center justify-center text-white text-xl font-black">
                                !</div>
                            <div>
                                <p class="text-amber-700 font-black text-lg leading-none uppercase">En Revisión
                                </p>
                                <p class="text-amber-600 text-[10px] font-bold mt-1 uppercase">Pendiente de
                                    sellado</p>
                            </div>
                        </div>
                    @endif
                    <div class="mt-4 flex gap-2">
                        <a href="{{ route('tarjeton.edit', $tarjeton->id) }}"
                            class="flex-1 text-center bg-gray-100 text-gray-600 py-2 rounded-xl text-xs font-bold hover:bg-gray-200 transition-all uppercase">✏️
                            Editar</a>
                        <a href="{{ route('login') }}"
                            class="flex-1 text-center bg-gray-100 text-gray-600 py-2 rounded-xl text-xs font-bold hover:bg-gray-200 transition-all uppercase">Salir</a>
                    </div>
                </div>

                <div class="bg-[#1a3a63] p-8 rounded-[2.5rem] text-white shadow-2xl">
                    <h3 class="text-lg font-black mb-6 border-b border-[#2a4a73] pb-2 uppercase italic">
                        Validación de Tarjetón</h3>
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <span
                                class="bg-white text-[#1a3a63] w-7 h-7 rounded-full flex items-center justify-center font-black shrink-0 text-sm">1</span>
                            <p class="text-xs font-bold leading-tight">Recorte cuidadosamente siguiendo las
                                líneas
                                marcadas, incluyendo el orificio circular superior.</p>
                        </div>
                        <div class="flex gap-4">
                            <span
                                class="bg-white text-[#1a3a63] w-7 h-7 rounded-full flex items-center justify-center font-black shrink-0 text-sm">2</span>
                            <p class="text-xs font-bold leading-tight">Pegue ambas caras (Frontal y Trasera) de
                                manera
                                que queden perfectamente alineadas.</p>
                        </div>
                        <div class="flex gap-4">
                            <span
                                class="bg-white text-[#1a3a63] w-7 h-7 rounded-full flex items-center justify-center font-black shrink-0 text-sm">3</span>
                            <p class="text-xs font-bold leading-tight uppercase text-amber-300">Validación:
                                Lleve el
                                tarjetón al Departamento de Comunicación y Difusión para su sellado oficial.</p>
                        </div>
                        <div class="flex gap-4">
                            <span
                                class="bg-white text-[#1a3a63] w-7 h-7 rounded-full flex items-center justify-center font-black shrink-0 text-sm">4</span>
                            <p class="text-xs font-bold leading-tight">Enmique el tarjetón para asegurar su
                                durabilidad
                                contra el desgaste diario.</p>
                        </div>
                    </div>
                    <p class="mt-8 text-[9px] text-gray-400 font-bold leading-relaxed border-t border-[#2a4a73] pt-4">
                        <span class="text-amber-400">Importante:</span> El tarjetón solo será válido después de
                        ser
                        validado por el Departamento de Comunicación y Difusión. Preséntese con una
                        identificación
                        oficial vigente.
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
