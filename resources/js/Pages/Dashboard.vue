<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

// Por ahora usaremos datos de ejemplo. 
// Más adelante, pasaremos estos datos reales desde el controlador de Laravel.
const stats = [
    { name: 'Reservas Pendientes Hoy', value: '5', icon: 'clock', change: '+2', changeType: 'increase' },
    { name: 'Espacios Activos', value: '12', icon: 'office', change: null, changeType: null },
    { name: 'Confirmadas este Mes', value: '45', icon: 'calendar-check', change: '+15%', changeType: 'increase' },
    { name: 'Ingresos Estimados (Mes)', value: '$2,450', icon: 'cash', change: '-5%', changeType: 'decrease' },
];

// Datos de ejemplo para últimas reservas
const recentReservations = [
    { id: 1, space: 'Auditorio Principal', user: 'Juan Pérez', date: '15 May, 10:00 AM', status: 'confirmada' },
    { id: 2, space: 'Sala de Conferencias B', user: 'María Gómez', date: '15 May, 02:00 PM', status: 'pendiente' },
    { id: 3, space: 'Estudio de Grabación', user: 'Carlos Ruiz', date: '16 May, 09:00 AM', status: 'confirmada' },
    { id: 4, space: 'Auditorio Principal', user: 'Ana López', date: '16 May, 11:00 AM', status: 'rechazada' },
];
</script>

<template>
    <Head title="Panel de Control Principal" />

    <AppLayout title="Panel de Control">
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    Bienvenido de nuevo, {{ $page.props.auth.user.name }} 👋
                </h2>
                <p class="text-sm text-gray-500">Jueves, 14 de Mayo, 2026</p>
            </div>
        </template>

        <div class="py-10 bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">
                
                <section>
                    <h3 class="text-lg font-medium text-gray-900 mb-5">Estado Actual del Sistema</h3>
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                        <div v-for="item in stats" :key="item.name" class="relative bg-white pt-5 px-4 pb-12 sm:pt-6 sm:px-6 shadow rounded-lg overflow-hidden transition hover:shadow-md border border-gray-100">
                            <dt>
                                <div class="absolute bg-indigo-100 rounded-md p-3">
                                    <span v-if="item.icon === 'clock'" class="text-indigo-600 text-2xl">⏳</span>
                                    <span v-else-if="item.icon === 'office'" class="text-indigo-600 text-2xl">🏢</span>
                                    <span v-else-if="item.icon === 'calendar-check'" class="text-indigo-600 text-2xl">📅</span>
                                    <span v-else-if="item.icon === 'cash'" class="text-indigo-600 text-2xl">💰</span>
                                </div>
                                <p class="ml-16 text-sm font-medium text-gray-500 truncate">{{ item.name }}</p>
                            </dt>
                            <dd class="ml-16 pb-6 flex items-baseline sm:pb-7">
                                <p class="text-3xl font-semibold text-gray-900">{{ item.value }}</p>
                                <p v-if="item.change" :class="[item.changeType === 'increase' ? 'text-green-600' : 'text-red-600', 'ml-2 flex items-baseline text-sm font-semibold']">
                                    {{ item.change }}
                                </p>
                                <div class="absolute bottom-0 inset-x-0 bg-gray-50 px-4 py-4 sm:px-6">
                                    <div class="text-sm">
                                        <Link href="#" class="font-medium text-indigo-600 hover:text-indigo-500"> Ver detalles<span class="sr-only"> de {{ item.name }}</span></Link>
                                    </div>
                                </div>
                            </dd>
                        </div>
                    </div>
                </section>

                <div class="grid grid-cols-1 gap-10 lg:grid-cols-3">
                    
                    <div class="bg-white p-6 shadow rounded-lg border border-gray-100 lg:col-span-2">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-medium text-gray-900">Últimas Solicitudes de Reserva</h3>
                            <Link :href="route('admin.reservations.index')" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Ver todas</Link>
                        </div>
                        <div class="flow-root">
                            <ul role="list" class="-mb-8">
                                <li v-for="(res, resIdx) in recentReservations" :key="res.id">
                                    <div class="relative pb-8">
                                        <span v-if="resIdx !== recentReservations.length - 1" class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span :class="[res.status === 'confirmada' ? 'bg-green-500' : (res.status === 'pendiente' ? 'bg-yellow-500' : 'bg-red-500'), 'h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white']">
                                                    <span class="text-white text-xs">●</span>
                                                </span>
                                            </div>
                                            <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                <div>
                                                    <p class="text-sm text-gray-800">
                                                        <span class="font-medium text-gray-900">{{ res.user }}</span> solicitó <span class="font-medium text-indigo-700">{{ res.space }}</span>
                                                    </p>
                                                </div>
                                                <div class="text-right text-sm text-gray-500 whitespace-nowrap">
                                                    <time :datetime="res.date">{{ res.date }}</time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="bg-white p-6 shadow rounded-lg border border-gray-100 h-fit">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Acciones Rápidas</h3>
                        <div class="space-y-4">
                            <Link :href="route('admin.spaces.create')" class="w-full inline-flex justify-center items-center px-4 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                ✨ Crear Nuevo Espacio
                            </Link>
                            <Link :href="route('admin.reservations.index')" class="w-full inline-flex justify-center items-center px-4 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                📋 Revisar Reservas Pendientes
                            </Link>
                            <button class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                📊 Generar Reporte Mensual
                            </button>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </AppLayout>
</template>