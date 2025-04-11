<template>
    <layout>
        <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-md space-y-6">
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
                        <td class="py-3 px-4">
                            <button v-if="reserva.estat === 0 || reserva.estat === 1"
                                    @click="cancelarReserva(reserva.id)"
                                    class="text-red-600 hover:text-red-800">
                                Cancel·lar
                            </button>
                            <span v-else class="text-gray-500">No disponible</span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div v-else class="text-gray-600">
                No tens cap reserva feta encara.
            </div>
        </div>
    </layout>
</template>

<script setup>
import { defineProps } from 'vue';
import Layout from '@/Layouts/Layout.vue';
import {route} from "ziggy-js";
import {router } from "@inertiajs/vue3";

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
