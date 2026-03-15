<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auditoría de Visitas - ITO</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-50 font-sans p-4 md:p-8" x-data="{ modalOpen: false, modalImage: '' }">
    <div class="max-w-7xl mx-auto">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-black text-[#1a3a63] uppercase leading-none">Visitas Externas</h1>
                <p class="text-gray-400 font-bold text-sm mt-2 uppercase">Registro de accesos capturados en caseta</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.usuarios') }}"
                    class="bg-white border-2 border-gray-200 text-gray-600 hover:border-[#1a3a63] hover:text-[#1a3a63] px-5 py-2.5 rounded-xl font-black text-xs uppercase shadow-sm transition-all">
                    ← Volver a Usuarios
                </a>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Fecha y Hora
                            </th>
                            <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Conductor
                            </th>
                            <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Placa</th>
                            <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Motivo /
                                Notas</th>
                            <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">
                                Evidencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($visitas as $v)
                            <tr class="border-b border-gray-50 hover:bg-indigo-50/30 transition-all">
                                <td class="p-5">
                                    <p class="font-black text-gray-800 text-sm">
                                        {{ \Carbon\Carbon::parse($v->created_at)->format('d/m/Y') }}</p>
                                    <p class="text-[11px] text-gray-500 font-bold uppercase tracking-wider mt-1">
                                        {{ \Carbon\Carbon::parse($v->created_at)->format('h:i A') }}</p>
                                </td>
                                <td class="p-5 font-black text-[#1a3a63] uppercase text-sm">
                                    {{ $v->nombre_conductor }}
                                </td>
                                <td class="p-5">
                                    <span
                                        class="bg-gray-100 text-gray-800 px-3 py-1.5 rounded-lg font-black tracking-widest uppercase border border-gray-200 text-xs">
                                        {{ $v->placa }}
                                    </span>
                                </td>
                                <td class="p-5 max-w-xs">
                                    <p class="text-xs font-black text-indigo-600 uppercase">{{ $v->motivo }}</p>
                                    <p class="text-[10px] text-gray-500 font-bold mt-1 leading-tight truncate"
                                        title="{{ $v->nota }}">
                                        {{ $v->nota ?: 'Sin notas adicionales' }}
                                    </p>
                                </td>
                                <td class="p-5 text-center">
                                    @if ($v->foto)
                                        <button
                                            @click="modalImage = '{{ asset('storage/' . $v->foto) }}'; modalOpen = true"
                                            class="inline-block overflow-hidden rounded-xl border-2 border-gray-200 hover:border-[#1a3a63] shadow-sm transition-all transform hover:scale-105">
                                            <img src="{{ asset('storage/' . $v->foto) }}"
                                                class="w-12 h-12 object-cover">
                                        </button>
                                    @else
                                        <span
                                            class="text-[9px] text-gray-400 font-bold uppercase border border-gray-200 px-2 py-1 rounded-md">Sin
                                            Foto</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-20 text-center bg-gray-50/50">
                                    <span class="text-4xl block mb-2">📋</span>
                                    <p class="text-gray-400 font-black uppercase text-sm italic">Aún no hay visitas
                                        registradas por los guardias.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div x-show="modalOpen" style="display: none;"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/90 backdrop-blur-sm"
        x-transition.opacity>
        <div @click.away="modalOpen = false"
            class="relative max-w-3xl w-full bg-black rounded-[2rem] overflow-hidden shadow-2xl border-4 border-white/10">
            <button @click="modalOpen = false"
                class="absolute top-4 right-4 bg-red-500 hover:bg-red-600 text-white w-10 h-10 rounded-full font-black text-xl flex items-center justify-center z-10 transition-colors shadow-lg">
                ×
            </button>
            <img :src="modalImage" class="w-full h-auto max-h-[80vh] object-contain">
        </div>
    </div>

</body>

</html>
