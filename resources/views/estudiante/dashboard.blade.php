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
            height: 640px;
            background: white;
            border-radius: 2.5rem;
            overflow: hidden;
            position: relative;
            display: flex;
            flex-direction: column;
            page-break-inside: avoid;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
            flex-shrink: 0;
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

        /* Estilo para asegurar que el QR encaje perfecto */
        .qr-container svg {
            width: 100%;
            height: 100%;
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

            /* MAGIA PARA QUE QUEPAN EN 1 SOLA HOJA */
            @page {
                margin: 0.5cm;
            }

            .print-container {
                display: flex !important;
                flex-direction: row !important;
                flex-wrap: nowrap !important;
                justify-content: center !important;
                align-items: center !important;
                gap: 20px !important;
                width: 100% !important;
            }

            /* FUERZA EL TAMAÑO EXACTO EN PAPEL */
            .tarjeton-card {
                width: 360px !important;
                height: 640px !important;
                border: 1px solid #eee !important;
                margin: 0 !important;
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
                            <p class="text-xs text-gray-400 font-bold mt-1 uppercase">Asegúrate de que el código QR sea
                                visible</p>
                        </div>
                        <button onclick="window.print()"
                            class="{{ $bgTheme }} text-white px-6 py-3 rounded-2xl font-black shadow-lg {{ $hoverTheme }} transition-all flex items-center gap-2">

                            <svg version="1.0" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 64 64"
                                enable-background="new 0 0 64 64" xml:space="preserve" fill="#000000">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <g>
                                        <path fill="#ffffff"
                                            d="M12,3c0-0.553,0.447-1,1-1h38c0.553,0,1,0.447,1,1v9H12V3z"></path>
                                        <path fill="#ffffff"
                                            d="M52,61c0,0.553-0.447,1-1,1H13c-0.553,0-1-0.447-1-1V37c0-0.553,0.447-1,1-1h38c0.553,0,1,0.447,1,1V61z">
                                        </path>
                                        <path fill="#000000"
                                            d="M62,48c0,1.104-0.896,2-2,2h-6V36c0-1.105-0.895-2-2-2H12c-1.105,0-2,0.895-2,2v14H4c-1.104,0-2-0.896-2-2 V16c0-1.104,0.896-2,2-2h56c1.104,0,2,0.896,2,2V48z">
                                        </path>
                                        <g>
                                            <path fill="#394240"
                                                d="M60,12h-6V2c0-1.105-0.895-2-2-2H12c-1.105,0-2,0.895-2,2v10H4c-2.211,0-4,1.789-4,4v32 c0,2.211,1.789,4,4,4h6v10c0,1.105,0.895,2,2,2h40c1.105,0,2-0.895,2-2V52h6c2.211,0,4-1.789,4-4V16C64,13.789,62.211,12,60,12z M12,3c0-0.553,0.447-1,1-1h38c0.553,0,1,0.447,1,1v9H12V3z M52,61c0,0.553-0.447,1-1,1H13c-0.553,0-1-0.447-1-1V37 c0-0.553,0.447-1,1-1h38c0.553,0,1,0.447,1,1V61z M62,48c0,1.104-0.896,2-2,2h-6V36c0-1.105-0.895-2-2-2H12c-1.105,0-2,0.895-2,2 v14H4c-1.104,0-2-0.896-2-2V16c0-1.104,0.896-2,2-2h56c1.104,0,2,0.896,2,2V48z">
                                            </path>
                                            <path fill="#394240"
                                                d="M19,44h12c0.553,0,1-0.447,1-1s-0.447-1-1-1H19c-0.553,0-1,0.447-1,1S18.447,44,19,44z">
                                            </path>
                                            <path fill="#394240"
                                                d="M45,48H19c-0.553,0-1,0.447-1,1s0.447,1,1,1h26c0.553,0,1-0.447,1-1S45.553,48,45,48z">
                                            </path>
                                            <path fill="#394240"
                                                d="M38,54H19c-0.553,0-1,0.447-1,1s0.447,1,1,1h19c0.553,0,1-0.447,1-1S38.553,54,38,54z">
                                            </path>
                                            <path fill="#394240"
                                                d="M55,18c-1.657,0-3,1.343-3,3s1.343,3,3,3s3-1.343,3-3S56.657,18,55,18z M55,22c-0.553,0-1-0.447-1-1 s0.447-1,1-1s1,0.447,1,1S55.553,22,55,22z">
                                            </path>
                                            <path fill="#394240"
                                                d="M45,18c-1.657,0-3,1.343-3,3s1.343,3,3,3s3-1.343,3-3S46.657,18,45,18z M45,22c-0.553,0-1-0.447-1-1 s0.447-1,1-1s1,0.447,1,1S45.553,22,45,22z">
                                            </path>
                                        </g>
                                        <g>
                                            <circle fill="#B4CCB9" cx="45" cy="21" r="1"></circle>
                                        </g>
                                        <circle fill="#F76D57" cx="55" cy="21" r="1"></circle>
                                    </g>
                                </g>
                            </svg>

                            <span>GENERAR PDF</span>
                        </button>
                    </div>

                    <div
                        class="print-container flex flex-nowrap overflow-x-auto pb-4 gap-6 bg-gray-50 p-6 rounded-[2rem] border-2 border-dashed border-gray-200 print:bg-white print:border-none print:p-0">

                        <div class="tarjeton-card border-2 border-gray-300 shrink-0">
                            <div class="h-[130px] shrink-0 bg-[#999] relative border-b border-gray-400">

                                <div
                                    class="absolute top-[30px] left-1/2 -translate-x-1/2 w-[90px] h-[90px] bg-white rounded-full border-2 border-gray-400 z-20">
                                </div>
                            </div>

                            <div class="p-5 flex-grow relative z-10 flex flex-col">
                                <div class="flex justify-between items-center mb-4 shrink-0">
                                    <img src="{{ asset('logo tecnm2.png') }}" class="h-8">
                                    <img src="{{ asset('logo.png') }}" class="h-10">
                                </div>

                                <div class="flex gap-4 mb-5">
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

                                    <div class="flex flex-col items-center gap-3 shrink-0">
                                        <div
                                            class="w-24 h-32 border-2 {{ $borderTheme }} bg-white overflow-hidden shadow-sm flex items-center justify-center shrink-0">
                                            @if (!empty($user->foto))
                                                <img src="{{ asset('storage/' . $user->foto) }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <div
                                                    class="text-[8px] font-bold text-gray-400 text-center uppercase rotate-90 leading-none">
                                                    FOTO Y SELLO<br>INSTITUCIONAL</div>
                                            @endif
                                        </div>
                                        <div
                                            class="w-24 h-24 bg-white border-2 {{ $borderTheme }} p-1.5 shadow-sm flex items-center justify-center shrink-0 qr-container">
                                            {!! $qrCode !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-y-2 border-t border-gray-200 pt-3 shrink-0 mb-auto">
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

                                <div class="h-[120px] w-full shrink-0"></div>

                                <div
                                    class="flex-grow flex flex-col justify-center border-t border-gray-200 pt-2 pb-2 text-center">

                                    <div class="mb-4 pb-3 border-b border-dashed border-gray-300">
                                        <p class="text-[10px] font-black text-red-600 uppercase leading-none mb-1">En
                                            caso de emergencia llamar a:</p>
                                        <p class="text-[14px] font-black text-gray-900 uppercase leading-tight">
                                            {{ $tarjeton->contacto_emergencia_nombre ?? 'NO REGISTRADO' }}</p>
                                        <p class="text-[14px] font-black text-[#1a3a63] mt-1">
                                            {{ $tarjeton->contacto_emergencia_telefono ?? 'NO REGISTRADO' }}</p>
                                    </div>

                                    <div class="space-y-1.5">
                                        <div>
                                            <p class="text-[13px] font-black text-gray-800 uppercase leading-none">
                                                EMERGENCIAS: <span class="font-bold text-gray-600">911</span></p>
                                        </div>
                                        <div>
                                            <p class="text-[12px] font-black text-gray-800 uppercase leading-none">
                                                BOMBEROS</p>
                                            <p class="text-[11px] font-bold text-gray-600 uppercase">Estación Central:
                                                54 92 197</p>
                                        </div>
                                        <div>
                                            <p class="text-[12px] font-black text-gray-800 uppercase leading-none">CRUZ
                                                ROJA</p>
                                            <p class="text-[11px] font-bold text-gray-600 uppercase">065, 51 6 44 55, 51
                                                6 40 03</p>
                                        </div>
                                        <div>
                                            <p class="text-[12px] font-black text-gray-800 uppercase leading-none">
                                                POLICÍA MUNICIPAL</p>
                                            <p class="text-[11px] font-bold text-gray-600 uppercase">51 4 45 25, 51 6
                                                04
                                                00</p>
                                        </div>
                                        <div>
                                            <p class="text-[12px] font-black text-gray-800 uppercase leading-none">
                                                DIRECCIÓN DE TRÁNSITO</p>
                                            <p class="text-[11px] font-bold text-gray-600 uppercase">57 25 800, 57 25
                                                801</p>
                                        </div>
                                        <div>
                                            <p class="text-[12px] font-black text-gray-800 uppercase leading-none">
                                                HOSPITAL DEL ISSSTE</p>
                                            <p class="text-[10px] font-bold text-gray-600 uppercase leading-tight">51 5
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
                                <p class="text-xs font-bold leading-tight uppercase text-amber-300">Validación: Lleve
                                    el tarjetón al Departamento de Comunicación y Difusión para su sellado oficial.</p>
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

                <svg viewBox="0 0 128 128" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img"
                    class="w-32 h-32 mx-auto mb-6 drop-shadow-md" preserveAspectRatio="xMidYMid meet" fill="#000000">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path
                            d="M20.85 97c-.41.14-17.5 5.37-17.5 5.37s-1.15-5.31-.1-10.71c1.11-5.72 2.56-9.12 2.56-9.12s1.58-3.85 6.47-7.01c4.11-2.66 13.53-5.48 15.7-6.86c1.44-.91 3.87-2.55 7.56-6.61s6.61-8.31 13.13-11.33s9.92-2.55 17-3.02c7.08-.47 14.73-.28 20.5 0s10.29 2.93 11.81 3.87c1.51.94 5.29 4.16 7.27 6.23c1.98 2.08 4.34 5.19 6.14 6.71c1.79 1.51 4.63 3.31 7.08 4.72c2.46 1.42 3.97 2.64 4.44 3.87c.47 1.23.46 1.29.46 1.29l-1.69 17.5L20.85 97z"
                            fill="#f92612"></path>
                        <path
                            d="M3.35 102.37s2.59-4.68 8.72-9.58c5.22-4.17 9.92-6.49 17.22-7.56c6.43-.94 19.32-.64 40.81-.54c21.49.09 36.78.12 38.74.03c1.96-.09 4.56-1.77 7.65-3.73c3.08-1.96 4.31.92 4.31.92l3.46 10.18s.13 11.74-.09 14.58c-.15 1.88-2.71 5.14-4.02 5.7s-24.95.93-31.95.93c-7.01 0-66.76-.18-72.78-.65c-2.8-.22-6.54-1.68-8.32-3.46c-3.28-3.27-3.75-6.82-3.75-6.82z"
                            fill="#d70617"></path>
                        <path
                            d="M7.32 82.52s7.99-1 8.56-.6c.71.5-2.43 5.91-3.26 7c-.41.54-7.45 3.94-7.45 3.94l.02-4.89l2.13-5.45z"
                            fill="#fffeff"></path>
                        <path
                            d="M2.91 94.06l3.1-1.62s.55-3.09.89-4.56c.55-2.43 1.9-5.56 1.9-5.56l-3.03.28s-.99 1.69-1.9 5.48c-.97 4.09-.96 5.98-.96 5.98z"
                            fill="#d5ccc2"></path>
                        <path
                            d="M32 76.59c.22 2.16 2.61 2.11 9.44 2.11c5.45 0 60.4.51 61.71-.07c.64-.28 1.52-1.47 2.61-3.05c1.64-2.36 2.4-3.48 2.25-4.36c-.29-1.72-9.22-12.99-14.81-16.33c-3.87-2.31-12.2-2.32-19.09-2.4c-6.24-.07-18.08-.22-23.74 3.41C41.94 61.31 31.71 73.76 32 76.59z"
                            fill="#546d81"></path>
                        <path
                            d="M39.41 74.42c.44.55 9.13.39 17.21.36c8.27-.03 16.05.37 16.31 0c.12-.17.53-17.69.19-18c-.25-.23-2.39-.07-5.11-.04c-4.54.05-11.15.76-15.31 3.09c-7.15 3.99-14.16 13.5-13.29 14.59z"
                            fill="#afe3fb"></path>
                        <path
                            d="M78.1 57.1c-.07.13-.2 17.82-.09 17.89c.58.36 22.73.08 22.95-.21s2.61-2.61 2.54-3.19s-7.54-9.81-12.67-12.94c-2.25-1.39-12.61-1.75-12.73-1.55z"
                            fill="#afe3fb"></path>
                        <path
                            d="M37.25 81.01c.65 1.72 4.1 2.47 6.32 2.27c3.28-.3 5.25-2.21 5.6-6.14c.41-4.51-1.65-6.58-3.08-6.85c-1.52-.29-8.9 7.56-8.9 7.56s-.48 1.73.06 3.16z"
                            fill="#af0f21"></path>
                        <path
                            d="M41.96 80.05c2.43-.26 4.17-2.5 5.01-4.29c.83-1.79 1.45-4.49-.42-5.31c-2.32-1.01-4.41.18-5.84 1.79c-1.26 1.42-3.7 4.65-3.52 5.6c.18.96 1.43 2.57 4.77 2.21z"
                            fill="#f92612"></path>
                        <path
                            d="M17.81 110.37c.09 5.52 4.49 13.46 14.07 13.52s14.55-7.28 14.25-14.8c-.31-7.64-6.25-13.04-14.8-12.8c-8 .24-13.64 6.44-13.52 14.08z"
                            fill="#4e433d"></path>
                        <path
                            d="M24.48 109.91c.05 2.91 2.41 7.1 7.56 7.13c5.15.03 7.64-3.62 7.48-7.58c-.17-4.03-3.46-7.03-7.78-6.97c-4.3.07-7.33 3.39-7.26 7.42z"
                            fill="#c8c8c8"></path>
                        <path
                            d="M88.53 110.83c.09 5.48 4.39 13.36 13.77 13.42s14.24-7.22 13.95-14.68c-.3-7.59-6.11-12.94-14.48-12.7c-7.83.23-13.36 6.38-13.24 13.96z"
                            fill="#4e433d"></path>
                        <path
                            d="M94.79 110.4c.05 2.91 2.41 7.1 7.56 7.13s7.64-3.62 7.48-7.58c-.17-4.03-3.46-7.03-7.78-6.97c-4.29.07-7.32 3.39-7.26 7.42z"
                            fill="#c8c8c8"></path>
                        <path
                            d="M121.26 76.17c-.29.41-5.31 4.55-5.31 5.19s5.72 8.91 5.72 8.91l1.34.81s.64-10.48.64-10.71c0-.21-2.39-4.2-2.39-4.2z"
                            fill="#fffeff"></path>
                        <path
                            d="M124.27 93.63s.47-7.53.41-11.6c-.06-4.43-1.32-7.68-1.32-7.68l-2.12 1.85s.72 3.92.72 6.49s-.29 7.58-.29 7.58l2.6 3.36z"
                            fill="#d5ccc2"></path>
                    </g>
                </svg>

                <h2 class="text-2xl md:text-3xl font-black {{ $textTheme }} uppercase mb-4 tracking-tight">Aún no
                    tienes un vehículo registrado</h2>
                <p class="text-gray-500 mb-10 font-bold text-sm md:text-base">Para generar tu tarjetón de acceso al
                    ITO, primero necesitas registrar los datos de tu vehículo (Marca, Modelo, Placas y Color).</p>
                <a href="{{ route('tarjeton.create') }}"
                    class="{{ $bgTheme }} {{ $hoverTheme }} text-white px-8 py-4 rounded-xl font-black text-lg shadow-lg inline-block transition-all uppercase tracking-widest w-full md:w-auto">Registrar
                    Mi Vehículo</a>
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
