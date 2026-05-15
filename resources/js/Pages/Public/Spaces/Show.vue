<script setup>
import { ref, watch, computed } from 'vue'
import { Head, Link, router, useForm } from '@inertiajs/vue3'

const props = defineProps({
    space: Object,
    selectedDate: String,
    availableSlots: Array,
})

const pickedDate = ref(props.selectedDate)
const selectedSlot = ref(null)

const slots = computed(() => props.availableSlots ?? [])

const nextAvailableSlots = computed(() => {
    return slots.value.slice(0, 5)
})

watch(pickedDate, (newDate) => {
    router.get(
        route('spaces.show', props.space.slug),
        {
            date: newDate
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true
        }
    )

    selectedSlot.value = null
})

const form = useForm({
    space_id: props.space.id,
    start_time: '',
    end_time: '',
    user_name: '',
    user_email: '',
    notes: '',
})

const selectTime = (slot) => {
    selectedSlot.value = slot.datetime

    form.start_time = slot.datetime

    const [hours, minutes] = slot.end.split(':')

    const endDate = new Date(slot.datetime)

    endDate.setHours(hours)
    endDate.setMinutes(minutes)
    endDate.setSeconds(0)

    form.end_time =
        endDate.getFullYear() +
        '-' +
        String(endDate.getMonth() + 1).padStart(2, '0') +
        '-' +
        String(endDate.getDate()).padStart(2, '0') +
        ' ' +
        String(endDate.getHours()).padStart(2, '0') +
        ':' +
        String(endDate.getMinutes()).padStart(2, '0') +
        ':00'

    form.clearErrors()
}

const submitReservation = () => {
    form.post(route('reservations.store'), {
        preserveScroll: true,

        onSuccess: () => {
            selectedSlot.value = null
            form.reset()
        }
    })
}
</script>

<template>
    <Head :title="space.name" />

    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <Link
                    :href="route('spaces.index')"
                    class="flex items-center text-gray-600 hover:text-indigo-600 transition"
                >
                    ← Volver al catálogo
                </Link>
            </div>
        </div>
    </nav>

    <div class="bg-gray-50 min-h-screen py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div
                v-if="$page.props.flash?.success"
                class="mb-8 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-xl shadow"
            >
                <div class="flex items-center">
                    <span class="text-2xl mr-3">🎉</span>

                    <div>
                        <p class="font-bold">
                            ¡Solicitud enviada correctamente!
                        </p>

                        <p class="text-sm">
                            {{ $page.props.flash.success }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

                <div class="space-y-6">

                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="h-52 bg-indigo-600 flex items-center justify-center">
                            <span class="text-8xl text-white">
                                🏛️
                            </span>
                        </div>

                        <div class="p-6">
                            <span class="text-xs uppercase font-bold text-indigo-600">
                                Auditorio
                            </span>

                            <h1 class="text-2xl font-bold text-gray-900 mt-2">
                                {{ space.name }}
                            </h1>

                            <p class="text-gray-500 text-sm mt-4">
                                {{ space.description }}
                            </p>

                            <div class="mt-6 space-y-4">

                                <div class="flex items-center">
                                    <span class="text-xl mr-3">
                                        👥
                                    </span>

                                    <div>
                                        <p class="text-xs text-gray-500">
                                            Capacidad
                                        </p>

                                        <p class="font-semibold">
                                            {{ space.capacity }} personas
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <span class="text-xl mr-3">
                                        💰
                                    </span>

                                    <div>
                                        <p class="text-xs text-gray-500">
                                            Tarifa
                                        </p>

                                        <p class="font-semibold">
                                            ${{ space.price_per_hour }} / hora
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div
                        v-if="nextAvailableSlots.length"
                        class="bg-white rounded-2xl shadow border border-gray-100 p-6"
                    >
                        <h3 class="font-bold text-gray-900 mb-4">
                            ⚡ Próximos horarios disponibles
                        </h3>

                        <div class="space-y-3">
                            <div
                                v-for="(slot, index) in nextAvailableSlots"
                                :key="index"
                                class="flex justify-between items-center bg-indigo-50 rounded-xl px-4 py-3"
                            >
                                <span class="font-semibold text-indigo-700">
                                    {{ slot.start }}
                                </span>

                                <span class="text-gray-500">
                                    a
                                </span>

                                <span class="font-semibold text-indigo-700">
                                    {{ slot.end }}
                                </span>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">

                        <h2 class="text-2xl font-bold text-gray-900 mb-6">
                            🗓️ Verifica Disponibilidad
                        </h2>

                        <div class="mb-8">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Selecciona una fecha
                            </label>

                            <input
                                type="date"
                                v-model="pickedDate"
                                :min="new Date().toISOString().split('T')[0]"
                                class="block w-full max-w-sm rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            >
                        </div>

                        <div class="mb-8">
                            <h3 class="text-sm font-medium text-gray-700 mb-4">
                                Horarios disponibles
                            </h3>

                            <div
                                v-if="slots.length"
                                class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3"
                            >
                                <button
                                    v-for="(slot, index) in slots"
                                    :key="index"
                                    @click="selectTime(slot)"
                                    :class="[
                                        selectedSlot === slot.datetime
                                            ? 'bg-indigo-600 text-white border-indigo-600'
                                            : 'bg-white text-gray-700 border-gray-300 hover:border-indigo-500 hover:text-indigo-600',
                                        'border rounded-xl px-4 py-3 text-sm font-semibold transition'
                                    ]"
                                >
                                    {{ slot.start }} - {{ slot.end }}
                                </button>
                            </div>

                            <div
                                v-else
                                class="text-center p-8 bg-gray-50 rounded-xl border border-dashed"
                            >
                                <span class="text-4xl">
                                    📭
                                </span>

                                <p class="mt-3 text-gray-600 font-medium">
                                    No hay horarios disponibles
                                </p>
                            </div>
                        </div>

                        <div
                            v-if="selectedSlot"
                            class="border-t pt-8"
                        >
                            <h3 class="text-lg font-bold text-gray-900 mb-5">
                                Completa tu Solicitud
                            </h3>

                            <form
                                @submit.prevent="submitReservation"
                                class="space-y-5"
                            >
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        Nombre Completo
                                    </label>

                                    <input
                                        v-model="form.user_name"
                                        type="text"
                                        required
                                        class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm"
                                    >
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        Correo Electrónico
                                    </label>

                                    <input
                                        v-model="form.user_email"
                                        type="email"
                                        required
                                        class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm"
                                    >
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        Observaciones
                                    </label>

                                    <textarea
                                        v-model="form.notes"
                                        rows="3"
                                        class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm"
                                    />
                                </div>

                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition"
                                >
                                    {{
                                        form.processing
                                            ? 'Procesando...'
                                            : 'Solicitar Reserva'
                                    }}
                                </button>
                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</template>