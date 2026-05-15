<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'

const props = defineProps({
    reservations: Array,
    filters: Object,
    spaces: Array
})

const currentFilter = ref(props.filters?.status || 'todos')
const currentSpace = ref(props.filters?.space || 'todos')
const currentDate = ref(props.filters?.date || '')
const currentSearch = ref(props.filters?.search || '')

const applyFilters = () => {
    router.get(
        route('admin.reservations.index'),
        {
            status: currentFilter.value,
            space: currentSpace.value,
            date: currentDate.value,
            search: currentSearch.value
        },
        {
            preserveState: true,
            replace: true,
            preserveScroll: true
        }
    )
}

watch(currentFilter, applyFilters)
watch(currentSpace, applyFilters)
watch(currentDate, applyFilters)

let timeout = null

watch(currentSearch, () => {
    clearTimeout(timeout)

    timeout = setTimeout(() => {
        applyFilters()
    }, 400)
})

const clearFilters = () => {
    currentFilter.value = 'todos'
    currentSpace.value = 'todos'
    currentDate.value = ''
    currentSearch.value = ''

    applyFilters()
}

const updateStatus = (id, action) => {
    const messages = {
        accept: '¿Confirmar esta solicitud?',
        reject: '¿Rechazar esta solicitud?',
        cancel: '¿Cancelar esta reserva?'
    }

    if (!confirm(messages[action])) {
        return
    }

    router.post(
        `/admin/reservas/${id}/${action}`,
        {},
        {
            preserveScroll: true,
            preserveState: true
        }
    )
}
</script>

<template>
    <Head title="Gestión de Reservas" />

    <AppLayout title="Gestión de Reservas">
        <template #header>
            <div class="flex flex-col gap-4">
                <div>
                    <h2 class="font-bold text-2xl text-gray-800">
                        📅 Solicitudes y Reservas
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Revisa y administra las reservas del sistema.
                    </p>
                </div>

                <div class="bg-white rounded-2xl shadow border border-gray-100 p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Buscar usuario
                            </label>

                            <input
                                v-model="currentSearch"
                                type="text"
                                placeholder="Nombre o correo..."
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Estado
                            </label>

                            <select
                                v-model="currentFilter"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="todos">Todos</option>
                                <option value="pendiente">⏳ Pendientes</option>
                                <option value="confirmada">✅ Confirmadas</option>
                                <option value="rechazada">❌ Rechazadas</option>
                                <option value="cancelada">🚫 Canceladas</option>
                                <option value="finalizada">🏁 Finalizadas</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Auditorio
                            </label>

                            <select
                                v-model="currentSpace"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="todos">
                                    Todos los auditorios
                                </option>

                                <option
                                    v-for="space in spaces"
                                    :key="space.id"
                                    :value="space.slug"
                                >
                                    {{ space.name }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Fecha
                            </label>

                            <input
                                v-model="currentDate"
                                type="date"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            >
                        </div>

                        <div class="flex items-end">
                            <button
                                @click="clearFilters"
                                class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl py-2 transition"
                            >
                                Limpiar filtros
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </template>

        <div class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div
                    v-if="$page.props.flash?.success"
                    class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-xl"
                >
                    {{ $page.props.flash.success }}
                </div>

                <div
                    class="bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden"
                >
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">
                                        Solicitante
                                    </th>

                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">
                                        Auditorio
                                    </th>

                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">
                                        Horario
                                    </th>

                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">
                                        Estado
                                    </th>

                                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100">

                                <tr
                                    v-for="reservation in reservations"
                                    :key="reservation.id"
                                    class="hover:bg-gray-50 transition"
                                >
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900">
                                            {{ reservation.user_name }}
                                        </div>

                                        <div class="text-sm text-gray-500">
                                            {{ reservation.user_email }}
                                        </div>

                                        <div
                                            v-if="reservation.notes"
                                            class="mt-2 text-xs bg-indigo-50 text-indigo-700 border border-indigo-100 rounded-lg p-2"
                                        >
                                            📝 {{ reservation.notes }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-indigo-700">
                                            {{ reservation.space?.name }}
                                        </div>

                                        <div class="text-xs text-gray-500">
                                            Reserva #{{ reservation.id }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-sm">
                                        <div>
                                            <strong>Inicio:</strong>
                                            {{ new Date(reservation.start_time).toLocaleString('es-CO') }}
                                        </div>

                                        <div class="mt-1">
                                            <strong>Fin:</strong>
                                            {{ new Date(reservation.end_time).toLocaleString('es-CO') }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <span
                                            v-if="reservation.status === 'pendiente'"
                                            class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold"
                                        >
                                            ⏳ Pendiente
                                        </span>

                                        <span
                                            v-else-if="reservation.status === 'confirmada'"
                                            class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold"
                                        >
                                            ✅ Confirmada
                                        </span>

                                        <span
                                            v-else-if="reservation.status === 'rechazada'"
                                            class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold"
                                        >
                                            ❌ Rechazada
                                        </span>

                                        <span
                                            v-else-if="reservation.status === 'finalizada'"
                                            class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold"
                                        >
                                            🏁 Finalizada
                                        </span>

                                        <span
                                            v-else
                                            class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-bold"
                                        >
                                            🚫 Cancelada
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <div class="flex flex-col items-end gap-2">

                                            <template v-if="reservation.status === 'pendiente'">
                                                <button
                                                    @click="updateStatus(reservation.slug, 'accept')"
                                                    class="bg-green-100 hover:bg-green-600 hover:text-white text-green-700 px-3 py-2 rounded-lg text-xs font-semibold transition"
                                                >
                                                    Confirmar
                                                </button>

                                                <button
                                                    @click="updateStatus(reservation.slug, 'reject')"
                                                    class="bg-red-100 hover:bg-red-600 hover:text-white text-red-700 px-3 py-2 rounded-lg text-xs font-semibold transition"
                                                >
                                                    Rechazar
                                                </button>
                                            </template>

                                            <template v-else-if="reservation.status === 'confirmada'">
                                                <button
                                                    @click="updateStatus(reservation.slug, 'cancel')"
                                                    class="bg-gray-100 hover:bg-gray-600 hover:text-white text-gray-700 px-3 py-2 rounded-lg text-xs font-semibold transition"
                                                >
                                                    Cancelar
                                                </button>
                                            </template>

                                        </div>
                                    </td>
                                </tr>

                                <tr v-if="reservations.length === 0">
                                    <td colspan="5" class="text-center py-12 text-gray-500">
                                        No se encontraron reservas
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