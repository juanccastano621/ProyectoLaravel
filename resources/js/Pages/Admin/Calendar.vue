<script setup>
import { Head, router, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    spaces: Array,
    selectedSpace: Object,
    reservations: Array,
    blocks: Array,
    currentDate: String
})

const form = useForm({
    space_id: props.selectedSpace?.id ?? '',
    start_time: `${props.currentDate}T08:00`,
    end_time: `${props.currentDate}T09:00`,
    reason: ''
})

const changeSpace = (slug) => {
    router.get(route('admin.calendar'), {
        space: slug,
        date: props.currentDate
    })
}

const navigate = (days) => {
    const newDate = new Date(props.currentDate)

    newDate.setDate(newDate.getDate() + days)

    router.get(route('admin.calendar'), {
        space: props.selectedSpace?.slug,
        date: newDate.toISOString().split('T')[0]
    })
}

const createBlock = () => {
    form.space_id = props.selectedSpace?.id

    form.post(route('admin.calendar.block'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reason = ''
        }
    })
}

const removeBlock = (id) => {
    if (!confirm('¿Eliminar este bloqueo?')) return

    router.delete(route('admin.calendar.unblock', id), {
        preserveScroll: true
    })
}

const formatDate = (date) => {
    return new Date(date).toLocaleString('es-CO', {
        dateStyle: 'medium',
        timeStyle: 'short'
    })
}
</script>

<template>
    <AppLayout title="Calendario">
        <Head title="Calendario" />

        <template #header>
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="font-bold text-2xl text-gray-800">
                        📅 Calendario de Reservas
                    </h2>

                    <p class="text-sm text-gray-500">
                        Gestiona reservas y bloqueos del espacio
                    </p>
                </div>

                <div class="flex gap-2">
                    <button
                        @click="navigate(-7)"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-xl transition"
                    >
                        ← Semana anterior
                    </button>

                    <button
                        @click="navigate(7)"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-xl transition"
                    >
                        Semana siguiente →
                    </button>
                </div>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto px-6 space-y-8">

                <div
                    v-if="$page.props.flash.success"
                    class="bg-green-50 border border-green-200 text-green-700 rounded-2xl p-4"
                >
                    {{ $page.props.flash.success }}
                </div>

                <div
                    v-if="$page.props.errors.error"
                    class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4"
                >
                    {{ $page.props.errors.error }}
                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">

                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-bold text-xl text-gray-800">
                            🔒 Bloquear horario
                        </h3>

                        <div class="text-sm text-gray-500">
                            Fecha actual:
                            <span class="font-semibold">
                                {{ currentDate }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Espacio
                        </label>

                        <select
                            @change="changeSpace($event.target.value)"
                            class="w-full border-gray-300 rounded-xl shadow-sm"
                        >
                            <option
                                v-for="space in spaces"
                                :key="space.id"
                                :value="space.slug"
                                :selected="space.slug === selectedSpace?.slug"
                            >
                                {{ space.name }}
                            </option>
                        </select>
                    </div>

                    <div class="grid md:grid-cols-3 gap-5">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Inicio
                            </label>

                            <input
                                v-model="form.start_time"
                                type="datetime-local"
                                class="w-full border-gray-300 rounded-xl shadow-sm"
                            >

                            <p
                                v-if="form.errors.start_time"
                                class="text-red-500 text-sm mt-1"
                            >
                                {{ form.errors.start_time }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Fin
                            </label>

                            <input
                                v-model="form.end_time"
                                type="datetime-local"
                                class="w-full border-gray-300 rounded-xl shadow-sm"
                            >

                            <p
                                v-if="form.errors.end_time"
                                class="text-red-500 text-sm mt-1"
                            >
                                {{ form.errors.end_time }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Motivo
                            </label>

                            <input
                                v-model="form.reason"
                                type="text"
                                placeholder="Ej: mantenimiento"
                                class="w-full border-gray-300 rounded-xl shadow-sm"
                            >
                        </div>

                    </div>

                    <button
                        @click="createBlock"
                        :disabled="form.processing"
                        class="mt-6 bg-red-600 hover:bg-red-700 text-white font-bold px-6 py-3 rounded-2xl transition"
                    >
                        {{
                            form.processing
                                ? 'Bloqueando...'
                                : 'Bloquear horario'
                        }}
                    </button>
                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">

                    <h3 class="font-bold text-xl text-gray-800 mb-6">
                        📌 Eventos registrados
                    </h3>

                    <div
                        v-if="reservations.length === 0 && blocks.length === 0"
                        class="text-center py-12 text-gray-500"
                    >
                        No hay eventos registrados esta semana.
                    </div>

                    <div class="space-y-4">

                        <div
                            v-for="reservation in reservations"
                            :key="reservation.id"
                            class="border-l-4 border-green-500 bg-green-50 rounded-2xl p-5"
                        >
                            <div class="flex justify-between items-start">

                                <div>
                                    <p class="font-bold text-green-800">
                                        Reserva — {{ reservation.user_name }}
                                    </p>

                                    <p class="text-sm text-gray-700">
                                        {{ reservation.user_email }}
                                    </p>

                                    <p class="text-sm text-gray-600 mt-2">
                                        {{ formatDate(reservation.start_time) }}
                                    </p>

                                    <p class="text-sm text-gray-600">
                                        {{ formatDate(reservation.end_time) }}
                                    </p>
                                </div>

                                <span class="bg-green-200 text-green-800 px-3 py-1 rounded-full text-xs font-bold uppercase">
                                    {{ reservation.status }}
                                </span>

                            </div>
                        </div>

                        <div
                            v-for="block in blocks"
                            :key="block.id"
                            class="border-l-4 border-red-500 bg-red-50 rounded-2xl p-5"
                        >
                            <div class="flex justify-between items-start">

                                <div>
                                    <p class="font-bold text-red-700">
                                        🚫 Horario bloqueado
                                    </p>

                                    <p class="text-sm text-gray-700 mt-2">
                                        {{ formatDate(block.start_time) }}
                                    </p>

                                    <p class="text-sm text-gray-700">
                                        {{ formatDate(block.end_time) }}
                                    </p>

                                    <p class="text-sm text-gray-500 mt-2">
                                        {{ block.reason || 'Sin motivo' }}
                                    </p>
                                </div>

                                <button
                                    @click="removeBlock(block.id)"
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl transition"
                                >
                                    Eliminar
                                </button>

                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </AppLayout>
</template>