<template>
    <layout>
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
            <button @click="toggleAdmin" class="bg-green-500 text-white px-4 py-2 rounded mb-4">
                {{ showAdmin ? 'Veure Restaurant' : 'Modificar' }}
            </button>

            <AdminEdit
                v-if="showAdmin"
                :adminData="editableAdminData"
                :tipusCuinaOptions="tipusCuinaOptions"
                mode="edit"
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
                            {{ restaurant.ubicacio?.municipi }},
                            {{ restaurant.ubicacio?.provincia }},
                            {{ restaurant.ubicacio?.codi_postal }}
                        </p>
                    </div>
                </div>
                <div class="w-1/3 pl-4">
                    <div class="mt-8">
                        <h2 class="text-xl font-bold mb-4">Crea una Reserva</h2>
                        <form @submit.prevent="submitReservation">
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
                                <input type="number" v-model="reservation.num_persones" id="num_persones" class="mt-1 block w-full" required min="1" max="20">
                            </div>
                            <div class="mb-4">
                                <label for="id_taula" class="block text-sm font-medium text-gray-700">Taula</label>
                                <select v-model="reservation.id_taula" id="id_taula" class="mt-1 block w-full" required>
                                    <option v-for="taula in taules" :key="taula.id" :value="taula.id">{{ taula.id }}</option>
                                </select>
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

            Object.assign(props.restaurant, response.props.restaurant);

            Object.assign(editableAdminData, response.props.restaurant);
            showAdmin.value = false;
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


