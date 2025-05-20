<template>
    <layout>
        <div class="restaurant-detail-page">
            <!-- Hero Section -->
            <div class="restaurant-hero" :style="{ backgroundImage: `url(/storage/${restaurant.profile_image})` }">
                <div class="hero-overlay">
                    <div class="container">
                        <div class="hero-content">
                            <h1 class="restaurant-name">{{ nom }}</h1>
                            <div class="restaurant-meta">
                                <div class="meta-item">
                                    <span class="meta-icon">🍽️</span>
                                    {{ tipus_cuina }}
                                </div>
                                <div class="meta-item">
                                    <span class="meta-icon">📍</span>
                                    {{ restaurant.municipio?.name || 'Municipi no disponible' }}
                                </div>
                                <div class="meta-item">
                                    <span class="meta-icon">⭐</span>
                                    4.8 (124 valoracions)
                                </div>
                            </div>
                            <button @click="toggleFavorite" class="favorite-button" :class="{ 'is-favorite': isFavorite }">
                                <span class="favorite-icon">{{ isFavorite ? '❤️' : '🤍' }}</span>
                            </button>
                            <br>
                            <br>

                        </div>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="content-wrapper">
                    <!-- Información del restaurante -->
                    <div class="restaurant-info">
                        <div class="info-section">
                            <h2 class="section-title">Sobre el Restaurant</h2>
                            <p class="restaurant-description">{{ descripcio }}</p>

                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-icon">📞</div>
                                    <div class="info-content">
                                        <h3 class="info-title">Telèfon</h3>
                                        <p class="info-value">{{ telefon }}</p>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-icon">🕒</div>
                                    <div class="info-content">
                                        <h3 class="info-title">Horari</h3>
                                        <p class="info-value">Dilluns - Dissabte ({{ horaObertura }} - {{ horaTancament }})</p>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-icon">📍</div>
                                    <div class="info-content">
                                        <h3 class="info-title">Ubicació</h3>
                                        <p class="info-value">
                                            {{ restaurant.carrer }},
                                            {{ restaurant.municipio?.name || 'Municipi no disponible' }},
                                            {{ restaurant.municipio?.provincia?.name || 'Provincia no disponible' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Carta del restaurante -->
                        <div class="menu-section">
                            <h2 class="section-title">La Nostra Carta</h2>

                            <div v-if="restaurant.plats && restaurant.plats.length > 0" class="menu-list">
                                <div v-for="plat in restaurant.plats.slice(0, visiblePlatsCount)" :key="plat.id" class="menu-item">
                                    <div class="menu-item-header">
                                        <h3 class="menu-item-name">{{ plat.nom }}</h3>
                                        <div class="menu-item-price">{{ plat.preu }} €</div>
                                    </div>

                                    <p class="menu-item-description">{{ plat.descripcio }}</p>

                                    <div class="menu-item-tags">
                                        <div v-if="allergenList(plat).length > 0" class="tags-group">
                                            <span class="tags-label">Al·lèrgens:</span>
                                            <div class="tags-list">
                                                <span v-for="allergen in allergenList(plat)" :key="allergen" class="tag allergen-tag">
                                                    {{ allergen }}
                                                </span>
                                            </div>
                                        </div>

                                        <div v-if="dietaryList(plat).length > 0" class="tags-group">
                                            <span class="tags-label">Opcions Dietètiques:</span>
                                            <div class="tags-list">
                                                <span v-for="dietary in dietaryList(plat)" :key="dietary" class="tag dietary-tag">
                                                    {{ dietary }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-else class="empty-menu">
                                <div class="empty-icon">🍽️</div>
                                <p class="empty-text">Aquest restaurant encara no ha afegit plats a la seva carta.</p>
                            </div>

                            <button
                                v-if="visiblePlatsCount < restaurant.plats.length"
                                @click="visiblePlatsCount += initialLimit"
                                class="load-more-button"
                            >
                                <span class="button-icon">+</span>
                                Mostrar més plats
                            </button>
                        </div>
                    </div>

                    <!-- Formulario de reserva -->
                    <div class="reservation-sidebar">
                        <div class="reservation-form-container">
                            <h2 class="form-title">Fes la teva Reserva</h2>

                            <form @submit.prevent="submitReservation" class="reservation-form">
                                <input type="hidden" v-model="reservation.id_restaurant">

                                <div class="form-group">
                                    <label for="telefon" class="form-label">Telèfon de contacte</label>
                                    <div class="input-container">
                                        <span class="input-icon">📞</span>
                                        <input
                                            type="text"
                                            v-model="reservation.telefon"
                                            id="telefon"
                                            class="form-input"
                                            placeholder="Ex: 93 123 45 67"
                                            required
                                        >
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group half">
                                        <label for="data" class="form-label">Data</label>
                                        <div class="input-container">
                                            <span class="input-icon">📅</span>
                                            <input
                                                type="date"
                                                v-model="reservation.data"
                                                id="data"
                                                class="form-input"
                                                :min="today"
                                                required
                                            >
                                        </div>
                                    </div>

                                    <div class="form-group half">
                                        <label for="hora" class="form-label">Hora</label>
                                        <div class="input-container">
                                            <span class="input-icon">🕒</span>
                                            <input
                                                type="time"
                                                v-model="reservation.hora"
                                                id="hora"
                                                class="form-input"
                                                required
                                            >
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="num_persones" class="form-label">Número de Persones</label>
                                    <div class="input-container">
                                        <span class="input-icon">👥</span>
                                        <input
                                            type="number"
                                            v-model="reservation.num_persones"
                                            id="num_persones"
                                            class="form-input"
                                            min="1"
                                            max="20"
                                            required
                                        >
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Ubicació Preferida</label>
                                    <div class="toggle-options">
                                        <label class="toggle-option" :class="{ 'active': !reservation.terrassa }">
                                            <input
                                                type="radio"
                                                v-model="reservation.terrassa"
                                                :value="false"
                                                class="toggle-input"
                                            >
                                            <span class="toggle-icon">🏠</span>
                                            <span class="toggle-text">Interior</span>
                                        </label>

                                        <label class="toggle-option" :class="{ 'active': reservation.terrassa }">
                                            <input
                                                type="radio"
                                                v-model="reservation.terrassa"
                                                :value="true"
                                                class="toggle-input"
                                            >
                                            <span class="toggle-icon">☀️</span>
                                            <span class="toggle-text">Terrassa</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="solicituds" class="form-label">Sol·licituds Especials</label>
                                    <textarea
                                        v-model="reservation.solicituds"
                                        id="solicituds"
                                        class="form-textarea"
                                        placeholder="Indica'ns qualsevol sol·licitud especial (al·lèrgies, celebracions, etc.)"
                                    ></textarea>
                                </div>

                                <button type="submit" class="submit-button" :disabled="isSubmitting">
                                    <span v-if="isSubmitting" class="loading-spinner"></span>
                                    <span v-else>Confirmar Reserva</span>
                                </button>
                            </form>

                            <div v-if="reservationSuccess" class="success-message">
                                <div class="success-icon">✅</div>
                                <div class="success-content">
                                    <h3 class="success-title">Reserva Confirmada!</h3>
                                    <p class="success-text">La teva reserva ha estat registrada correctament. Rebràs un correu de confirmació en breu.</p>
                                </div>
                            </div>
                        </div>

                        <div class="restaurant-map">
                            <div class="map-placeholder">
                                <div class="map-icon">📍</div>
                                <div class="map-text">Mapa de localització</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </layout>
</template>

<script setup>
import { defineProps, reactive, ref, onMounted, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import { route } from 'ziggy-js';
import axios from 'axios';
import Layout from "@/Layouts/Layout.vue";

// Definición de propiedades
const props = defineProps({
    restaurant: Object,
});

// Extracción de datos del restaurante
const { nom, descripcio, telefon, tipus_cuina, hora_obertura, hora_tancament } = props.restaurant;
const horaObertura = hora_obertura;
const horaTancament = hora_tancament;

// Inicialización de variables reactivas
const page = usePage();
const initialLimit = 3;
const visiblePlatsCount = ref(initialLimit);
const isFavorite = ref(false);
const isSubmitting = ref(false);
const reservationSuccess = ref(false);

// Obtener el ID del usuario logueado
const userId = page.props.auth.user.id;

// Inicialización del objeto de reserva
const reservation = reactive({
    id_restaurant: props.restaurant.id,
    user_id: userId,
    telefon: '',
    data: '',
    hora: '',
    num_persones: 1,
    estat: 0,
    terrassa: false,
    solicituds: '',
});

// Fecha mínima para la reserva (hoy)
const today = computed(() => {
    const date = new Date();
    return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
});

// Comprobar si el restaurante está en favoritos
const checkIfFavorite = async () => {
    try {
        const response = await axios.get(route('restaurants.isFavorite', { restaurant: props.restaurant.id }));
        isFavorite.value = response.data.isFavorite;
    } catch (error) {
        console.error('Error checking favorite status:', error);
    }
};

// Añadir o quitar de favoritos
const toggleFavorite = async () => {
    try {
        const response = await axios.post(route('restaurants.favorite', { restaurant: props.restaurant.id }));
        isFavorite.value = response.data.isFavorite;
    } catch (error) {
        console.error('Error toggling favorite:', error);
    }
};

// Obtener lista de alérgenos
const allergenList = (plat) => {
    const allergens = [];
    if (plat.gluten) allergens.push('Gluten');
    if (plat.lactics) allergens.push('Lactics');
    if (plat.crustaci) allergens.push('Crustacis');
    if (plat.ous) allergens.push('Ous');
    if (plat.lupines) allergens.push('Lupines');
    if (plat.mostassa) allergens.push('Mostassa');
    if (plat.cacahuats) allergens.push('Cacahuets');
    if (plat.soja) allergens.push('Soja');
    return allergens;
};

// Obtener lista de opciones dietéticas
const dietaryList = (plat) => {
    const dietary = [];
    if (plat.vegetaria) dietary.push('Vegetarià');
    if (plat.vega) dietary.push('Vegà');
    if (plat.kosher) dietary.push('Kosher');
    if (plat.halal) dietary.push('Halal');
    if (plat.keto) dietary.push('Keto');
    if (plat.carn_vermella) dietary.push('Carn Vermella');
    return dietary;
};

// Enviar la reserva
const submitReservation = () => {
    isSubmitting.value = true;

    Inertia.post(route('reserves.store'), reservation, {
        onSuccess: () => {
            // Reiniciar el formulario
            Object.assign(reservation, {
                telefon: '',
                data: '',
                hora: '',
                num_persones: 1,
                estat: 0,
                solicituds: '',
            });

            isSubmitting.value = false;
            reservationSuccess.value = true;

            // Ocultar el mensaje de éxito después de 5 segundos
            setTimeout(() => {
                reservationSuccess.value = false;
            }, 5000);

            Inertia.clearErrors();
        },
        onError: (errors) => {
            isSubmitting.value = false;
            console.error('Error submitting reservation:', errors);
        },
    });
};

onMounted(() => {
    checkIfFavorite();
});
</script>

<style scoped>
.restaurant-detail-page {
    background-color: #f8f9fa;
    min-height: 100vh;
}

.restaurant-hero {
    height: 50vh;
    min-height: 400px;
    background-size: cover;
    background-position: center;
    position: relative;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to bottom, rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.7));
    display: flex;
    align-items: flex-end;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.hero-content {
    color: white;
    padding-bottom: 40px;
}

.restaurant-name {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 15px;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
}

.restaurant-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 20px;
}

.meta-item {
    display: flex;
    align-items: center;
    background-color: rgba(255, 255, 255, 0.2);
    padding: 8px 15px;
    border-radius: 30px;
    font-size: 0.9rem;
}

.meta-icon {
    margin-right: 8px;
}

.favorite-button {
    display: inline-flex;
    align-items: center;
    background-color: white;
    color: #333;
    padding: 10px 20px;
    border-radius: 30px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.favorite-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.favorite-button.is-favorite {
    background-color: #FF5A5F;
    color: white;
}

.favorite-icon {
    margin-right: 8px;
    font-size: 1.2rem;
}

.content-wrapper {
    display: flex;
    gap: 30px;
    margin-top: -60px;
    position: relative;
    z-index: 10;
    padding-bottom: 60px;
}

.restaurant-info {
    flex: 2;
}

.reservation-sidebar {
    flex: 1;
    position: sticky;
    top: 20px;
    align-self: flex-start;
}

.info-section, .menu-section, .reservation-form-container, .restaurant-map {
    background-color: white;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    margin-bottom: 30px;
}

.section-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #f1f1f1;
}

.restaurant-description {
    color: #555;
    line-height: 1.6;
    margin-bottom: 30px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.info-item {
    display: flex;
    align-items: flex-start;
}

.info-icon {
    width: 40px;
    height: 40px;
    background-color: #f8f9fa;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-right: 15px;
}

.info-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 5px;
}

.info-value {
    color: #666;
}

.menu-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.menu-item {
    background-color: #f9f9f9;
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s ease;
}

.menu-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.menu-item-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.menu-item-name {
    font-size: 1.3rem;
    font-weight: 700;
    color: #333;
    margin: 0;
}

.menu-item-price {
    background-color: #00B140;
    color: white;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 1rem;
    font-weight: 600;
}

.menu-item-description {
    color: #666;
    margin-bottom: 15px;
    line-height: 1.5;
}

.menu-item-tags {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.tags-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.tags-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: #555;
}

.tags-list {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
}

.tag {
    font-size: 0.75rem;
    padding: 3px 8px;
    border-radius: 15px;
    white-space: nowrap;
}

.dietary-tag {
    background-color: #e1f5fe;
    color: #0288d1;
}

.allergen-tag {
    background-color: #ffebee;
    color: #e53935;
}

.empty-menu {
    text-align: center;
    padding: 40px 0;
}

.empty-icon {
    font-size: 3rem;
    margin-bottom: 15px;
}

.empty-text {
    color: #666;
}

.load-more-button {
    background-color: #f1f1f1;
    color: #555;
    border: none;
    padding: 12px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    margin-top: 20px;
}

.load-more-button:hover {
    background-color: #e5e5e5;
}

.button-icon {
    margin-right: 8px;
}

.form-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 20px;
    text-align: center;
}

.reservation-form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.form-row {
    display: flex;
    gap: 15px;
}

.form-group {
    flex: 1;
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
    border-color: #FF5A5F;
    box-shadow: 0 0 0 2px rgba(255, 90, 95, 0.2);
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
    border-color: #FF5A5F;
    background-color: rgba(255, 90, 95, 0.05);
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

.submit-button {
    background-color: #FF5A5F;
    color: white;
    border: none;
    padding: 15px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.submit-button:hover:not(:disabled) {
    background-color: #e5484d;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(255, 90, 95, 0.3);
}

.submit-button:disabled {
    background-color: #ffb5b8;
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

.success-message {
    margin-top: 20px;
    background-color: #e8f5e9;
    border-radius: 8px;
    padding: 15px;
    display: flex;
    align-items: flex-start;
}

.success-icon {
    font-size: 1.5rem;
    margin-right: 10px;
}

.success-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2e7d32;
    margin-bottom: 5px;
}

.success-text {
    color: #388e3c;
    font-size: 0.9rem;
}

.restaurant-map {
    height: 200px;
}

.map-placeholder {
    height: 100%;
    background-color: #f1f1f1;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.map-icon {
    font-size: 2rem;
    margin-bottom: 10px;
    color: #555;
}

.map-text {
    color: #777;
}

@media (max-width: 992px) {
    .content-wrapper {
        flex-direction: column;
    }

    .reservation-sidebar {
        position: static;
    }
}

@media (max-width: 768px) {
    .restaurant-hero {
        height: 40vh;
    }

    .restaurant-name {
        font-size: 2.2rem;
    }

    .form-row {
        flex-direction: column;
        gap: 15px;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>
