<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    spaces: Array,
});
</script>

<template>
    <Head title="Reserva de Espacios" />
    <div class="bg-gray-100 min-h-screen">
        <nav class="bg-white shadow-sm p-4 flex justify-between items-center">
            <span class="text-xl font-bold text-indigo-600">Sistema de Reservas</span>
            <div>
                <Link :href="route('login')" class="text-gray-600 hover:text-indigo-600 px-4">Admin Login</Link>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-12 px-4">
            <h1 class="text-4xl font-extrabold text-gray-900 text-center mb-10">
                Nuestros Espacios Disponibles
            </h1>

            <div class="grid md:grid-cols-3 gap-8">
                <div v-for="space in spaces" :key="space.id" class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                    <img v-if="space.image_path" :src="`/storage/${space.image_path}`" class="h-48 w-full object-cover">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs font-semibold uppercase tracking-wide text-indigo-500">{{ space.type }}</span>
                            <span class="text-gray-500 text-sm">Cap. {{ space.capacity }}</span>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 mb-2">{{ space.name }}</h2>
                        <p class="text-gray-600 text-sm line-clamp-2 mb-4">{{ space.description }}</p>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-gray-900">${{ space.price_per_hour }}/hr</span>
                            <Link :href="route('spaces.show', space.slug)" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
                                Ver Disponibilidad
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>