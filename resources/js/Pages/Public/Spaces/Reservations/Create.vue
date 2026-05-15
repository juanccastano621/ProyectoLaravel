    <script setup>
    import { Head, Link, useForm } from '@inertiajs/vue3';

    const props = defineProps({
        space: Object,
        startTime: String,
        endTime: String,
        displayDate: String,
        displayTime: String
    });

    const form = useForm({
        space_id: props.space.id,
        start_time: props.startTime,
        end_time: props.endTime,
        user_name: '',
        user_email: '',
        notes: ''
    });

    const submit = () => {
        form.post(route('reservations.store'));
    };
    </script>

    <template>
        <Head title="Confirmar Reserva" />
        <div class="min-h-screen bg-gray-50 py-12 px-4">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-3xl shadow-sm border p-8">
                    <h1 class="text-2xl font-bold mb-6">Datos de tu Reserva</h1>
                    <div class="bg-indigo-50 p-4 rounded-xl mb-8 border border-indigo-100">
                        <p><strong>Lugar:</strong> {{ space.name }}</p>
                        <p><strong>Fecha:</strong> {{ displayDate }}</p>
                        <p><strong>Horario:</strong> {{ displayTime }}</p>
                    </div>

                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium">Nombre Completo</label>
                            <input type="text" v-model="form.user_name" required class="w-full rounded-xl border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Correo Electrónico</label>
                            <input type="email" v-model="form.user_email" required class="w-full rounded-xl border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Notas (opcional)</label>
                            <textarea v-model="form.notes" class="w-full rounded-xl border-gray-300 shadow-sm"></textarea>
                        </div>
                        <button type="submit" :disabled="form.processing" class="w-full py-4 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all">
                            {{ form.processing ? 'Guardando...' : 'Finalizar Reserva' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </template>