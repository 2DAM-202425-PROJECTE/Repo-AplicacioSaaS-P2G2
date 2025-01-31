<template>
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Crear Restaurant</h1>
        <form @submit.prevent="submitForm">
            <div class="mb-4">
                <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                <input type="text" v-model="form.nom" id="nom" class="mt-1 block w-full" required>
            </div>
            <div class="mb-4">
                <label for="descripcio" class="block text-sm font-medium text-gray-700">Descripció</label>
                <textarea v-model="form.descripcio" id="descripcio" class="mt-1 block w-full" required></textarea>
            </div>
            <div class="mb-4">
                <label for="telefon" class="block text-sm font-medium text-gray-700">Telèfon</label>
                <input type="text" v-model="form.telefon" id="telefon" class="mt-1 block w-full" required>
            </div>
            <div class="mb-4">
                <label for="tipus_cuina" class="block text-sm font-medium text-gray-700">Tipus de Cuina</label>
                <input type="text" v-model="form.tipus_cuina" id="tipus_cuina" class="mt-1 block w-full" required>
            </div>
            <div class="mb-4">
                <label for="hora_obertura" class="block text-sm font-medium text-gray-700">Hora Obertura</label>
                <input type="time" v-model="form.hora_obertura" id="hora_obertura" class="mt-1 block w-full" required step="60">
            </div>
            <div class="mb-4">
                <label for="hora_tancament" class="block text-sm font-medium text-gray-700">Hora Tancament</label>
                <input type="time" v-model="form.hora_tancament" id="hora_tancament" class="mt-1 block w-full" required step="60">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Crear</button>
        </form>
    </div>
</template>

<script setup>
import { reactive } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const form = reactive({
    nom: '',
    descripcio: '',
    telefon: '',
    tipus_cuina: '',
    hora_obertura: '',
    hora_tancament: '',
});

const submitForm = () => {
    Inertia.post(route('restaurants.store'), form, {
        onSuccess: () => {
            console.log('Restaurant creat amb èxit');
        },
        onError: (errors) => {
            console.error('Error creant el restaurant:', errors);
        }
    });
};
</script>
