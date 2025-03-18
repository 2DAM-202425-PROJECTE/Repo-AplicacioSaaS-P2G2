<template>
    <layout>
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
            <h1 class="text-2xl font-bold mb-4">Restaurants:</h1>
            <div class="mb-4">
                <input v-model="filters.nom" type="text" placeholder="Cerca per nom" class="border p-2 rounded mb-2 w-full">
                <select v-model="filters.municipio" class="border p-2 rounded mb-2 w-full">
                    <option value="">Tots els municipis</option>
                    <option v-for="municipio in uniqueMunicipios" :key="municipio" :value="municipio">{{ municipio }}</option>
                </select>

                <select v-model="filters.tipus_cuina" class="border p-2 rounded mb-2 w-full">
                    <option value="">Tots els tipus de cuina</option>
                    <option v-for="tipus in uniqueTipusCuina" :key="tipus" :value="tipus">{{ tipus }}</option>
                </select>
                <button @click="applyFilters" class="bg-blue-500 text-white px-4 py-2 rounded">Aplicar filtres</button>
            </div>
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
