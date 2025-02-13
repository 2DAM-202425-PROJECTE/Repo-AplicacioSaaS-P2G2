<template>
    <div>
        <h2 class="text-xl font-bold mb-4">Create Restaurant</h2>

        <form @submit.prevent="submitCreateForm">
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
                <label for="provincia" class="block text-sm font-medium text-gray-700">Província</label>
                <select v-model="selectedProvinciaId" id="provincia" class="mt-1 block w-full" required @change="updateMunicipis">
                    <option v-for="option in uniqueProvincias" :key="option.id_ubicacio" :value="option.id_ubicacio">{{ option.provincia }}</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="municipi" class="block text-sm font-medium text-gray-700">Municipi</label>
                <select v-model="form.municipi" id="municipi" class="mt-1 block w-full" required>
                    <option v-for="option in allMunicipios" :key="option.id_ubicacio" :value="option.municipi">{{ option.municipi }}</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="carrer" class="block text-sm font-medium text-gray-700">Carrer</label>
                <input v-model="form.carrer" id="carrer" type="text" class="mt-1 block w-full" required />
            </div>

            <div class="mb-4">
                <label for="codi_postal" class="block text-sm font-medium text-gray-700">Codi Postal</label>
                <input v-model="form.codi_postal" id="codi_postal" type="text" class="mt-1 block w-full" required />
            </div>

            <div class="mb-4">
                <label for="hora_obertura" class="block text-sm font-medium text-gray-700">Hora d'Obertura</label>
                <input step="any" v-model="form.hora_obertura" id="hora_obertura" type="time" class="mt-1 block w-full" required />
            </div>

            <div class="mb-4">
                <label for="hora_tancament" class="block text-sm font-medium text-gray-700">Hora de Tancament</label>
                <input step="any" v-model="form.hora_tancament" id="hora_tancament" type="time" class="mt-1 block w-full" required />
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                Create Restaurant
            </button>
        </form>
    </div>
</template>

<script setup>
import { reactive, computed, ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { route } from 'ziggy-js';

const props = defineProps({
    tipusCuinaOptions: Array,
    ubicacioOptions: Array,
});

const form = reactive({
    nom: '',
    descripcio: '',
    telefon: '',
    tipus_cuina: '',
    provincia: '', // Keep this for display purposes
    municipi: '',
    carrer: '',
    codi_postal: '',
    hora_obertura: '',
    hora_tancament: '',
    id_ubicacio: null, // Add this to store the actual ID
});

const selectedProvinciaId = ref(null); // Store the selected province's ID

const uniqueProvincias = computed(() => {
    const provincias = [];
    return props.ubicacioOptions.filter(option => {
        if (!provincias.includes(option.provincia)) {
            provincias.push(option.provincia);
            return true;
        }
        return false;
    });
});

const allMunicipios = computed(() => {
    return props.ubicacioOptions;
});
const updateMunicipis = () => {
    if (selectedProvinciaId.value) {
        const selectedUbicacio = props.ubicacioOptions.find(u => u.id_ubicacio === selectedProvinciaId.value);
        form.provincia = selectedUbicacio.provincia;
        form.id_ubicacio = selectedProvinciaId.value;

        const municipios = props.ubicacioOptions.filter(u => u.provincia === selectedUbicacio.provincia);
        if (municipios.length > 0) {
            form.municipi = municipios[0].municipi; // Set default, but the user can choose any
        }
    } else {
        form.provincia = '';
        form.id_ubicacio = null;
        form.municipi = '';
    }
};

watch(() => props.ubicacioOptions, () => {
    if (props.ubicacioOptions.length > 0) {
        updateMunicipis(); // Call updateMunicipis when ubicacioOptions are available
    }
});

const submitCreateForm = () => {
    Inertia.post(route('restaurants.store'), form, {
        onSuccess: () => {
            console.log('Restaurant created successfully');
        },
        onError: (errors) => {
            console.error('Error creating the restaurant:', errors);
        },
    });
};

</script>
