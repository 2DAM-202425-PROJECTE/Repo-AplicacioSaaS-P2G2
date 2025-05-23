<script setup>
import {defineProps, computed} from 'vue';
import {Link, usePage} from '@inertiajs/vue3';
import {route} from 'ziggy-js';
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

    tipusCuinaOptions: Array,
    provincias: Array,
    municipios: Array,

    reserves: Array,

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
            <div class="p-6">
                <Link :href="route('restaurants.show', { id: restaurant.id })"
                      class="block rounded-xl hover:bg-gray-50 transition-colors duration-200 border border-gray-100 hover:shadow-md">
                    <div class="p-6">
                        <div class="flex items-start md:items-center mb-6 flex-col md:flex-row">
                            <div class="flex-shrink-0 mb-4 md:mb-0">
                                <img v-if="restaurant.profile_image"
                                     :src="`/storage/${restaurant.profile_image}`"
                                     alt="Restaurant Image"
                                     class="w-24 h-24 rounded-lg object-cover shadow-sm md:mr-6">
                                <div v-else
                                     class="w-24 h-24 rounded-lg bg-gray-200 flex items-center justify-center md:mr-6">
                                    <span class="text-3xl">🍽️</span>
                                </div>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ restaurant.nom }}</h1>
                                <div class="flex flex-wrap gap-2 mb-3">
                            <span class="bg-[#e1f5fe] text-[#0288d1] text-xs px-3 py-1 rounded-full font-medium">
                                {{ restaurant.tipus_cuina }}
                            </span>
                                    <span
                                        class="bg-gray-100 text-gray-600 text-xs px-3 py-1 rounded-full font-medium">
                                {{ restaurant.hora_obertura }} - {{ restaurant.hora_tancament }}
                            </span>
                                </div>
                                <p class="text-gray-500 text-sm flex items-center">
                                    <span class="mr-1">📍</span>
                                    {{ restaurant.carrer }}, {{ restaurant.municipio?.name }},
                                    {{ restaurant.municipio?.provincia?.name }}
                                </p>
                            </div>
                        </div>
                    </div>
                </Link>
            </div>
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
                <div class="flex border-b">
                    <Link :class="isActive('edit') ? 'bg-[#FF5A5F] text-white' : 'hover:bg-gray-50 text-gray-700'"
                          :href="route('restaurants.edit', { id: restaurant.id })"
                          class="py-4 px-8 font-semibold text-sm transition-colors duration-200 flex items-center">
                        <span class="mr-2">✏️</span>
                        Editar Restaurant
                    </Link>

                    <Link :class="isActive('plats') ? 'bg-[#FF5A5F] text-white' : 'hover:bg-gray-50 text-gray-700'"
                          :href="route('restaurants.plats', { id: restaurant.id })"
                          class="py-4 px-8 font-semibold text-sm transition-colors duration-200 flex items-center">
                        <span class="mr-2">🍽️</span>
                        Gestionar Carta
                    </Link>

                    <Link :class="isActive('reserves') ? 'bg-[#FF5A5F] text-white' : 'hover:bg-gray-50 text-gray-700'"
                          :href="route('restaurant.reserves', { id: restaurant.id })"
                          class="py-4 px-8 font-semibold text-sm transition-colors duration-200 flex items-center">
                        <span class="mr-2">📅</span>
                        Reserves
                    </Link>
                </div>

                <div class="p-6">

                    <EditRestaurant v-if="isActive('edit')"
                                    :municipios="municipios"
                                    :provincias="provincias"
                                    :restaurant="restaurant"
                                    :tipusCuinaOptions="tipusCuinaOptions"/>

                    <ManagePlats v-else-if="isActive('plats')"
                                 :restaurant="restaurant"/>


                    <ReservesIndex v-else-if="isActive('reserves')"
                                   :reserves="reserves"
                                   :restaurant="restaurant"/>
                </div>
            </div>
        </div>
    </layout>
</template>
