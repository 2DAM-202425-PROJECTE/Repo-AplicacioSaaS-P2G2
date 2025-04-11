<template>
    <Modal  v-slot="{ close }" max-width="xl" panel-classes="bg-white rounded-lg" >
        <h1 class="text-2xl font-bold mb-4">Modificar Reserva</h1>
        <form @submit.prevent="() => updateReserva(close)">
            <div class="mb-4">
                <label for="telefon" class="block text-sm font-medium text-gray-700">Telèfon:</label>
                <input type="text" v-model="form.telefon" id="telefon"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
            </div>
            <div class="mb-4">
                <label for="data" class="block text-sm font-medium text-gray-700">Data:</label>
                <input type="date" v-model="form.data" id="data"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
            </div>
            <div class="mb-4">


                <label for="hora" class="block text-sm font-medium text-gray-700">Hora:</label>
                <input v-model="form.hora" id="hora" type="time" class="mt-1 block w-full" required />
            </div>

            <div class="mb-4">
                <label for="num_persones" class="block text-sm font-medium text-gray-700">Persones:</label>
                <input type="number" v-model="form.num_persones" id="num_persones"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
            </div>

            <div class="mb-4">
                <label for="estat" class="block text-sm font-medium text-gray-700">Estat:</label>
                <select v-model="form.estat" id="estat" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option :value="0">Pendent</option>
                    <option :value="1">Confirmada</option>
                    <option :value="2">Cancel·lada</option>
                    <option :value="3">Completada</option>
                </select>
            </div>

            <div class="flex justify-end">
                <button type="button" @click="close"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">Cancel·lar</button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Confirmar</button>
            </div>
        </form>
    </Modal>
</template>

<script setup>
import { defineProps, reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import { Modal } from '@inertiaui/modal-vue';

const props = defineProps({
    reserva: {
        type: Object,
        required: true,
    },
});

const form = reactive({ ...props.reserva });

const updateReserva = (close) => {
    router.put(route('reserves.update', { id: form.id }), form, {
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
/* Canviar color fons del modal d'edició */
.im-backdrop {
    background-color: rgba(0, 0, 0, 0.5)

}
.im-modal-wrapper {

    transition-duration: 0.2s;
}

</style>
