<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Tarjetón ITO - {{ $user->nombre_completo }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Arial:wght@400;700&display=swap');

        /* Dimensiones Proporcionales y Centradas */
        .tarjeton-card {
            width: 360px;
            /* Un poco más ancho para mejor balance */
            height: 640px;
            /* Largo aumentado */
            background: white;
            border-radius: 2.5rem;
            overflow: hidden;
            position: relative;
            display: flex;
            flex-direction: column;
            page-break-inside: avoid;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
            /* Centrado horizontal */
        }

        /* Rectángulo blanco de la percha mejorado */
        .percha-bg {
            position: absolute;
            right: 0;
            top: 25px;
            width: 48%;
            height: 90px;
            background: white;
            border-radius: 1.5rem 0 0 1.5rem;
            z-index: 10;
        }

        /* Código de barras más grande para legibilidad */
        .barcode-svg svg {
            width: 100%;
            height: 85px;
        }

        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            body {
                background: white !important;
                padding: 0 !important;
            }

            .print\:hidden {
                display: none !important;
            }

            /* FUERZA EL TAMAÑO EXACTO EN PAPEL */
            .tarjeton-card {
                width: 360px !important;
                height: 640px !important;
                border: 1px solid #eee !important;
                margin: 20px auto !important;
            }
        }
    </style>
</head>

<body class="bg-gray-100 font-sans antialiased p-4 md:p-10">

    @php
        $isEstudiante = isset($user->carrera);
        $bgTheme = $isEstudiante ? 'bg-[#1a3a63]' : 'bg-green-700';
        $hoverTheme = $isEstudiante ? 'hover:bg-[#0f2a4a]' : 'hover:bg-green-800';
        $textTheme = $isEstudiante ? 'text-[#1a3a63]' : 'text-green-700';
        $borderTheme = $isEstudiante ? 'border-[#1a3a63]' : 'border-green-700';
        $borderDarkTheme = $isEstudiante ? 'border-[#2a4a73]' : 'border-green-800';
        $textAccent = $isEstudiante ? 'text-indigo-800' : 'text-green-800';
    @endphp

    <div class="max-w-7xl mx-auto container-main">

        @if ($tarjeton)
            <div class="flex flex-col lg:flex-row gap-10">

                <div
                    class="flex-1 bg-white p-6 md:p-8 rounded-[2.5rem] shadow-sm border border-gray-200 print:shadow-none print:border-none print:p-0">
                    <div class="flex justify-between items-center mb-8 border-b pb-4 print:hidden">
                        <div>
                            <h2 class="text-xl font-black text-gray-800 uppercase italic leading-none">Vista de Impresión
                            </h2>
                            <p class="text-xs text-gray-400 font-bold mt-1 uppercase">Asegúrate de que el código de
                                barras sea visible</p>
                        </div>
                        <button onclick="window.print()"
                            class="{{ $bgTheme }} text-white px-6 py-3 rounded-2xl font-black shadow-lg {{ $hoverTheme }} transition-all flex items-center gap-2">
                            <span>🖨️</span> GENERAR PDF
                        </button>
                    </div>

                    <div
                        class="flex flex-wrap justify-center gap-6 bg-gray-50 p-6 rounded-[2rem] border-2 border-dashed border-gray-200 print:bg-white print:border-none print:p-0">

                        <div class="tarjeton-card border-2 border-gray-300 shrink-0">
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
                                        <p
                                            class="text-[11px] font-black {{ $textTheme }} uppercase tracking-widest mb-1">
                                            Nombre:</p>
                                        <p class="text-lg font-black text-gray-800 leading-tight mb-4 uppercase">
                                            {{ $user->nombre_completo }}</p>

                                        @if ($isEstudiante)
                                            <p
                                                class="text-[11px] font-black {{ $textTheme }} uppercase tracking-widest mb-1">
                                                Carrera:</p>
                                            <p class="text-sm font-bold text-gray-700 mb-4 uppercase leading-snug">
                                                {{ $user->carrera }}</p>
                                            <p
                                                class="text-[11px] font-black {{ $textTheme }} uppercase tracking-widest mb-1">
                                                No. de Control:</p>
                                            <p class="text-2xl font-black text-gray-900 uppercase tracking-tighter">
                                                {{ $user->numero_control }}</p>
                                        @else
                                            <p
                                                class="text-[11px] font-black {{ $textTheme }} uppercase tracking-widest mb-1">
                                                Departamento:</p>
                                            <p class="text-sm font-bold text-gray-700 mb-4 uppercase leading-snug">
                                                {{ $user->departamento_adscripcion }}</p>
                                            <p
                                                class="text-[11px] font-black {{ $textTheme }} uppercase tracking-widest mb-1">
                                                No. de empleado:</p>
                                            <p class="text-2xl font-black text-gray-900 uppercase tracking-tighter">
                                                {{ $user->numero_empleado }}</p>
                                        @endif
                                    </div>
                                    <div
                                        class="w-20 h-28 border-2 {{ $borderTheme }} bg-white overflow-hidden shadow-sm flex items-center justify-center shrink-0">
                                        @if (!empty($user->foto))
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
                                        <p class="text-[15px] font-black {{ $textTheme }} uppercase">Vehículo</p>
                                        <p class="text-xs font-bold uppercase">{{ $tarjeton->marca }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[15px] font-black {{ $textTheme }} uppercase">Color</p>
                                        <p class="text-xs font-bold uppercase">{{ $tarjeton->color }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[15px] font-black {{ $textTheme }} uppercase">Modelo</p>
                                        <p class="text-xs font-bold uppercase">{{ $tarjeton->modelo }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[15px] font-black {{ $textTheme }} uppercase">Placas</p>
                                        <p class="text-xs font-black {{ $textAccent }} uppercase">
                                            {{ $tarjeton->placas }}</p>
                                    </div>
                                </div>
                                <div
                                    class="{{ $bgTheme }} text-white text-center py-3 font-black text-xl tracking-[0.3em] uppercase mt-auto -mx-5 -mb-5 shrink-0">
                                    {{ $isEstudiante ? 'Estudiante' : 'Personal' }}
                                </div>
                            </div>
                        </div>

                        <div class="tarjeton-card border-2 border-gray-300 shrink-0">
                            <div class="watermark"></div>
                            <div class="h-[130px] shrink-0 bg-[#e5e5e5] relative border-b border-gray-300">
                                <div
                                    class="absolute top-[30px] left-1/2 -translate-x-1/2 w-[90px] h-[90px] bg-white rounded-full border-2 border-gray-400 z-20">
                                </div>
                            </div>
                            <div class="p-5 flex-grow relative z-10 flex flex-col bg-white">
                                <div class="h-2 shrink-0"></div>
                                <div class="flex justify-center mb-1 barcode-svg shrink-0">{!! $barcode !!}</div>
                                <p
                                    class="text-[9px] font-mono font-bold mb-3 text-gray-600 text-center uppercase shrink-0">
                                    {{ $tarjeton->folio }}</p>
                                <div
                                    class="flex-grow flex flex-col justify-center border-t border-gray-200 pt-2 pb-2 text-center">
                                    <div class="space-y-2.5">
                                        <div>
                                            <p class="text-[15px] font-black text-gray-800 uppercase leading-none">
                                                EMERGENCIAS</p>
                                            <p class="text-[15px] font-bold text-gray-600">911</p>
                                        </div>
                                        <div>
                                            <p class="text-[15px] font-black text-gray-800 uppercase leading-none">
                                                BOMBEROS</p>
                                            <p class="text-[15px] font-bold text-gray-600 uppercase">Estación Central:
                                                54
                                                92 197</p>
                                        </div>
                                        <div>
                                            <p class="text-[15px] font-black text-gray-800 uppercase leading-none">CRUZ
                                                ROJA</p>
                                            <p class="text-[15px] font-bold text-gray-600 uppercase">065, 51 6 44 55, 51
                                                6 40 03</p>
                                        </div>
                                        <div>
                                            <p class="text-[15px] font-black text-gray-800 uppercase leading-none">
                                                POLICÍA MUNICIPAL</p>
                                            <p class="text-[15px] font-bold text-gray-600 uppercase">51 4 45 25, 51 6 04
                                                00</p>
                                        </div>
                                        <div>
                                            <p class="text-[15px] font-black text-gray-800 uppercase leading-none">
                                                DIRECCIÓN DE TRÁNSITO</p>
                                            <p class="text-[15px] font-bold text-gray-600 uppercase">57 25 800, 57 25
                                                801
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-[15px] font-black text-gray-800 uppercase leading-none">
                                                HOSPITAL DEL ISSSTE</p>
                                            <p class="text-[15px] font-bold text-gray-600 uppercase leading-tight">51 5
                                                33 11, 51 5 35 00, 51 5 39 02</p>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="{{ $bgTheme }} text-white text-center py-3 font-black text-xl tracking-[0.3em] uppercase mt-auto -mx-5 -mb-5 shrink-0">
                                    {{ $isEstudiante ? 'Estudiante' : 'Personal' }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="w-full lg:w-96 space-y-6 print:hidden">
                    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-200">
                        <h3 class="text-xs font-black text-gray-400 uppercase mb-4 tracking-widest">Estatus del Trámite
                        </h3>
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
                                    <p class="text-amber-700 font-black text-lg leading-none uppercase">En Revisión</p>
                                    <p class="text-amber-600 text-[10px] font-bold mt-1 uppercase">Pendiente de sellado
                                    </p>
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

                    <div class="{{ $bgTheme }} p-8 rounded-[2.5rem] text-white shadow-2xl">
                        <h3 class="text-lg font-black mb-6 border-b {{ $borderDarkTheme }} pb-2 uppercase italic">
                            Validación de Tarjetón</h3>
                        <div class="space-y-6">
                            <div class="flex gap-4"><span
                                    class="bg-white {{ $textTheme }} w-7 h-7 rounded-full flex items-center justify-center font-black shrink-0 text-sm">1</span>
                                <p class="text-xs font-bold leading-tight">Recorte cuidadosamente siguiendo las líneas
                                    marcadas, incluyendo el orificio circular superior.</p>
                            </div>
                            <div class="flex gap-4"><span
                                    class="bg-white {{ $textTheme }} w-7 h-7 rounded-full flex items-center justify-center font-black shrink-0 text-sm">2</span>
                                <p class="text-xs font-bold leading-tight">Pegue ambas caras (Frontal y Trasera) de
                                    manera que queden perfectamente alineadas.</p>
                            </div>
                            <div class="flex gap-4"><span
                                    class="bg-white {{ $textTheme }} w-7 h-7 rounded-full flex items-center justify-center font-black shrink-0 text-sm">3</span>
                                <p class="text-xs font-bold leading-tight uppercase text-amber-300">Validación: Lleve el
                                    tarjetón al Departamento de Comunicación y Difusión para su sellado oficial.</p>
                            </div>
                            <div class="flex gap-4"><span
                                    class="bg-white {{ $textTheme }} w-7 h-7 rounded-full flex items-center justify-center font-black shrink-0 text-sm">4</span>
                                <p class="text-xs font-bold leading-tight">Enmique el tarjetón para asegurar su
                                    durabilidad contra el desgaste diario.</p>
                            </div>
                        </div>
                        <p
                            class="mt-8 text-[9px] text-gray-200 font-bold leading-relaxed border-t {{ $borderDarkTheme }} pt-4">
                            <span class="text-amber-400">Importante:</span> El tarjetón solo será válido después de ser
                            validado por el Departamento de Comunicación y Difusión. Preséntese con una identificación
                            oficial vigente.
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div
                class="bg-white rounded-[2.5rem] p-10 md:p-16 text-center shadow-lg border border-gray-200 max-w-2xl mx-auto mt-10">
                <div class="text-7xl mb-6">🚗</div>
                <h2 class="text-2xl md:text-3xl font-black {{ $textTheme }} uppercase mb-4 tracking-tight">Aún no
                    tienes un vehículo registrado</h2>
                <p class="text-gray-500 mb-10 font-bold text-sm md:text-base">Para generar tu tarjetón de acceso al
                    ITO, primero necesitas registrar los datos de tu vehículo (Marca, Modelo, Placas y Color).</p>

                <a href="{{ route('tarjeton.create') }}"
                    class="{{ $bgTheme }} {{ $hoverTheme }} text-white px-8 py-4 rounded-xl font-black text-lg shadow-lg inline-block transition-all uppercase tracking-widest w-full md:w-auto">
                    Registrar Mi Vehículo
                </a>

                <div class="mt-8 border-t border-gray-100 pt-6">
                    <a href="{{ route('login') }}"
                        class="text-gray-400 text-xs font-black hover:{{ $textTheme }} transition-all uppercase tracking-widest">←
                        Salir y Cerrar Sesión</a>
                </div>
            </div>
        @endif

    </div>

</body>

</html>
