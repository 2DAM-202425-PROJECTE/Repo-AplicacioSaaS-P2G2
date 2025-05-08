<template>
    <Modal v-slot="{ close }" max-width="xl" panel-classes="bg-white rounded-lg">
        <h1 class="text-2xl font-bold mb-4">Modificar Reserva</h1>
        <form @submit.prevent="() => updateReserva(close)">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="telefon">Telèfon:</label>
                <input id="telefon" v-model="form.telefon" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                       type="text"/>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="data">Data:</label>
                <input id="data" v-model="form.data" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                       type="date"/>
            </div>
            <div class="mb-4">


                <label class="block text-sm font-medium text-gray-700" for="hora">Hora:</label>
                <input id="hora" v-model="form.hora" class="mt-1 block w-full" required type="time"/>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="num_persones">Persones:</label>
                <input id="num_persones" v-model="form.num_persones" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                       type="number"/>
            </div>

            <div>
                <label class="inline-flex items-center">
                    <input v-model="form.terrassa" :value="true" class="form-radio" type="radio">
                    <span class="ml-2">Terrassa</span>
                </label>
                <label class="inline-flex items-center ml-6">
                    <input v-model="form.terrassa" :value="false" class="form-radio" type="radio">
                    <span class="ml-2">Interior</span>
                </label>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="solicituds"> Sol·licituds </label>
                <textarea id="solicituds" v-model="form.solicituds" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                          type="text"/>
            </div>


            <div class="flex justify-end">
                <button class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2" type="button"
                        @click="close">Cancel·lar
                </button>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">
                    Confirmar
                </button>
            </div>
        </form>
    </Modal>
</template>

<script setup>
import {defineProps, reactive} from 'vue';
import {router} from '@inertiajs/vue3';
import {Modal} from '@inertiaui/modal-vue';
import TextInput from "@/Components/TextInput.vue";

const props = defineProps({
    reserva: {
        type: Object,
        required: true,
    },
});

const form = reactive({...props.reserva});

const updateReserva = (close) => {
    router.put(route('user.reserves.update', {id: form.id}), form, {
        onSuccess: () => {
            close();
        },
        onError: (errors) => {
            console.error('Error updating reserva:', errors);
        },
    });
};
</script>

<style>


</style>
