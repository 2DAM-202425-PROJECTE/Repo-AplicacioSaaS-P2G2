<template>
    <layout>
        <div class="max-w-5xl mx-auto bg-[#1B1B1B] p-10 rounded-xl shadow-lg border border-[#3D3D3D] text-white">
            <h1 class="text-4xl font-extrabold text-gold-500 mb-6 border-b border-[#3D3D3D] pb-4">Reserves</h1>
            <ul class="list-none space-y-4">
                <li v-for="reserva in reserves" :key="reserva.id" class="reservation-card">
                    <h2 class="text-2xl font-bold text-gold-500">Reserva per a {{ reserva.num_persones }} persones</h2>
                    <p class="text-gray-400">📞 {{ reserva.telefon }}</p>
                    <p class="text-gray-400">📅 {{ reserva.data }}</p>
                    <p class="text-gray-400">🕒 {{ reserva.hora }}</p>
                    <p class="text-gray-400">🪑 Taula: {{ reserva.id_taula }}</p>
                </li>
            </ul>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Layouts/Layout.vue';
import { route } from "ziggy-js";
import axios from 'axios';

export default {
    data() {
        return {
            reserves: [],
        };
    },
    components: {
        Layout,
    },
    props: {
        restaurantId: {
            type: Number,
            required: true,
        },
    },
    mounted() {
        this.fetchReserves();
    },
    methods: {
        async fetchReserves() {
            try {
                const response = await axios.get(route('reserves.index', { restaurant_id: this.restaurantId }));
                this.reserves = response.data;
            } catch (error) {
                console.error('Error fetching reserves:', error);
            }
        },
    },
}
</script>

<style scoped>
.reservation-card {
    display: block;
    background: #2A2A2A;
    border-radius: 10px;
    padding: 15px;
    transition: transform 0.2s ease, background 0.2s ease;
}

.reservation-card:hover {
    background: #3A3A3A;
    transform: scale(1.02);
}
</style>
