<template>
    <layout>
        <div class="account-config-page">
            <div class="account-container">
                <div class="account-header">
                    <div class="header-content">
                        <h1 class="page-title">Configuració del Compte</h1>
                        <p class="page-subtitle">Gestiona les teves dades personals i preferències</p>
                    </div>
                    <div class="user-avatar">
                        <div class="avatar-circle">
                            {{ user.name.charAt(0).toUpperCase() }}
                        </div>
                    </div>
                </div>

                <div class="account-content">
                    <div class="account-sidebar">
                        <div class="sidebar-menu">
                            <div class="menu-item active">
                                <span class="menu-icon">👤</span>
                                <span>Informació Personal</span>
                            </div>
                            <div class="menu-item">
                                <span class="menu-icon">🔒</span>
                                <span>Seguretat</span>
                            </div>
                            <div class="menu-item">
                                <span class="menu-icon">🔔</span>
                                <span>Notificacions</span>
                            </div>
                            <div class="menu-item">
                                <span class="menu-icon">💳</span>
                                <span>Pagaments</span>
                            </div>
                        </div>
                    </div>

                    <div class="account-main">
                        <div class="section personal-info">
                            <h2 class="section-title">Informació Personal</h2>

                            <div class="info-card">
                                <div class="info-row">
                                    <div class="info-label">Nom</div>
                                    <div class="info-value">{{ user.name }}</div>
                                    <button class="edit-button">Editar</button>
                                </div>

                                <div class="info-row">
                                    <div class="info-label">Correu electrònic</div>
                                    <div class="info-value">{{ user.email }}</div>
                                    <button class="edit-button">Editar</button>
                                </div>

                                <div class="info-row">
                                    <div class="info-label">Tipus de compte</div>
                                    <div class="info-value">{{ isEmpresa() ? 'Empresa' : 'Client' }}</div>
                                </div>

                            </div>
                        </div>

                        <div v-if="isEmpresa()" class="section business-section">
                            <h2 class="section-title">Gestió del Negoci</h2>

                            <div v-if="restaurant" class="business-card">
                                <div class="business-header">
                                    <div class="business-info">
                                        <h3 class="business-name">{{ restaurant.nom }}</h3>
                                        <p class="business-type">{{ restaurant.tipus_cuina }}</p>
                                    </div>
                                    <img :src="`/storage/${restaurant.profile_image}`" alt="Restaurant" class="business-image" />
                                </div>

                                <div class="business-stats">
                                    <div class="stat-item">
                                        <div class="stat-value">24</div>
                                        <div class="stat-label">Reserves aquest mes</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value">4.8</div>
                                        <div class="stat-label">Valoració mitjana</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value">86%</div>
                                        <div class="stat-label">Ocupació</div>
                                    </div>
                                </div>

                                <div class="business-actions">
                                    <Link :href="route('restaurant.management', { id: restaurant.id })" class="action-button primary">
                                        <span class="button-icon">⚙️</span>
                                        Gestionar Restaurant
                                    </Link>
                                    <Link :href="route('restaurants.show', { id: restaurant.id })" class="action-button secondary">
                                        <span class="button-icon">👁️</span>
                                        Veure Perfil Públic
                                    </Link>
                                </div>
                            </div>

                            <div v-else class="empty-business">
                                <div class="empty-icon">🍽️</div>
                                <h3 class="empty-title">No tens cap restaurant registrat</h3>
                                <p class="empty-description">Crea el teu primer restaurant per començar a gestionar el teu negoci i rebre reserves en línia.</p>
                                <Link :href="route('restaurants.create')" class="action-button primary">
                                    <span class="button-icon">➕</span>
                                    Crear Restaurant
                                </Link>
                            </div>
                            <div class="mt-6">
                                <Link :href="route('restaurants.delete', { id: restaurant.id })" class="bg-red-500 text-white px-4 py-2 rounded" method="GET">
                                    Eliminar Negoci
                                </Link>
                            </div>
                        </div>

                        <div v-else class="section client-section">
                            <h2 class="section-title">El Meu Perfil de Client</h2>

                            <div class="client-card">
                                <div class="client-stats">
                                    <div class="stat-item">
                                        <div class="stat-value">12</div>
                                        <div class="stat-label">Reserves realitzades</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value">5</div>
                                        <div class="stat-label">Restaurants favorits</div>
                                    </div>
                                </div>

                                <div class="client-actions">
                                    <Link :href="route('user.reserves')" class="action-button primary">
                                        <span class="button-icon">📅</span>
                                        Les Meves Reserves
                                    </Link>
                                    <Link :href="route('restaurants.index')" class="action-button secondary">
                                        <span class="button-icon">🔍</span>
                                        Descobrir Restaurants
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>

<!--                    &lt;!&ndash; Informació del restaurant associat &ndash;&gt;-->
<!--                    <div v-if="restaurant" class="mt-8">-->
<!--                        <RestaurantInfo :restaurant="restaurant" />-->
<!--                    </div>-->
                </div>
            </div>

            <!-- Popup per crear un nou restaurant -->
            <PopupModal v-if="showPopup" @close="closePopup" @confirm="createRestaurant" />
        </div>


    </layout>
</template>

<script setup>
import {defineProps, ref, onMounted} from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { usePage } from '@inertiajs/vue3';
import UserInfo from '@/Components/UserInfo.vue';
import RestaurantInfo from '@/Components/RestaurantInfo.vue';
import PopupModal from '@/Components/PopupModal.vue';
import Layout from "@/Layouts/Layout.vue";
import {route} from "ziggy-js";

const { props } = usePage();
const user = ref(props.user);
const restaurant = ref(props.restaurant || null);
const showPopup = ref(false);


onMounted(() => {
    fetchUserRestaurant(props.user.id);
});

function fetchUserRestaurant(userId) {
    fetch(route('user.restaurant', { userId }))
        .then(response => response.json())
        .then(data => {
            restaurant.value = data.restaurant;
        });
}




// Funció per verificar si l'usuari és empresa
function isEmpresa() {
    return user.value?.empresa === 1;
}

// Funció per crear el restaurant
function createRestaurant() {
    Inertia.visit(route('restaurants.create'));
    showPopup.value = false;
}

// Funció per tancar el popup
function closePopup() {
    showPopup.value = false;
}
</script>

<style scoped>
.account-config-page {
    background-color: #f8f9fa;
    min-height: 100vh;
    padding: 40px 20px;
}

.account-container {
    max-width: 1200px;
    margin: 0 auto;
    background-color: white;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.account-header {
    background: linear-gradient(135deg, #FF5A5F, #FF8A8E);
    color: white;
    padding: 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 10px;
}

.page-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
}

.user-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-circle {
    width: 80px;
    height: 80px;
    background-color: white;
    color: #FF5A5F;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    font-weight: 700;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.account-content {
    display: flex;
    min-height: 600px;
}

.account-sidebar {
    width: 280px;
    border-right: 1px solid #eee;
    padding: 30px 0;
}

.sidebar-menu {
    display: flex;
    flex-direction: column;
}

.menu-item {
    padding: 15px 30px;
    display: flex;
    align-items: center;
    cursor: pointer;
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
}

.menu-item.active {
    background-color: rgba(255, 90, 95, 0.05);
    border-left: 3px solid #FF5A5F;
    color: #FF5A5F;
    font-weight: 600;
}

.menu-item:hover:not(.active) {
    background-color: rgba(0, 0, 0, 0.02);
}

.menu-icon {
    margin-right: 15px;
    font-size: 1.2rem;
}

.account-main {
    flex: 1;
    padding: 30px;
}

.section {
    margin-bottom: 40px;
}

.section-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #f1f1f1;
}

.info-card {
    background-color: #f9f9f9;
    border-radius: 12px;
    padding: 20px;
}

.info-row {
    display: flex;
    padding: 15px 0;
    border-bottom: 1px solid #eee;
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    width: 200px;
    font-weight: 600;
    color: #666;
}

.info-value {
    flex: 1;
    color: #333;
}

.edit-button {
    background: none;
    border: none;
    color: #FF5A5F;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
}

.edit-button:hover {
    text-decoration: underline;
}

.business-card, .client-card, .empty-business {
    background-color: #f9f9f9;
    border-radius: 12px;
    padding: 25px;
}

.business-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.business-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 5px;
}

.business-type {
    color: #666;
}

.business-image {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    object-fit: cover;
}

.business-stats, .client-stats {
    display: flex;
    justify-content: space-between;
    margin-bottom: 25px;
}

.stat-item {
    text-align: center;
    flex: 1;
    padding: 15px;
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    margin: 0 5px;
}

.stat-value {
    font-size: 1.8rem;
    font-weight: 700;
    color: #FF5A5F;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 0.9rem;
    color: #666;
}

.business-actions, .client-actions {
    display: flex;
    gap: 15px;
}

.action-button {
    flex: 1;
    padding: 15px;
    border-radius: 10px;
    font-weight: 600;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.action-button.primary {
    background-color: #FF5A5F;
    color: white;
}

.action-button.primary:hover {
    background-color: #e5484d;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(255, 90, 95, 0.3);
}

.action-button.secondary {
    background-color: white;
    color: #333;
    border: 1px solid #ddd;
}

.action-button.secondary:hover {
    background-color: #f8f9fa;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.button-icon {
    margin-right: 8px;
}

.empty-business {
    text-align: center;
    padding: 50px 20px;
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 20px;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 10px;
}

.empty-description {
    color: #666;
    margin-bottom: 25px;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

@media (max-width: 992px) {
    .account-content {
        flex-direction: column;
    }

    .account-sidebar {
        width: 100%;
        border-right: none;
        border-bottom: 1px solid #eee;
        padding: 0;
    }

    .sidebar-menu {
        flex-direction: row;
        overflow-x: auto;
        padding: 15px;
    }

    .menu-item {
        padding: 10px 15px;
        border-left: none;
        border-bottom: 3px solid transparent;
        white-space: nowrap;
    }

    .menu-item.active {
        border-left: none;
        border-bottom: 3px solid #FF5A5F;
    }

    .business-stats, .client-stats {
        flex-direction: column;
        gap: 10px;
    }

    .stat-item {
        margin: 5px 0;
    }

    .business-actions, .client-actions {
        flex-direction: column;
    }
}

@media (max-width: 768px) {
    .account-header {
        flex-direction: column;
        text-align: center;
    }

    .user-avatar {
        margin-top: 20px;
    }

    .info-row {
        flex-direction: column;
    }

    .info-label {
        width: 100%;
        margin-bottom: 5px;
    }

    .edit-button {
        margin-top: 10px;
        align-self: flex-start;
    }

    .business-header {
        flex-direction: column;
        text-align: center;
    }

    .business-image {
        margin-top: 15px;
    }
}
</style>
