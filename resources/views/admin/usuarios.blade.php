<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - ITO</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-50 font-sans p-4 md:p-8" x-data="gestionUsuarios()">
    <div class="max-w-6xl mx-auto">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-black text-[#1a3a63] uppercase leading-none">Control de Usuarios</h1>
                <p class="text-gray-400 font-bold text-sm mt-2 uppercase">Gestiona el acceso y credenciales de la
                    comunidad</p>

                <a href="{{ route('admin.visitas') }}"
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl font-black text-xs uppercase shadow-lg transition-all">
                    📋 Ver Visitas
                </a>

            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.escaner') }}"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-black text-xs uppercase shadow-lg transition-all">
                    📷 Abrir Escáner
                </a>
            </div>
        </div>

        <div class="bg-white p-4 rounded-3xl shadow-sm border border-gray-200 mb-6 flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">🔍</span>
                <input type="text" x-model="search"
                    placeholder="Buscar por nombre, correo o número de control/empleado..."
                    class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none font-bold text-sm transition-all">
            </div>

            <div class="flex bg-gray-100 p-1 rounded-2xl">
                <button @click="filtroTipo = 'Todos'"
                    :class="filtroTipo === 'Todos' ? 'bg-white text-[#1a3a63] shadow-sm' : 'text-gray-500'"
                    class="px-4 py-2 text-xs font-black uppercase rounded-xl transition-all">Todos</button>
                <button @click="filtroTipo = 'Estudiante'"
                    :class="filtroTipo === 'Estudiante' ? 'bg-[#1a3a63] text-white shadow-sm' : 'text-gray-500'"
                    class="px-4 py-2 text-xs font-black uppercase rounded-xl transition-all">Estudiantes</button>
                <button @click="filtroTipo = 'Personal'"
                    :class="filtroTipo === 'Personal' ? 'bg-green-700 text-white shadow-sm' : 'text-gray-500'"
                    class="px-4 py-2 text-xs font-black uppercase rounded-xl transition-all">Personal</button>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Usuario</th>
                            <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Información
                                de Acceso</th>
                            <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="user in filteredUsers" :key="user.id + user.tipo">
                            <tr class="border-b border-gray-50 hover:bg-indigo-50/30 transition-all group">
                                <td class="p-5">
                                    <div class="flex items-center gap-3">
                                        <div :class="user.tipo === 'Estudiante' ? 'bg-indigo-100 text-indigo-700' :
                                            'bg-green-100 text-green-700'"
                                            class="w-10 h-10 rounded-full flex items-center justify-center font-black text-xs uppercase"
                                            x-text="user.nombre_completo.charAt(0)">
                                        </div>
                                        <div>
                                            <p class="font-black text-gray-800 uppercase text-sm leading-none"
                                                x-text="user.nombre_completo"></p>
                                            <span
                                                :class="user.tipo === 'Estudiante' ? 'text-indigo-600' : 'text-green-600'"
                                                class="text-[9px] font-black uppercase tracking-tighter"
                                                x-text="user.tipo"></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-5">
                                    <p class="text-xs font-bold text-gray-600" x-text="user.correo_electronico"></p>
                                    <p class="text-[10px] text-gray-400 uppercase font-black tracking-tight mt-1"
                                        x-text="user.adscripcion"></p>
                                </td>
                                <td class="p-5 text-center">
                                    <button @click="promptPassword(user.id, user.tipo)"
                                        class="bg-gray-100 group-hover:bg-indigo-600 group-hover:text-white text-gray-500 px-4 py-2 rounded-xl text-[10px] font-black uppercase transition-all shadow-sm">
                                        🔑 Cambiar Contraseña
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div x-show="filteredUsers.length === 0" class="p-20 text-center bg-gray-50/50">
                <p class="text-gray-400 font-black uppercase text-sm italic">No se encontraron usuarios con esos filtros
                </p>
            </div>
        </div>
    </div>

    <script>
        function gestionUsuarios() {
            return {
                search: '',
                filtroTipo: 'Todos',
                // Pasamos los datos de PHP a Javascript una sola vez
                users: @json($usuarios),

                get filteredUsers() {
                    return this.users.filter(u => {
                        // Filtro de texto (Nombre, correo o adscripción)
                        const searchLower = this.search.toLowerCase();
                        const matchesSearch = u.nombre_completo.toLowerCase().includes(searchLower) ||
                            u.correo_electronico.toLowerCase().includes(searchLower) ||
                            u.adscripcion.toLowerCase().includes(searchLower);

                        // Filtro de botones (Estudiante / Personal)
                        const matchesTipo = this.filtroTipo === 'Todos' || u.tipo === this.filtroTipo;

                        return matchesSearch && matchesTipo;
                    });
                },

                promptPassword(id, tipo) {
                    const newPass = prompt(
                        `Estás cambiando la contraseña de un ${tipo}. \n\nIngresa la nueva contraseña (mínimo 8 caracteres):`
                    );
                    if (newPass && newPass.length >= 8) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = "{{ route('admin.user.password') }}";
                        form.innerHTML = `
                            @csrf
                            <input type="hidden" name="id" value="${id}">
                            <input type="hidden" name="tipo" value="${tipo}">
                            <input type="hidden" name="password" value="${newPass}">
                        `;
                        document.body.appendChild(form);
                        form.submit();
                    } else if (newPass) {
                        alert("⚠️ La contraseña es demasiado corta.");
                    }
                }
            }
        }
    </script>
</body>

</html>
