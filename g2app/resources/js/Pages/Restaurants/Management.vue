<template>
    <layout>
        <div class="max-w-4xl mx-auto p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Gestió del Restaurant</h1>

            <!-- Informació del restaurant -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">

                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-semibold text-gray-700">{{ restaurant.nom }}</h2>
                    <img :src="`/storage/${restaurant.profile_image}`" alt="Profile Image" class="w-12 h-12 rounded-full object-cover mb-2 profile-image">
                </div>
                <p class="text-gray-600 mt-2">{{ restaurant.descripcio }}</p>
                <div class="mt-4 text-gray-700">
                    <p><strong>Telèfon:</strong> {{ restaurant.telefon }}</p>
                    <p><strong>Tipus de Cuina:</strong> {{ restaurant.tipus_cuina }}</p>
                </div>

            </div>

            <!-- Accions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-blue-100 p-6 rounded-lg shadow-md text-center">
                    <h3 class="text-xl font-semibold text-blue-700 mb-2">Gestionar Reserves</h3>
                    <p class="text-gray-600 mb-4">Consulta i administra les reserves del teu restaurant de manera eficient.</p>
                    <Link :href="route('restaurant.reserves', { id: restaurant.id })" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded inline-block">
                        Veure Reserves
                    </Link>
                </div>

                <div class="bg-green-100 p-6 rounded-lg shadow-md text-center">
                    <h3 class="text-xl font-semibold text-green-700 mb-2">Modificar Restaurant</h3>
                    <p class="text-gray-600 mb-4">Edita la informació del restaurant i mantén-la actualitzada.</p>
                    <Link :href="route('restaurants.edit', { id: restaurant.id })" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded inline-block">
                        Editar Restaurant
                    </Link>
                </div>

                <div class="bg-red-100 p-6 rounded-lg shadow-md text-center">
                    <h3 class="text-xl font-semibold text-red-700 mb-2">Eliminar Restaurant</h3>
                    <p class="text-gray-600 mb-4">Elimina el restaurant juntament amb tots els registres associats. (Acció Irreversible)</p>
                    <Link :href="route('restaurants.delete', { id: restaurant.id })" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded inline-block">
                        Eliminar Restaurant
                    </Link>
                </div>

                <div class="bg-sky-100 p-6 rounded-lg shadow-md text-center">
                    <h3 class="text-xl font-semibold text-indigo-700 mb-2">Gestionar Carta</h3>
                    <p class="text-gray-600 mb-4">Afegeix i modifica la informació de la carta del teu restaurant.</p>
                    <Link :href="route('restaurants.plats', { id: restaurant.id })" class="bg-sky-500 hover:bg-sky-600 text-white font-bold py-2 px-4 rounded inline-block">
                        Editar Carta
                    </Link>
                </div>


            </div>
        </div>
    </layout>

</template>

<style>
.profile-image {
    width: 75px; /* Increased from 50px to 75px */
    height: 75px; /* Increased from 50px to 75px */
    border-radius: 50%;
    object-fit: cover;
}
</style>

<script>
import { route } from "ziggy-js";
import Layout from "@/Layouts/Layout.vue";

export default {
    components: {Layout},
    methods: { route },
    props: {
        restaurant: Object
    }
}
</script>
