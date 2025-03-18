<template>
    <layout>
        <div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-md flex">
            <!-- Sidebar de Filtres -->
            <div class="w-1/4 bg-gray-100 p-4 rounded-lg shadow-md">
                <h2 class="text-xl font-bold mb-4">Filtres</h2>

                <!-- Municipi -->
                <label class="block text-gray-700 font-semibold">Municipi</label>
                <select v-model="filters.municipio" class="border p-2 rounded mb-4 w-full">
                    <option value="">Tots els municipis</option>
                    <option v-for="municipio in uniqueMunicipios" :key="municipio" :value="municipio">{{ municipio }}</option>
                </select>

                <!-- Tipus de cuina -->
                <label class="block text-gray-700 font-semibold">Tipus de Cuina</label>
                <select v-model="filters.tipus_cuina" class="border p-2 rounded mb-4 w-full">
                    <option value="">Tots els tipus de cuina</option>
                    <option v-for="tipus in uniqueTipusCuina" :key="tipus" :value="tipus">{{ tipus }}</option>
                </select>

                <button @click="applyFilters" class="bg-blue-500 text-white px-4 py-2 rounded w-full flex items-center justify-center">
                    <i class="fas fa-filter mr-2"></i> Aplicar filtres
                </button>
            </div>

            <!-- Contingut principal -->
            <div class="w-3/4 pl-6">
                <!-- Barra de cerca -->
                <div class="flex items-center border-b-2 border-gray-300 py-2 mb-4">
                    <i class="fas fa-search text-gray-500 mr-2"></i>
                    <input
                        v-model="filters.nom"
                        type="text"
                        placeholder="Cerca per restaurants"
                        class="w-full p-2 focus:outline-none"
                        @keyup.enter="applyFilters"
                        @blur="applyFilters"
                    >
                </div>

                <!-- Llistat de Restaurants -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <Link v-for="restaurant in filteredRestaurants" :key="restaurant.id" :href="`/restaurants/${restaurant.id}`" class="bg-gray-100 p-4 shadow block text-blue-500 hover:underline hover:text-blue-700">
                        <h2 class="text-xl font-bold">{{ restaurant.nom }}</h2>
                        <p class="text-gray-700">{{ restaurant.municipio.name }}</p>
                        <p class="text-gray-700">{{ restaurant.tipus_cuina }}</p>
                    </Link>
                </div>

                <div class="mt-4">
                    <Link :href="route('restaurants.create')" class="bg-blue-500 text-white px-4 py-2 rounded">
                        Crear Restaurant
                    </Link>
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Layouts/Layout.vue';
import { route } from "ziggy-js";

export default {
    components: {
        Layout,
    },
    props: {
        restaurants: Array,
        municipios: Array,
        tipusCuina: Array,
    },
    data() {
        return {
            filters: {
                nom: '',
                municipio: '',
                tipus_cuina: '',
            },
            filteredRestaurants: this.restaurants,
        };
    },
    methods: {
        route,
        applyFilters() {
            this.filteredRestaurants = this.restaurants.filter(restaurant => {
                return (
                    (!this.filters.nom || restaurant.nom.toLowerCase().includes(this.filters.nom.toLowerCase())) &&
                    (!this.filters.municipio || restaurant.municipio.name.trim() === String(this.filters.municipio).trim()) &&
                    (!this.filters.tipus_cuina || restaurant.tipus_cuina.trim() === String(this.filters.tipus_cuina).trim())
                );
            });
        }
    },
    computed: {
        uniqueMunicipios() {
            return [...new Set(this.restaurants.map(r => r.municipio.name))].sort();
        },
        uniqueTipusCuina() {
            return [...new Set(this.restaurants.map(r => r.tipus_cuina))].sort();
        }
    }
};
</script>

<!-- Afegir Font Awesome -->
<style>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');
</style>
