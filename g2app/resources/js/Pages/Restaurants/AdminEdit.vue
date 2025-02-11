<template>
    <div class="max-w-4xl mx-auto bg-[#1B1B1B] p-8 rounded-lg shadow-lg border border-[#3D3D3D] text-white">
        <h2 class="text-3xl font-extrabold text-gold-500 mb-6 border-b border-[#3D3D3D] pb-4">Personalitzar Restaurant</h2>
        <form @submit.prevent="submitAdminForm" class="space-y-6">
            <div>
                <label for="nom" class="block text-lg font-medium text-gold-400">Nom</label>
                <input v-model="adminData.nom" id="nom" type="text" class="input-field" required />
            </div>
            <div>
                <label for="descripcio" class="block text-lg font-medium text-gold-400">Descripció</label>
                <textarea v-model="adminData.descripcio" id="descripcio" class="input-field" required></textarea>
            </div>
            <div>
                <label for="telefon" class="block text-lg font-medium text-gold-400">Telèfon</label>
                <input v-model="adminData.telefon" id="telefon" type="text" class="input-field" required />
            </div>
            <div>
                <label for="tipus_cuina" class="block text-lg font-medium text-gold-400">Tipus de Cuina</label>
                <select v-model="adminData.tipus_cuina" id="tipus_cuina" class="input-field" required>
                    <option v-for="option in tipusCuinaOptions" :key="option" :value="option">{{ option }}</option>
                </select>
            </div>
            <div>
                <label for="hora_obertura" class="block text-lg font-medium text-gold-400">Hora d'Obertura</label>
                <input step="any" v-model="adminData.hora_obertura" id="hora_obertura" type="time" class="input-field" required />
            </div>
            <div>
                <label for="hora_tancament" class="block text-lg font-medium text-gold-400">Hora de Tancament</label>
                <input step="any" v-model="adminData.hora_tancament" id="hora_tancament" type="time" class="input-field" required />
            </div>
            <button type="submit" class="bg-gold-500 hover:bg-gold-600 text-white font-semibold px-6 py-3 rounded-lg shadow-lg transition-all duration-300 w-full">
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
    console.log('adminData.tipus_cuina:', props.adminData.tipus_cuina);

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

<style scoped>
.input-field {
    margin-top: 0.5rem;
    display: block;
    width: 100%;
    background-color: #2A2A2A;
    border: 1px solid #3D3D3D;
    border-radius: 0.5rem;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    padding: 0.75rem;
    font-size: 1.125rem;
    color: #FFFFFF;
    transition: box-shadow 0.2s;
}
.input-field:focus {
    box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.5);
}
.text-gold-500 {
    color: #FFD700;
}
.text-gold-400 {
    color: #FFC300;
}
.bg-gold-500 {
    background-color: #FFD700;
}
</style>
