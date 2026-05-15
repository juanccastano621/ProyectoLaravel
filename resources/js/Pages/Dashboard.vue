<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    stats: Object,
    recentReservations: Array,
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                📊 Panel Principal
            </h2>
        </template>

        <div class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border-b-4 border-yellow-400 p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div class="ml-5">
                                <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">Pendientes</p>
                                <h3 class="text-3xl font-bold text-gray-900">{{ stats.pending }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border-b-4 border-green-500 p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div class="ml-5">
                                <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">Confirmadas</p>
                                <h3 class="text-3xl font-bold text-gray-900">{{ stats.approved }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border-b-4 border-blue-500 p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <div class="ml-5">
                                <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">Espacios</p>
                                <h3 class="text-3xl font-bold text-gray-900">{{ stats.total_spaces }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border-b-4 border-indigo-500 p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div class="ml-5">
                                <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">Histórico</p>
                                <h3 class="text-3xl font-bold text-gray-900">{{ stats.total_reservations }}</h3>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900">Últimas Solicitudes Recibidas</h3>
                        <Link :href="route('admin.reservations.index')" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                            Ver todas &rarr;
                        </Link>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Solicitante</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Espacio</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha / Hora</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                <tr v-for="res in recentReservations" :key="res.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ res.user_name }}</div>
                                        <div class="text-xs text-gray-500">{{ res.user_email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ res.space?.name || 'Espacio eliminado' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ new Date(res.start_time).toLocaleString('es-ES', { dateStyle: 'short', timeStyle: 'short' }) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span v-if="res.status === 'pendiente'" class="px-2 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800 uppercase">Pendiente</span>
                                        <span v-else-if="res.status === 'confirmada' || res.status === 'aprobada'" class="px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800 uppercase">Confirmada</span>
                                        <span v-else-if="res.status === 'rechazada'" class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 uppercase">Rechazada</span>
                                        <span v-else class="px-2 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-800 uppercase">{{ res.status }}</span>
                                    </td>
                                </tr>
                                <tr v-if="recentReservations.length === 0">
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500 text-sm">
                                        No hay reservas recientes en el sistema.
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