<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    spaces: Array
});

const getIcon = (type) => {
    switch (type) {
        case 'Auditorio': return '🏛️';
        case 'Sala de Conferencias': return '👥';
        case 'Estudio de Grabación': return '🎙️';
        case 'Laboratorio': return '🧪';
        default: return '📍';
    }
};
</script>

<template>
    <Head title="Explorar Espacios" />

    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <span class="text-2xl mr-2">🏢</span>
                    <span class="font-bold text-xl text-gray-900 tracking-tight">Reserva<span class="text-indigo-600">Espacios</span></span>
                </div>
                <div class="flex items-center space-x-4">
                    <Link :href="route('login')" class="text-sm font-medium text-gray-500 hover:text-gray-700">Acceso Admin</Link>
                </div>
            </div>
        </div>
    </nav>

    <div class="bg-gray-50 min-h-screen pb-20">
        <header class="bg-indigo-700 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl font-extrabold text-white sm:text-5xl">
                    Encuentra el lugar perfecto para tu evento
                </h1>
                <p class="mt-4 text-xl text-indigo-100">
                    Reserva auditorios, salas y estudios con tecnología de punta.
                </p>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10">
            
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="space in spaces" :key="space.id" class="flex flex-col bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl transition-shadow duration-300">
                    
                    <div class="h-48 bg-indigo-50 flex items-center justify-center relative">
                        <span class="text-7xl opacity-80">{{ getIcon(space.type) }}</span>
                        <div class="absolute top-4 right-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white text-indigo-600 shadow-sm uppercase">
                                {{ space.type }}
                            </span>
                        </div>
                    </div>

                    <div class="flex-1 p-6 flex flex-col justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ space.name }}</h3>
                            
                            <div class="space-y-3 mb-6">
                                <div class="flex items-center text-sm text-gray-600">
                                    <span class="mr-2">👥</span>
                                    <span>Capacidad para <strong>{{ space.capacity }}</strong> personas</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <span class="mr-2">💰</span>
                                    <span>Desde <strong>${{ space.price_per_hour }}</strong> por hora</span>
                                </div>
                            </div>
                        </div>

                        <Link 
                            :href="route('spaces.show', space.slug)" 
                            class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors shadow-md"
                        >
                            Ver Disponibilidad y Reservar
                        </Link>
                    </div>
                </div>
            </div>

            <div v-if="spaces.length === 0" class="text-center py-20">
                <div class="text-6xl mb-4">🔍</div>
                <h3 class="text-lg font-medium text-gray-900">No hay espacios disponibles en este momento</h3>
                <p class="text-gray-500">Vuelve a consultar más tarde o contacta con administración.</p>
            </div>
        </main>
    </div>

    <footer class="bg-white py-8 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-400 text-sm">
            &copy; 2026 Sistema de Reservas de Auditorios. Todos los derechos reservados.
        </div>
    </footer>
</template>