<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    reservations: Array,
    filters: Object
});

const currentFilter = ref(props.filters?.status || 'todos');

watch(currentFilter, (value) => {
    router.get(route('admin.reservations.index'), { status: value }, {
        preserveState: true,
        replace: true
    });
});

// Esta función llama a las rutas que ya tienes configuradas (accept, reject, cancel)
const updateStatus = (id, action) => {
    const messages = {
        accept: '¿Confirmar esta solicitud? Se enviará un correo automático al cliente.',
        reject: '¿Rechazar esta solicitud? Se notificará al cliente por correo.',
        cancel: '¿Cancelar esta reserva? La acción se reflejará inmediatamente.'
    };

    if (confirm(messages[action])) {
        router.post(route(`admin.reservations.${action}`, id), {}, {
            preserveScroll: true
        });
    }
};
</script>

<template>
    <Head title="Gestión de Reservas" />

    <AppLayout title="Gestión de Reservas">
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <div>
                    <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                        📅 Solicitudes y Reservas
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Revisa y gestiona las solicitudes de auditorios de tus usuarios.</p>
                </div>

                <div class="flex items-center space-x-2">
                    <label for="statusFilter" class="text-sm font-medium text-gray-700">Filtrar por:</label>
                    <select id="statusFilter" v-model="currentFilter" class="block w-48 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm">
                        <option value="todos">Todos los estados</option>
                        <option value="pendiente">⏳ Pendientes</option>
                        <option value="confirmada">✅ Confirmadas</option>
                        <option value="rechazada">❌ Rechazadas</option>
                        <option value="cancelada">🚫 Canceladas</option>
                    </select>
                </div>
            </div>
        </template>

        <div class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <div v-if="$page.props.flash?.success" class="mb-6 flex items-center p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm">
                    <span class="text-xl mr-3">✅</span>
                    <p class="text-sm font-medium">{{ $page.props.flash.success }}</p>
                </div>
                <div v-if="$page.props.flash?.error" class="mb-6 flex items-center p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg shadow-sm">
                    <span class="text-xl mr-3">⚠️</span>
                    <p class="text-sm font-medium">{{ $page.props.flash.error }}</p>
                </div>

                <div class="bg-white shadow-xl rounded-xl border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-1/4">Solicitante</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-1/4">Espacio</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-1/5">Horario Solicitado</th>
                                    <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-1/6">Estado</th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider w-1/6">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                <tr v-for="reservation in reservations" :key="reservation.id" class="hover:bg-gray-50 transition-colors duration-150">
                                    
                                    <td class="px-6 py-4">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold uppercase mt-1">
                                                {{ reservation.user_name ? reservation.user_name.charAt(0) : 'U' }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900">{{ reservation.user_name || 'Desconocido' }}</div>
                                                <div class="text-xs text-gray-500 mb-2">{{ reservation.user_email || 'Sin correo' }}</div>
                                                
                                                <div v-if="reservation.notes" class="text-xs text-indigo-700 bg-indigo-50 p-2 rounded-md border border-indigo-100 whitespace-normal">
                                                    <strong>📝 Notas:</strong> {{ reservation.notes }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-indigo-700">{{ reservation.space?.name || 'Espacio Eliminado' }}</div>
                                        <div class="text-xs text-gray-500">ID Reserva: #{{ reservation.id }}</div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            <span class="font-medium text-gray-500">Inicio:</span> 
                                            {{ new Date(reservation.start_time).toLocaleString('es-ES', { dateStyle: 'short', timeStyle: 'short' }) }}
                                        </div>
                                        <div class="text-sm text-gray-900 mt-1">
                                            <span class="font-medium text-gray-500">Fin:</span> 
                                            {{ new Date(reservation.end_time).toLocaleString('es-ES', { dateStyle: 'short', timeStyle: 'short' }) }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span v-if="reservation.status === 'pendiente'" class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200 uppercase tracking-wide">
                                            ⏳ Pendiente
                                        </span>
                                        <span v-else-if="reservation.status === 'confirmada' || reservation.status === 'aprobada'" class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-green-100 text-green-800 border border-green-200 uppercase tracking-wide">
                                            ✅ Confirmada
                                        </span>
                                        <span v-else-if="reservation.status === 'rechazada'" class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-red-100 text-red-800 border border-red-200 uppercase tracking-wide">
                                            ❌ Rechazada
                                        </span>
                                        <span v-else-if="reservation.status === 'cancelada'" class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-gray-100 text-gray-800 border border-gray-200 uppercase tracking-wide">
                                            🚫 Cancelada
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex flex-col items-end space-y-2">
                                            <template v-if="reservation.status === 'pendiente'">
                                                <button @click="updateStatus(reservation.id, 'accept')" class="w-full inline-flex justify-center items-center px-3 py-1.5 bg-green-50 text-green-700 hover:bg-green-600 hover:text-white border border-green-200 rounded-md transition font-semibold text-xs shadow-sm">
                                                    Confirmar
                                                </button>
                                                <button @click="updateStatus(reservation.id, 'reject')" class="w-full inline-flex justify-center items-center px-3 py-1.5 bg-red-50 text-red-700 hover:bg-red-600 hover:text-white border border-red-200 rounded-md transition font-semibold text-xs shadow-sm">
                                                    Rechazar
                                                </button>
                                            </template>
                                            
                                            <template v-else-if="reservation.status === 'confirmada' || reservation.status === 'aprobada'">
                                                <button @click="updateStatus(reservation.id, 'cancel')" class="w-full inline-flex justify-center items-center px-3 py-1.5 bg-gray-50 text-gray-700 hover:bg-gray-600 hover:text-white border border-gray-200 rounded-md transition font-semibold text-xs shadow-sm">
                                                    Cancelar
                                                </button>
                                            </template>
                                        </div>
                                    </td>
                                </tr>

                                <tr v-if="reservations.length === 0">
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="text-gray-300 text-5xl mb-4">📭</div>
                                        <div class="text-lg font-medium text-gray-900">No se encontraron reservas</div>
                                        <p class="text-gray-500">No hay registros que coincidan con los filtros actuales.</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>