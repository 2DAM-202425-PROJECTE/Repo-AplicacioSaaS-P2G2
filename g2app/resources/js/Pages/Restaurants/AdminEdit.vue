<template>
    <div>
        <h2 class="text-xl font-bold mb-4">Personalitzar Restaurant</h2>
        <form @submit.prevent="submitAdminForm">
            <div class="mb-4">
                <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                <input v-model="adminData.nom" id="nom" type="text" class="mt-1 block w-full" required />
            </div>
            <div class="mb-4">
                <label for="descripcio" class="block text-sm font-medium text-gray-700">Descripció</label>
                <textarea v-model="adminData.descripcio" id="descripcio" class="mt-1 block w-full" required></textarea>
            </div>
            <div class="mb-4">
                <label for="telefon" class="block text-sm font-medium text-gray-700">Telèfon</label>
                <input v-model="adminData.telefon" id="telefon" type="text" class="mt-1 block w-full" required />
            </div>
            <div class="mb-4">
                <label for="tipus_cuina" class="block text-sm font-medium text-gray-700">Tipus de Cuina</label>
                <select v-model="adminData.tipus_cuina" id="tipus_cuina" class="mt-1 block w-full" required>
                    <option v-for="option in tipusCuinaOptions" :key="option" :value="option">{{ option }}</option>
                </select>
            </div>


            <div class="mb-4">
                <label for="hora_obertura" class="block text-sm font-medium text-gray-700">Hora d'Obertura</label>
                <input step="any" v-model="adminData.hora_obertura" id="hora_obertura" type="time" class="mt-1 block w-full" required />
            </div>
            <div class="mb-4">
                <label for="hora_tancament" class="block text-sm font-medium text-gray-700">Hora de Tancament</label>
                <input step="any" v-model="adminData.hora_tancament" id="hora_tancament" type="time" class="mt-1 block w-full" required />
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                Guardar canvis
            </button>
        </form>
    </div>
</template>

<script setup>
import { Inertia } from '@inertiajs/inertia';
import { route } from 'ziggy-js';

const props = defineProps({
    adminData: Object,
    tipusCuinaOptions: Array,
});

const submitAdminForm = () => {
    console.log('adminData.tipus_cuina:', props.adminData.tipus_cuina); // Log the value

    const updateUrl = route('restaurants.update', { restaurant: props.adminData.id });

    Inertia.put(updateUrl, {
        nom: props.adminData.nom,
        descripcio: props.adminData.descripcio,
        telefon: props.adminData.telefon,
        tipus_cuina: props.adminData.tipus_cuina,
        hora_obertura: props.adminData.hora_obertura,
        hora_tancament: props.adminData.hora_tancament,
    }, {
        preserveState: true,
        onSuccess: () => {
            props.adminData = response.props.adminData;
            console.log('Restaurant updated successfully');
        },
        onError: (errors) => {
            console.error('Error updating restaurant data:', errors);
        }
    });
};
</script>
