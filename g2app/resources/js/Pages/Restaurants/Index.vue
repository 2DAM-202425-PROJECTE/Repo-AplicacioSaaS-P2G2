<template>
    <layout>
        <div class="restaurants-page">
            <div class="header-section">
                <div class="container">
                    <h1 class="page-title">Descobreix els millors restaurants</h1>
                    <p class="page-subtitle">Troba el restaurant perfecte i fes la teva reserva en pocs clics</p>
                </div>
            </div>

            <div class="main-content container">
                <!-- Sidebar de Filtres -->
                <div class="filters-sidebar">
                    <div class="filters-header">
                        <h2 class="filters-title">🍽️ Filtres</h2>
                    </div>

                    <div class="filters-body">
                        <!-- Favorits -->
                        <div class="filter-group">
                            <label class="filter-label">❤️ Favorits</label>
                            <select v-model="filters.favorites" class="filter-select">
                                <option value="">Tots</option>
                                <option value="true">Només favorits</option>
                                <option value="false">Sense favorits</option>
                            </select>
                        </div>

                        <!-- Municipi -->
                        <div class="filter-group">
                            <label class="filter-label">📍 Municipi</label>
                            <select v-model="filters.municipio" class="filter-select">
                                <option value="">Tots els municipis</option>
                                <option v-for="municipio in uniqueMunicipios" :key="municipio" :value="municipio">{{ municipio }}</option>
                            </select>
                        </div>

                        <!-- Tipus de cuina -->
                        <div class="filter-group">
                            <label class="filter-label">🍲 Tipus de Cuina</label>
                            <select v-model="filters.tipus_cuina" class="filter-select">
                                <option value="">Tots els tipus de cuina</option>
                                <option v-for="tipus in uniqueTipusCuina" :key="tipus" :value="tipus">{{ tipus }}</option>
                            </select>
                        </div>

                        <!-- Tipus de plat -->
                        <div class="filter-group">
                            <label class="filter-label">🍽️ Tipus de Plat</label>
                            <select v-model="filters.tipus_plat" class="filter-select">
                                <option value="">Tots els tipus de plats</option>
                                <option v-for="tipus in uniqueTipusPlat" :key="tipus" :value="tipus">{{ tipus }}</option>
                            </select>
                        </div>

                        <button @click="applyFilters" class="apply-filters-btn">
                            <i class="fas fa-filter"></i> Aplicar filtres
                        </button>
                    </div>
                </div>

                <!-- Contingut principal -->
                <div class="restaurants-content">
                    <!-- Barra de cerca -->
                    <div class="search-bar">
                        <i class="fas fa-search search-icon"></i>
                        <input
                            v-model="filters.nom"
                            type="text"
                            placeholder="Cerca restaurants..."
                            class="search-input"
                            @keyup.enter="applyFilters"
                            @blur="applyFilters"
                        >
                    </div>

                    <!-- Resultats i ordenació -->
                    <div class="results-header">
                        <p class="results-count">{{ filteredRestaurants.length }} restaurants trobats</p>
                        <div class="sort-options">
                            <label class="sort-label">Ordenar per:</label>
                            <select class="sort-select">
                                <option value="popular">Popularitat</option>
                                <option value="rating">Valoració</option>
                                <option value="name">Nom</option>
                            </select>
                        </div>
                    </div>

                    <!-- Llistat de Restaurants -->
                    <div class="restaurants-grid">
                        <div v-for="restaurant in paginatedRestaurants" :key="restaurant.id"
                             class="restaurant-card">
                            <div class="restaurant-image-container">
                                <img :src="`/storage/${restaurant.profile_image}`" alt="Restaurant Image" class="restaurant-image">
                                <button @click.prevent="toggleFavorite(restaurant)" class="favorite-button" :class="{ 'is-favorite': isFavorite(restaurant.id) }">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                            <Link :href="`/restaurants/${restaurant.id}`" class="restaurant-details">
                                <div class="restaurant-info">
                                    <h3 class="restaurant-name">{{ restaurant.nom }}</h3>
                                    <p class="restaurant-location"><i class="fas fa-map-marker-alt location-icon"></i> {{ restaurant.municipio.name }}</p>
                                    <p class="restaurant-cuisine"><i class="fas fa-utensils cuisine-icon"></i> {{ restaurant.tipus_cuina }}</p>
                                </div>
                                <div class="restaurant-rating">
                                    <span class="rating-badge">4.8</span>
                                    <div class="rating-stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                    </div>
                                    <span class="rating-count">(124)</span>
                                </div>
                            </Link>
                            <div class="restaurant-footer">
                                <div class="restaurant-features">
                                    <span class="feature-tag" v-if="restaurant.tipus_cuina === 'Mediterrània'">🌊 Mediterrània</span>
                                    <span class="feature-tag" v-if="restaurant.tipus_cuina === 'Italiana'">🍕 Italiana</span>
                                    <span class="feature-tag" v-if="restaurant.tipus_cuina === 'Japonesa'">🍣 Japonesa</span>
                                </div>
                                <Link :href="`/restaurants/${restaurant.id}`" class="view-details-btn">Veure detalls</Link>
                            </div>
                        </div>
                    </div>

                    <!-- Paginació -->
                    <div class="pagination">
                        <button @click="prevPage" :disabled="currentPage === 1" class="pagination-btn prev-btn">
                            <i class="fas fa-chevron-left"></i> Anterior
                        </button>
                        <div class="pagination-info">
                            Pàgina {{ currentPage }} de {{ totalPages }}
                        </div>
                        <button @click="nextPage" :disabled="currentPage === totalPages" class="pagination-btn next-btn">
                            Següent <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </layout>
</template>


<style>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');

.restaurants-page {
    background-color: #f8f9fa;
    min-height: 100vh;
}

.header-section {
    background: linear-gradient(135deg, #FF5A5F, #FF8A8E);
    color: white;
    padding: 60px 0;
    text-align: center;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 15px;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
}

.page-subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
    max-width: 600px;
    margin: 0 auto;
}

.main-content {
    display: flex;
    gap: 30px;
    padding: 40px 20px;
}

/* Sidebar de Filtres */
.filters-sidebar {
    width: 300px;
    flex-shrink: 0;
    background-color: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    position: sticky;
    top: 20px;
    align-self: flex-start;
}

.filters-header {
    background: linear-gradient(135deg, #FF5A5F, #FF8A8E);
    padding: 20px;
    color: white;
}

.filters-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0;
}

.filters-body {
    padding: 20px;
}

.filter-group {
    margin-bottom: 20px;
}

.filter-label {
    display: block;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 8px;
    color: #555;
}

.filter-select {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 10px;
    background-color: white;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.filter-select:focus {
    border-color: #FF5A5F;
    box-shadow: 0 0 0 2px rgba(255, 90, 95, 0.2);
    outline: none;
}

.apply-filters-btn {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #FF5A5F, #FF8A8E);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.apply-filters-btn:hover {
    background: linear-gradient(135deg, #e5484d, #e57a7e);
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(255, 90, 95, 0.3);
}

/* Contingut principal */
.restaurants-content {
    flex: 1;
}

.search-bar {
    position: relative;
    margin-bottom: 25px;
}

.search-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #777;
}

.search-input {
    width: 100%;
    padding: 15px 15px 15px 45px;
    border: 1px solid #ddd;
    border-radius: 10px;
    font-size: 1rem;
    background-color: white;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.search-input:focus {
    border-color: #FF5A5F;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
    outline: none;
}

.results-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.results-count {
    font-size: 0.9rem;
    color: #777;
}

.sort-options {
    display: flex;
    align-items: center;
    gap: 10px;
}

.sort-label {
    font-size: 0.9rem;
    color: #777;
}

.sort-select {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 0.9rem;
    background-color: white;
}

.restaurants-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
}

.restaurant-card {
    background-color: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.restaurant-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.restaurant-image-container {
    position: relative;
    height: 200px;
}

.restaurant-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.favorite-button {
    position: absolute;
    top: 15px;
    right: 15px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: white;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    color: #ccc;
}

.favorite-button:hover {
    transform: scale(1.1);
}

.favorite-button.is-favorite {
    color: #FF5A5F;
}

.restaurant-details {
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.restaurant-info {
    flex: 1;
}

.restaurant-name {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 8px;
    color: #333;
}

.restaurant-location, .restaurant-cuisine {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 5px;
    display: flex;
    align-items: center;
}

.location-icon, .cuisine-icon {
    color: #FF5A5F;
    margin-right: 5px;
    font-size: 0.8rem;
}

.restaurant-rating {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}

.rating-badge {
    background-color: #00B140;
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.9rem;
    margin-bottom: 5px;
}

.rating-stars {
    color: #FFD700;
    font-size: 0.8rem;
    margin-bottom: 3px;
}

.rating-count {
    font-size: 0.8rem;
    color: #777;
}

.restaurant-footer {
    padding: 15px 20px;
    border-top: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.restaurant-features {
    display: flex;
    gap: 8px;
}

.feature-tag {
    background-color: #f0f0f0;
    color: #555;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.8rem;
}

.view-details-btn {
    background-color: #FF5A5F;
    color: white;
    padding: 8px 15px;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.view-details-btn:hover {
    background-color: #e5484d;
}

.pagination {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 40px;
}

.pagination-btn {
    background-color: white;
    border: 1px solid #ddd;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.pagination-btn:hover:not(:disabled) {
    background-color: #f8f9fa;
    border-color: #ccc;
}

.pagination-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.pagination-info {
    font-size: 0.9rem;
    color: #666;
}

/* Responsive */
@media (max-width: 992px) {
    .main-content {
        flex-direction: column;
    }

    .filters-sidebar {
        width: 100%;
        position: static;
        margin-bottom: 30px;
    }

    .restaurants-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    }
}

@media (max-width: 576px) {
    .header-section {
        padding: 40px 0;
    }

    .page-title {
        font-size: 2rem;
    }

    .page-subtitle {
        font-size: 1rem;
    }

    .results-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .restaurants-grid {
        grid-template-columns: 1fr;
    }

    .pagination {
        flex-direction: column;
        gap: 15px;
    }
}
</style>

<script>
import Layout from '@/Layouts/Layout.vue';
import { route } from "ziggy-js";
import { usePage, Link } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import axios from 'axios';
import { reactive, ref } from "vue";

export default {
    components: {
        Layout,
        Link
    },
    props: {
        restaurants: Array,
        municipios: Array,
        tipusCuina: Array,
        plats: Array,
    },
    data() {
        return {
            filters: {
                nom: '',
                municipio: '',
                tipus_cuina: '',
                tipus_plat: '',
                favorites: '',
            },
            currentPage: 1,
            itemsPerPage: 9,
            filteredRestaurants: this.restaurants,
            favoriteRestaurants: reactive({}),
        };
    },
    methods: {
        route,
        applyFilters() {
            console.log("Plats disponibles:", this.plats); // Debugging

            this.filteredRestaurants = this.restaurants.filter(restaurant => {
                let platsValids = true; // Per defecte és true
                let isFavorite = this.isFavorite(restaurant.id);

                if (this.filters.tipus_plat) {
                    platsValids = Array.isArray(this.plats) && this.plats.some(plat => {
                        return plat.id_restaurant === restaurant.id && (plat[this.filters.tipus_plat] === 1 || plat[this.filters.tipus_plat] === true);
                    });
                }
                let favoritesFilter = true;
                if (this.filters.favorites === 'true') {
                    favoritesFilter = isFavorite;
                } else if (this.filters.favorites === 'false') {
                    favoritesFilter = !isFavorite;
                }

                return (
                    (!this.filters.nom || restaurant.nom.toLowerCase().includes(this.filters.nom.toLowerCase())) &&
                    (!this.filters.municipio || restaurant.municipio.name.trim() === String(this.filters.municipio).trim()) &&
                    (!this.filters.tipus_cuina || restaurant.tipus_cuina.trim() === String(this.filters.tipus_cuina).trim()) &&
                    platsValids &&
                    favoritesFilter
                );
            });

            this.currentPage = 1;
        },
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
            }
        },
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },

        async toggleFavorite(restaurant) {
            try {
                const response = await axios.post(route('restaurants.favorite', { restaurant: restaurant.id }));
                this.favoriteRestaurants[restaurant.id] = response.data.isFavorite;
            } catch (error) {
                console.error('Error toggling favorite:', error);
            }
        },
        isFavorite(restaurantId) {
            return this.favoriteRestaurants[restaurantId] !== undefined ? this.favoriteRestaurants[restaurantId] : false;
        },
        async fetchFavorites() {
            try {
                const response = await axios.get('/favorites');
                const favorites = response.data.restaurants.map(restaurant => restaurant.id);
                favorites.forEach(restaurantId => {
                    this.favoriteRestaurants[restaurantId] = true;
                });
            } catch (error) {
                console.error('Error fetching favorites:', error);
            }
        },
    },
    computed: {
        uniqueMunicipios() {
            return [...new Set(this.restaurants.map(r => r.municipio.name))].sort();
        },
        uniqueTipusCuina() {
            return [...new Set(this.restaurants.map(r => r.tipus_cuina))].sort();
        },
        uniqueTipusPlat() {
            return [
                'vegetaria', 'vega', 'kosher', 'halal', 'keto', 'carn_vermella', 'gluten', 'lactics', 'crustaci', 'ous', 'lupines', 'mostassa', 'cacahuats', 'soja'
            ];
        },
        totalPages() {
            return Math.ceil(this.filteredRestaurants.length / this.itemsPerPage);
        },
        paginatedRestaurants() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            return this.filteredRestaurants.slice(start, end);
        }
    },
    mounted() {
        const page = usePage();
        if (page.props.flash.error) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: page.props.flash.error,
                confirmButtonText: "Entès"
            });
        }
        this.fetchFavorites();
    }
};
</script>
