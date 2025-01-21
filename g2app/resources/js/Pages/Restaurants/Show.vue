<template>
    <layout>
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
            <div class="mb-4">
                <h1 class="text-2xl font-bold">{{ restaurant.nom }}</h1>
            </div>
            <div class="mb-4">
                <p><strong>Descripció:</strong></p>
                <p> {{ restaurant.descripcio }}</p>
            </div>
            <div class="mb-4">
                <p><strong>Telèfon:</strong></p>
                <p>{{ restaurant.telefon }}</p>
            </div>
            <div class="mb-4">
                <p><strong>Cuina:</strong> </p>
                <p>{{ Array.isArray(restaurant.tipus_cuina) ? restaurant.tipus_cuina.join(', ') : restaurant.tipus_cuina }}</p>
            </div>
        </div>
    </layout>
</template>

<script setup>
import {defineProps} from 'vue';
import Layout from '@/Layouts/Layout.vue';

const props = defineProps({
    restaurant: Object,
});
</script>

<style scoped>
</style>
