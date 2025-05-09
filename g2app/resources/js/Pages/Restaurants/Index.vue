<template>
    <layout>
        <div class="background">
            <div class="max-w-6xl mx-auto bg-white bg-opacity-90 p-8 rounded-lg shadow-lg flex border border-gray-200 backdrop-blur-md">
                <!-- Sidebar de Filtres -->
                <div class="w-1/4 bg-gray-50 p-6 rounded-lg shadow-md border-r border-gray-200">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">🍽️ Filtres</h2>

                    <!-- Favorits -->
                    <label class="block text-gray-700 font-medium mb-1">❤️ Favorits</label>
                    <select v-model="filters.favorites" class="border p-3 rounded-lg mb-4 w-full bg-white focus:ring-2 focus:ring-gold">
                        <option value="">Tots</option>
                        <option value="true">Només favorits</option>
                        <option value="false">Sense favorits</option>
                    </select>

                    <!-- Municipi -->
                    <label class="block text-gray-700 font-medium mb-1">📍 Municipi</label>
                    <select v-model="filters.municipio" class="border p-3 rounded-lg mb-4 w-full bg-white focus:ring-2 focus:ring-gold">
                        <option value="">Tots els municipis</option>
                        <option v-for="municipio in uniqueMunicipios" :key="municipio" :value="municipio">{{ municipio }}</option>
                    </select>

                    <!-- Tipus de cuina -->
                    <label class="block text-gray-700 font-medium mb-1">🍲 Tipus de Cuina</label>
                    <select v-model="filters.tipus_cuina" class="border p-3 rounded-lg mb-4 w-full bg-white focus:ring-2 focus:ring-gold">
                        <option value="">Tots els tipus de cuina</option>
                        <option v-for="tipus in uniqueTipusCuina" :key="tipus" :value="tipus">{{ tipus }}</option>
                    </select>

                    <!-- Tipus de plat -->
                    <label class="block text-gray-700 font-medium mb-1">🍽️ Tipus de Plat</label>
                    <select v-model="filters.tipus_plat" class="border p-3 rounded-lg mb-4 w-full bg-white focus:ring-2 focus:ring-gold">
                        <option value="">Tots els tipus de plats</option>
                        <option v-for="tipus in uniqueTipusPlat" :key="tipus" :value="tipus">{{ tipus }}</option>
                    </select>

                    <button @click="applyFilters" class="bg-gold text-white px-4 py-3 rounded-lg w-full flex items-center justify-center shadow-md hover:bg-yellow-600 transition">
                        <i class="fas fa-filter mr-2"></i> Aplicar filtres
                    </button>
                </div>

                <!-- Contingut principal -->
                <div class="w-3/4 pl-8">
                    <!-- Barra de cerca -->
                    <div class="flex items-center border-b-2 border-gray-300 py-2 mb-6">
                        <i class="fas fa-search text-gray-500 mr-3"></i>
                        <input
                            v-model="filters.nom"
                            type="text"
                            placeholder="Cerca restaurants..."
                            class="w-full p-3 text-lg border-none focus:ring-0 focus:outline-none"
                            @keyup.enter="applyFilters"
                            @blur="applyFilters"
                        >
                    </div>

                    <!-- Llistat de Restaurants -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="restaurant in paginatedRestaurants" :key="restaurant.id"
                             class="bg-white p-6 shadow-lg rounded-lg transition-transform transform hover:scale-105 hover:shadow-2xl border border-gray-200">
                            <Link :href="`/restaurants/${restaurant.id}`">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-2xl font-semibold text-gray-900 mb-2">{{ restaurant.nom }}</h2>
                                    <img :src="`/storage/${restaurant.profile_image}`" alt="Profile Image" class="profile-image ml-4">
                                </div>
                                <p class="text-gray-600"><i class="fas fa-map-marker-alt text-gold"></i> {{ restaurant.municipio.name }}</p>
                                <p class="text-gray-600"><i class="fas fa-utensils text-gold"></i> {{ restaurant.tipus_cuina }}</p>
                            </Link>
                            <button @click.prevent="toggleFavorite(restaurant)" class="focus:outline-none">
                                <svg v-if="isFavorite(restaurant.id)" class="h-6 w-6 text-yellow-500 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M10 0 L13.09 6.26 L20 7.26 L15 12.14 L16.18 19 L10 15.77 L3.82 19 L5 12.14 L0 7.26 L6.91 6.26 L10 0 Z"/>
                                </svg>
                                <svg v-else class="h-6 w-6 text-gray-400 fill-current hover:text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M10 0 L13.09 6.26 L20 7.26 L15 12.14 L16.18 19 L10 15.77 L3.82 19 L5 12.14 L0 7.26 L6.91 6.26 L10 0 Z" fill="none" stroke="currentColor" stroke-width="1"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Paginació -->
                    <div class="mt-6 flex justify-center">
                        <button @click="prevPage" :disabled="currentPage === 1" class="px-4 py-2 mx-1 bg-gray-300 rounded hover:bg-gray-400 disabled:opacity-50">
                            &lt; Anterior
                        </button>
                        <button @click="nextPage" :disabled="currentPage === totalPages" class="px-4 py-2 mx-1 bg-gray-300 rounded hover:bg-gray-400 disabled:opacity-50">
                            Següent &gt;
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </layout>
</template>


<style>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');

/* Colors personalitzats */
:root {
    --gold: #D4AF37;
}

/* Personalització general */
.bg-gold {
    background-color: var(--gold);
}

.text-gold {
    color: var(--gold);
}

.profile-image {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}
</style>

<script>
import Layout from '@/Layouts/Layout.vue';
import { route } from "ziggy-js";
import { usePage } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import axios from 'axios';
import {reactive, ref} from "vue";
export default {
    components: {
        Layout,
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
