<template>
    <div>
        <h2 class="text-xl font-bold mb-4">Edit Restaurant</h2>
        <form @submit.prevent="submitEditForm">
            <!-- Nom -->
            <div class="mb-4">
                <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                <input v-model="form.nom" id="nom" type="text" class="mt-1 block w-full" required />
                <span v-if="errors.nom" class="text-red-500 text-sm">{{ errors.nom }}</span>
            </div>


            <!-- Descripcio -->
            <div class="mb-4">
                <label for="descripcio" class="block text-sm font-medium text-gray-700">Descripció</label>
                <textarea v-model="form.descripcio" id="descripcio" class="mt-1 block w-full" required></textarea>
                <span v-if="errors.descripcio" class="text-red-500 text-sm">{{ errors.descripcio }}</span>
            </div>

            <!-- Telefon -->
            <div class="mb-4">
                <label for="telefon" class="block text-sm font-medium text-gray-700">Telèfon</label>
                <input v-model="form.telefon" id="telefon" type="text" class="mt-1 block w-full" required />
                <span v-if="errors.telefon" class="text-red-500 text-sm">{{ errors.telefon }}</span>
            </div>

            <!-- Imatge de perfil -->
            <div class="mb-4">
                <label for="profile_image" class="block text-sm font-medium text-gray-700">Imatge de Perfil</label>
                <input id="profile_image" type="file" class="mt-1 block w-full" @change="handleFileUpload" />
                <span v-if="errors.profile_image" class="text-red-500 text-sm">{{ errors.profile_image }}</span>
            </div>

            <!-- Tipus de Cuina (Dropdown) -->
            <div class="mb-4">
                <label for="tipus_cuina" class="block text-sm font-medium text-gray-700">Tipus de Cuina</label>
                <select v-model="form.tipus_cuina" id="tipus_cuina" class="mt-1 block w-full" required>
                    <option v-for="option in tipusCuinaOptions" :key="option" :value="option">{{ option }}</option>
                </select>
                <span v-if="errors.tipus_cuina" class="text-red-500 text-sm">{{ errors.tipus_cuina }}</span>
            </div>

            <!-- Hora Obertura -->
            <div class="mb-4">
                <label for="hora_obertura" class="block text-sm font-medium text-gray-700">Hora d'Obertura</label>
                <input step="any" v-model="form.hora_obertura" id="hora_obertura" type="time" class="mt-1 block w-full" required />
                <span v-if="errors.hora_obertura" class="text-red-500 text-sm">{{ errors.hora_obertura }}</span>
            </div>

            <!-- Hora Tancament -->
            <div class="mb-4">
                <label for="hora_tancament" class="block text-sm font-medium text-gray-700">Hora de Tancament</label>
                <input step="any" v-model="form.hora_tancament" id="hora_tancament" type="time" class="mt-1 block w-full" required />
                <span v-if="errors.hora_tancament" class="text-red-500 text-sm">{{ errors.hora_tancament }}</span>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                Update Restaurant
            </button>
        </form>
    </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { route } from 'ziggy-js';

const props = defineProps({
    restaurant: Object,
    tipusCuinaOptions: Array,
});

const form = reactive({
    nom: props.restaurant.nom || '',
    descripcio: props.restaurant.descripcio || '',
    telefon: props.restaurant.telefon || '',
    profile_image: null,
    tipus_cuina: props.restaurant.tipus_cuina || '',
    hora_obertura: props.restaurant.hora_obertura || '',
    hora_tancament: props.restaurant.hora_tancament || ''
});

const errors = ref({});

const handleFileUpload = (event) => {
    form.profile_image = event.target.files[0];
};

const submitEditForm = () => {
    const formData = new FormData();

    formData.append('nom', form.nom);
    formData.append('descripcio', form.descripcio);
    formData.append('telefon', form.telefon);
    formData.append('tipus_cuina', form.tipus_cuina);
    formData.append('hora_obertura', form.hora_obertura);
    formData.append('hora_tancament', form.hora_tancament);

    if (form.profile_image) {
        formData.append('profile_image', form.profile_image);
    }

    Inertia.post(route('restaurants.update', props.restaurant.id), formData, {
        method: 'post', // Important per evitar conflictes amb PUT i fitxers
        forceFormData: true, // força Inertia a enviar-ho com multipart/form-data
        onSuccess: () => {
            console.log('Restaurant updated successfully');
        },
        onError: (errorData) => {
            errors.value = errorData.errors;
            console.error('Error updating the restaurant:', errorData.errors);
        },
    });
};

</script>
