<script setup>
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    spaces: Array,
    selectedSpace: Object,
    reservations: Array,
    blocks: Array,
    currentDate: String
});

const changeSpace = (slug) => {
    router.get(route('admin.calendar'), { space: slug, date: props.currentDate });
};

const navigate = (days) => {
    const newDate = new Date(props.currentDate);
    newDate.setDate(newDate.getDate() + days);
    router.get(route('admin.calendar'), { 
        space: props.selectedSpace?.slug, 
        date: newDate.toISOString().split('T')[0] 
    });
};
</script>

<template>
    <AppLayout title="Calendario">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">📅 Calendario Semanal</h2>
                <div class="flex space-x-2">
                    <button @click="navigate(-7)" class="px-4 py-2 bg-gray-200 rounded">Anterior</button>
                    <button @click="navigate(7)" class="px-4 py-2 bg-gray-200 rounded">Siguiente</button>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white p-6 rounded-lg shadow">
                    <select @change="changeSpace($event.target.value)" class="mb-6 block w-full rounded-md border-gray-300 shadow-sm">
                        <option v-for="space in spaces" :key="space.id" :value="space.slug" :selected="space.slug === selectedSpace?.slug">
                            {{ space.name }}
                        </option>
                    </select>

                    <div class="grid grid-cols-1 gap-4">
                        <div v-for="res in reservations" :key="res.id" class="p-4 border-l-4 border-green-500 bg-green-50 rounded">
                            <p class="font-bold">Reserva: {{ res.user_name }}</p>
                            <p class="text-sm">{{ res.start_time }} - {{ res.end_time }}</p>
                            <span class="text-xs uppercase px-2 py-1 bg-green-200 rounded">{{ res.status }}</span>
                        </div>
                        <div v-for="block in blocks" :key="block.id" class="p-4 border-l-4 border-red-500 bg-red-50 rounded">
                            <p class="font-bold text-red-700">BLOQUEO: {{ block.reason }}</p>
                            <p class="text-sm text-red-600">{{ block.start_time }} - {{ block.end_time }}</p>
                        </div>
                        <p v-if="reservations.length === 0 && blocks.length === 0" class="text-center text-gray-500">No hay eventos esta semana.</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>