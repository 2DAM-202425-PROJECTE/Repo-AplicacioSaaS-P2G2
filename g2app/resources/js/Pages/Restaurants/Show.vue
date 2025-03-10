<template>
    <layout>
        <div class="max-w-5xl mx-auto bg-[#1a1a1a] p-12 rounded-xl shadow-2xl border border-gray-700 text-white">
            <button @click="toggleAdmin" class="bg-[#c9a14a] hover:bg-[#b8923a] text-white font-semibold px-6 py-3 rounded-full transition-all duration-300 mb-8 shadow-lg">

                {{ showAdmin ? 'Veure Restaurant' : 'Modificar' }}
            </button>

            <Link :href="route('restaurant.reserves', { id: restaurant.id })" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded mb-4">
                Reserves
            </Link>

            <AdminEdit
                v-if="showAdmin"
                :adminData="editableAdminData"
                :tipusCuinaOptions="tipusCuinaOptions"
                :provincias="provincias"
                :municipios="municipios"
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

                    <div class="mb-4">
                        <p><strong>Ubicació:</strong></p>
                        <p>
                            {{ restaurant.carrer }},
                            {{ restaurant.municipio?.name || 'Municipi no disponible' }},
                            {{ restaurant.municipio?.provincia?.name || 'Provincia no disponible' }}
                        </p>
                    </div>
                </div>
                <div class="w-1/3 pl-4">
                    <div class="mt-8">
                    <h2 class="text-xl font-bold mb-4">Crea una Reserva</h2>
                    <form @submit.prevent="submitReservation">
                        <input type="hidden" v-model="reservation.id_restaurant">
                        <div class="mb-4">
                            <label for="telefon" class="block text-sm font-medium text-gray-700">Telèfon</label>
                            <input type="text" v-model="reservation.telefon" id="telefon" class="mt-1 block w-full" required>
                        </div>
                        <div class="mb-4">
                            <label for="data" class="block text-sm font-medium text-gray-700">Data</label>
                            <input type="date" v-model="reservation.data" id="data" class="mt-1 block w-full" required>
                        </div>
                        <div class="mb-4">
                            <label for="hora" class="block text-sm font-medium text-gray-700">Hora</label>
                            <input type="time" v-model="reservation.hora" id="hora" class="mt-1 block w-full" required>
                        </div>
                        <div class="mb-4">
                            <label for="num_persones" class="block text-sm font-medium text-gray-700">Número de Persones</label>
                            <input type="number" v-model="reservation.num_persones" id="num_persones" class="mt-1 block w-full" required min="1">
                        </div>

                        <div class="mt-1">
                            <label class="inline-flex items-center">
                                <input type="radio" v-model="reservation.terrassa" :value="true" class="form-radio">
                                <span class="ml-2">Terrassa</span>
                            </label>
                            <label class="inline-flex items-center ml-6">
                                <input type="radio" v-model="reservation.terrassa" :value="false" class="form-radio">
                                <span class="ml-2">Interior</span>
                            </label>
                        </div>
                        <div class="mb-4">
                            <label for="solicituds" class="block text-sm font-medium text-gray-700">Solicituds</label>
                            <textarea v-model="reservation.solicituds" id="solicituds" class="mt-1 block w-full"></textarea>
                        </div>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded" :disabled="loading">
                            Reservar
                        </button>
                        <div v-if="loading" class="mt-4 text-blue-500">Creant reserva...</div>
                    </form>
                </div>
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
import {Link} from '@inertiajs/vue3';
import Layout from '@/Layouts/Layout.vue';
import AdminEdit from './AdminEdit.vue';
import { Inertia } from '@inertiajs/inertia';
import { route } from 'ziggy-js';
import axios from 'axios';

const props = defineProps({
    restaurant: Object,
    tipusCuinaOptions: Array,
    provincias: Array,
    municipios: Array,
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
    estat: 0, // Default to PENDENT
    terrassa: false, // Default to terrassa
    solicituds: '',
});

const taules = ref([]);
const loading = ref(false);
const showAdmin = ref(false);

const editableAdminData = reactive(JSON.parse(JSON.stringify({
    ...props.restaurant,
    provincia_id: props.restaurant.municipio?.provincia_id,
    municipio_id: props.restaurant.municipio?.id,
})));

onMounted(async () => {
    try {
        const response = await axios.get(route('taules.index', { restaurant_id: props.restaurant.id }));
        taules.value = response.data;
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
            Object.assign(props.restaurant, response.props.restaurant);
            Object.assign(editableAdminData, response.props.restaurant);
            showAdmin.value = false;
        },
        onError: (errors) => {
            console.error('Error updating restaurant:', errors);
        },
    });
};

const submitReservation = () => {
    loading.value = true;
    Inertia.post(route('reserves.store'), reservation, {
        onSuccess: () => {
            console.log('Reserva creada amb èxit');
            Object.assign(reservation, {
                telefon: '',
                data: '',
                hora: '',
                num_persones: 1,
                id_taula: null,
                estat: 0, // Reset to PENDENT
            });
        },
        onError: (errors) => {
            console.error('Error creant la reserva:', errors);
        },
        onFinish: () => {
            setTimeout(() => {
                loading.value = false;
            }, 1000);
        },
    });
};
</script>
