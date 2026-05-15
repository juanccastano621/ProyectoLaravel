<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    space: Object
});

// Inicializamos el formulario con los datos que vienen de la base de datos
const form = useForm({
    name: props.space.name,
    type: props.space.type,
    capacity: props.space.capacity,
    price_per_hour: props.space.price_per_hour,
    is_active: props.space.is_active ? true : false,
});

const submit = () => {
    // Usamos PUT para actualizar
    form.put(route('admin.spaces.update', props.space.id));
};
</script>

<template>
    <Head :title="'Editando ' + space.name" />

    <AppLayout title="Editar Espacio">
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                        ✏️ Editando: {{ space.name }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Modifica los detalles técnicos o la disponibilidad del espacio.</p>
                </div>
                <Link :href="route('admin.spaces.index')" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition">
                    ← Cancelar y volver
                </Link>
            </div>
        </template>

        <div class="py-10">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-xl rounded-xl border border-gray-100 overflow-hidden">
                    <form @submit.prevent="submit" class="p-8 space-y-6">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre del Espacio</label>
                            <input v-model="form.name" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <div v-if="form.errors.name" class="mt-1 text-xs text-red-600">{{ form.errors.name }}</div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tipo</label>
                                <select v-model="form.type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="Auditorio">Auditorio</option>
                                    <option value="Sala de Conferencias">Sala de Conferencias</option>
                                    <option value="Estudio de Grabación">Estudio de Grabación</option>
                                    <option value="Laboratorio">Laboratorio</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Capacidad Máxima</label>
                                <input v-model="form.capacity" type="number" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Precio por Hora ($)</label>
                            <input v-model="form.price_per_hour" type="number" step="0.01" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="flex items-start bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div class="flex h-5 items-center">
                                <input id="is_active" v-model="form.is_active" type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_active" class="font-medium text-gray-700">Espacio Activo</label>
                                <p class="text-gray-500">Determina si los usuarios pueden ver este espacio en el catálogo público.</p>
                            </div>
                        </div>

                        <div class="pt-5 flex items-center justify-end space-x-3 border-t border-gray-100">
                            <button type="submit" :disabled="form.processing" class="inline-flex justify-center px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                                <span v-if="form.processing">Actualizando...</span>
                                <span v-else>Guardar Cambios</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>