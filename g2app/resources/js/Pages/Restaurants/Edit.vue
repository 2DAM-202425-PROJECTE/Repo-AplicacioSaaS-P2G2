<template>
    <div>
        <form @submit.prevent="submitAdminForm">
            <div class="mb-4">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="nom">Nom</label>
                <input id="nom" v-model="form.nom" class="mt-1 block w-full" required type="text"/>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="descripcio">Descripció</label>
                <textarea id="descripcio" v-model="form.descripcio" class="mt-1 block w-full" required></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="telefon">Telèfon</label>
                <input id="telefon" v-model="form.telefon" class="mt-1 block w-full" required type="text"/>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="profile_image">Imatge de Perfil</label>
                <input id="profile_image" class="mt-1 block w-full" type="file" @change="HandleFileUpload"/>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="tipus_cuina">Tipus de Cuina</label>
                <select id="tipus_cuina" v-model="form.tipus_cuina" class="mt-1 block w-full" required>
                    <option v-for="option in tipusCuinaOptions" :key="option" :value="option">{{ option }}</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="provincia">Provincia</label>
                <select id="provincia" v-model="selectedProvinciaId" class="mt-1 block w-full" required
                        @change="fetchMunicipios">
                    <option v-for="provincia in provincias" :key="provincia.id" :value="provincia.id">{{
                            provincia.name
                        }}
                    </option>
                </select>
            </div>

            <div v-if="municipios.length > 0" class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="municipi">Municipi</label>
                <select id="municipi" v-model="form.municipio_id" class="mt-1 block w-full" required>
                    <option disabled value="">Select a Municipality</option>
                    <option v-for="municipio in municipios" :key="municipio.id" :value="municipio.id">{{
                            municipio.name
                        }}
                    </option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="carrer">Carrer</label>
                <input id="carrer" v-model="form.carrer" class="mt-1 block w-full" required type="text"/>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="hora_obertura">Hora d'Obertura</label>
                <input id="hora_obertura" v-model="form.hora_obertura" class="mt-1 block w-full" required type="time"/>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="hora_tancament">Hora de Tancament</label>
                <input id="hora_tancament" v-model="form.hora_tancament" class="mt-1 block w-full" required
                       type="time"/>
            </div>


            <button class="bg-blue-500 text-white px-4 py-2 rounded" type="submit">
                Guardar canvis
            </button>

        </form>
    </div>
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
    const formData = new FormData();

    // Add all text fields
    formData.append('nom', form.nom);
    formData.append('descripcio', form.descripcio);
    formData.append('telefon', form.telefon);
    formData.append('tipus_cuina', form.tipus_cuina);
    formData.append('municipio_id', form.municipio_id);
    formData.append('carrer', form.carrer);
    formData.append('hora_obertura', form.hora_obertura);
    formData.append('hora_tancament', form.hora_tancament);

    // Only append profile_image if it's a File object (new upload)
    if (form.profile_image instanceof File) {
        formData.append('profile_image', form.profile_image);
    }

    Inertia.post(route('restaurants.update', {restaurant: props.restaurant.id}), formData, {
        forceFormData: true,
        onSuccess: () => {
            Inertia.visit(route('restaurants.show', {id: props.restaurant.id}));
        },
        onError: (errors) => {
            console.error("Error updating restaurant:", errors);
        },
    });
};
</script>
