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
                <select v-model="selectedProvinciaId" id="provincia" class="mt-1 block w-full" required>
                    <option v-for="provincia in provincias" :key="provincia.id" :value="provincia.id">{{ provincia.name }}</option>
                </select>
            </div>

            <div class="mb-4" v-if="selectedProvinciaId">
                <label for="municipi" class="block text-sm font-medium text-gray-700">Municipi</label>
                <select v-model="form.municipio_id" id="municipi" class="mt-1 block w-full" required>
                    <option value="" disabled>Select a Municipality</option>
                    <option v-for="municipio in filteredMunicipios" :key="municipio.id" :value="municipio.id">{{ municipio.name }}</option>
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
import { ref, computed, watch, reactive } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { route } from 'ziggy-js';

const props = defineProps({
    adminData: Object,
    tipusCuinaOptions: Array,
    provincias: Array,
    municipios: Array,
    filteredMunicipios: Array,
});

const form = reactive({ ...props.adminData });

const selectedProvinciaId = ref(props.adminData.provincia_id);

const filteredMunicipios = computed(() => {
    return props.municipios.filter(municipio => municipio.provincia_id === selectedProvinciaId.value);
});

watch(selectedProvinciaId, (newVal) => {
    form.municipio_id = null; // Reset municipio selection
});

const submitAdminForm = () => {
    Inertia.put(route('restaurants.update', { restaurant: form.id }), form, {
        onSuccess: (response) => {
            // Handle success, maybe emit an event to parent
            defineEmits(['adminDataUpdated'])(response.props.restaurant); // Emit the updated data
        },
        onError: (errors) => {
            // Handle errors
            console.error("Error updating restaurant:", errors);
        },
    });
};

defineEmits(['adminDataUpdated']);

</script>
