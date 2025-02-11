<template>
    <layout>
        <div class="max-w-5xl mx-auto bg-[#1a1a1a] p-12 rounded-xl shadow-2xl border border-gray-700 text-white">
            <button @click="toggleAdmin" class="bg-[#c9a14a] hover:bg-[#b8923a] text-white font-semibold px-6 py-3 rounded-full transition-all duration-300 mb-8 shadow-lg">
                {{ showAdmin ? 'Veure Restaurant' : 'Modificar' }}
            </button>

            <AdminEdit
                v-if="showAdmin"
                :adminData="editableAdminData"
                :tipusCuinaOptions="tipusCuinaOptions"
                mode="edit"
                @adminDataUpdated="updateRestaurantData"
            />

            <div v-else class="flex flex-col md:flex-row">
                <div class="md:w-2/3 pr-8">
                    <div class="mb-8">
                        <h1 class="text-5xl font-extrabold text-[#c9a14a] tracking-wide uppercase">{{ nom }}</h1>
                    </div>
                    <div class="mb-6 border-b border-gray-600 pb-4">
                        <p class="text-lg font-semibold text-gray-400">Descripció:</p>
                        <p class="text-gray-300 text-lg">{{ descripcio }}</p>
                    </div>
                    <div class="mb-6 border-b border-gray-600 pb-4">
                        <p class="text-lg font-semibold text-gray-400">Telèfon:</p>
                        <p class="text-gray-300 text-lg">{{ telefon }}</p>
                    </div>
                    <div class="mb-6 border-b border-gray-600 pb-4">
                        <p class="text-lg font-semibold text-gray-400">Cuina:</p>
                        <p class="text-gray-300 text-lg">{{ tipus_cuina }}</p>
                    </div>
                    <div class="mb-6">
                        <p class="text-lg font-semibold text-gray-400">Horari:</p>
                        <p class="text-gray-300 text-lg">Dilluns - Dissabte ({{ horaObertura }} - {{ horaTancament }})</p>
                    </div>
                </div>
                <div class="md:w-1/3 pl-6 bg-[#2a2a2a] p-10 rounded-xl shadow-xl border border-gray-600">
                    <h2 class="text-2xl font-bold text-[#c9a14a] mb-6">Reserva una Taula</h2>
                    <form @submit.prevent="submitReservation" class="space-y-6">
                        <div>
                            <label for="telefon" class="block text-lg font-medium text-gray-400">Telèfon</label>
                            <input type="text" v-model="reservation.telefon" id="telefon" class="input-field" required>
                        </div>
                        <div>
                            <label for="data" class="block text-lg font-medium text-gray-400">Data</label>
                            <input type="date" v-model="reservation.data" id="data" class="input-field" required>
                        </div>
                        <div>
                            <label for="hora" class="block text-lg font-medium text-gray-400">Hora</label>
                            <input type="time" v-model="reservation.hora" id="hora" class="input-field" required>
                        </div>
                        <div>
                            <label for="num_persones" class="block text-lg font-medium text-gray-400">Número de Persones</label>
                            <input type="number" v-model="reservation.num_persones" id="num_persones" class="input-field" required min="1" max="20">
                        </div>
                        <div>
                            <label for="id_taula" class="block text-lg font-medium text-gray-400">Taula</label>
                            <select v-model="reservation.id_taula" id="id_taula" class="input-field" required>
                                <option v-for="taula in taules" :key="taula.id" :value="taula.id">Taula {{ taula.id }}</option>
                            </select>
                        </div>
                        <button type="submit" class="bg-[#c9a14a] hover:bg-[#b8923a] text-white font-semibold px-6 py-3 rounded-full shadow-lg transition-all duration-300 w-full" :disabled="loading">
                            Reservar
                        </button>
                        <div v-if="loading" class="mt-4 text-[#c9a14a] font-medium">Creant reserva...</div>
                    </form>
                </div>
            </div>
        </div>
    </layout>
</template>

<style scoped>
.input-field {
    @apply mt-2 block w-full border-gray-500 bg-[#1e1e1e] rounded-md shadow-sm focus:border-[#c9a14a] focus:ring focus:ring-[#c9a14a] focus:ring-opacity-50 p-3 text-lg text-white placeholder-gray-400;
    transition: all 0.3s ease-in-out;
}
.input-field:focus {
    background-color: #292929;
}
</style>

<script setup>
import { defineProps, reactive, ref, onMounted } from 'vue';
import Layout from '@/Layouts/Layout.vue';
import AdminEdit from './AdminEdit.vue';
import { Inertia } from '@inertiajs/inertia';
import { route } from "ziggy-js";
import axios from 'axios';

const props = defineProps({
    restaurant: Object,
    tipusCuinaOptions: Array,
});

const { nom, descripcio, telefon, tipus_cuina, hora_obertura, hora_tancament } = props.restaurant;

const horaObertura = hora_obertura;
const horaTancament = hora_tancament;

const reservation = reactive({
    id_restaurant: props.restaurant.id,
    telefon: '',
    data: '',
    hora: '',
    num_persones: 1,
    id_taula: null,
    estat: 'pendent',
});

const taules = reactive([]);
const loading = ref(false);
const showAdmin = ref(false);

// Deep copy for editing:
const editableAdminData = reactive(JSON.parse(JSON.stringify(props.restaurant)));


onMounted(async () => {
    try {
        const response = await axios.get(route('taules.index', { restaurant_id: props.restaurant.id }));
        taules.push(...response.data);
    } catch (error) {
        console.error('Error fetching taules:', error);
    }
});

const toggleAdmin = () => {
    showAdmin.value = !showAdmin.value;
};


const updateRestaurantData = (updatedRestaurant) => {
    Inertia.put(route('restaurants.update', { restaurant: props.restaurant.id }), updatedRestaurant, {
        onSuccess: (response) => {
            // Update the original restaurant prop:
            Object.assign(props.restaurant, response.props.restaurant);
            // Update the editable copy:
            Object.assign(editableAdminData, response.props.restaurant);
            showAdmin.value = false; // Close the admin view
        },
        onError: (errors) => {
            console.error("Error updating restaurant:", errors);
        },
    });
};

const submitReservation = () => {
    loading.value = true;
    Inertia.post(route('reserves.store'), reservation, {
        onSuccess: () => {
            console.log('Reserva creada amb èxit');
            // Reset the reservation form or provide feedback to the user
            Object.assign(reservation, {
                telefon: '',
                data: '',
                hora: '',
                num_persones: 1,
                id_taula: null,
            });
        },
        onError: (errors) => {
            console.error('Error creant la reserva:', errors);
        },
        onFinish: () => {
            setTimeout(() => {
                loading.value = false;
            }, 1000);
        }
    });
};

</script>
