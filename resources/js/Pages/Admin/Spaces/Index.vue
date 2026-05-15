<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    spaces: Array
});

const deleteSpace = (id) => {
    if (confirm('¿Estás seguro de que deseas eliminar permanentemente este espacio? Esta acción no se puede deshacer.')) {
        router.delete(route('admin.spaces.destroy', id));
    }
};
</script>

<template>
    <Head title="Administrar Espacios" />

    <AppLayout title="Gestión de Espacios">
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <div>
                    <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                        🏢 Gestión de Espacios
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Administra los auditorios y salas disponibles para reserva.</p>
                </div>
                
                <Link :href="route('admin.spaces.create')" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                    <span class="mr-2">+</span> Nuevo Espacio
                </Link>
            </div>
        </template>

        <div class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <div v-if="$page.props.flash?.success" class="mb-6 flex items-center p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm">
                    <span class="text-xl mr-3">✅</span>
                    <p class="text-sm font-medium">{{ $page.props.flash.success }}</p>
                </div>

                <div class="bg-white shadow-xl rounded-xl border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Espacio</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Detalles</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Costo / Hora</th>
                                    <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                <tr v-for="space in spaces" :key="space.id" class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 font-bold text-lg">
                                                {{ space.name.charAt(0) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900">{{ space.name }}</div>
                                                <div class="text-xs text-gray-500">ID: #00{{ space.id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ space.type }}</div>
                                        <div class="text-xs text-gray-500">Capacidad: {{ space.capacity }} personas</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900">${{ space.price_per_hour }}</div>
                                        <div class="text-[10px] text-gray-400">Cobro por hora</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span v-if="space.is_active" class="px-3 py-1 inline-flex text-[11px] leading-5 font-bold rounded-full bg-green-100 text-green-800 border border-green-200 uppercase tracking-wide">
                                            Activo
                                        </span>
                                        <span v-else class="px-3 py-1 inline-flex text-[11px] leading-5 font-bold rounded-full bg-red-100 text-red-800 border border-red-200 uppercase tracking-wide">
                                            Inactivo
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-3">
                                            <Link :href="route('admin.spaces.edit', space.id)" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-md transition" title="Editar Espacio">
                                                ✏️
                                            </Link>
                                            <button @click="deleteSpace(space.id)" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-md transition" title="Eliminar Espacio">
                                                🗑️
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <tr v-if="spaces.length === 0">
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="text-gray-400 text-5xl mb-4">🏜️</div>
                                        <div class="text-lg font-medium text-gray-900">No hay espacios registrados</div>
                                        <p class="text-gray-500">Comienza agregando tu primer auditorio o sala.</p>
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