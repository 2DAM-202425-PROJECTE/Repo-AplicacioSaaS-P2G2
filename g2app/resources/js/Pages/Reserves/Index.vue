<template>
    <layout>
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md space-y-6">
            <Link :href="route('restaurants.show', { id: restaurant.id })"
                  class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded mb-6">
                <-Tornar
            </Link>
            <h1 class="text-3xl font-bold mb-4">Reserves de {{ restaurant.nom }}</h1>
            <table class="min-w-full bg-white">
                <thead>
                <tr class="bg-gray-200 text-gray-900">
                    <th class="py-2 px-4 text-left">Data</th>
                    <th class="py-2 px-4 text-left">Hora</th>
                    <th class="py-2 px-4 text-left">Telèfon</th>
                    <th class="py-2 px-4 text-left">Persones</th>
                    <th class="py-2 px-4 text-left">Estat</th>
                    <th class="py-2 px-4 text-left">Terrassa</th>
                    <th class="py-2 px-4 text-left">Taula</th>
                    <th class="py-2 px-4 text-left">Solicituds</th>
                </tr>
                </thead>
                <tbody>
                <template v-for="reserves in reserves" :key="reserves.id">
                    <tr @click="toggleExpanded(reserves.id)" class="border-b cursor-pointer hover:bg-gray-50 text-blue-950">
                        <td class="py-2 px-4">{{ reserves.data }}</td>
                        <td class="py-2 px-4">{{ reserves.hora }}</td>
                        <td class="py-2 px-4">{{ reserves.telefon }}</td>
                        <td class="py-2 px-4">{{ reserves.num_persones }}</td>
                        <td class="py-2 px-4">{{ getStatusText(reserves.estat) }}</td>
                        <td class="py-2 px-4">{{ reserves.terrassa ? 'Sí' : 'No' }}</td>
                        <td class="py-2 px-4">{{ reserves.id_taula }}</td>
                        <td class="py-2 px-4">
                            <span v-if="reserves.solicituds && reserves.solicituds.length > 10"
                                  :title="reserves.solicituds">
                                {{ reserves.solicituds.substring(0, 10) }}...
                            </span>
                            <span v-else>{{ reserves.solicituds }}</span>
                        </td>
                    </tr>
                    <tr :class="{ 'hidden': expandedRowId !== reserves.id }">
                        <td colspan="8" class="p-4 bg-gray-100">
                            <div class="space-y-2">
                                <p><strong>Sol·licituds:</strong> {{ reserves.solicituds }}</p>
                            </div>
                        </td>
                    </tr>
                </template>
                </tbody>
            </table>
        </div>
    </layout>
</template>

<script setup>
import { defineProps, ref } from 'vue';
import Layout from '@/Layouts/Layout.vue';
import { route } from "ziggy-js";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    restaurant: Object,
    reserves: Array,
});

const expandedRowId = ref(null);

const toggleExpanded = (id) => {
    expandedRowId.value = expandedRowId.value === id ? null : id;
};

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
            return 'Desconegut';
    }
};
</script>

<style scoped>
.hidden {
    display: none;
}
</style>
