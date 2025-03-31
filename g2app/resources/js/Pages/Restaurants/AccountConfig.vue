<template>
    <layout>
        <div class="flex justify-center items-center min-h-screen bg-gray-50">
            <div class="bg-white p-8 rounded-lg shadow-lg max-w-4xl w-full text-left">
                <!-- Capçalera -->
                <header class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-800">Configuració del Compte</h1>
                    <p class="text-gray-600 text-lg mt-2">Actualitza les teves dades personals i configura el teu compte aquí.</p>
                </header>

                <!-- Informació de l'usuari -->
                <div class="space-y-6">
                    <div class="border-b pb-4">
                        <h2 class="text-xl font-semibold text-gray-700">Informació Personal</h2>
                        <!-- Aquí utilitzem el component UserInfo per mostrar la informació de l'usuari -->
                        <UserInfo :user="user" />
                    </div>

                    <!-- Comprovació si és empresa i si té restaurant associat -->
                    <div v-if="isEmpresa()" class="mt-6">
                        <!-- Comprovació si l'usuari té un restaurant associat -->
                        <div v-if="restaurant">

                            <div class="mt-4">
                                <Link :href="route('restaurant.management', { id: restaurant.id })" class="bg-blue-500 text-white px-4 py-2 rounded">
                                    Gestiona el teu negoci
                                </Link>
                            </div>
                        </div>
                        <!-- Si no té restaurant associat, mostrar la creació de negoci -->
                        <div v-else>
                            <p class="text-gray-600 text-lg">No tens cap negoci associat.</p>
                            <!--
                            <SecondaryButton @click="showCreatePopup" class="w-full sm:w-auto mt-4">
                                Crear negoci
                            </SecondaryButton>
                            -->
                            <div class="mt-4">
                                <Link :href="route('restaurants.create')" class="bg-blue-500 text-white px-4 py-2 rounded">
                                    Crear Negoci
                                </Link>
                            </div>
                        </div>
                    </div>
                    <!-- Si l'usuari no és empresa, no mostra cap botó -->
                    <div v-else>
                        <p class="text-gray-600 text-lg">No tens permisos per gestionar un negoci.</p>
                    </div>

                    <!-- Informació del restaurant associat -->
                    <div v-if="restaurant" class="mt-8">
                        <RestaurantInfo :restaurant="restaurant" />
                    </div>
                </div>
            </div>

            <!-- Popup per crear un nou restaurant -->
            <PopupModal v-if="showPopup" @close="closePopup" @confirm="createRestaurant" />
        </div>
    </layout>
</template>

<script setup>
import {defineProps, ref, onMounted} from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { usePage } from '@inertiajs/vue3';
import UserInfo from '@/Components/UserInfo.vue';
import RestaurantInfo from '@/Components/RestaurantInfo.vue';
import PopupModal from '@/Components/PopupModal.vue';
import Layout from "@/Layouts/Layout.vue";
import {route} from "ziggy-js";

const { props } = usePage();
const user = ref(props.user);
const restaurant = ref(props.restaurant || null);
const showPopup = ref(false);


onMounted(() => {
    fetchUserRestaurant(props.user.id);
});

function fetchUserRestaurant(userId) {
    fetch(route('user.restaurant', { userId }))
        .then(response => response.json())
        .then(data => {
            restaurant.value = data.restaurant;
        });
}




// Funció per verificar si l'usuari és empresa
function isEmpresa() {
    return user.value?.empresa === 1;
}

// Funció per crear el restaurant
function createRestaurant() {
    Inertia.visit(route('restaurants.create'));
    showPopup.value = false;
}

// Funció per tancar el popup
function closePopup() {
    showPopup.value = false;
}
</script>

<style scoped>
/* Estils Globals */
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

.mt-6 {
    margin-top: 1.5rem;
}

.mt-8 {
    margin-top: 2rem;
}

/* Estils dels botons */
.bg-blue-500 {
    background-color: #3b82f6;
}

.bg-gray-300 {
    background-color: #e5e7eb;
}

.text-white {
    color: white;
}

.text-gray-700 {
    color: #374151;
}

.hover\:bg-blue-600:hover {
    background-color: #2563eb;
}

.hover\:bg-gray-400:hover {
    background-color: #d1d5db;
}

.transition {
    transition: all 0.3s ease;
}

/* Responsive */
@media (max-width: 640px) {
    .max-w-4xl {
        max-width: 100%;
    }
    .p-8 {
        padding: 1.5rem;
    }
}
</style>
