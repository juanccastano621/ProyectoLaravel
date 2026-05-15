<script setup>
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    spaces: Array,
});

// Variables para controlar el modal
const showingModal = ref(false);
const isEditing = ref(false);
const currentSpaceId = ref(null);

// Formulario de Inertia preparado para subir archivos
const form = useForm({
    name: '',
    description: '',
    capacity: '',
    image: null,
    _method: 'post', // Laravel necesita esto para subir archivos al editar
});

// Abrir modal para CREAR
const openModalCreate = () => {
    isEditing.value = false;
    form.reset();
    form.clearErrors();
    form._method = 'post';
    showingModal.value = true;
};

// Abrir modal para EDITAR
const openModalEdit = (space) => {
    isEditing.value = true;
    currentSpaceId.value = space.id;
    form.name = space.name;
    form.description = space.description;
    form.capacity = space.capacity;
    form.image = null; // No cargamos la imagen anterior por seguridad
    form._method = 'put'; // Truco de Laravel para actualizar con archivos
    form.clearErrors();
    showingModal.value = true;
};

// Cerrar modal
const closeModal = () => {
    showingModal.value = false;
    form.reset();
};

// Guardar o Actualizar
const submit = () => {
    if (isEditing.value) {
        form.post(route('admin.spaces.update', currentSpaceId.value), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('admin.spaces.store'), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    }
};

// Eliminar
const deleteSpace = (id) => {
    if (confirm('¿Estás seguro de que deseas eliminar este espacio? Esta acción no se puede deshacer.')) {
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
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    🏛️ Gestión de Espacios
                </h2>
                <button @click="openModalCreate" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow">
                    + Nuevo Espacio
                </button>
            </div>
        </template>

        <div class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Imagen</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capacidad</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="space in spaces" :key="space.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <img v-if="space.image_path" :src="`/storage/${space.image_path}`" alt="Foto" class="h-12 w-16 object-cover rounded">
                                        <div v-else class="h-12 w-16 bg-gray-200 rounded flex items-center justify-center text-xs text-gray-500">Sin foto</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ space.name }}</div>
                                        <div class="text-sm text-gray-500 truncate w-48">{{ space.description }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ space.capacity }} personas
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button @click="openModalEdit(space)" class="text-indigo-600 hover:text-indigo-900 mx-2">Editar</button>
                                        <button @click="deleteSpace(space.id)" class="text-red-600 hover:text-red-900">Eliminar</button>
                                    </td>
                                </tr>
                                <tr v-if="spaces.length === 0">
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                        No hay espacios registrados. Haz clic en "Nuevo Espacio" para comenzar.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="showingModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form @submit.prevent="submit">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                {{ isEditing ? 'Editar Espacio' : 'Registrar Nuevo Espacio' }}
                            </h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nombre del Auditorio</label>
                                    <input v-model="form.name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                    <p v-if="form.errors.name" class="mt-2 text-sm text-red-600">{{ form.errors.name }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Capacidad (Personas)</label>
                                    <input v-model="form.capacity" type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                    <p v-if="form.errors.capacity" class="mt-2 text-sm text-red-600">{{ form.errors.capacity }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Descripción / Detalles</label>
                                    <textarea v-model="form.description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required></textarea>
                                    <p v-if="form.errors.description" class="mt-2 text-sm text-red-600">{{ form.errors.description }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Fotografía (JPG, PNG)</label>
                                    <input type="file" @input="form.image = $event.target.files[0]" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept="image/*">
                                    <p v-if="form.errors.image" class="mt-2 text-sm text-red-600">{{ form.errors.image }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" :disabled="form.processing" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                                Guardar
                            </button>
                            <button type="button" @click="closeModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>