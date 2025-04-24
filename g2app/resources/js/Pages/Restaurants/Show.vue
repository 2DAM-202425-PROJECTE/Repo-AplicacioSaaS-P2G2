<template>
    <layout>
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md space-x-2">
<!--            <div class="mb-4">-->
<!--                <Link :href="route('restaurants.edit', { id: restaurant.id })" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mr-4">-->
<!--                    Modificar-->
<!--                </Link>-->

<!--                <Link :href="route('restaurant.reserves', { id: restaurant.id })" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">-->
<!--                    Mostrar Reserves-->
<!--                </Link>-->
<!--            </div>-->

            <div class="flex">
                <div class="w-2/3 pr-4">
                    <div class="mb-4">
                        <h1 class="text-3xl font-bold">{{ nom }}</h1>
                    </div>
                    <div class="mb-4">
                        <p><strong>Descripció:</strong></p>
                        <p>{{ descripcio }}</p>
                    </div>
                    <div class="mb-4">
                        <p><strong>Telèfon:</strong></p>
                        <p>{{ telefon }}</p>
                    </div>
                    <div class="mb-4">
                        <p><strong>Imatge de perfil:</strong></p>
                        <img :src="`/storage/${profile_image}`" alt="Profile Image" class="w-32 h-32 rounded-full">
                    </div>
                    <div class="mb-4">
                        <p><strong>Cuina:</strong></p>
                        <p>{{ tipus_cuina }}</p>
                    </div>
                    <div class="mb-4">
                        <p><strong>Horari:</strong></p>
                        <p>Dilluns - Dissabte ({{ horaObertura }} - {{ horaTancament }})</p>
                    </div>

                    <div class="mb-4">
                        <p><strong>Ubicació:</strong></p>
                        <p>
                            {{ restaurant.carrer }},
                            {{ restaurant.municipio?.name || 'Municipi no disponible' }},
                            {{ restaurant.municipio?.provincia?.name || 'Provincia no disponible' }}
                        </p>
                    </div>

                    <div class="mt-8">
                        <h2 class="text-xl font-bold mb-4">Carta</h2>
                        <ul>

                            <li v-for="plat in restaurant.plats.slice(o, visiblePlatsCount)" :key="plat.id" class="border-b-2 p-2 mb-4">
                                <div class="flex flex-wrap gap-3">

                                    <p class="text-lg font-semibold">{{ plat.nom }}</p>
                                    <p class=""> {{ plat.preu }} €</p>
                                </div>
                                <p class="mb-1">{{ plat.descripcio }}</p>

                                <div v-if="allergenList(plat).length > 0">
                                    <h4 class="font-semibold mb-1">Al·lèrgens:</h4>
                                    <div class="flex flex-wrap gap-2">
                                        <a class= "px-2 py-0.5 bg-gray-100" v-for="allergen in allergenList(plat)" :key="allergen">{{ allergen }}</a>
                                    </div>
                                </div>

                                <div v-if="dietaryList(plat).length > 0" class="mt-2">
                                    <h4 class="font-semibold mb-1">Opcions Dietètiques:</h4>
                                    <div class="flex flex-wrap gap-2">
                                        <a class = "px-2 py-0.5 bg-gray-100" v-for="dietary in dietaryList(plat)" :key="dietary">{{ dietary }}</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <button v-if="visiblePlatsCount < restaurant.plats.length" @click="visiblePlatsCount += initialLimit" class="text-blue-600 px-4 py-2 mt-2">
                            + Mostrar més
                        </button>
                    </div>

                </div>
                <div class="w-1/3 pl-4">
                    <div class="mt-8">
                        <h2 class="text-xl font-bold mb-4">Crea una Reserva</h2>
                        <form @submit.prevent="submitReservation">
                            <input type="hidden" v-model="reservation.id_restaurant">
                            <div class="mb-4">
                                <label for="telefon" class="block text-sm font-medium text-gray-700">Telèfon</label>
                                <input type="text" v-model="reservation.telefon" id="telefon" class="mt-1 block w-full" required>
                            </div>
                            <div class="mb-4">
                                <label for="data" class="block text-sm font-medium text-gray-700">Data</label>
                                <input type="date" v-model="reservation.data" id="data" class="mt-1 block w-full" required>
                            </div>
                            <div class="mb-4">
                                <label for="hora" class="block text-sm font-medium text-gray-700">Hora</label>
                                <input type="time" v-model="reservation.hora" id="hora" class="mt-1 block w-full" required>
                            </div>
                            <div class="mb-4">
                                <label for="num_persones" class="block text-sm font-medium text-gray-700">Número de Persones</label>
                                <input type="number" v-model="reservation.num_persones" id="num_persones" class="mt-1 block w-full" required min="1">
                            </div>

                            <div class="mt-1">
                                <label class="inline-flex items-center">
                                    <input type="radio" v-model="reservation.terrassa" :value="true" class="form-radio">
                                    <span class="ml-2">Terrassa</span>
                                </label>
                                <label class="inline-flex items-center ml-6">
                                    <input type="radio" v-model="reservation.terrassa" :value="false" class="form-radio">
                                    <span class="ml-2">Interior</span>
                                </label>
                            </div>
                            <div class="mb-4">
                                <label for="solicituds" class="block text-sm font-medium text-gray-700">Solicituds</label>
                                <textarea v-model="reservation.solicituds" id="solicituds" class="mt-1 block w-full"></textarea>
                            </div>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                                Reservar
                            </button>

                        </form>
                    </div>
                    <div v-if="page.props.flash && page.props.flash.message" class="mt-4 px-4 py-2  text-green-900 bg-green-200" :class="page.props.flash.type === 'success' ? 'text-green-500' : 'text-red-500'">
                        {{ page.props.flash.message }}
                    </div>
                </div>
            </div>

        </div>
    </layout>
</template>

<script setup>
import { defineProps, reactive, ref, onMounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import { route } from 'ziggy-js';
import axios from 'axios';
import Layout from "@/Layouts/Layout.vue";

//Definició de les propietats
const props = defineProps({
    restaurant: Object,
});

//Extracció de dades del restaurant
const { nom, descripcio, telefon, tipus_cuina, hora_obertura, hora_tancament } = props.restaurant;
const horaObertura = hora_obertura;
const horaTancament = hora_tancament;

//Inicialització de l'objecte de reserva
const reservation = reactive({
    id_restaurant: props.restaurant.id,
    telefon: '',
    data: '',
    hora: '',
    num_persones: 1,
    estat: 0,
    terrassa: false,
    solicituds: '',
});

//Inicialització de dades i variables reactives
const page = usePage();
const initialLimit = 3;
const visiblePlatsCount = ref(initialLimit);

// Get the logged-in user's ID
const userId = page.props.auth.user.id;
reservation.user_id = userId;

// Get allergen list
const allergenList = (plat) => {
    const allergens = [];
    if (plat.gluten) allergens.push('Gluten');
    if (plat.lactics) allergens.push('Lactics');
    if (plat.crustaci) allergens.push('Crustacis');
    if (plat.ous) allergens.push('Ous');
    if (plat.lupines) allergens.push('Lupines');
    if (plat.mostassa) allergens.push('Mostassa');
    if (plat.cacahuats) allergens.push('Cacahuets');
    if (plat.soja) allergens.push('Soja');
    if (plat.carn_vermella) allergens.push('Carn vermella');
    return allergens;
};

// Obtenir la llista d'opcions dietètiques
const dietaryList = (plat) => {
    const dietary = [];
    if (plat.vegetaria) dietary.push('Vegetarià');
    if (plat.vega) dietary.push('Vegà');
    if (plat.kosher) dietary.push('Kosher');
    if (plat.halal) dietary.push('Halal');
    if (plat.keto) dietary.push('Keto');
    return dietary;
};

//Enviar la reserva
const submitReservation = () => {
    Inertia.post(route('reserves.store'), reservation, {
        onSuccess: () => {
            // Reinicia el formulari
            Object.assign(reservation, {
                telefon: '',
                data: '',
                hora: '',
                num_persones: 1,
                estat: 0,
                solicituds: '',
            });
            Inertia.clearErrors();
        },
        onError: (errors) => {
            console.error('Error submitting reservation:', errors);
        },
    });
};
</script>
