<template>
    <Modal v-slot="{ close }" max-width="xl" panel-classes="bg-white rounded-lg">
        <h1 class="text-2xl font-bold mb-4">Cancel·lar Reserva</h1>
        <div class="p-6">
            <p class="text-gray-700 mb-4">
                Estàs segur que vols cancel·lar aquesta reserva?
            </p>
            <p class="text-sm text-red-600 font-semibold mb-4">
                Aquesta acció és irreversible.
            </p>
            <div class="flex justify-end">
                <button type="button" @click="close"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">Ometre</button>
                <button type="button" @click="cancelReserva(close)"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Cancel·lar</button>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import { defineProps } from 'vue';
import { router } from '@inertiajs/vue3';
import { Modal } from '@inertiaui/modal-vue';

const props = defineProps({
    reservaId: {
        type: Number,
        required: true,
    },
});

const cancelReserva = (close) => {
    close(); // Close immediately

    router.post(route('user.reserves.cancelConfirm', { id: props.reservaId }), {}, {
        onSuccess: () => {
            router.reload();
        },
        onError: (errors) => {
            console.error('Error cancelling reserva:', errors);
        },
        preserveScroll: true,
    });
};
</script>

<style>

</style>
