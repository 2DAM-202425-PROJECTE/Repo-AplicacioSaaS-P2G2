<template>
    <layout>
        <div class="management-page">
            <div class="management-container">
                <div class="page-header">
                    <div class="restaurant-info">
                        <img :src="`/storage/${restaurant.profile_image}`" alt="Restaurant" class="restaurant-image" />
                        <div class="restaurant-details">
                            <h1 class="restaurant-name">{{ restaurant.nom }}</h1>
                            <div class="restaurant-meta">
                                <span class="meta-item">
                                    <span class="meta-icon">🍽️</span>
                                    {{ restaurant.tipus_cuina }}
                                </span>
                                <span class="meta-item">
                                    <span class="meta-icon">📞</span>
                                    {{ restaurant.telefon }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="restaurant-stats">
                        <div class="stat-card">
                            <div class="stat-value">24</div>
                            <div class="stat-label">Reserves Actives</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">4.8</div>
                            <div class="stat-label">Valoració</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">86%</div>
                            <div class="stat-label">Ocupació</div>
                        </div>
                    </div>
                </div>

                <div class="management-content">
                    <div class="management-grid">
                        <!-- Tarjeta de Reservas -->
                        <div class="management-card reservations-card">
                            <div class="card-header">
                                <div class="card-icon reservations-icon">📅</div>
                                <h2 class="card-title">Gestionar Reserves</h2>
                            </div>
                            <p class="card-description">
                                Consulta i administra les reserves del teu restaurant. Confirma, cancel·la o modifica les reserves dels teus clients.
                            </p>
                            <div class="card-stats">
                                <div class="mini-stat">
                                    <div class="mini-stat-value">12</div>
                                    <div class="mini-stat-label">Pendents</div>
                                </div>
                                <div class="mini-stat">
                                    <div class="mini-stat-value">8</div>
                                    <div class="mini-stat-label">Avui</div>
                                </div>
                                <div class="mini-stat">
                                    <div class="mini-stat-value">4</div>
                                    <div class="mini-stat-label">Demà</div>
                                </div>
                            </div>
                            <Link :href="route('restaurant.reserves', { id: restaurant.id })" class="card-button reservations-button">
                                <span class="button-icon">👁️</span>
                                Veure Reserves
                            </Link>
                        </div>

                        <!-- Tarjeta de Editar Restaurante -->
                        <div class="management-card edit-card">
                            <div class="card-header">
                                <div class="card-icon edit-icon">✏️</div>
                                <h2 class="card-title">Modificar Restaurant</h2>
                            </div>
                            <p class="card-description">
                                Actualitza la informació del teu restaurant, com ara la descripció, horaris, ubicació i imatge principal.
                            </p>
                            <div class="card-features">
                                <div class="feature-item">
                                    <span class="feature-icon">📝</span>
                                    <span class="feature-text">Editar informació bàsica</span>
                                </div>
                                <div class="feature-item">
                                    <span class="feature-icon">🕒</span>
                                    <span class="feature-text">Actualitzar horaris</span>
                                </div>
                                <div class="feature-item">
                                    <span class="feature-icon">📷</span>
                                    <span class="feature-text">Canviar imatges</span>
                                </div>
                            </div>
                            <Link :href="route('restaurants.edit', { id: restaurant.id })" class="card-button edit-button">
                                <span class="button-icon">✏️</span>
                                Editar Restaurant
                            </Link>
                        </div>

                        <!-- Tarjeta de Carta -->
                        <div class="management-card menu-card">
                            <div class="card-header">
                                <div class="card-icon menu-icon">🍽️</div>
                                <h2 class="card-title">Gestionar Carta</h2>
                            </div>
                            <p class="card-description">
                                Afegeix, edita o elimina plats de la teva carta. Actualitza preus, descripcions i informació sobre al·lèrgens.
                            </p>
                            <div class="card-stats">
                                <div class="mini-stat">
                                    <div class="mini-stat-value">{{ restaurant.plats ? restaurant.plats.length : 0 }}</div>
                                    <div class="mini-stat-label">Plats</div>
                                </div>
                                <div class="mini-stat">
                                    <div class="mini-stat-value">{{ getVegetarianCount() }}</div>
                                    <div class="mini-stat-label">Vegetarians</div>
                                </div>
                                <div class="mini-stat">
                                    <div class="mini-stat-value">{{ getVeganCount() }}</div>
                                    <div class="mini-stat-label">Vegans</div>
                                </div>
                            </div>
                            <Link :href="route('restaurants.plats', { id: restaurant.id })" class="card-button menu-button">
                                <span class="button-icon">🍽️</span>
                                Editar Carta
                            </Link>
                        </div>

                        <!-- Tarjeta de Eliminar Restaurante -->
                        <div class="management-card delete-card">
                            <div class="card-header">
                                <div class="card-icon delete-icon">⚠️</div>
                                <h2 class="card-title">Eliminar Restaurant</h2>
                            </div>
                            <p class="card-description">
                                Elimina el restaurant juntament amb tots els registres associats. Aquesta acció és irreversible i eliminarà totes les dades.
                            </p>
                            <div class="card-warning">
                                <div class="warning-icon">⚠️</div>
                                <div class="warning-text">
                                    <strong>Atenció:</strong> Aquesta acció no es pot desfer. Totes les reserves, plats i valoracions seran eliminats permanentment.
                                </div>
                            </div>
                            <Link :href="route('restaurants.delete', { id: restaurant.id })" class="card-button delete-button">
                                <span class="button-icon">🗑️</span>
                                Eliminar Restaurant
                            </Link>
                        </div>
                    </div>
                </div>

                <div class="quick-actions">
                    <Link :href="route('restaurants.show', { id: restaurant.id })" class="quick-action-button">
                        <span class="action-icon">👁️</span>
                        <span class="action-text">Veure Perfil Públic</span>
                    </Link>
                    <button class="quick-action-button" @click="togglePreviewMode">
                        <span class="action-icon">📱</span>
                        <span class="action-text">Vista Prèvia Mòbil</span>
                    </button>
                    <Link :href="route('home')" class="quick-action-button">
                        <span class="action-icon">🏠</span>
                        <span class="action-text">Tornar a l'Inici</span>
                    </Link>
                </div>
            </div>
        </div>
    </layout>
</template>

<script setup>
import { ref } from 'vue';
import { route } from "ziggy-js";
import Layout from "@/Layouts/Layout.vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    restaurant: Object
});

const isPreviewMode = ref(false);

const togglePreviewMode = () => {
    isPreviewMode.value = !isPreviewMode.value;
    // Aquí podrías implementar la lógica para mostrar una vista previa móvil
    alert('Funcionalitat de vista prèvia mòbil en desenvolupament');
};

const getVegetarianCount = () => {
    if (!props.restaurant.plats) return 0;
    return props.restaurant.plats.filter(plat => plat.vegetaria).length;
};

const getVeganCount = () => {
    if (!props.restaurant.plats) return 0;
    return props.restaurant.plats.filter(plat => plat.vega).length;
};
</script>

<style scoped>
.management-page {
    background-color: #f8f9fa;
    min-height: 100vh;
    padding: 40px 20px;
}

.management-container {
    max-width: 1200px;
    margin: 0 auto;
}

.page-header {
    background: linear-gradient(135deg, #FF5A5F, #FF8A8E);
    color: white;
    border-radius: 16px;
    padding: 30px;
    margin-bottom: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 10px 30px rgba(255, 90, 95, 0.2);
}

.restaurant-info {
    display: flex;
    align-items: center;
}

.restaurant-image {
    width: 100px;
    height: 100px;
    border-radius: 12px;
    object-fit: cover;
    border: 3px solid white;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    margin-right: 20px;
}

.restaurant-name {
    font-size: 2.2rem;
    font-weight: 800;
    margin-bottom: 10px;
}

.restaurant-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
}

.meta-item {
    display: flex;
    align-items: center;
    background-color: rgba(255, 255, 255, 0.2);
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.9rem;
}

.meta-icon {
    margin-right: 5px;
}

.restaurant-stats {
    display: flex;
    gap: 15px;
}

.stat-card {
    background-color: rgba(255, 255, 255, 0.2);
    padding: 15px;
    border-radius: 12px;
    text-align: center;
    min-width: 100px;
}

.stat-value {
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

.management-content {
    margin-bottom: 30px;
}

.management-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
}

.management-card {
    background-color: white;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.management-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

.card-header {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.card-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    margin-right: 15px;
}

.reservations-icon {
    background-color: #e3f2fd;
    color: #1976d2;
}

.edit-icon {
    background-color: #e8f5e9;
    color: #388e3c;
}

.menu-icon {
    background-color: #fff3e0;
    color: #f57c00;
}

.delete-icon {
    background-color: #ffebee;
    color: #d32f2f;
}

.card-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
}

.card-description {
    color: #666;
    margin-bottom: 20px;
    line-height: 1.5;
}

.card-stats {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}

.mini-stat {
    text-align: center;
    flex: 1;
}

.mini-stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #FF5A5F;
}

.mini-stat-label {
    font-size: 0.8rem;
    color: #666;
}

.card-features {
    margin-bottom: 20px;
}

.feature-item {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.feature-icon {
    margin-right: 10px;
}

.feature-text {
    color: #555;
}

.card-warning {
    display: flex;
    align-items: flex-start;
    background-color: #fff8e1;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.warning-icon {
    font-size: 1.5rem;
    margin-right: 10px;
    color: #f57f17;
}

.warning-text {
    color: #555;
    font-size: 0.9rem;
    line-height: 1.4;
}

.card-button {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 12px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.button-icon {
    margin-right: 8px;
}

.reservations-button {
    background-color: #1976d2;
    color: white;
}

.reservations-button:hover {
    background-color: #1565c0;
}

.edit-button {
    background-color: #388e3c;
    color: white;
}

.edit-button:hover {
    background-color: #2e7d32;
}

.menu-button {
    background-color: #f57c00;
    color: white;
}

.menu-button:hover {
    background-color: #ef6c00;
}

.delete-button {
    background-color: #d32f2f;
    color: white;
}

.delete-button:hover {
    background-color: #c62828;
}

.quick-actions {
    display: flex;
    justify-content: center;
    gap: 20px;
}

.quick-action-button {
    display: flex;
    align-items: center;
    background-color: white;
    padding: 10px 20px;
    border-radius: 30px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    color: #555;
    font-weight: 600;
}

.quick-action-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.action-icon {
    margin-right: 8px;
}

@media (max-width: 992px) {
    .page-header {
        flex-direction: column;
    }

    .restaurant-stats {
        margin-top: 20px;
        width: 100%;
        justify-content: space-between;
    }

    .quick-actions {
        flex-direction: column;
        gap: 10px;
    }

    .quick-action-button {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 768px) {
    .restaurant-info {
        flex-direction: column;
        text-align: center;
    }

    .restaurant-image {
        margin-right: 0;
        margin-bottom: 15px;
    }

    .restaurant-meta {
        justify-content: center;
    }
}
</style>
