<template>
    <layout>
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md space-y-6">
            <h1 class="text-2xl font-bold mb-4">Les Meves Reserves</h1>
            <div v-if="reserves.length > 0" class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300">
                    <thead>
                    <tr class="bg-gray-200 text-gray-900">
                        <th class="py-3 px-4 text-left border-b border-gray-300">Restaurant</th>
                        <th class="py-3 px-4 text-left border-b border-gray-300">Día</th>
                        <th class="py-3 px-4 text-left border-b border-gray-300">Hora</th>
                        <th class="py-3 px-4 text-left border-b border-gray-300">Persones</th>
                        <th class="py-3 px-4 text-left border-b border-gray-300">Estat</th>
                        <th class="py-3 px-4 text-left border-b border-gray-300">Terrassa</th>
                        <th class="py-3 px-4 text-left border-b border-gray-300">Solicituds</th>
                        <th class="py-3 px-4 text-left border-b border-gray-300">Accions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="reserva in reserves" :key="reserva.id" class="border-b border-gray-300">
                        <td class="py-3 px-4">{{ reserva.restaurant.nom }}</td>
                        <td class="py-3 px-4">{{ formatDate(reserva.data) }}</td>
                        <td class="py-3 px-4">{{ formatHour(reserva.hora) }}</td>
                        <td class="py-3 px-4">{{ reserva.num_persones }}</td>
                        <td class="py-3 px-4">{{ getStatusText(reserva.estat) }}</td>
                        <td class="py-3 px-4">{{ reserva.terrassa ? 'Sí' : 'No' }}</td>
                        <td class="py-3 px-4">
                            <span v-if="reserva.solicituds && reserva.solicituds.length > 10"
                                  :title="reserva.solicituds">
                                {{ reserva.solicituds.substring(0, 10) }}...
                            </span>
                            <span v-else>{{ reserva.solicituds }}</span>
                        </td>
                        <td class="py-3 px-4 flex space-x-2">
                            <ModalLink v-if="(reserva.estat === 0 || reserva.estat === 1) && reserva.estat !== 2" :href="route('user.reserves.edit', { id: reserva.id })"
                                       class="text-blue-800 hover:text-blue-600 rounded-md hover:bg-blue-50">
                                <svg class="size-7" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z"/>
                                    <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z"/>
                                </svg>
                            </ModalLink>
                            <span v-else class="text-gray-500">
                                <svg class="size-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687 1.688a1.875 1.875 0 1 1-2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931a1.875 1.875 0 1 1 2.652 2.652l-1.687 1.688m-6.75 7.35l-.632.631m0 0a1.5 1.5 0 0 1-2.122-2.122m2.121 2.121l.472.472"/>
                                </svg>
                            </span>

                            <ModalLink v-if="(reserva.estat === 0 || reserva.estat === 1) && reserva.estat !== 2" :href="route('user.reserves.cancel', { id: reserva.id })"
                                       class="text-red-600 hover:text-red-800 rounded-md hover:bg-red-50">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </ModalLink>
                            <span v-else class="text-gray-500">
                                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div v-else class="text-gray-600">
                No tens cap reserva en aquest moment.
            </div>
        </div>
    </layout>
</template>

<script setup>
import { defineProps } from 'vue';
import Layout from '@/Layouts/Layout.vue';
import {route} from "ziggy-js";
import {router } from "@inertiajs/vue3";
import {ModalLink} from "@inertiaui/modal-vue";

const props = defineProps({
    reserves: Array,
});

const formatDate = (dateString) => {
    const date = new Date(dateString);
    const day = date.getDate().toString().padStart(2, '0');
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    return `${day}/${month}`;
};

const formatHour = (timeString) => {
    const time = new Date(`1970-01-01T${timeString}`);
    const hours = time.getHours().toString().padStart(2, '0');
    const minutes = time.getMinutes().toString().padStart(2, '0');
    return `${hours}:${minutes}`;
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

const cancelarReserva = (id) => {
    if (confirm('Estàs segur que vols cancel·lar aquesta reserva?')) {
        router.put(route('user.reserves.cancelar', { id }), { estat: 2 }, { // Assumim que estat 2 és "Cancel·lada"
            onSuccess: () => {
                router.reload(); // Recarrega la pàgina per actualitzar la llista
            },
            onError: (errors) => {
                console.error('Error al cancel·lar la reserva:', errors);
            },
        });
    }
};
</script>

<style scoped>

</style>
