<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - ITO</title>
    <link rel="icon" type="image/x-icon" href="/icon.ico">
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
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl font-black text-xs uppercase shadow-lg transition-all inline-block mt-4">
                    📋 Ver Visitas
                </a>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.escaner') }}"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-black text-xs uppercase shadow-lg transition-all">
                    📷 Abrir Escáner
                </a>
                <a href="{{ route('login') }}"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2.5 rounded-xl font-black text-xs uppercase shadow-sm transition-all">
                    Salir
                </a>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
            <div
                class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200 flex flex-col justify-center items-center text-center">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Registros</p>
                <p class="text-3xl font-black text-[#1a3a63]" x-text="countTotal"></p>
            </div>

            <div
                class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200 flex flex-col justify-center items-center text-center">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Estudiantes</p>
                <p class="text-3xl font-black text-indigo-600" x-text="countEstudiantes"></p>
            </div>

            <div
                class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200 flex flex-col justify-center items-center text-center">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Personal</p>
                <p class="text-3xl font-black text-indigo-600" x-text="countPersonal"></p>
            </div>

            <div
                class="bg-green-50 p-4 rounded-2xl shadow-sm border border-green-200 flex flex-col justify-center items-center text-center">
                <p class="text-[10px] font-black text-green-600 uppercase tracking-widest mb-1">Tarjetones Activos</p>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></span>
                    <p class="text-3xl font-black text-green-700" x-text="countActivos"></p>
                </div>
            </div>

            <div
                class="bg-amber-50 p-4 rounded-2xl shadow-sm border border-amber-200 flex flex-col justify-center items-center text-center">
                <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest mb-1">Pendientes Sello</p>
                <div class="flex items-center gap-2">
                    <span class="text-amber-500 text-xl font-black">!</span>
                    <p class="text-3xl font-black text-amber-700" x-text="countPendientes"></p>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div
                class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl font-bold text-sm text-center">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->has('password_admin'))
            <div
                class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl font-bold text-sm text-center">
                {{ $errors->first('password_admin') }}
            </div>
        @endif

        <div class="bg-white p-4 rounded-3xl shadow-sm border border-gray-200 mb-6 flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">🔍</span>
                <input type="text" x-model="search"
                    placeholder="Buscar por nombre, correo o número de control/empleado..."
                    class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none font-bold text-sm transition-all">
            </div>

            <div class="flex bg-gray-100 p-1 rounded-2xl overflow-x-auto">
                <button @click="filtroTipo = 'Todos'"
                    :class="filtroTipo === 'Todos' ? 'bg-white text-[#1a3a63] shadow-sm' : 'text-gray-500'"
                    class="px-4 py-2 text-xs font-black uppercase rounded-xl transition-all whitespace-nowrap">Todos</button>
                <button @click="filtroTipo = 'Estudiante'"
                    :class="filtroTipo === 'Estudiante' ? 'bg-[#1a3a63] text-white shadow-sm' : 'text-gray-500'"
                    class="px-4 py-2 text-xs font-black uppercase rounded-xl transition-all whitespace-nowrap">Estudiantes</button>
                <button @click="filtroTipo = 'Personal'"
                    :class="filtroTipo === 'Personal' ? 'bg-green-700 text-white shadow-sm' : 'text-gray-500'"
                    class="px-4 py-2 text-xs font-black uppercase rounded-xl transition-all whitespace-nowrap">Personal</button>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[600px]">
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
                                            class="w-10 h-10 rounded-full flex items-center justify-center font-black text-xs uppercase shrink-0"
                                            x-text="user.nombre_completo.charAt(0)">
                                        </div>
                                        <div>
                                            <p class="font-black text-gray-800 uppercase text-sm leading-tight mb-1"
                                                x-text="user.nombre_completo"></p>
                                            <span
                                                :class="user.tipo === 'Estudiante' ?
                                                    'text-indigo-600 bg-indigo-50 border-indigo-100' :
                                                    'text-green-600 bg-green-50 border-green-100'"
                                                class="text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-md border"
                                                x-text="user.tipo"></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-5">
                                    <p class="text-xs font-bold text-gray-600" x-text="user.correo_electronico"></p>
                                    <p class="text-[10px] text-gray-400 uppercase font-black tracking-tight mt-1 mb-3"
                                        x-text="user.adscripcion"></p>

                                    <div class="flex items-center">
                                        <template x-if="user.estatus_tarjeton === 1">
                                            <span
                                                class="inline-block bg-green-100 text-green-700 border border-green-200 text-[9px] font-black px-2 py-1 rounded-md uppercase tracking-widest shadow-sm">
                                                ✅ Tarjetón Activo
                                            </span>
                                        </template>

                                        <template x-if="user.estatus_tarjeton === 0">
                                            <span
                                                class="inline-block bg-amber-100 text-amber-700 border border-amber-200 text-[9px] font-black px-2 py-1 rounded-md uppercase tracking-widest shadow-sm">
                                                ⏳ Pendiente Sello
                                            </span>
                                        </template>

                                        <template x-if="user.estatus_tarjeton === null">
                                            <span
                                                class="inline-block bg-gray-100 text-gray-500 border border-gray-200 text-[9px] font-black px-2 py-1 rounded-md uppercase tracking-widest shadow-sm">
                                                🚗 Sin Registro
                                            </span>
                                        </template>
                                    </div>
                                </td>
                                <td class="p-5 text-center align-middle">
                                    <div class="flex flex-col gap-1.5 w-full max-w-[180px] mx-auto">
                                        <button @click="abrirDetalles(user)"
                                            class="bg-indigo-50 hover:bg-indigo-600 hover:text-white text-indigo-700 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase transition-all shadow-sm w-full">
                                            👁️ Ver Detalles
                                        </button>
                                        
                                        <template x-if="user.estatus_tarjeton !== null">
                                            <button @click="toggleEstatusUsuario(user)" :disabled="togglingEstatus"
                                                :class="user.estatus_tarjeton === 1 ? 'bg-amber-50 text-amber-700 hover:bg-amber-500 hover:text-white' : 'bg-green-50 text-green-700 hover:bg-green-600 hover:text-white'"
                                                class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase transition-all shadow-sm w-full">
                                                <span x-text="user.estatus_tarjeton === 1 ? '❌ Quitar Sello' : '✅ Sellar Tarjetón'"></span>
                                            </button>
                                        </template>

                                        <button @click="promptPassword(user.id, user.tipo)"
                                            class="bg-gray-50 hover:bg-gray-600 hover:text-white text-gray-500 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase transition-all shadow-sm w-full">
                                            🔑 Contraseña
                                        </button>
                                        <button @click="abrirModal(user)"
                                            class="bg-red-50 hover:bg-red-600 hover:text-white text-red-500 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase transition-all shadow-sm w-full">
                                            🗑️ Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div x-show="filteredUsers.length === 0" class="p-20 text-center bg-gray-50/50">
                <span class="text-4xl block mb-2">🔍</span>
                <p class="text-gray-400 font-black uppercase text-sm italic">No se encontraron usuarios con esos filtros
                </p>
            </div>
        </div>
    </div>

    <div x-show="showModal" style="display: none;"
        class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 backdrop-blur-sm" x-transition.opacity>
        <div class="bg-white p-8 rounded-[2rem] shadow-2xl w-full max-w-md border border-gray-100 mx-4"
            @click.away="cerrarModal()">
            <h3 class="text-xl font-black text-red-600 uppercase mb-2 text-center">⚠️ Confirmar Eliminación</h3>
            <p class="text-sm text-gray-500 mb-6 font-bold text-center">Estás a punto de eliminar a <span
                    x-text="deleteData.nombre" class="text-gray-800 font-black"></span>. Esta acción borrará todo su
                registro.</p>

            <form action="{{ route('admin.usuarios.eliminar') }}" method="POST">
                @csrf
                @method('DELETE')
                <input type="hidden" name="id" :value="deleteData.id">
                <input type="hidden" name="tipo" :value="deleteData.tipo">

                <div class="mb-6">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Tu Contraseña
                        de Administrador</label>
                    <input type="password" name="password_admin" required placeholder="Verifica tu identidad"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-red-500 text-gray-800 font-bold text-center">
                </div>

                <div class="flex gap-3">
                    <button type="button" @click="cerrarModal()"
                        class="w-1/2 py-3 text-gray-500 font-bold bg-gray-100 rounded-xl hover:bg-gray-200 uppercase text-xs tracking-widest transition-all">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="w-1/2 py-3 bg-red-600 text-white font-black rounded-xl hover:bg-red-700 uppercase text-xs tracking-widest shadow-lg shadow-red-200 transition-all">
                        Eliminar
                    </button>
                </div>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL DE DETALLES -->
    <div x-show="showDetailsModal" style="display: none;"
        class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 backdrop-blur-sm" x-transition.opacity>
        <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg border border-gray-100 mx-4 overflow-hidden"
            @click.away="cerrarDetalles()">
            
            <div class="p-6 text-center text-white shrink-0 relative"
                :class="selectedUser && selectedUser.estatus_tarjeton === 1 ? 'bg-green-600' : (selectedUser && selectedUser.estatus_tarjeton === 0 ? 'bg-amber-500' : 'bg-gray-600')">
                <button type="button" @click="cerrarDetalles()" class="absolute top-4 right-4 text-white hover:text-gray-200 font-bold text-lg">✕</button>
                <h3 class="text-xl font-black uppercase tracking-tight">Expediente de Usuario</h3>
                <p class="text-xs opacity-90 mt-1 uppercase font-bold" x-text="selectedUser ? selectedUser.tipo : ''"></p>
            </div>

            <div class="p-6 max-h-[60vh] overflow-y-auto space-y-6">
                <!-- Información General -->
                <div class="flex items-center gap-4 border-b border-gray-100 pb-5" x-show="selectedUser">
                    <div class="w-20 h-24 bg-gray-100 rounded-2xl overflow-hidden border-2 border-gray-300 flex items-center justify-center shadow-inner shrink-0">
                        <template x-if="selectedUser && selectedUser.foto">
                            <img :src="'/storage/' + selectedUser.foto" class="w-full h-full object-cover">
                        </template>
                        <template x-if="selectedUser && !selectedUser.foto">
                            <span class="text-[10px] text-gray-400 font-bold uppercase text-center">Sin Foto</span>
                        </template>
                    </div>
                    <div class="flex-grow" x-show="selectedUser">
                        <h4 class="font-black text-lg text-gray-800 uppercase leading-snug" x-text="selectedUser ? selectedUser.nombre_completo : ''"></h4>
                        <p class="text-xs font-bold text-gray-500 uppercase mt-1" x-text="selectedUser ? selectedUser.adscripcion : ''"></p>
                        <p class="text-sm font-black text-indigo-600 mt-2" x-text="selectedUser ? 'ID: ' + selectedUser.numero_id : ''"></p>
                        <p class="text-xs text-gray-400 font-medium" x-text="selectedUser ? selectedUser.correo_electronico : ''"></p>
                    </div>
                </div>

                <!-- Vehículo y Tarjetón -->
                <div x-show="selectedUser">
                    <h5 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Información del Tarjetón</h5>
                    
                    <template x-if="selectedUser && selectedUser.estatus_tarjeton !== null">
                        <div class="space-y-4">
                            <div class="bg-gray-50 p-4 rounded-2xl grid grid-cols-2 gap-3 border border-gray-100">
                                <div>
                                    <p class="text-[9px] font-bold text-gray-500 uppercase">Folio</p>
                                    <p class="font-black text-gray-800 text-sm uppercase" x-text="selectedUser.folio"></p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-gray-500 uppercase">Placas</p>
                                    <p class="font-black text-indigo-700 text-sm uppercase" x-text="selectedUser.placas"></p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-gray-500 uppercase">Marca / Modelo</p>
                                    <p class="font-bold text-gray-800 text-xs uppercase" x-text="selectedUser.marca + ' ' + selectedUser.modelo"></p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-gray-500 uppercase">Color</p>
                                    <p class="font-bold text-gray-800 text-xs uppercase" x-text="selectedUser.color"></p>
                                </div>
                                <div class="col-span-2 border-t border-gray-200 pt-2 mt-1">
                                    <p class="text-[9px] font-bold text-gray-500 uppercase">Vigencia del sello</p>
                                    <p class="font-black text-xs" :class="selectedUser.estatus_tarjeton === 1 ? 'text-green-600' : 'text-amber-500'" x-text="selectedUser.vigencia ? selectedUser.vigencia : 'Pendiente de activar'"></p>
                                </div>
                            </div>

                            <!-- Contacto Emergencia -->
                            <div class="bg-red-50/50 p-4 rounded-2xl border border-red-100">
                                <p class="text-[9px] font-black text-red-600 uppercase tracking-widest mb-2 leading-none">Contacto de Emergencia</p>
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div>
                                        <p class="text-[9px] text-gray-400 font-bold uppercase">Nombre</p>
                                        <p class="font-bold text-gray-800 uppercase" x-text="selectedUser.contacto_emergencia_nombre || 'No Registrado'"></p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] text-gray-400 font-bold uppercase">Teléfono</p>
                                        <p class="font-black text-indigo-905" x-text="selectedUser.contacto_emergencia_telefono || 'No Registrado'"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template x-if="selectedUser && selectedUser.estatus_tarjeton === null">
                        <div class="text-center p-8 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                            <span class="text-3xl block mb-2">🚗</span>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-tight">Este usuario no ha registrado ningún vehículo todavía.</p>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="p-6 bg-gray-50 border-t border-gray-100 flex gap-3">
                <button type="button" @click="cerrarDetalles()"
                    class="flex-1 py-3 text-gray-500 font-bold bg-white rounded-xl hover:bg-gray-100 uppercase text-xs tracking-widest transition-all border border-gray-200">
                    Cerrar
                </button>
                <template x-if="selectedUser && selectedUser.estatus_tarjeton !== null">
                    <button type="button" @click="toggleEstatusUsuario(selectedUser)" :disabled="togglingEstatus"
                        :class="selectedUser.estatus_tarjeton === 1 ? 'bg-amber-500 hover:bg-amber-600 text-white' : 'bg-green-600 hover:bg-green-700 text-white'"
                        class="flex-1 py-3 font-black rounded-xl uppercase text-xs tracking-widest transition-all shadow-lg flex justify-center items-center">
                        <span x-text="togglingEstatus ? 'Procesando...' : (selectedUser.estatus_tarjeton === 1 ? '❌ Quitar Sello' : '✅ Validar Sello')"></span>
                    </button>
                </template>
            </div>
        </div>
    </div>

    <script>
        function gestionUsuarios() {
            return {
                search: '',
                filtroTipo: 'Todos',
                users: @json($usuarios),

                // NUEVAS VARIABLES PARA EL MODAL DE ELIMINACIÓN
                showModal: false,
                deleteData: {
                    id: '',
                    tipo: '',
                    nombre: ''
                },

                // VARIABLES DE EXPEDIENTE
                selectedUser: null,
                showDetailsModal: false,
                togglingEstatus: false,

                get countTotal() {
                    return this.users.length;
                },
                get countEstudiantes() {
                    return this.users.filter(u => u.tipo === 'Estudiante').length;
                },
                get countPersonal() {
                    return this.users.filter(u => u.tipo === 'Personal').length;
                },
                get countActivos() {
                    return this.users.filter(u => u.estatus_tarjeton === 1).length;
                },
                get countPendientes() {
                    return this.users.filter(u => u.estatus_tarjeton === 0).length;
                },

                get filteredUsers() {
                    return this.users.filter(u => {
                        const searchLower = this.search.toLowerCase();
                        const matchesSearch = u.nombre_completo.toLowerCase().includes(searchLower) ||
                            u.correo_electronico.toLowerCase().includes(searchLower) ||
                            u.adscripcion.toLowerCase().includes(searchLower) ||
                            (u.numero_id && u.numero_id.toLowerCase().includes(searchLower)) ||
                            (u.placas && u.placas.toLowerCase().includes(searchLower));

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
                },

                abrirModal(user) {
                    this.deleteData.id = user.id;
                    this.deleteData.tipo = user.tipo.toLowerCase(); // Convertimos a minúscula para backend
                    this.deleteData.nombre = user.nombre_completo;
                    this.showModal = true;
                },

                cerrarModal() {
                    this.showModal = false;
                },

                abrirDetalles(user) {
                    this.selectedUser = user;
                    this.showDetailsModal = true;
                },

                cerrarDetalles() {
                    this.selectedUser = null;
                    this.showDetailsModal = false;
                },

                async toggleEstatusUsuario(user) {
                    if (!user.folio) return;
                    this.togglingEstatus = true;
                    try {
                        let res = await fetch('/admin/escaner/toggle', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                folio: user.folio
                            })
                        });
                        let data = await res.json();
                        if (data.success) {
                            // Actualizar localmente en el array reactivo
                            user.estatus_tarjeton = data.estado;
                            user.vigencia = data.vigencia;
                        } else {
                            alert(data.message);
                        }
                    } catch (e) {
                        console.error("Error al activar/desactivar:", e);
                        alert("Error de conexión al cambiar el estatus.");
                    } finally {
                        this.togglingEstatus = false;
                    }
                }
            }
        }
    </script>
</body>

</html>
