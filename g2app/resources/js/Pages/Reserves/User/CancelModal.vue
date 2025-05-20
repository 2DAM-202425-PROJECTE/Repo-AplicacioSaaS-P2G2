<template>
    <Modal v-slot="{ close }" max-width="xl" panel-classes="cancel-modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon">⚠️</div>
                <h2 class="modal-title">Cancel·lar Reserva</h2>
            </div>

            <div class="modal-body">
                <p class="modal-message">
                    Estàs segur que vols cancel·lar aquesta reserva?
                </p>
                <div class="warning-box">
                    <div class="warning-icon">⚠️</div>
                    <p class="warning-text">
                        Aquesta acció és irreversible. Un cop cancel·lada la reserva, hauràs de fer-ne una de nova si vols tornar a reservar.
                    </p>
                </div>
            </div>

            <div class="modal-actions">
                <button
                    type="button"
                    @click="close"
                    class="cancel-action"
                >
                    Tornar Enrere
                </button>
                <button
                    type="button"
                    @click="cancelReserva(close)"
                    class="confirm-action"
                    :disabled="isSubmitting"
                >
                    <span v-if="isSubmitting" class="loading-spinner"></span>
                    <span v-else>Confirmar Cancel·lació</span>
                </button>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import { defineProps, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { Modal } from '@inertiaui/modal-vue';
import { route } from 'vue-router'; // Import the route function

const props = defineProps({
    reservaId: {
        type: Number,
        required: true,
    },
});

const isSubmitting = ref(false);

const cancelReserva = (close) => {
    isSubmitting.value = true;

    router.post(route('user.reserves.cancelConfirm', { id: props.reservaId }), {}, {
        onSuccess: () => {
            close();
            router.reload();
        },
        onError: (errors) => {
            console.error('Error cancelling reserva:', errors);
            isSubmitting.value = false;
        },
        preserveScroll: true,
    });
};
</script>

<style scoped>
.cancel-modal {
    background-color: white;
    border-radius: 16px;
    overflow: hidden;
}

.modal-content {
    padding: 0;
}

.modal-header {
    background-color: #ffebee;
    padding: 25px;
    text-align: center;
    border-bottom: 1px solid #ffcdd2;
}

.modal-icon {
    font-size: 3rem;
    margin-bottom: 15px;
}

.modal-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: #c62828;
    margin: 0;
}

.modal-body {
    padding: 25px;
}

.modal-message {
    font-size: 1.1rem;
    color: #333;
    margin-bottom: 20px;
    text-align: center;
}

.warning-box {
    background-color: #fff8e1;
    border-radius: 8px;
    padding: 15px;
    display: flex;
    align-items: flex-start;
}

.warning-icon {
    font-size: 1.5rem;
    margin-right: 10px;
    color: #f57f17;
}

.warning-text {
    color: #555;
    font-size: 0.9rem;
    line-height: 1.5;
    margin: 0;
}

.modal-actions {
    display: flex;
    justify-content: space-between;
    padding: 20px 25px;
    background-color: #f9f9f9;
    border-top: 1px solid #eee;
}

.cancel-action, .confirm-action {
    padding: 12px 20px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
}

.cancel-action {
    background-color: #f1f1f1;
    color: #555;
}

.cancel-action:hover {
    background-color: #e5e5e5;
}

.confirm-action {
    background-color: #c62828;
    color: white;
}

.confirm-action:hover:not(:disabled) {
    background-color: #b71c1c;
}

.confirm-action:disabled {
    background-color: #ef9a9a;
    cursor: not-allowed;
}

.loading-spinner {
    width: 20px;
    height: 20px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 1s linear infinite;
    margin-right: 10px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

@media (max-width: 576px) {
    .modal-actions {
        flex-direction: column-reverse;
        gap: 10px;
    }

    .cancel-action, .confirm-action {
        width: 100%;
    }
}
</style>
