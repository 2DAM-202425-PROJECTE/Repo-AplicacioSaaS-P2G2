<template>
    <Modal v-slot="{ close }" max-width="xl" panel-classes="delete-modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon">🗑️</div>
                <h2 class="modal-title">Eliminar Reserva</h2>
            </div>

            <div class="modal-body">
                <p class="modal-message">
                    Estàs segur que vols eliminar aquesta reserva?
                </p>

                <div class="reservation-details">
                    <div class="detail-item">
                        <div class="detail-icon">📅</div>
                        <div class="detail-content">
                            <div class="detail-label">Data</div>
                            <div class="detail-value">{{ formatDate(reserva.data) }}</div>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">🕒</div>
                        <div class="detail-content">
                            <div class="detail-label">Hora</div>
                            <div class="detail-value">{{ formatHour(reserva.hora) }}</div>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">📞</div>
                        <div class="detail-content">
                            <div class="detail-label">Telèfon</div>
                            <div class="detail-value">{{ reserva.telefon }}</div>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">👥</div>
                        <div class="detail-content">
                            <div class="detail-label">Persones</div>
                            <div class="detail-value">{{ reserva.num_persones }}</div>
                        </div>
                    </div>
                </div>

                <div class="warning-box">
                    <div class="warning-icon">⚠️</div>
                    <p class="warning-text">
                        <strong>Atenció:</strong> Aquesta acció és irreversible. La reserva serà eliminada permanentment del sistema.
                    </p>
                </div>
            </div>

            <div class="modal-actions">
                <button
                    type="button"
                    @click="close"
                    class="cancel-action"
                >
                    Cancel·lar
                </button>
                <button
                    type="button"
                    @click="deleteReserva(close)"
                    class="confirm-action"
                    :disabled="isSubmitting"
                >
                    <span v-if="isSubmitting" class="loading-spinner"></span>
                    <span v-else>Eliminar Reserva</span>
                </button>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import { defineProps, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { Modal } from '@inertiaui/modal-vue';
import { route } from 'ziggy-js';

const props = defineProps({
    reserva: {
        type: Object,
        required: true,
    },
});

const isSubmitting = ref(false);

const formatDate = (dateString) => {
    const date = new Date(dateString);
    const day = date.getDate().toString().padStart(2, '0');
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
};

const formatHour = (timeString) => {
    const time = new Date(`1970-01-01T${timeString}`);
    const hours = time.getHours().toString().padStart(2, '0');
    const minutes = time.getMinutes().toString().padStart(2, '0');
    return `${hours}:${minutes}`;
};

const deleteReserva = (close) => {
    isSubmitting.value = true;

    router.delete(route('reserves.destroy', {id: props.reserva.id}), {
        onSuccess: () => {
            close();
            router.reload();
        },
        onError: (errors) => {
            console.error('Error deleting reserva:', errors);
            isSubmitting.value = false;
        },
    });
};
</script>

<style scoped>
.delete-modal {
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

.reservation-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    background-color: #f9f9f9;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
}

.detail-item {
    display: flex;
    align-items: flex-start;
}

.detail-icon {
    width: 40px;
    height: 40px;
    background-color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    margin-right: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
}

.detail-label {
    font-size: 0.8rem;
    color: #666;
    margin-bottom: 3px;
}

.detail-value {
    font-weight: 600;
    color: #333;
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
    to {
        transform: rotate(360deg);
    }
}

@media (max-width: 768px) {
    .reservation-details {
        grid-template-columns: 1fr;
    }

    .modal-actions {
        flex-direction: column-reverse;
        gap: 10px;
    }

    .cancel-action, .confirm-action {
        width: 100%;
    }
}
</style>
