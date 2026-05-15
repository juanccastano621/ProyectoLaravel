<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

// useForm de Inertia maneja el estado, los errores y bloquea el botón al enviar
const form = useForm({
    name: '',
    type: 'Auditorio', // Valor por defecto
    capacity: '',
    price_per_hour: '',
    is_active: true,
});

// Función funcional para enviar los datos de forma segura
const submit = () => {
    form.post(route('admin.spaces.store'), {
        preserveScroll: true,
        // Si quieres hacer algo justo al terminar (como limpiar el form), se haría aquí
    });
};
</script>

<template>
    <Head title="Crear Nuevo Espacio" />

    <AppLayout title="Crear Espacio">
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                        ✨ Registrar Nuevo Espacio
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Completa los datos para habilitar un nuevo auditorio o sala en el sistema.</p>
                </div>
                <Link :href="route('admin.spaces.index')" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition">
                    ← Volver a la lista
                </Link>
            </div>
        </template>

        <div class="py-10">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-xl rounded-xl border border-gray-100 overflow-hidden">
                    
                    <form @submit.prevent="submit" class="p-8 space-y-6">
                        
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nombre del Espacio <span class="text-red-500">*</span></label>
                            <input 
                                id="name" 
                                v-model="form.name" 
                                type="text" 
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Ej. Auditorio Principal"
                            >
                            <div v-if="form.errors.name" class="mt-2 text-sm text-red-600">{{ form.errors.name }}</div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">Tipo de Espacio <span class="text-red-500">*</span></label>
                                <select 
                                    id="type" 
                                    v-model="form.type" 
                                    required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                >
                                    <option value="Auditorio">Auditorio</option>
                                    <option value="Sala de Conferencias">Sala de Conferencias</option>
                                    <option value="Estudio de Grabación">Estudio de Grabación</option>
                                    <option value="Laboratorio">Laboratorio</option>
                                    <option value="Otro">Otro</option>
                                </select>
                                <div v-if="form.errors.type" class="mt-2 text-sm text-red-600">{{ form.errors.type }}</div>
                            </div>

                            <div>
                                <label for="capacity" class="block text-sm font-medium text-gray-700">Capacidad (Personas) <span class="text-red-500">*</span></label>
                                <input 
                                    id="capacity" 
                                    v-model="form.capacity" 
                                    type="number" 
                                    min="1"
                                    required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Ej. 50"
                                >
                                <div v-if="form.errors.capacity" class="mt-2 text-sm text-red-600">{{ form.errors.capacity }}</div>
                            </div>
                        </div>

                        <div>
                            <label for="price_per_hour" class="block text-sm font-medium text-gray-700">Costo por Hora ($) <span class="text-red-500">*</span></label>
                            <input 
                                id="price_per_hour" 
                                v-model="form.price_per_hour" 
                                type="number" 
                                step="0.01"
                                min="0"
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Ej. 15.50 (Usa 0 si es gratis)"
                            >
                            <div v-if="form.errors.price_per_hour" class="mt-2 text-sm text-red-600">{{ form.errors.price_per_hour }}</div>
                        </div>

                        <div class="flex items-start mt-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div class="flex h-5 items-center">
                                <input 
                                    id="is_active" 
                                    v-model="form.is_active" 
                                    type="checkbox" 
                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                >
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_active" class="font-medium text-gray-700">Espacio Activo (Visible para el público)</label>
                                <p class="text-gray-500">Si desmarcas esta opción, los clientes no podrán ver ni reservar este espacio.</p>
                            </div>
                            <div v-if="form.errors.is_active" class="mt-2 text-sm text-red-600">{{ form.errors.is_active }}</div>
                        </div>

                        <div class="pt-5 flex items-center justify-end space-x-3 border-t border-gray-100">
                            <Link :href="route('admin.spaces.index')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancelar
                            </Link>
                            
                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                <span v-if="form.processing">Guardando...</span>
                                <span v-else>Guardar Espacio</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>