<template>
    <layout>
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
            <div class="mb-4">
            <Link :href="route('restaurants.show', { id: restaurant.id })"
                  class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                <-Tornar
            </Link>
            </div>
            <h1 class="text-2xl font-bold mb-4">Editar Restaurant</h1>
            <form @submit.prevent="submitAdminForm">
                <div class="mb-4">
                    <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                    <input v-model="form.nom" id="nom" type="text" class="mt-1 block w-full" required />
                </div>
                <div class="mb-4">
                    <label for="descripcio" class="block text-sm font-medium text-gray-700">Descripció</label>
                    <textarea v-model="form.descripcio" id="descripcio" class="mt-1 block w-full" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="telefon" class="block text-sm font-medium text-gray-700">Telèfon</label>
                    <input v-model="form.telefon" id="telefon" type="text" class="mt-1 block w-full" required />
                </div>
                <div class="mb-4">
                    <label for="profile_image" class="block text-sm font-medium text-gray-700">Imatge de Perfil</label>
                    <input id="profile_image" type="file" class="mt-1 block w-full" @change="HandleFileUpload" />
                </div>
                <div class="mb-4">
                    <label for="tipus_cuina" class="block text-sm font-medium text-gray-700">Tipus de Cuina</label>
                    <select v-model="form.tipus_cuina" id="tipus_cuina" class="mt-1 block w-full" required>
                        <option v-for="option in tipusCuinaOptions" :key="option" :value="option">{{ option }}</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="provincia" class="block text-sm font-medium text-gray-700">Provincia</label>
                    <select v-model="selectedProvinciaId" @change="fetchMunicipios" id="provincia" class="mt-1 block w-full" required>
                        <option v-for="provincia in provincias" :key="provincia.id" :value="provincia.id">{{ provincia.name }}</option>
                    </select>
                </div>

                <div class="mb-4" v-if="municipios.length > 0">
                    <label for="municipi" class="block text-sm font-medium text-gray-700">Municipi</label>
                    <select v-model="form.municipio_id" id="municipi" class="mt-1 block w-full" required>
                        <option value="" disabled>Select a Municipality</option>
                        <option v-for="municipio in municipios" :key="municipio.id" :value="municipio.id">{{ municipio.name }}</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="carrer" class="block text-sm font-medium text-gray-700">Carrer</label>
                    <input v-model="form.carrer" id="carrer" type="text" class="mt-1 block w-full" required />
                </div>
                <div class="mb-4">
                    <label for="hora_obertura" class="block text-sm font-medium text-gray-700">Hora d'Obertura</label>
                    <input v-model="form.hora_obertura" id="hora_obertura" type="time" class="mt-1 block w-full" required />
                </div>
                <div class="mb-4">
                    <label for="hora_tancament" class="block text-sm font-medium text-gray-700">Hora de Tancament</label>
                    <input v-model="form.hora_tancament" id="hora_tancament" type="time" class="mt-1 block w-full" required />
                </div>


                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                    Guardar canvis
                </button>
            </form>
        </div>
    </layout>
</template>

<script setup>
import {ref, reactive, onMounted, computed} from 'vue';
import {Inertia} from '@inertiajs/inertia';
import {route} from 'ziggy-js';
import axios from 'axios';
import Layout from '@/Layouts/Layout.vue';
import {Link, useForm} from "@inertiajs/vue3";
import ToggleButton from '@/Components/CheckButton.vue';

const props = defineProps({
    restaurant: Object,
    tipusCuinaOptions: Array,
    provincias: Array,
    municipios: Array,
    plats: Array,

});

const form = useForm({
    nom: props.restaurant.nom,
    descripcio: props.restaurant.descripcio,
    telefon: props.restaurant.telefon,
    profile_image: props.restaurant.profile_image,
    tipus_cuina: props.restaurant.tipus_cuina,
    municipio_id: props.restaurant.municipio_id,
    carrer: props.restaurant.carrer,
    hora_obertura: props.restaurant.hora_obertura,
    hora_tancament: props.restaurant.hora_tancament,
    plats: props.restaurant.plats || [],
});

const HandleFileUpload = (event) => {
    form.profile_image = event.target.files[0];
};

const selectedProvinciaId = ref(props.restaurant.municipio.provincia_id);
const municipios = ref([]);

onMounted(() => {
    fetchMunicipios();
});

const fetchMunicipios = async () => {
    try {
        const response = await axios.get(route('get.municipios', {provincia_id: selectedProvinciaId.value}));
        municipios.value = response.data;
    } catch (error) {
        console.error("Error fetching municipios:", error);
    }
};


const submitAdminForm = () => {
    form.transform(data => {
        let formData = new FormData();
        for (let key in data) {
            formData.append(key, data[key] === null ? '' : data[key]);
        }
        return formData;
    }).post(route('restaurants.update', { restaurant: props.restaurant.id }), {
        onSuccess: () => {
            Inertia.visit(route('restaurants.show', { id: props.restaurant.id }));
        },
        onError: (errors) => {
            console.error("Error updating restaurant:", errors);
        },
    });
};
</script>
