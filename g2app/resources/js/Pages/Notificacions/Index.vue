<template>
    <layout>
        <div class="p-4">
            <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Les teves Notificacions</h1>

            <!-- Llista de notificacions -->
            <div v-if="notificacions.length > 0">
                <ul class="space-y-4">
                    <li
                        v-for="(notificacio, index) in notificacions"
                        :key="index"
                        class="p-5 bg-white shadow-md rounded-lg border border-gray-200 hover:bg-gray-50 transition transform hover:scale-105"
                    >
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-lg font-semibold text-gray-800">{{ notificacio.missatge }}</p>
                            <span class="text-sm text-gray-500">{{ notificacio.data }}</span>
                        </div>
                        <div class="text-gray-600">
                            <p class="text-sm">{{ notificacio.missatge }}</p>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Missatge quan no hi ha notificacions -->
            <div v-else class="text-center text-gray-500 mt-10">
                <p class="text-xl font-semibold">No tens notificacions per a aquest moment.</p>
                <p class="text-sm">Potser hauràs de tornar més tard per veure les noves actualitzacions!</p>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from "@/Layouts/Layout.vue";

export default {
    name: 'NotificacionsList',
    components: { Layout },
    props: {
        // Rebem les notificacions passades per Inertia
        notificacions: Array,
    },
};
</script>

<style scoped>
/* Estils personalitzats per millorar l'aparença */
ul {
    max-width: 800px;
    margin: 0 auto;
}
</style>
