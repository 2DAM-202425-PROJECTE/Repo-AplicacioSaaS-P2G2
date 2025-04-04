<template>
    <layout>
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md space-y-6">
            <Link :href="route('restaurants.show', { id: restaurant.id })"
                  class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded mb-6">
                <-Tornar
            </Link>
            <h1 class="text-3xl font-bold mb-4">Reserves de {{ restaurant.nom }}</h1>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300">
                    <thead>
                    <tr class="bg-gray-200 text-gray-900">
                        <th class="py-3 px-4 text-left border-b border-gray-300">Día</th>
                        <th class="py-3 px-4 text-left border-b border-gray-300">Hora</th>
                        <th class="py-3 px-4 text-left border-b border-gray-300">Telèfon</th>
                        <th class="py-3 px-4 text-left border-b border-gray-300">Persones</th>
                        <th class="py-3 px-4 text-left border-b border-gray-300">Estat</th>
                        <th class="py-3 px-4 text-left border-b border-gray-300">Terrassa</th>
                        <th class="py-3 px-4 text-left border-b border-gray-300">Taula</th>
                        <th class="py-3 px-4 text-left border-b border-gray-300">Solicituds</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-for="reserves in reserves" :key="reserves.id">
                        <tr @click="toggleExpanded(reserves.id)"
                            class="border-b border-gray-300 cursor-pointer hover:bg-gray-100 text-blue-950">
                            <td class="py-3 px-4">{{ formatDate(reserves.data) }}</td>
                            <td class="py-3 px-4">{{ formatHour(reserves.hora) }}</td>
                            <td class="py-3 px-4">{{ reserves.telefon }}</td>
                            <td class="py-3 px-4">{{ reserves.num_persones }}</td>
                            <td class="py-1 px-2">
                                <select @click.stop v-model="reserves.estat"
                                        @change="updateStatus(reserves.id, reserves.estat)" class="p-2 text-sm pr-6">
                                    <option :value="0">Pendent</option>
                                    <option :value="1">Confirmada</option>
                                    <option :value="2">Cancel·lada</option>
                                    <option :value="3">Completada</option>
                                </select>
                            </td>
                            <td class="py-3 px-4">{{ reserves.terrassa ? 'Sí' : 'No' }}</td>
                            <td class="py-3 px-4">{{ reserves.id_taula }}</td>
                            <td class="py-3 px-4">
                                <span v-if="reserves.solicituds && reserves.solicituds.length > 10"
                                      :title="reserves.solicituds">
                                    {{ reserves.solicituds.substring(0, 10) }}...
                                </span>
                                <span v-else>{{ reserves.solicituds }}</span>
                            </td>
                        </tr>
                        <tr :class="{ 'hidden': expandedRowId !== reserves.id }">
                            <td colspan="8" class="p-4 bg-gray-100 border-b border-gray-300">
                                <div class="space-y-2">
                                    <p><strong>Sol·licituds:</strong> {{ reserves.solicituds }}</p>
                                </div>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                </table>
            </div>
        </div>
    </layout>
</template>

<script setup>
import {defineProps, ref} from 'vue';
import Layout from '@/Layouts/Layout.vue';
import {route} from "ziggy-js";
import {Link, router} from "@inertiajs/vue3";

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

const formatDate = (dateString) => {
    const date = new Date(dateString);
    const day = date.getDate().toString().padStart(2, '0');
    const month = (date.getMonth() + 1).toString().padStart(2, '0'); // Months are 0-indexed
    return `${day}/${month}`;
};

const formatHour = (timeString) => {
    const time = new Date(`1970-01-01T${timeString}`);
    const hours = time.getHours().toString().padStart(2, '0');
    const minutes = time.getMinutes().toString().padStart(2, '0');
    return `${hours}:${minutes}`;
};

const updateStatus = (id, newStatus) => {
    router.put(route('reserves.update', {id}), {estat: newStatus}, {
        onSuccess: () => {
            console.log('Status updated successfully');
        },
        onError: (errors) => {
            console.error('Error updating status:', errors);
        }
    });
};
</script>

<style scoped>
.hidden {
    display: none;
}
</style>
