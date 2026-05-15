<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

const days = [
    { label: 'Lunes', value: 1 },
    { label: 'Martes', value: 2 },
    { label: 'Miércoles', value: 3 },
    { label: 'Jueves', value: 4 },
    { label: 'Viernes', value: 5 },
    { label: 'Sábado', value: 6 },
    { label: 'Domingo', value: 7 },
]

const form = useForm({
    name: '',
    description: '',
    type: 'Auditorio',
    capacity: '',
    price_per_hour: '',
    image: null,
    is_active: true,

    availabilities: days.map(day => ({
        day_of_week: day.value,
        enabled: day.value <= 5,
        start_time: '08:00',
        end_time: '18:00',
    }))
})

const submit = () => {
    form.post(route('admin.spaces.store'))
}
</script>

<template>
    <Head title="Crear Espacio" />

    <AppLayout title="Crear Espacio">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-bold text-2xl text-gray-800">
                    ✨ Nuevo Espacio
                </h2>

                <Link
                    :href="route('admin.spaces.index')"
                    class="text-gray-600 hover:text-indigo-600"
                >
                    ← Volver
                </Link>
            </div>
        </template>

        <div class="py-10">
            <div class="max-w-5xl mx-auto">

                <div class="bg-white rounded-3xl shadow-sm p-8">

                    <form
                        @submit.prevent="submit"
                        class="space-y-8"
                    >

                        <div class="grid md:grid-cols-2 gap-6">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nombre
                                </label>

                                <input
                                    v-model="form.name"
                                    type="text"
                                    class="w-full rounded-xl border-gray-300"
                                />

                                <p
                                    v-if="form.errors.name"
                                    class="text-red-500 text-sm mt-1"
                                >
                                    {{ form.errors.name }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tipo
                                </label>

                                <select
                                    v-model="form.type"
                                    class="w-full rounded-xl border-gray-300"
                                >
                                    <option>Auditorio</option>
                                    <option>Sala de Conferencias</option>
                                    <option>Estudio de Grabación</option>
                                    <option>Laboratorio</option>
                                </select>
                            </div>

                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Descripción
                            </label>

                            <textarea
                                v-model="form.description"
                                rows="4"
                                class="w-full rounded-xl border-gray-300"
                            />
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Capacidad
                                </label>

                                <input
                                    v-model="form.capacity"
                                    type="number"
                                    class="w-full rounded-xl border-gray-300"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Precio por Hora
                                </label>

                                <input
                                    v-model="form.price_per_hour"
                                    type="number"
                                    step="0.01"
                                    class="w-full rounded-xl border-gray-300"
                                />
                            </div>

                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Imagen
                            </label>

                            <input
                                type="file"
                                @change="form.image = $event.target.files[0]"
                                class="w-full"
                            />
                        </div>

                        <div class="border rounded-3xl p-6 bg-gray-50">
                            <h3 class="font-bold text-lg mb-4">
                                🕒 Horarios Semanales
                            </h3>

                            <div class="space-y-4">

                                <div
                                    v-for="(day, index) in days"
                                    :key="day.value"
                                    class="grid grid-cols-4 gap-4 items-center bg-white rounded-2xl p-4"
                                >

                                    <div class="font-medium">
                                        {{ day.label }}
                                    </div>

                                    <div>
                                        <input
                                            v-model="form.availabilities[index].enabled"
                                            type="checkbox"
                                        />
                                        Activo
                                    </div>

                                    <input
                                        v-model="form.availabilities[index].start_time"
                                        type="time"
                                        class="rounded-xl border-gray-300"
                                    />

                                    <input
                                        v-model="form.availabilities[index].end_time"
                                        type="time"
                                        class="rounded-xl border-gray-300"
                                    />

                                </div>

                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-2xl font-bold"
                            >
                                {{
                                    form.processing
                                        ? 'Guardando...'
                                        : 'Guardar Espacio'
                                }}
                            </button>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </AppLayout>
</template>