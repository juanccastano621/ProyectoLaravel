<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';

defineProps({
    reservations: Array
});

// Función para manejar los 3 tipos de acciones usando las rutas que definimos en web.php
const updateStatus = (id, action) => {
    const messages = {
        accept: '¿Estás seguro de que deseas APROBAR esta solicitud?',
        reject: '¿Estás seguro de que deseas RECHAZAR esta solicitud?',
        cancel: '¿Estás seguro de que deseas CANCELAR esta reserva ya aprobada?'
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
            </div>
        </template>

        <div class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <div v-if="$page.props.flash?.success" class="mb-6 flex items-center p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm">
                    <span class="text-xl mr-3">✅</span>
                    <p class="text-sm font-medium">{{ $page.props.flash.success }}</p>
                </div>

                <div class="bg-white shadow-xl rounded-xl border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Solicitante</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Espacio</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Horario Solicitado</th>
                                    <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                <tr v-for="reservation in reservations" :key="reservation.id" class="hover:bg-gray-50 transition-colors duration-150">
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold">
                                                {{ reservation.user?.name?.charAt(0) || 'U' }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900">{{ reservation.user?.name || 'Usuario Desconocido' }}</div>
                                                <div class="text-xs text-gray-500">{{ reservation.user?.email || 'Sin correo' }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold uppercase">
                                                {{ reservation.user_name ? reservation.user_name.charAt(0) : 'U' }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900">{{ reservation.user_name }}</div>
                                                <div class="text-xs text-gray-500">{{ reservation.user_email }}</div>
                                                
                                                <div v-if="reservation.notes" class="mt-2 text-xs text-indigo-700 bg-indigo-50 p-2 rounded-md border border-indigo-100 whitespace-normal min-w-[200px]">
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
                                            <span class="font-medium">Inicio:</span> {{ new Date(reservation.start_time).toLocaleString('es-ES', { dateStyle: 'short', timeStyle: 'short' }) }}
                                        </div>
                                        <div class="text-sm text-gray-600 mt-1">
                                            <span class="font-medium">Fin:</span> {{ new Date(reservation.end_time).toLocaleString('es-ES', { dateStyle: 'short', timeStyle: 'short' }) }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span v-if="reservation.status === 'pendiente'" class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200 uppercase tracking-wide">
                                            ⏳ Pendiente
                                        </span>
                                        <span v-else-if="reservation.status === 'aprobada'" class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-green-100 text-green-800 border border-green-200 uppercase tracking-wide">
                                            ✅ Aprobada
                                        </span>
                                        <span v-else-if="reservation.status === 'rechazada'" class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-red-100 text-red-800 border border-red-200 uppercase tracking-wide">
                                            ❌ Rechazada
                                        </span>
                                        <span v-else-if="reservation.status === 'cancelada'" class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-gray-100 text-gray-800 border border-gray-200 uppercase tracking-wide">
                                            🚫 Cancelada
                                        </span>
                                        <span v-else class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-gray-100 text-gray-800">
                                            {{ reservation.status }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <template v-if="reservation.status === 'pendiente'">
                                                <button @click="updateStatus(reservation.id, 'accept')" class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-700 hover:bg-green-600 hover:text-white border border-green-200 rounded-md transition font-semibold text-xs" title="Aprobar">
                                                    Aprobar
                                                </button>
                                                <button @click="updateStatus(reservation.id, 'reject')" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 hover:bg-red-600 hover:text-white border border-red-200 rounded-md transition font-semibold text-xs" title="Rechazar">
                                                    Rechazar
                                                </button>
                                            </template>
                                            
                                            <template v-else-if="reservation.status === 'aprobada'">
                                                <button @click="updateStatus(reservation.id, 'cancel')" class="inline-flex items-center px-3 py-1.5 bg-gray-50 text-gray-700 hover:bg-gray-600 hover:text-white border border-gray-200 rounded-md transition font-semibold text-xs" title="Cancelar Reserva">
                                                    Cancelar
                                                </button>
                                            </template>

                                            </div>
                                    </td>
                                </tr>

                                <tr v-if="reservations.length === 0">
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="text-gray-300 text-5xl mb-4">📭</div>
                                        <div class="text-lg font-medium text-gray-900">No hay reservas registradas</div>
                                        <p class="text-gray-500">Cuando un usuario solicite un espacio, aparecerá aquí.</p>
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