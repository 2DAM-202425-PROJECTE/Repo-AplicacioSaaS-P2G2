<template>
    <Modal v-slot="{ close }" max-width="xl" panel-classes="edit-modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon">✏️</div>
                <h2 class="modal-title">Modificar Reserva</h2>
            </div>

            <form @submit.prevent="() => updateReserva(close)" class="modal-form">
                <div class="form-body">
                    <div class="form-group">
                        <label for="estat" class="form-label">Estat de la Reserva</label>
                        <div class="status-selector">
                            <label
                                class="status-option"
                                :class="{ 'active': form.estat === 0 }"
                                @click="form.estat = 0"
                            >
                                <input type="radio" v-model="form.estat" :value="0" class="status-input" />
                                <div class="status-icon pending-icon">⏳</div>
                                <div class="status-text">Pendent</div>
                            </label>

                            <label
                                class="status-option"
                                :class="{ 'active': form.estat === 1 }"
                                @click="form.estat = 1"
                            >
                                <input type="radio" v-model="form.estat" :value="1" class="status-input" />
                                <div class="status-icon confirmed-icon">✅</div>
                                <div class="status-text">Confirmada</div>
                            </label>

                            <label
                                class="status-option"
                                :class="{ 'active': form.estat === 2 }"
                                @click="form.estat = 2"
                            >
                                <input type="radio" v-model="form.estat" :value="2" class="status-input" />
                                <div class="status-icon cancelled-icon">❌</div>
                                <div class="status-text">Cancel·lada</div>
                            </label>

                            <label
                                class="status-option"
                                :class="{ 'active': form.estat === 3 }"
                                @click="form.estat = 3"
                            >
                                <input type="radio" v-model="form.estat" :value="3" class="status-input" />
                                <div class="status-icon completed-icon">🏁</div>
                                <div class="status-text">Completada</div>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="telefon" class="form-label">Telèfon de contacte</label>
                        <div class="input-container">
                            <span class="input-icon">📞</span>
                            <input
                                type="text"
                                v-model="form.telefon"
                                id="telefon"
                                class="form-input"
                                required
                            />
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group half">
                            <label for="data" class="form-label">Data</label>
                            <div class="input-container">
                                <span class="input-icon">📅</span>
                                <input
                                    type="date"
                                    v-model="form.data"
                                    id="data"
                                    class="form-input"
                                    required
                                />
                            </div>
                        </div>

                        <div class="form-group half">
                            <label for="hora" class="form-label">Hora</label>
                            <div class="input-container">
                                <span class="input-icon">🕒</span>
                                <input
                                    type="time"
                                    v-model="form.hora"
                                    id="hora"
                                    class="form-input"
                                    required
                                />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="num_persones" class="form-label">Número de Persones</label>
                        <div class="input-container">
                            <span class="input-icon">👥</span>
                            <input
                                type="number"
                                v-model="form.num_persones"
                                id="num_persones"
                                class="form-input"
                                min="1"
                                max="20"
                                required
                            />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ubicació</label>
                        <div class="toggle-options">
                            <label class="toggle-option" :class="{ 'active': !form.terrassa }">
                                <input
                                    type="radio"
                                    v-model="form.terrassa"
                                    :value="false"
                                    class="toggle-input"
                                />
                                <span class="toggle-icon">🏠</span>
                                <span class="toggle-text">Interior</span>
                            </label>

                            <label class="toggle-option" :class="{ 'active': form.terrassa }">
                                <input
                                    type="radio"
                                    v-model="form.terrassa"
                                    :value="true"
                                    class="toggle-input"
                                />
                                <span class="toggle-icon">☀️</span>
                                <span class="toggle-text">Terrassa</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="solicituds" class="form-label">Sol·licituds Especials</label>
                        <textarea
                            v-model="form.solicituds"
                            id="solicituds"
                            class="form-textarea"
                            placeholder="Indica'ns qualsevol sol·licitud especial (al·lèrgies, celebracions, etc.)"
                        ></textarea>
                    </div>

                    <div class="form-group">
                        <label for="notes_restaurant" class="form-label">Notes Internes (només visibles pel restaurant)</label>
                        <textarea
                            v-model="form.notes_restaurant"
                            id="notes_restaurant"
                            class="form-textarea"
                            placeholder="Afegeix notes internes sobre aquesta reserva"
                        ></textarea>
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
                        type="submit"
                        class="confirm-action"
                        :disabled="isSubmitting"
                    >
                        <span v-if="isSubmitting" class="loading-spinner"></span>
                        <span v-else>Guardar Canvis</span>
                    </button>
                </div>
            </form>
        </div>
    </Modal>
</template>

<script setup>
import { defineProps, reactive, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { Modal } from '@inertiaui/modal-vue';
import { route } from 'ziggy-js';

const props = defineProps({
    reserva: {
        type: Object,
        required: true,
    },
});

const form = reactive({...props.reserva});
const isSubmitting = ref(false);

const updateReserva = (close) => {
    isSubmitting.value = true;

    router.put(route('reserves.update', {id: form.id}), form, {
        onSuccess: () => {
            close();
            router.reload();
        },
        onError: (errors) => {
            console.error('Error updating reserva:', errors);
            isSubmitting.value = false;
        },
    });
};
</script>

<style scoped>
.edit-modal {
    background-color: white;
    border-radius: 16px;
    overflow: hidden;
}

.modal-content {
    padding: 0;
}

.modal-header {
    background-color: #e3f2fd;
    padding: 25px;
    text-align: center;
    border-bottom: 1px solid #bbdefb;
}

.modal-icon {
    font-size: 3rem;
    margin-bottom: 15px;
}

.modal-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: #1976d2;
    margin: 0;
}

.form-body {
    padding: 25px;
}

.form-group {
    margin-bottom: 20px;
}

.form-row {
    display: flex;
    gap: 15px;
}

.form-group.half {
    flex: 1;
}

.form-label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #555;
}

.status-selector {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 10px;
    margin-bottom: 10px;
}

.status-option {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 15px 10px;
    border: 2px solid #eee;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.status-option.active {
    border-color: currentColor;
    background-color: rgba(0, 0, 0, 0.03);
}

.status-input {
    position: absolute;
    opacity: 0;
}

.status-icon {
    font-size: 2rem;
    margin-bottom: 10px;
}

.pending-icon {
    color: #f57f17;
}

.confirmed-icon {
    color: #2e7d32;
}

.cancelled-icon {
    color: #c62828;
}

.completed-icon {
    color: #1565c0;
}

.status-text {
    font-weight: 600;
    font-size: 0.9rem;
}

.input-container {
    position: relative;
}

.input-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 1rem;
}

.form-input, .form-textarea {
    width: 100%;
    padding: 12px 12px 12px 40px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-textarea {
    min-height: 100px;
    resize: vertical;
    padding: 12px;
}

.form-input:focus, .form-textarea:focus {
    border-color: #1976d2;
    box-shadow: 0 0 0 2px rgba(25, 118, 210, 0.2);
    outline: none;
}

.toggle-options {
    display: flex;
    gap: 10px;
}

.toggle-option {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.toggle-option.active {
    border-color: #1976d2;
    background-color: rgba(25, 118, 210, 0.05);
}

.toggle-input {
    position: absolute;
    opacity: 0;
}

.toggle-icon {
    font-size: 1.5rem;
    margin-bottom: 8px;
}

.toggle-text {
    font-weight: 600;
    color: #555;
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
    background-color: #1976d2;
    color: white;
}

.confirm-action:hover:not(:disabled) {
    background-color: #1565c0;
}

.confirm-action:disabled {
    background-color: #90caf9;
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

@media (max-width: 768px) {
    .status-selector {
        grid-template-columns: repeat(2, 1fr);
    }

    .form-row {
        flex-direction: column;
        gap: 20px;
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
