<template>
    <layout>
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md space-x-2">
            <button @click="toggleAdmin" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mb-4">
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

            <div v-else class="flex">
                <div class="w-2/3 pr-4">
                    <div class="mb-4">
                        <h1 class="text-3xl font-bold">{{ nom }}</h1>
                    </div>
                    <div class="mb-4">
                        <p><strong>Descripció:</strong></p>
                        <p>{{ descripcio }}</p>
                    </div>
                    <div class="mb-4">
                        <p><strong>Telèfon:</strong></p>
                        <p>{{ telefon }}</p>
                    </div>
                    <div class="mb-4">
                        <p><strong>Cuina:</strong></p>
                        <p>{{ tipus_cuina }}</p>
                    </div>
                    <div class="mb-4">
                        <p><strong>Horari:</strong></p>
                        <p>Dilluns - Dissabte ({{ horaObertura }} - {{ horaTancament }})</p>
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
