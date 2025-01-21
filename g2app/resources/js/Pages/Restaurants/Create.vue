<template>
    <layout>
        <form @submit.prevent="submit" class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-md">
            <div class="mb-4">
                <label for="nom" class="block text-gray-700 font-bold mb-2">Nom</label>
                <input v-model="form.nom" id="nom" type="text" class="form-input w-full border border-gray-300 p-2 rounded" />
            </div>
            <div class="mb-4">
                <label for="horari" class="block text-gray-700 font-bold mb-2">Horari</label>
                <input v-model="form.horari" id="horari" type="text" class="form-input w-full border border-gray-300 p-2 rounded" />
            </div>
            <div class="mb-4">
                <label for="descripcio" class="block text-gray-700 font-bold mb-2">Descripció</label>
                <textarea v-model="form.descripcio" id="descripcio" class="form-textarea w-full border border-gray-300 p-2 rounded"></textarea>
            </div>
            <div class="mb-4">
                <label for="telefon" class="block text-gray-700 font-bold mb-2">Telèfon</label>
                <input v-model="form.telefon" id="telefon" type="text" class="form-input w-full border border-gray-300 p-2 rounded" />
            </div>
            <div class="mb-4">
                <label for="tipus_cuina" class="block text-gray-700 font-bold mb-2">Tipus de Cuina</label>
                <select v-model="form.tipus_cuina" id="tipus_cuina" class="form-select w-full border border-gray-300 p-2 rounded">
                    <option v-for="option in tipusCuinaOptions" :key="option" :value="option">
                        {{ option }}
                    </option>
                </select>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">Crear Restaurant</button>
        </form>
    </layout>
</template>

<script>
import Layout from '@/Layouts/Layout.vue';
import { ref } from 'vue';

export default {
    components: {
        Layout,
    },
    props: {
        tipusCuinaOptions: Array,
    },
    setup(props) {
        const form = ref({
            nom: '',
            horari: '',
            descripcio: '',
            telefon: '',
            tipus_cuina: '',
        });

        const submit = () => {
            // Logic to submit the form
        };

        return {
            form,
            tipusCuinaOptions: props.tipusCuinaOptions,
            submit,
        };
    },
};
</script>
