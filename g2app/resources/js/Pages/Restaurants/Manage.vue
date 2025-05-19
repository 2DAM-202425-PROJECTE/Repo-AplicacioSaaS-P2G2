<script setup>
import { defineProps, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import Layout from '@/Layouts/Layout.vue';
import EditRestaurant from './Edit.vue';
import ManagePlats from './Plats.vue';
import ReservesIndex from '../Reserves/Restaurant/Index.vue';

const props = defineProps({
    restaurant: Object,
    activeTab: {
        type: String,
        default: 'edit'
    },
    // Props for Edit component
    tipusCuinaOptions: Array,
    provincias: Array,
    municipios: Array,
    // Props for Reserves component
    reserves: Array,
    // Props for Plats component
    plats: Array
});

const currentUrl = computed(() => usePage().url);

const isActive = (tab) => {
    const url = currentUrl.value;

    switch (tab) {
        case 'edit':
            return url.includes(`/restaurants/${props.restaurant.id}/edit`);
        case 'plats':
            return url.includes(`/restaurants/${props.restaurant.id}/plats`);
        case 'reserves':
            return url.includes(`/restaurants/${props.restaurant.id}/reserves`);
        default:
            return false;
    }
};
</script>

<template>
    <layout>
        <div class="max-w-7xl mx-auto p-6">

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="flex border-b">
                    <Link :href="route('restaurants.edit', { id: restaurant.id })"
                          class="py-4 px-6 font-medium transition-colors duration-200"
                          :class="isActive('edit') ? 'bg-blue-500 text-white' : 'hover:bg-gray-100 text-gray-700'">
                        Editar Restaurant
                    </Link>

                    <Link :href="route('restaurants.plats', { id: restaurant.id })"
                          class="py-4 px-6 font-medium transition-colors duration-200"
                          :class="isActive('plats') ? 'bg-blue-500 text-white' : 'hover:bg-gray-100 text-gray-700'">
                        Gestionar Carta
                    </Link>

                    <Link :href="route('restaurant.reserves', { id: restaurant.id })"
                          class="py-4 px-6 font-medium transition-colors duration-200"
                          :class="isActive('reserves') ? 'bg-blue-500 text-white' : 'hover:bg-gray-100 text-gray-700'">
                        Reserves
                    </Link>
                </div>

                <div class="p-6">
                    <Link :href="route('restaurants.show', { id: restaurant.id })" class="block shadow-md hover:bg-gray-50 transition-colors duration-200">
                        <div class="p-6">
                            <div class="flex items-center mb-6">
                                <img v-if="restaurant.profile_image" :src="`/storage/${restaurant.profile_image}`" alt="Restaurant Image"
                                     class="w-20 h-20 rounded-full object-cover mr-4">
                                <div>
                                    <h1 class="text-2xl font-bold">{{ restaurant.nom }}</h1>
                                    <p class="text-gray-600">{{ restaurant.tipus_cuina }}</p>
                                    <p class="text-gray-600">{{ restaurant.descripcio }}</p>
                                    <p class="text-gray-600">
                                        {{ restaurant.carrer }}, {{ restaurant.municipio?.name }}, {{ restaurant.municipio?.provincia?.name }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </Link>

                    <!-- Dynamic Component Display -->
                    <EditRestaurant v-if="isActive('edit')"
                                    :restaurant="restaurant"
                                    :tipusCuinaOptions="tipusCuinaOptions"
                                    :provincias="provincias"
                                    :municipios="municipios" />

                    <ManagePlats v-else-if="isActive('plats')"
                                 :restaurant="restaurant" />


                    <ReservesIndex v-else-if="isActive('reserves')"
                                   :restaurant="restaurant"
                                   :reserves="reserves" />
                </div>
            </div>
        </div>
    </layout>
</template>
