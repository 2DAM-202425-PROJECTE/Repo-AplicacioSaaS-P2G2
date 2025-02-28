<template>
    <layout>
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md space-y-6">
        <Link :href="route('restaurants.show', { id: restaurant.id })" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded mb-6">
            <-Tornar
        </Link>
        <h1 class="text-3xl font-bold mb-4">Reserves de {{ restaurant.nom }}</h1>
            <table class="min-w-full bg-white">
                <thead>
                <tr class="bg-gray-200">
                    <th class="py-2 px-4 text-left">Data</th>
                    <th class="py-2 px-4 text-left">Hora</th>
                    <th class="py-2 px-4 text-left">Telèfon</th>
                    <th class="py-2 px-4 text-left">Persones</th>
                    <th class="py-2 px-4 text-left">Estat</th>
                    <th class="py-2 px-4 text-left">Terrassa</th>
                    <th class="py-2 px-4 text-left">Solicituds</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="reserves in reservations" :key="reserves.id" class="border-b">
                    <td class="py-2 px-4">{{ reserves.data }}</td>
                    <td class="py-2 px-4">{{ reserves.hora }}</td>
                    <td class="py-2 px-4">{{ reserves.telefon }}</td>
                    <td class="py-2 px-4">{{ reserves.num_persones }}</td>
                    <td class="py-2 px-4">{{ getStatusText(reserves.estat) }}</td>
                    <td class="py-2 px-4">{{ reserves.terrassa ? 'Sí' : 'No' }}</td>
                    <td class="py-2 px-4">{{ reserves.solicituds }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </layout>
</template>

<script setup>
import { defineProps } from 'vue';
import Layout from '@/Layouts/Layout.vue';
import {route} from "ziggy-js";
import {Link} from "@inertiajs/vue3";

const props = defineProps({
    restaurant: Object,
    reservations: Array,
});

const getStatusText = (status) => {
    switch (status) {
        case 0:
            return 'Pendent';
        case 1:
            return 'Confirmada';
        case 2:
            return 'Cancel·lada';
        case 3:
            return 'Completada';
        default:
            return 'Desconegut'; // Handle unexpected values
    }
};
</script>
