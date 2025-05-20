<template>
    <layout>
        <div class="restaurant-reservations-page">
            <div class="container">
                <div class="page-header">
                    <div class="header-content">
                        <Link :href="route('restaurants.show', { id: restaurant.id })" class="back-button">
                            <span class="back-icon">←</span>
                            <span>Tornar al Restaurant</span>
                        </Link>
                        <h1 class="page-title">Reserves de {{ restaurant.nom }}</h1>
                        <p class="page-subtitle">Gestiona les reserves del teu restaurant</p>
                    </div>
                </div>

                <div class="reservations-container">
                    <div class="reservations-filters">
                        <div class="filters-section">
                            <div class="filter-group">
                                <label for="date-filter" class="filter-label">Filtrar per data</label>
                                <div class="filter-input-container">
                                    <span class="filter-icon">📅</span>
                                    <input
                                        type="date"
                                        id="date-filter"
                                        v-model="filters.date"
                                        class="filter-input"
                                    />
                                </div>
                            </div>

                            <div class="filter-group">
                                <label for="status-filter" class="filter-label">Estat</label>
                                <div class="filter-input-container">
                                    <span class="filter-icon">🔍</span>
                                    <select id="status-filter" v-model="filters.status" class="filter-input">
                                        <option value="all">Tots els estats</option>
                                        <option value="0">Pendents</option>
                                        <option value="1">Confirmades</option>
                                        <option value="2">Cancel·lades</option>
                                        <option value="3">Completades</option>
                                    </select>
                                </div>
                            </div>

                            <div class="filter-group">
                                <label for="search-filter" class="filter-label">Cerca</label>
                                <div class="filter-input-container">
                                    <span class="filter-icon">🔍</span>
                                    <input
                                        type="text"
                                        id="search-filter"
                                        v-model="filters.search"
                                        class="filter-input"
                                        placeholder="Cerca per telèfon..."
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="filters-actions">
                            <button @click="resetFilters" class="reset-button">
                                <span class="button-icon">↺</span>
                                <span>Reiniciar</span>
                            </button>
                        </div>
                    </div>

                    <div class="reservations-stats">
                        <div class="stat-card">
                            <div class="stat-value">{{ countByStatus(0) }}</div>
                            <div class="stat-label">Pendents</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">{{ countByStatus(1) }}</div>
                            <div class="stat-label">Confirmades</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">{{ countByStatus(2) }}</div>
                            <div class="stat-label">Cancel·lades</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">{{ countByStatus(3) }}</div>
                            <div class="stat-label">Completades</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">{{ countToday() }}</div>
                            <div class="stat-label">Avui</div>
                        </div>
                    </div>

                    <div v-if="filteredReserves.length > 0" class="reservations-table-container">
                        <table class="reservations-table">
                            <thead>
                            <tr>
                                <th>Data</th>
                                <th>Hora</th>
                                <th>Telèfon</th>
                                <th>Persones</th>
                                <th>Estat</th>
                                <th>Ubicació</th>
                                <th>Sol·licituds</th>
                                <th>Accions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-for="reserva in filteredReserves" :key="reserva.id">
                                <tr
                                    :class="{
                                            'expanded': expandedRowId === reserva.id,
                                            'status-pending': reserva.estat === 0,
                                            'status-confirmed': reserva.estat === 1,
                                            'status-cancelled': reserva.estat === 2,
                                            'status-completed': reserva.estat === 3
                                        }"
                                    @click="toggleExpanded(reserva.id)"
                                >
                                    <td>{{ formatDate(reserva.data) }}</td>
                                    <td>{{ formatHour(reserva.hora) }}</td>
                                    <td>{{ reserva.telefon }}</td>
                                    <td>{{ reserva.num_persones }}</td>
                                    <td>
                                        <select
                                            v-model="reserva.estat"
                                            class="status-select"
                                            :class="getStatusClass(reserva.estat)"
                                            @change="updateStatus(reserva.id, reserva.estat)"
                                            @click.stop
                                        >
                                            <option :value="0">Pendent</option>
                                            <option :value="1">Confirmada</option>
                                            <option :value="2">Cancel·lada</option>
                                            <option :value="3">Completada</option>
                                        </select>
                                    </td>
                                    <td>{{ reserva.terrassa ? 'Terrassa' : 'Interior' }}</td>
                                    <td>
                                            <span v-if="reserva.solicituds" class="has-notes" @click.stop="toggleExpanded(reserva.id)">
                                                <span class="notes-icon">📝</span>
                                                <span>Veure notes</span>
                                            </span>
                                        <span v-else>-</span>
                                    </td>
                                    <td class="actions-cell">
                                        <div class="table-actions">
                                            <ModalLink :href="route('reserves.edit', { id: reserva.id })">
                                                <button class="action-button edit-button" @click.stop>
                                                    <span class="action-icon">✏️</span>
                                                </button>
                                            </ModalLink>
                                            <ModalLink :href="route('reserves.delete', { id: reserva.id })">
                                                <button class="action-button delete-button" @click.stop>
                                                    <span class="action-icon">🗑️</span>
                                                </button>
                                            </ModalLink>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="expandedRowId === reserva.id" class="expanded-row">
                                    <td colspan="8">
                                        <div class="expanded-content">
                                            <div class="expanded-section">
                                                <h3 class="expanded-title">Sol·licituds Especials</h3>
                                                <p class="expanded-text">{{ reserva.solicituds || 'Cap sol·licitud especial' }}</p>
                                            </div>
                                            <div class="expanded-section">
                                                <h3 class="expanded-title">Informació Addicional</h3>
                                                <div class="expanded-info">
                                                    <div class="info-item">
                                                        <span class="info-label">ID de Reserva:</span>
                                                        <span class="info-value">{{ reserva.id }}</span>
                                                    </div>
                                                    <div class="info-item">
                                                        <span class="info-label">Creada el:</span>
                                                        <span class="info-value">{{ formatDateTime(reserva.created_at) }}</span>
                                                    </div>
                                                    <div class="info-item">
                                                        <span class="info-label">Última actualització:</span>
                                                        <span class="info-value">{{ formatDateTime(reserva.updated_at) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>

                    <div v-else class="empty-reservations">
                        <div class="empty-icon">📅</div>
                        <h3 class="empty-title">No hi ha reserves que coincideixin amb els filtres</h3>
                        <p class="empty-text">Prova a canviar els filtres o espera a rebre noves reserves.</p>
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
import { Link, router } from "@inertiajs/vue3";
import { ModalLink } from '@inertiaui/modal-vue';

const props = defineProps({
    restaurant: Object,
    reserves: Array,
});

const expandedRowId = ref(null);
const filters = ref({
    date: '',
    status: 'all',
    search: '',
});

const toggleExpanded = (id) => {
    expandedRowId.value = expandedRowId.value === id ? null : id;
};

const filteredReserves = computed(() => {
    return props.reserves.filter(reserva => {
        // Filtro por fecha
        if (filters.value.date && !reserva.data.includes(filters.value.date)) {
            return false;
        }

        // Filtro por estado
        if (filters.value.status !== 'all' && reserva.estat !== parseInt(filters.value.status)) {
            return false;
        }

        // Filtro por búsqueda (teléfono)
        if (filters.value.search && !reserva.telefon.includes(filters.value.search)) {
            return false;
        }

        return true;
    });
});

const resetFilters = () => {
    filters.value = {
        date: '',
        status: 'all',
        search: '',
    };
};

const countByStatus = (status) => {
    return props.reserves.filter(reserva => reserva.estat === status).length;
};

const countToday = () => {
    const today = new Date().toISOString().split('T')[0];
    return props.reserves.filter(reserva => reserva.data === today).length;
};

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

const formatDateTime = (dateTimeString) => {
    const date = new Date(dateTimeString);
    return `${formatDate(dateTimeString)} ${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')}`;
};

const getStatusClass = (status) => {
    switch (status) {
        case 0:
            return 'status-pending-select';
        case 1:
            return 'status-confirmed-select';
        case 2:
            return 'status-cancelled-select';
        case 3:
            return 'status-completed-select';
        default:
            return '';
    }
};

const updateStatus = (id, newStatus) => {
    router.put(route('reserves.updateEstat', {id}), {estat: newStatus}, {
        onSuccess: () => {
            console.log('Status updated successfully');
        },
        onError: (errors) => {
            console.error('Error updating status:', errors);
        }
    });
};
</script>

<style scoped>
.restaurant-reservations-page {
    background-color: #f8f9fa;
    min-height: 100vh;
    padding: 40px 0;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.page-header {
    margin-bottom: 30px;
}

.back-button {
    display: inline-flex;
    align-items: center;
    color: #555;
    background-color: white;
    padding: 8px 15px;
    border-radius: 30px;
    margin-bottom: 15px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.back-button:hover {
    transform: translateX(-5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.back-icon {
    margin-right: 8px;
    font-size: 1.2rem;
}

.page-title {
    font-size: 2.2rem;
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

.reservations-filters {
    padding: 20px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
}

.filters-section {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.filter-group {
    flex: 1;
    min-width: 200px;
}

.filter-label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #555;
    font-size: 0.9rem;
}

.filter-input-container {
    position: relative;
}

.filter-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 1rem;
}

.filter-input {
    width: 100%;
    padding: 10px 10px 10px 35px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.filter-input:focus {
    border-color: #FF5A5F;
    box-shadow: 0 0 0 2px rgba(255, 90, 95, 0.2);
    outline: none;
}

.filters-actions {
    display: flex;
    align-items: flex-end;
}

.reset-button {
    display: flex;
    align-items: center;
    background-color: #f1f1f1;
    color: #555;
    padding: 10px 15px;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.reset-button:hover {
    background-color: #e5e5e5;
}

.button-icon {
    margin-right: 5px;
}

.reservations-stats {
    display: flex;
    justify-content: space-between;
    padding: 20px;
    border-bottom: 1px solid #eee;
    flex-wrap: wrap;
    gap: 10px;
}

.stat-card {
    background-color: #f9f9f9;
    border-radius: 8px;
    padding: 15px;
    text-align: center;
    flex: 1;
    min-width: 100px;
}

.stat-value {
    font-size: 1.8rem;
    font-weight: 800;
    color: #FF5A5F;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 0.9rem;
    color: #666;
}

.reservations-table-container {
    padding: 20px;
    overflow-x: auto;
}

.reservations-table {
    width: 100%;
    border-collapse: collapse;
}

.reservations-table th {
    background-color: #f9f9f9;
    padding: 12px 15px;
    text-align: left;
    font-weight: 600;
    color: #555;
    border-bottom: 2px solid #eee;
}

.reservations-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #eee;
    color: #333;
}

.reservations-table tr {
    cursor: pointer;
    transition: all 0.3s ease;
}

.reservations-table tr:hover:not(.expanded-row) {
    background-color: #f9f9f9;
}

.reservations-table tr.expanded {
    background-color: #f9f9f9;
}

.status-pending {
    border-left: 4px solid #f57f17;
}

.status-confirmed {
    border-left: 4px solid #2e7d32;
}

.status-cancelled {
    border-left: 4px solid #c62828;
}

.status-completed {
    border-left: 4px solid #1565c0;
}

.status-select {
    padding: 8px 10px;
    border-radius: 6px;
    border: 1px solid #ddd;
    font-weight: 600;
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 8px center;
    background-size: 1em;
    padding-right: 30px;
}

.status-pending-select {
    background-color: #fff8e1;
    color: #f57f17;
}

.status-confirmed-select {
    background-color: #e8f5e9;
    color: #2e7d32;
}

.status-cancelled-select {
    background-color: #ffebee;
    color: #c62828;
}

.status-completed-select {
    background-color: #e3f2fd;
    color: #1565c0;
}

.has-notes {
    display: flex;
    align-items: center;
    color: #1976d2;
    font-size: 0.9rem;
    cursor: pointer;
}

.notes-icon {
    margin-right: 5px;
}

.actions-cell {
    width: 100px;
}

.table-actions {
    display: flex;
    gap: 8px;
}

.action-button {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.edit-button {
    background-color: #e3f2fd;
    color: #1976d2;
}

.edit-button:hover {
    background-color: #bbdefb;
}

.delete-button {
    background-color: #ffebee;
    color: #c62828;
}

.delete-button:hover {
    background-color: #ffcdd2;
}

.action-icon {
    font-size: 1rem;
}

.expanded-row {
    background-color: #f9f9f9;
}

.expanded-content {
    padding: 15px;
}

.expanded-section {
    margin-bottom: 15px;
}

.expanded-section:last-child {
    margin-bottom: 0;
}

.expanded-title {
    font-size: 1rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
}

.expanded-text {
    color: #555;
    line-height: 1.5;
}

.expanded-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 10px;
}

.info-item {
    display: flex;
    flex-direction: column;
}

.info-label {
    font-size: 0.8rem;
    color: #666;
    margin-bottom: 3px;
}

.info-value {
    font-weight: 600;
    color: #333;
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

@media (max-width: 992px) {
    .reservations-filters {
        flex-direction: column;
        align-items: stretch;
        gap: 15px;
    }

    .filters-actions {
        justify-content: flex-end;
    }

    .reservations-stats {
        flex-wrap: wrap;
    }

    .stat-card {
        min-width: calc(50% - 10px);
    }
}

@media (max-width: 768px) {
    .filters-section {
        flex-direction: column;
    }

    .filter-group {
        width: 100%;
    }

    .reservations-table th:nth-child(6),
    .reservations-table td:nth-child(6) {
        display: none;
    }

    .stat-card {
        min-width: 100%;
    }
}

@media (max-width: 576px) {
    .reservations-table th:nth-child(3),
    .reservations-table td:nth-child(3),
    .reservations-table th:nth-child(4),
    .reservations-table td:nth-child(4) {
        display: none;
    }
}
</style>
