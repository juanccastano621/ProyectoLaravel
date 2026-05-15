<script setup>
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    spaces: Array,
});

const showingModal = ref(false);
const isEditing = ref(false);
const currentSpaceSlug = ref(null);

const days = [
    { label: 'Lunes', value: 1 },
    { label: 'Martes', value: 2 },
    { label: 'Miércoles', value: 3 },
    { label: 'Jueves', value: 4 },
    { label: 'Viernes', value: 5 },
    { label: 'Sábado', value: 6 },
    { label: 'Domingo', value: 7 },
];

const defaultAvailabilities = () =>
    days.map(day => ({
        day_of_week: day.value,
        enabled: day.value <= 5,
        start_time: '08:00',
        end_time: '18:00',
    }));

const form = useForm({
    name: '',
    description: '',
    capacity: '',
    type: 'Auditorio',
    price_per_hour: '',
    is_active: true,
    image: null,
    availabilities: defaultAvailabilities(),
    _method: 'post',
});

const openModalCreate = () => {
    isEditing.value = false;

    form.reset();

    form.type = 'Auditorio';
    form.price_per_hour = '';
    form.is_active = true;
    form.availabilities = defaultAvailabilities();

    form.clearErrors();
    form._method = 'post';

    showingModal.value = true;
};

const openModalEdit = (space) => {
    isEditing.value = true;

    currentSpaceSlug.value = space.slug;

    form.name = space.name;
    form.description = space.description;
    form.capacity = space.capacity;
    form.type = space.type;
    form.price_per_hour = space.price_per_hour;
    form.is_active = space.is_active;
    form.image = null;

    form.availabilities = days.map(day => {
        const existing = space.availabilities?.find(
            a => a.day_of_week === day.value
        );

        return {
            day_of_week: day.value,
            enabled: !!existing,
            start_time: existing?.start_time?.substring(0, 5) ?? '08:00',
            end_time: existing?.end_time?.substring(0, 5) ?? '18:00',
        };
    });

    form._method = 'put';

    form.clearErrors();

    showingModal.value = true;
};

const closeModal = () => {
    showingModal.value = false;
    form.reset();
};

const submit = () => {

    console.log('DATOS ENVIADOS');
    console.log(form.data());

    if (isEditing.value) {

        form.post(route('admin.spaces.update', currentSpaceSlug.value), {
            preserveScroll: true,
            forceFormData: true,

            onStart: () => {
                console.log('INICIANDO REQUEST');
            },

            onSuccess: () => {
                console.log('SUCCESS');
                closeModal();
            },

            onError: (errors) => {
                console.log('ERROR');
                console.log(errors);
            },

            onFinish: () => {
                console.log('FINISH');
            }
        });

    } else {

        form.post(route('admin.spaces.store'), {
            preserveScroll: true,
            forceFormData: true,

            onStart: () => {
                console.log('INICIANDO REQUEST');
            },

            onSuccess: () => {
                console.log('SUCCESS');
                closeModal();
            },

            onError: (errors) => {
                console.log('ERROR');
                console.log(errors);
            },

            onFinish: () => {
                console.log('FINISH');
            }
        });
    }
};

const deleteSpace = (id) => {
    if (confirm('¿Seguro que deseas eliminar este espacio?')) {
        router.delete(route('admin.spaces.destroy', id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head title="Gestión de Espacios" />

    <AppLayout title="Espacios">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-bold text-2xl text-gray-800">
                    🏛️ Gestión de Espacios
                </h2>

                <button
                    @click="openModalCreate"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow"
                >
                    + Nuevo Espacio
                </button>
            </div>
        </template>

        <div class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl rounded-xl">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Imagen
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Nombre
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Tipo
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Capacidad
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Precio
                                    </th>

                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr
                                    v-for="space in spaces"
                                    :key="space.id"
                                >
                                    <td class="px-6 py-4">
                                        <img
                                            v-if="space.image_path"
                                            :src="`/storage/${space.image_path}`"
                                            class="h-14 w-20 rounded-lg object-cover"
                                        >

                                        <div
                                            v-else
                                            class="h-14 w-20 rounded-lg bg-gray-200 flex items-center justify-center text-xs text-gray-500"
                                        >
                                            Sin foto
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-gray-900">
                                            {{ space.name }}
                                        </p>

                                        <p class="text-sm text-gray-500 truncate w-56">
                                            {{ space.description }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ space.type }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ space.capacity }} personas
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        ${{ space.price_per_hour }}
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <button
                                            @click="openModalEdit(space)"
                                            class="text-indigo-600 hover:text-indigo-900 mr-4"
                                        >
                                            Editar
                                        </button>

                                        <button
                                            @click="deleteSpace(space.id)"
                                            class="text-red-600 hover:text-red-900"
                                        >
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>

                                <tr v-if="spaces.length === 0">
                                    <td
                                        colspan="6"
                                        class="text-center py-8 text-gray-500"
                                    >
                                        No hay espacios registrados
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div
            v-if="showingModal"
            class="fixed inset-0 z-50 overflow-y-auto"
        >
            <div class="flex items-center justify-center min-h-screen p-4">
                <div
                    class="fixed inset-0 bg-black/40"
                    @click="closeModal"
                ></div>

                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-3xl p-8">
                    <form @submit.prevent="submit">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">
                            {{ isEditing ? 'Editar Espacio' : 'Nuevo Espacio' }}
                        </h3>

                        <div class="space-y-5">
                            <input
                                v-model="form.name"
                                type="text"
                                placeholder="Nombre del espacio"
                                class="w-full rounded-lg border-gray-300"
                                required
                            >

                            <div class="grid grid-cols-2 gap-4">
                                <select
                                    v-model="form.type"
                                    class="rounded-lg border-gray-300"
                                >
                                    <option>Auditorio</option>
                                    <option>Sala de Conferencias</option>
                                    <option>Laboratorio</option>
                                    <option>Cancha</option>
                                    <option>Estudio de Grabación</option>
                                </select>

                                <input
                                    v-model="form.capacity"
                                    type="number"
                                    placeholder="Capacidad"
                                    class="rounded-lg border-gray-300"
                                >
                            </div>

                            <input
                                v-model="form.price_per_hour"
                                type="number"
                                placeholder="Precio por hora"
                                class="rounded-lg border-gray-300"
                            >

                            <textarea
                                v-model="form.description"
                                rows="3"
                                placeholder="Descripción"
                                class="rounded-lg border-gray-300"
                            ></textarea>

                            <div>
                                <h4 class="font-bold mb-4">
                                    Horarios Disponibles
                                </h4>

                                <div class="space-y-3 max-h-64 overflow-y-auto border rounded-xl p-4">
                                    <div
                                        v-for="day in form.availabilities"
                                        :key="day.day_of_week"
                                        class="border rounded-lg p-3"
                                    >
                                        <div class="flex justify-between items-center">
                                            <label class="flex items-center gap-2">
                                                <input
                                                    v-model="day.enabled"
                                                    type="checkbox"
                                                >

                                                <span>
                                                    {{
                                                        days.find(
                                                            d => d.value === day.day_of_week
                                                        )?.label
                                                    }}
                                                </span>
                                            </label>
                                        </div>

                                        <div
                                            v-if="day.enabled"
                                            class="grid grid-cols-2 gap-3 mt-3"
                                        >
                                            <input
                                                v-model="day.start_time"
                                                type="time"
                                                class="rounded-lg border-gray-300"
                                            >

                                            <input
                                                v-model="day.end_time"
                                                type="time"
                                                class="rounded-lg border-gray-300"
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <label class="flex items-center gap-3">
                                <input
                                    v-model="form.is_active"
                                    type="checkbox"
                                >

                                <span>Espacio activo</span>
                            </label>

                            <input
                                type="file"
                                @input="form.image = $event.target.files[0]"
                            >
                        </div>

                        <div class="flex justify-end gap-3 mt-8">
                            <button
                                type="button"
                                @click="closeModal"
                                class="px-5 py-2 border rounded-lg"
                            >
                                Cancelar
                            </button>

                            <button
                                type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg"
                            >
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>