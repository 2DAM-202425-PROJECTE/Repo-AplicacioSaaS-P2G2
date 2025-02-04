<template>
    <div>
        <h2 class="text-xl font-bold mb-4">Create Restaurant</h2>
        <form @submit.prevent="submitCreateForm">
            <!-- Nom -->
            <div class="mb-4">
                <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                <input v-model="form.nom" id="nom" type="text" class="mt-1 block w-full" required />
            </div>

            <!-- Descripcio -->
            <div class="mb-4">
                <label for="descripcio" class="block text-sm font-medium text-gray-700">Descripció</label>
                <textarea v-model="form.descripcio" id="descripcio" class="mt-1 block w-full" required></textarea>
            </div>

            <!-- Telefon -->
            <div class="mb-4">
                <label for="telefon" class="block text-sm font-medium text-gray-700">Telèfon</label>
                <input v-model="form.telefon" id="telefon" type="text" class="mt-1 block w-full" required />
            </div>

            <!-- Tipus de Cuina (Dropdown) -->
            <div class="mb-4">
                <label for="tipus_cuina" class="block text-sm font-medium text-gray-700">Tipus de Cuina</label>
                <select v-model="form.tipus_cuina" id="tipus_cuina" class="mt-1 block w-full" required>
                    <option v-for="option in tipusCuinaOptions" :key="option" :value="option">{{ option }}</option>
                </select>
            </div>

            <!-- Hora Obertura -->
            <div class="mb-4">
                <label for="hora_obertura" class="block text-sm font-medium text-gray-700">Hora d'Obertura</label>
                <input step="any" v-model="form.hora_obertura" id="hora_obertura" type="time" class="mt-1 block w-full" required />
            </div>

            <!-- Hora Tancament -->
            <div class="mb-4">
                <label for="hora_tancament" class="block text-sm font-medium text-gray-700">Hora de Tancament</label>
                <input step="any" v-model="form.hora_tancament" id="hora_tancament" type="time" class="mt-1 block w-full" required />
            </div>

            <!-- Submit Button -->
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                Create Restaurant
            </button>
        </form>
    </div>
</template>

<script setup>
import { reactive } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { route } from 'ziggy-js';

const props = defineProps({
    tipusCuinaOptions: Array,
});

const form = reactive({
    nom: '',
    descripcio: '',
    telefon: '',
    tipus_cuina: '',
    hora_obertura: '',
    hora_tancament: '',
});

const submitCreateForm = () => {
    const newRestaurant = {...form};

    Inertia.post(route('restaurants.store'), newRestaurant, {
        onSuccess: () => {
            console.log('Restaurant created successfully');
        },
        onError: (errors) => {
            console.error('Error creating the restaurant:', errors);
        },
    });
};
</script>
