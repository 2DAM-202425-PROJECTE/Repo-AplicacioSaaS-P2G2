<template>
    <layout>
        <div class="user-reservations-page">
            <div class="container">
                <div class="page-header">
                    <h1 class="page-title">Les Meves Reserves</h1>
                    <p class="page-subtitle">Gestiona les teves reserves de restaurants</p>
                </div>

                <div class="reservations-container">
                    <div class="reservations-tabs">
                        <button
                            class="tab-button"
                            :class="{ 'active': activeTab === 'all' }"
                            @click="activeTab = 'all'"
                        >
                            Totes
                        </button>
                        <button
                            class="tab-button"
                            :class="{ 'active': activeTab === 'pending' }"
                            @click="activeTab = 'pending'"
                        >
                            Pendents
                        </button>
                        <button
                            class="tab-button"
                            :class="{ 'active': activeTab === 'confirmed' }"
                            @click="activeTab = 'confirmed'"
                        >
                            Confirmades
                        </button>
                        <button
                            class="tab-button"
                            :class="{ 'active': activeTab === 'completed' }"
                            @click="activeTab = 'completed'"
                        >
                            Completades
                        </button>
                        <button
                            class="tab-button"
                            :class="{ 'active': activeTab === 'cancelled' }"
                            @click="activeTab = 'cancelled'"
                        >
                            Cancel·lades
                        </button>
                    </div>

                    <div v-if="filteredReserves.length > 0" class="reservations-list">
                        <div v-for="reserva in filteredReserves" :key="reserva.id" class="reservation-card">
                            <div class="reservation-header">
                                <div class="restaurant-info">
                                    <img :src="`/storage/${reserva.restaurant.profile_image}`" alt="Restaurant" class="restaurant-image" />
                                    <div class="restaurant-details">
                                        <h3 class="restaurant-name">{{ reserva.restaurant.nom }}</h3>
                                        <p class="restaurant-type">{{ reserva.restaurant.tipus_cuina }}</p>
                                    </div>
                                </div>
                                <div class="reservation-status" :class="getStatusClass(reserva.estat)">
                                    {{ getStatusText(reserva.estat) }}
                                </div>
                            </div>

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
                                    <div class="detail-icon">👥</div>
                                    <div class="detail-content">
                                        <div class="detail-label">Persones</div>
                                        <div class="detail-value">{{ reserva.num_persones }}</div>
                                    </div>
                                </div>

                                <div class="detail-item">
                                    <div class="detail-icon">{{ reserva.terrassa ? '☀️' : '🏠' }}</div>
                                    <div class="detail-content">
                                        <div class="detail-label">Ubicació</div>
                                        <div class="detail-value">{{ reserva.terrassa ? 'Terrassa' : 'Interior' }}</div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="reserva.solicituds" class="reservation-notes">
                                <div class="notes-label">Sol·licituds especials:</div>
                                <div class="notes-content">{{ reserva.solicituds }}</div>
                            </div>

                            <div class="reservation-actions">
                                <Link :href="route('restaurants.show', { id: reserva.restaurant.id })" class="action-button view-button">
                                    <span class="button-icon">👁️</span>
                                    <span>Veure Restaurant</span>
                                </Link>

                                <div class="action-buttons">
                                    <ModalLink
                                        v-if="canEdit(reserva.estat)"
                                        :href="route('user.reserves.edit', { id: reserva.id })"
                                        class="action-button edit-button"
                                    >
                                        <span class="button-icon">✏️</span>
                                        <span>Modificar</span>
                                    </ModalLink>

                                    <ModalLink
                                        v-if="canCancel(reserva.estat)"
                                        :href="route('user.reserves.cancel', { id: reserva.id })"
                                        class="action-button cancel-button"
                                    >
                                        <span class="button-icon">❌</span>
                                        <span>Cancel·lar</span>
                                    </ModalLink>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-else class="empty-reservations">
                        <div class="empty-icon">📅</div>
                        <h3 class="empty-title">No tens cap reserva {{ getEmptyMessage() }}</h3>
                        <p class="empty-text">Descobreix els millors restaurants i fes la teva primera reserva.</p>
                        <Link :href="route('restaurants.index')" class="empty-button">
                            <span class="button-icon">🔍</span>
                            <span>Descobrir Restaurants</span>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </layout>
</template>

<script setup>
import { defineProps, ref, computed } from 'vue';
import Layout from '@/Layouts/Layout.vue';
import { route } from "ziggy-js";
import { router, Link } from "@inertiajs/vue3";
import { ModalLink } from "@inertiaui/modal-vue";

const props = defineProps({
    reserves: Array,
});

const activeTab = ref('all');

const filteredReserves = computed(() => {
    if (activeTab.value === 'all') {
        return props.reserves;
    } else if (activeTab.value === 'pending') {
        return props.reserves.filter(reserva => reserva.estat === 0);
    } else if (activeTab.value === 'confirmed') {
        return props.reserves.filter(reserva => reserva.estat === 1);
    } else if (activeTab.value === 'completed') {
        return props.reserves.filter(reserva => reserva.estat === 3);
    } else if (activeTab.value === 'cancelled') {
        return props.reserves.filter(reserva => reserva.estat === 2);
    }
    return props.reserves;
});

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

const getStatusText = (status) => {
    switch (status) {
        case 0:
            return 'Pendent';
        case 1:
            return 'Confirmada';
        case 2:
            return 'Cancel·lada';
        case 3:
            return 'Completada';
        default:
            return 'Desconegut';
    }
};

const getStatusClass = (status) => {
    switch (status) {
        case 0:
            return 'status-pending';
        case 1:
            return 'status-confirmed';
        case 2:
            return 'status-cancelled';
        case 3:
            return 'status-completed';
        default:
            return '';
    }
};

const canEdit = (status) => {
    return status === 0 || status === 1;
};

const canCancel = (status) => {
    return status === 0 || status === 1;
};

const getEmptyMessage = () => {
    if (activeTab.value === 'all') {
        return '';
    } else if (activeTab.value === 'pending') {
        return 'pendent';
    } else if (activeTab.value === 'confirmed') {
        return 'confirmada';
    } else if (activeTab.value === 'completed') {
        return 'completada';
    } else if (activeTab.value === 'cancelled') {
        return 'cancel·lada';
    }
    return '';
};
</script>

<style scoped>
.user-reservations-page {
    background-color: #f8f9fa;
    min-height: 100vh;
    padding: 40px 0;
}

.container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 0 20px;
}

.page-header {
    text-align: center;
    margin-bottom: 40px;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: #333;
    margin-bottom: 10px;
}

.page-subtitle {
    font-size: 1.1rem;
    color: #666;
}

.reservations-container {
    background-color: white;
    border-radius: 16px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.reservations-tabs {
    display: flex;
    border-bottom: 1px solid #eee;
    overflow-x: auto;
    scrollbar-width: none;
}

.reservations-tabs::-webkit-scrollbar {
    display: none;
}

.tab-button {
    padding: 15px 20px;
    background: none;
    border: none;
    font-weight: 600;
    color: #666;
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
    border-bottom: 3px solid transparent;
}

.tab-button.active {
    color: #FF5A5F;
    border-bottom-color: #FF5A5F;
}

.tab-button:hover:not(.active) {
    background-color: #f9f9f9;
    color: #333;
}

.reservations-list {
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.reservation-card {
    background-color: #f9f9f9;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.reservation-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.reservation-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background-color: white;
    border-bottom: 1px solid #eee;
}

.restaurant-info {
    display: flex;
    align-items: center;
}

.restaurant-image {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    object-fit: cover;
    margin-right: 15px;
}

.restaurant-name {
    font-size: 1.2rem;
    font-weight: 700;
    color: #333;
    margin: 0 0 5px 0;
}

.restaurant-type {
    font-size: 0.9rem;
    color: #666;
    margin: 0;
}

.reservation-status {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
}

.status-pending {
    background-color: #fff8e1;
    color: #f57f17;
}

.status-confirmed {
    background-color: #e8f5e9;
    color: #2e7d32;
}

.status-cancelled {
    background-color: #ffebee;
    color: #c62828;
}

.status-completed {
    background-color: #e3f2fd;
    color: #1565c0;
}

.reservation-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
    padding: 20px;
}

.detail-item {
    display: flex;
    align-items: flex-start;
}

.detail-icon {
    width: 30px;
    height: 30px;
    background-color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    margin-right: 10px;
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

.reservation-notes {
    padding: 0 20px 20px;
    border-top: 1px dashed #eee;
}

.notes-label {
    font-size: 0.9rem;
    font-weight: 600;
    color: #555;
    margin-bottom: 5px;
}

.notes-content {
    font-size: 0.9rem;
    color: #666;
    line-height: 1.4;
}

.reservation-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    background-color: white;
    border-top: 1px solid #eee;
}

.action-buttons {
    display: flex;
    gap: 10px;
}

.action-button {
    display: inline-flex;
    align-items: center;
    padding: 8px 15px;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.button-icon {
    margin-right: 5px;
}

.view-button {
    background-color: #f1f1f1;
    color: #555;
}

.view-button:hover {
    background-color: #e5e5e5;
}

.edit-button {
    background-color: #e3f2fd;
    color: #1976d2;
}

.edit-button:hover {
    background-color: #bbdefb;
}

.cancel-button {
    background-color: #ffebee;
    color: #c62828;
}

.cancel-button:hover {
    background-color: #ffcdd2;
}

.empty-reservations {
    padding: 60px 20px;
    text-align: center;
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 20px;
    color: #ccc;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 10px;
}

.empty-text {
    color: #666;
    margin-bottom: 30px;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

.empty-button {
    display: inline-flex;
    align-items: center;
    background-color: #FF5A5F;
    color: white;
    padding: 12px 25px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.empty-button:hover {
    background-color: #e5484d;
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(255, 90, 95, 0.3);
}

@media (max-width: 768px) {
    .reservation-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .reservation-status {
        margin-top: 10px;
    }

    .reservation-details {
        grid-template-columns: 1fr;
    }

    .reservation-actions {
        flex-direction: column;
        gap: 15px;
    }

    .action-buttons {
        width: 100%;
        justify-content: space-between;
    }

    .view-button {
        width: 100%;
        justify-content: center;
    }
}
</style>
