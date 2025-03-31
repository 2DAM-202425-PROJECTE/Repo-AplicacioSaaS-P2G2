<template>
    <div class="flex justify-center items-center min-h-screen bg-gray-50">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-4xl w-full text-left">
            <!-- Capçalera -->
            <header class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Informació del Restaurant</h1>
                <p class="text-gray-600 text-lg mt-2">Dades del teu restaurant aquí.</p>
            </header>

            <!-- Aquí anirà la informació del restaurant -->
            <div v-if="restaurant">
                <p><strong>Nom del Restaurant:</strong> {{ restaurant.name }}</p>
                <p><strong>Telefon:</strong> {{ restaurant.telefon }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';

defineProps({
    restaurant: Object
});
</script>

<style scoped>
/* Estils locals per al component */
.bg-gray-50 {
    background-color: #f9fafb;
}
.text-gray-800 {
    color: #1f2937;
}
.text-gray-600 {
    color: #4b5563;
}
.text-lg {
    font-size: 1.125rem;
}
.text-xl {
    font-size: 1.25rem;
}
.font-semibold {
    font-weight: 600;
}
.shadow-lg {
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}
.max-w-4xl {
    max-width: 56rem;
}
.w-full {
    width: 100%;
}
.p-8 {
    padding: 2rem;
}
.rounded-lg {
    border-radius: 12px;
}
.mb-6 {
    margin-bottom: 1.5rem;
}
.mt-2 {
    margin-top: 0.5rem;
}
.mt-4 {
    margin-top: 1rem;
}
</style>
