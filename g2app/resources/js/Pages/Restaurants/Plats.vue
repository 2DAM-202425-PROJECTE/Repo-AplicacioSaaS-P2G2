<template>
    <div>
        <form @submit.prevent="submitAdminForm">

            <div v-if="form.plats.length > 0" class="mb-8">
                <h3 class="text-lg font-bold mb-4 mt-4">Plats Existents:</h3>
                <ul class="flex flex-wrap">
                    <li v-for="(plat, index) in form.plats" :key="plat.id || index"
                        class="border m-1 p-4 rounded relative"
                        :style="{ width: 'calc(50% - 0.5rem)' }">
                        <span class="font-semibold line-clamp-2">{{ plat.nom }}</span>
                        <span class="text-gray-600 block text-sm line-clamp-2 overflow-hidden">{{ plat.descripcio }}</span>
                        <span>Preu: {{ plat.preu }} €</span>
                        <div class="flex mt-2 flex-wrap">
                            <span v-if="plat.gluten" class="mr-2 text-sm line-clamp-1">Gluten</span>
                            <span v-if="plat.lactics" class="mr-2 text-sm line-clamp-1">Lactics</span>
                            <span v-if="plat.vegetaria" class="mr-2 text-sm line-clamp-1">Vegetarià</span>
                            <span v-if="plat.vega" class="mr-2 text-sm line-clamp-1">Vegà</span>
                            <span v-if="plat.carn_vermella" class="mr-2 text-sm line-clamp-1">Carn Roja</span>
                            <span v-if="plat.kosher" class="mr-2 text-sm line-clamp-1">Kosher</span>
                            <span v-if="plat.halal" class="mr-2 text-sm line-clamp-1">Halal</span>
                            <span v-if="plat.keto" class="mr-2 text-sm line-clamp-1">Keto</span>
                            <span v-if="plat.crustaci" class="mr-2 text-sm line-clamp-1">Crustacis</span>
                            <span v-if="plat.ous" class="mr-2 text-sm line-clamp-1">Ous</span>
                            <span v-if="plat.lupines" class="mr-2 text-sm line-clamp-1">Lupines</span>
                        </div>
                        <button type="button" @click="removePlat(plat.id, index)" class="text-red-500 hover:text-red-700 absolute top-2 right-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </li>
                </ul>
            </div>
            <div v-else class="mb-8 mt-6">
                <p>No hi ha plats creats per aquest restaurant.</p>
            </div>

            <div class="mb-4">
                <label for="plat_nom" class="block text-sm font-medium text-gray-700">Nom del Plat</label>
                <input v-model="newPlat.nom" id="plat_nom" type="text" class="mt-1 block w-full"/>
            </div>
            <div class="mb-4">
                <label for="plat_descripcio" class="block text-sm font-medium text-gray-700">Descripció del Plat</label>
                <textarea v-model="newPlat.descripcio" id="plat_descripcio" class="mt-1 block w-full"></textarea>
            </div>
            <div class="mb-4">
                <label for="plat_preu" class="block text-sm font-medium text-gray-700">Preu del Plat</label>
                <input v-model="newPlat.preu" id="plat_preu" type="number" step="0.01" class="mt-1 block w-full"/>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Al·lèrgens i Opcions Dietètiques</label>
                <div class="grid grid-cols-7 gap-2">
                    <ToggleButton v-model="newPlat.gluten" label="Gluten"/>
                    <ToggleButton v-model="newPlat.lactics" label="Lactics"/>
                    <ToggleButton v-model="newPlat.crustaci" label="Crustacis"/>
                    <ToggleButton v-model="newPlat.ous" label="Ous"/>
                    <ToggleButton v-model="newPlat.lupines" label="Lupines"/>
                    <ToggleButton v-model="newPlat.mostassa" label="Mostassa"/>
                    <ToggleButton v-model="newPlat.cacahuats" label="Cacahuets"/>
                    <ToggleButton v-model="newPlat.soja" label="Soja"/>
                    <ToggleButton v-model="newPlat.vegetaria" label="Vegetarià"/>
                    <ToggleButton v-model="newPlat.vega" label="Vegà"/>
                    <ToggleButton v-model="newPlat.carn_vermella" label="Carn Roja"/>
                    <ToggleButton v-model="newPlat.kosher" label="Kosher"/>
                    <ToggleButton v-model="newPlat.halal" label="Halal"/>
                    <ToggleButton v-model="newPlat.keto" label="Keto"/>

                </div>
            </div>

            <button
                type="button"
                @click="addPlat"
                :disabled="isPlatEmpty"
                :class="{
                    'bg-green-500 text-white cursor-pointer': !isPlatEmpty,
                    'bg-gray-300 text-gray-500 cursor-not-allowed': isPlatEmpty,
                }"
                class="px-4 py-2 rounded">
                Afegir Plat
            </button>


            </form>
        </div>
</template>

<script setup>
import {ref, reactive, computed, onMounted} from 'vue';
import {Inertia} from '@inertiajs/inertia';
import {route} from 'ziggy-js';
import Layout from '@/Layouts/Layout.vue';
import {Link} from "@inertiajs/vue3";
import ToggleButton from '@/Components/CheckButton.vue';
import axios from 'axios';

const props = defineProps({
    restaurant: Object,
});

const form = reactive({
    plats: props.restaurant.plats || [],
});

const newPlat = reactive({
    nom: '',
    descripcio: '',
    preu: null,
    gluten: true,
    lactics: false,
    crustaci: false,
    ous: false,
    lupines: false,
    mostassa: false,
    cacahuats: false,
    soja: false,
    vegetaria: false,
    vega: false,
    carn_vermella: false,
    kosher: false,
    halal: false,
    keto: false,
});

const isPlatEmpty = computed(() => {
    return !newPlat.nom || !newPlat.preu;
});

const addPlat = () => {
    axios.post(route('restaurants.plats.store', {restaurant: props.restaurant.id}), newPlat)
        .then(response => {
            form.plats.push(response.data);

            // Reset form
            Object.assign(newPlat, {
                nom: '',
                descripcio: '',
                preu: null,
                gluten: true,
                lactics: false,
                crustaci: false,
                ous: false,
                lupines: false,
                mostassa: false,
                cacahuats: false,
                soja: false,
                vegetaria: false,
                vega: false,
                carn_vermella: false,
                kosher: false,
                halal: false,
                keto: false,
            });
        })
        .catch(error => {
            console.error("Error creating plat:", error);
        });
};

const removePlat = (platId, index) => {
    axios.delete(route('restaurants.plats.destroy', {restaurant: props.restaurant.id, plat: platId}))
        .then(() => {
            form.plats.splice(index, 1);
        })
        .catch(error => {
            console.error("Error deleting plat:", error);
        });
};


</script>
