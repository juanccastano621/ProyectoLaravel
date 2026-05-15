<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'

const props = defineProps({
    spaces: Array,
    filters: Object
})

const search = ref(props.filters?.search || '')
const capacity = ref(props.filters?.capacity || '')
const maxPrice = ref(props.filters?.max_price || '')

const applyFilters = () => {
    router.get(
        route('spaces.index'),
        {
            search: search.value,
            capacity: capacity.value,
            max_price: maxPrice.value
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true
        }
    )
}

watch(search, applyFilters)
watch(capacity, applyFilters)
watch(maxPrice, applyFilters)
</script>

<template>
    <Head title="Explorar Auditorios" />

    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <span class="text-2xl mr-2">🏢</span>

                    <span class="font-bold text-xl text-gray-900">
                        Reserva
                        <span class="text-indigo-600">
                            Espacios
                        </span>
                    </span>
                </div>

                <div class="flex items-center">
                    <Link
                        :href="route('login')"
                        class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition"
                    >
                        Acceso Admin
                    </Link>
                </div>
            </div>
        </div>
    </nav>

    <div class="bg-gray-50 min-h-screen pb-20">

        <header class="bg-indigo-700 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

                <h1 class="text-4xl font-extrabold text-white sm:text-5xl">
                    Encuentra el lugar perfecto
                </h1>

                <p class="mt-4 text-xl text-indigo-100">
                    Reserva auditorios modernos para conferencias, eventos y reuniones.
                </p>

            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10">

            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6 mb-10">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <input
                        v-model="search"
                        type="text"
                        placeholder="Buscar auditorio..."
                        class="rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    >

                    <input
                        v-model="capacity"
                        type="number"
                        placeholder="Capacidad mínima"
                        class="rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    >

                    <input
                        v-model="maxPrice"
                        type="number"
                        placeholder="Precio máximo"
                        class="rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    >

                </div>

            </div>

            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">

                <div
                    v-for="space in spaces"
                    :key="space.id"
                    class="flex flex-col bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl transition duration-300"
                >

                    <div class="h-56 relative overflow-hidden bg-gray-100">

                        <img
                            v-if="space.image_path"
                            :src="`/storage/${space.image_path}`"
                            :alt="space.name"
                            class="w-full h-full object-cover"
                        >

                        <div
                            v-else
                            class="w-full h-full flex items-center justify-center bg-indigo-50"
                        >
                            <span class="text-7xl opacity-80">
                                🏛️
                            </span>
                        </div>

                        <div class="absolute top-4 right-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white text-indigo-600 shadow-sm uppercase">
                                AUDITORIO
                            </span>
                        </div>
                    </div>

                    <div class="flex-1 p-6 flex flex-col justify-between">

                        <div>

                            <h3 class="text-2xl font-bold text-gray-900 mb-3">
                                {{ space.name }}
                            </h3>

                            <div class="space-y-3 text-sm text-gray-600">

                                <div class="flex items-center">
                                    <span class="mr-2">
                                        👥
                                    </span>

                                    <span>
                                        <strong>
                                            {{ space.capacity }}
                                        </strong>
                                        personas
                                    </span>
                                </div>

                                <div class="flex items-center">
                                    <span class="mr-2">
                                        💰
                                    </span>

                                    <span>
                                        <strong>
                                            ${{ space.price_per_hour }}
                                        </strong>
                                        / hora
                                    </span>
                                </div>

                            </div>
                        </div>

                        <Link
                            :href="route('spaces.show', space.slug)"
                            class="mt-6 w-full inline-flex justify-center items-center px-4 py-3 rounded-2xl text-white bg-indigo-600 hover:bg-indigo-700 transition font-bold shadow-md"
                        >
                            Ver Disponibilidad
                        </Link>

                    </div>

                </div>

            </div>

            <div
                v-if="spaces.length === 0"
                class="text-center py-20"
            >
                <div class="text-6xl mb-4">
                    🔍
                </div>

                <h3 class="text-lg font-medium text-gray-900">
                    No hay auditorios disponibles
                </h3>

                <p class="text-gray-500">
                    Intenta ajustar los filtros de búsqueda.
                </p>
            </div>

        </main>
    </div>

    <footer class="bg-white py-8 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-400 text-sm">
            © 2026 Sistema de Reserva de Auditorios
        </div>
    </footer>
</template>