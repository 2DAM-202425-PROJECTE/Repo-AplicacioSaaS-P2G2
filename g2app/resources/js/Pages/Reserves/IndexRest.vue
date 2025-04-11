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
                        <th class="py-3 px-4 text-left border-b border-gray-300">Solicituds</th>
                        <th class="py-3 px-4 text-left border-b border-gray-300">Accions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-for="reserva in reserves" :key="reserva.id">
                        <tr
                            class="border-b border-gray-300 cursor-pointer hover:bg-gray-100 text-blue-950"
                            @click="toggleExpanded(reserva.id)"
                        >
                            <td class="py-3 px-4">{{ formatDate(reserva.data) }}</td>
                            <td class="py-3 px-4">{{ formatHour(reserva.hora) }}</td>
                            <td class="py-3 px-4">{{ reserva.telefon }}</td>
                            <td class="py-3 px-4">{{ reserva.num_persones }}</td>
                            <td class="py-1 px-2">
                                <select v-model="reserva.estat" class="p-2 text-sm pr-6"
                                        @change="updateStatus(reserva.id, reserva.estat)" @click.stop>
                                    <option :value="0">Pendent</option>
                                    <option :value="1">Confirmada</option>
                                    <option :value="2">Cancel·lada</option>
                                    <option :value="3">Completada</option>
                                </select>
                            </td>
                            <td class="py-3 px-4">{{ reserva.terrassa ? 'Sí' : 'No' }}</td>
                            <td class="py-3 px-4">
                                    <span v-if="reserva.solicituds && reserva.solicituds.length > 10"
                                          :title="reserva.solicituds">
                                        {{ reserva.solicituds.substring(0, 10) }}...
                                    </span>
                                <span v-else>{{ reserva.solicituds }}</span>
                            </td>
                            <td class="flex items-center space-x-4 py-3 px-4" @click.stop>
                                <ModalLink :href="route('reserves.edit', { id: reserva.id })">
                                    <button class="text-blue-950 hover:text-blue-700 ">
                                        <svg class="size-7" fill="currentColor" viewBox="0 0 24 24"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z"/>
                                            <path
                                                d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z"/>
                                        </svg>
                                    </button>
                                </ModalLink>
                                <ModalLink :href="route('reserves.delete', { id: reserva.id })">
                                    <button class="text-red-600 hover:text-red-800">
                                        <svg class="size-6" fill="currentColor" viewBox="0 0 22 26"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path clip-rule="evenodd"
                                                  d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z"
                                                  fill-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </ModalLink>
                            </td>
                        </tr>
                        <tr v-if="expandedRowId === reserva.id" class="bg-gray-50">
                            <td colspan="8" class="py-4 px-6">
                                <p class="font-semibold">Sol·licituds:</p>
                                <p class="text-gray-700">{{ reserva.solicituds }}</p>
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
import {ModalLink} from '@inertiaui/modal-vue';

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
    router.put(route('reserves.updateEstat', {id}), {estat: newStatus}, {
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

</style>
