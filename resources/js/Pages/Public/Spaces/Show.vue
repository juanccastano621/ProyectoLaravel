<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';

const props = defineProps({
    space: Object,
    selectedDate: String,
    availableSlots: Array,
});

const pickedDate = ref(props.selectedDate);

watch(pickedDate, (newDate) => {
    router.get(route('spaces.show', props.space.slug), { date: newDate }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    });
    selectedSlot.value = null; 
});

const selectedSlot = ref(null);

const form = useForm({
    space_id: props.space.id,
    start_time: '',
    end_time: '',
    user_name: '',
    user_email: '',
    notes: '',
});

const selectTime = (slot) => {
    selectedSlot.value = slot;
    form.start_time = slot.datetime;
    form.end_time = `${props.selectedDate} ${slot.end}:00`;
    
    form.clearErrors();
};

const submitReservation = () => {
    form.post(route('reservations.store'), {
        preserveScroll: true,
        onSuccess: () => {
            selectedSlot.value = null;
            form.reset();
        }
    });
};

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
    <Head :title="space.name" />

    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <Link :href="route('spaces.index')" class="flex items-center text-gray-600 hover:text-indigo-600 transition">
                    <span class="mr-2">←</span> Volver al catálogo
                </Link>
            </div>
        </div>
    </nav>

    <div class="bg-gray-50 min-h-screen py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div v-if="$page.props.flash?.success" class="mb-8 flex items-center p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm">
                <span class="text-2xl mr-3">🎉</span>
                <div>
                    <p class="text-sm font-bold">¡Solicitud enviada con éxito!</p>
                    <p class="text-xs">{{ $page.props.flash.success }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="h-48 bg-indigo-600 flex items-center justify-center">
                            <span class="text-7xl text-white opacity-90">{{ getIcon(space.type) }}</span>
                        </div>
                        <div class="p-6">
                            <div class="uppercase tracking-wide text-xs text-indigo-600 font-bold mb-1">{{ space.type }}</div>
                            <h1 class="text-2xl font-extrabold text-gray-900 mb-4">{{ space.name }}</h1>
                            
                            <div class="space-y-4 pt-4 border-t border-gray-100">
                                <div class="flex items-center text-gray-700">
                                    <span class="text-xl mr-3">👥</span>
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium">Capacidad Máxima</p>
                                        <p class="text-sm font-bold">{{ space.capacity }} personas</p>
                                    </div>
                                </div>
                                <div class="flex items-center text-gray-700">
                                    <span class="text-xl mr-3">💵</span>
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium">Tarifa</p>
                                        <p class="text-sm font-bold">${{ space.price_per_hour }} / hora</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sm:p-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">🗓️ Verifica Disponibilidad</h2>
                        
                        <div class="mb-8">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Selecciona una fecha</label>
                            <input 
                                type="date" 
                                v-model="pickedDate"
                                :min="new Date().toISOString().split('T')[0]"
                                class="mt-1 block w-full max-w-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            >
                        </div>

                        <div class="mb-8">
                            <h3 class="text-sm font-medium text-gray-700 mb-3">Horarios Disponibles para el {{ new Date(pickedDate + 'T00:00:00').toLocaleDateString('es-ES', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}</h3>
                            
                            <div v-if="availableSlots.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                                <button 
                                    v-for="(slot, index) in availableSlots" 
                                    :key="index"
                                    @click="selectTime(slot)"
                                    :class="[
                                        selectedSlot === slot 
                                            ? 'bg-indigo-600 text-white border-indigo-600 ring-2 ring-offset-2 ring-indigo-500' 
                                            : 'bg-white text-gray-700 border-gray-300 hover:border-indigo-500 hover:text-indigo-600',
                                        'border rounded-lg px-4 py-3 text-sm font-semibold transition-all focus:outline-none'
                                    ]"
                                >
                                    {{ slot.start }} - {{ slot.end }}
                                </button>
                            </div>
                            
                            <div v-else class="text-center p-8 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                                <span class="text-3xl">📭</span>
                                <p class="mt-2 text-sm text-gray-600 font-medium">No hay horarios disponibles para esta fecha.</p>
                                <p class="text-xs text-gray-500">Intenta seleccionando un día diferente.</p>
                            </div>
                        </div>

                        <div v-if="selectedSlot" class="mt-8 pt-8 border-t border-gray-100 transition-all">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Completa tu Solicitud</h3>
                            <p class="text-sm text-gray-500 mb-6">Has seleccionado el horario de <strong class="text-indigo-600">{{ selectedSlot.start }} a {{ selectedSlot.end }}</strong>. Ingresa tus datos para continuar.</p>
                            
                            <form @submit.prevent="submitReservation" class="space-y-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nombre Completo <span class="text-red-500">*</span></label>
                                    <input v-model="form.user_name" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Ej. Juan Pérez">
                                    <div v-if="form.errors.user_name" class="mt-1 text-xs text-red-600">{{ form.errors.user_name }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Correo Electrónico <span class="text-red-500">*</span></label>
                                    <input v-model="form.user_email" type="email" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="ejemplo@correo.com">
                                    <div v-if="form.errors.user_email" class="mt-1 text-xs text-red-600">{{ form.errors.user_email }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Notas Adicionales (Opcional)</label>
                                    <textarea 
                                        v-model="form.notes" 
                                        rows="3" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                        placeholder="Ej. Necesitamos un proyector, acomodación en U, etc."
                                    ></textarea>
                                    <div v-if="form.errors.notes" class="mt-1 text-xs text-red-600">{{ form.errors.notes }}</div>
                                </div>
                                <div class="pt-4">
                                    <button 
                                        type="submit" 
                                        :disabled="form.processing"
                                        :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                                        class="w-full inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-bold rounded-xl shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition"
                                    >
                                        <span v-if="form.processing">Procesando...</span>
                                        <span v-else>Solicitar Reserva</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>