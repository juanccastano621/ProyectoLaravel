<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale
} from 'chart.js'
import { Bar } from 'vue-chartjs'

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale
)

const props = defineProps({
    stats: Object,
    recentReservations: Array,
    chartData: Array
})

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false
        }
    }
}

const chartValues = {
    labels: props.chartData.map(item => item.month),
    datasets: [
        {
            label: 'Reservas',
            data: props.chartData.map(item => item.total),
            borderRadius: 8
        }
    ]
}
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout title="Dashboard">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-bold text-3xl text-gray-800">
                    📊 Dashboard Administrativo
                </h2>

                <span class="text-sm text-gray-500">
                    {{ new Date().toLocaleDateString('es-CO') }}
                </span>
            </div>
        </template>

        <div class="py-10 bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

                    <div class="bg-white rounded-3xl shadow-sm p-6 border border-yellow-100">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-500 uppercase font-semibold">
                                    Pendientes
                                </p>

                                <h3 class="text-4xl font-extrabold text-yellow-500 mt-2">
                                    {{ stats.pending }}
                                </h3>
                            </div>

                            <div class="bg-yellow-100 p-4 rounded-2xl">
                                ⏳
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl shadow-sm p-6 border border-green-100">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-500 uppercase font-semibold">
                                    Confirmadas
                                </p>

                                <h3 class="text-4xl font-extrabold text-green-600 mt-2">
                                    {{ stats.approved }}
                                </h3>
                            </div>

                            <div class="bg-green-100 p-4 rounded-2xl">
                                ✅
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl shadow-sm p-6 border border-red-100">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-500 uppercase font-semibold">
                                    Canceladas
                                </p>

                                <h3 class="text-4xl font-extrabold text-red-500 mt-2">
                                    {{ stats.cancelled }}
                                </h3>
                            </div>

                            <div class="bg-red-100 p-4 rounded-2xl">
                                ❌
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl shadow-sm p-6 border border-blue-100">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-500 uppercase font-semibold">
                                    Espacios
                                </p>

                                <h3 class="text-4xl font-extrabold text-blue-600 mt-2">
                                    {{ stats.total_spaces }}
                                </h3>
                            </div>

                            <div class="bg-blue-100 p-4 rounded-2xl">
                                🏢
                            </div>
                        </div>
                    </div>

                </div>

                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">

                    <div class="bg-white rounded-3xl shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">
                            📈 Reservas del Mes
                        </h3>

                        <p class="text-5xl font-extrabold text-indigo-600">
                            {{ stats.month_reservations }}
                        </p>
                    </div>

                    <div class="bg-white rounded-3xl shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">
                            🏆 Espacio Más Reservado
                        </h3>

                        <p class="text-xl font-bold text-indigo-600">
                            {{ stats.most_reserved_space }}
                        </p>
                    </div>

                    <div class="bg-white rounded-3xl shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">
                            📚 Histórico Total
                        </h3>

                        <p class="text-5xl font-extrabold text-indigo-600">
                            {{ stats.total_reservations }}
                        </p>
                    </div>

                </div>

                <div class="bg-white rounded-3xl shadow-sm p-8 mb-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-900">
                            📊 Reservas por Mes
                        </h3>
                    </div>

                    <div class="h-[400px]">
                        <Bar
                            :data="chartValues"
                            :options="chartOptions"
                        />
                    </div>
                </div>

                <div class="bg-white rounded-3xl shadow-sm overflow-hidden">

                    <div class="px-8 py-6 border-b flex justify-between items-center">
                        <h3 class="text-xl font-bold text-gray-900">
                            📨 Últimas Solicitudes
                        </h3>

                        <Link
                            :href="route('admin.reservations.index')"
                            class="text-indigo-600 font-semibold hover:text-indigo-800"
                        >
                            Ver todas →
                        </Link>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full">

                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="text-left px-8 py-4 text-sm text-gray-500">
                                        Solicitante
                                    </th>

                                    <th class="text-left px-8 py-4 text-sm text-gray-500">
                                        Espacio
                                    </th>

                                    <th class="text-left px-8 py-4 text-sm text-gray-500">
                                        Fecha
                                    </th>

                                    <th class="text-left px-8 py-4 text-sm text-gray-500">
                                        Estado
                                    </th>
                                </tr>
                            </thead>

                            <tbody>

                                <tr
                                    v-for="res in recentReservations"
                                    :key="res.id"
                                    class="border-b hover:bg-gray-50 transition"
                                >
                                    <td class="px-8 py-5">
                                        <div class="font-semibold text-gray-900">
                                            {{ res.user_name }}
                                        </div>

                                        <div class="text-sm text-gray-500">
                                            {{ res.user_email }}
                                        </div>
                                    </td>

                                    <td class="px-8 py-5">
                                        {{ res.space?.name }}
                                    </td>

                                    <td class="px-8 py-5">
                                        {{
                                            new Date(
                                                res.start_time
                                            ).toLocaleString('es-CO')
                                        }}
                                    </td>

                                    <td class="px-8 py-5">

                                        <span
                                            v-if="res.status === 'pendiente'"
                                            class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full text-xs font-bold"
                                        >
                                            PENDIENTE
                                        </span>

                                        <span
                                            v-else-if="res.status === 'confirmada'"
                                            class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-xs font-bold"
                                        >
                                            CONFIRMADA
                                        </span>

                                        <span
                                            v-else-if="res.status === 'rechazada'"
                                            class="bg-red-100 text-red-700 px-4 py-2 rounded-full text-xs font-bold"
                                        >
                                            RECHAZADA
                                        </span>

                                        <span
                                            v-else
                                            class="bg-gray-100 text-gray-700 px-4 py-2 rounded-full text-xs font-bold"
                                        >
                                            {{ res.status }}
                                        </span>

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