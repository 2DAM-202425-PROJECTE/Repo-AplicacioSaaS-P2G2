Fragmento de código

// AdminEdit.vue
<template>
    <div>
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
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { route } from 'ziggy-js';
import axios from 'axios';

const props = defineProps({
    adminData: Object,
    tipusCuinaOptions: Array,
    provincias: Array,
});

const form = reactive({ ...props.adminData });

const selectedProvinciaId = ref(props.adminData.provincia_id);
const municipios = ref([]);

onMounted(() => {
    fetchMunicipios();
});

const fetchMunicipios = async () => {
    try {
        const response = await axios.get(route('get.municipios', { provincia_id: selectedProvinciaId.value }));
        municipios.value = response.data;
    } catch (error) {
        console.error("Error fetching municipios:", error);
    }
};

const submitAdminForm = () => {
    Inertia.put(route('restaurants.update', { restaurant: form.id }), form, {
        onSuccess: (response) => {
            defineEmits(['adminDataUpdated'])(response.props.restaurant);
        },
        onError: (errors) => {
            console.error("Error updating restaurant:", errors);
        },
    });
};

defineEmits(['adminDataUpdated']);
</script>
