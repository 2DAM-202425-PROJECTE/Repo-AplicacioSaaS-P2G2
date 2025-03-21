<template>
    <layout>
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
            <div class="mb-4">
            <Link :href="route('restaurants.show', { id: restaurant.id })"
                  class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                <-Tornar
            </Link>
            </div>
            <h1 class="text-2xl font-bold mb-4">Editar Restaurant</h1>
            <form @submit.prevent="submitAdminForm">
                <div class="mb-4">
                    <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                    <input v-model="form.nom" id="nom" type="text" class="mt-1 block w-full" required />
                </div>
                <div class="mb-4">
                    <label for="descripcio" class="block text-sm font-medium text-gray-700">Descripció</label>
                    <textarea v-model="form.descripcio" id="descripcio" class="mt-1 block w-full" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="telefon" class="block text-sm font-medium text-gray-700">Telèfon</label>
                    <input v-model="form.telefon" id="telefon" type="text" class="mt-1 block w-full" required />
                </div>
                <div class="mb-4">
                    <label for="tipus_cuina" class="block text-sm font-medium text-gray-700">Tipus de Cuina</label>
                    <select v-model="form.tipus_cuina" id="tipus_cuina" class="mt-1 block w-full" required>
                        <option v-for="option in tipusCuinaOptions" :key="option" :value="option">{{ option }}</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="provincia" class="block text-sm font-medium text-gray-700">Provincia</label>
                    <select v-model="selectedProvinciaId" @change="fetchMunicipios" id="provincia" class="mt-1 block w-full" required>
                        <option v-for="provincia in provincias" :key="provincia.id" :value="provincia.id">{{ provincia.name }}</option>
                    </select>
                </div>

                <div class="mb-4" v-if="municipios.length > 0">
                    <label for="municipi" class="block text-sm font-medium text-gray-700">Municipi</label>
                    <select v-model="form.municipio_id" id="municipi" class="mt-1 block w-full" required>
                        <option value="" disabled>Select a Municipality</option>
                        <option v-for="municipio in municipios" :key="municipio.id" :value="municipio.id">{{ municipio.name }}</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="carrer" class="block text-sm font-medium text-gray-700">Carrer</label>
                    <input v-model="form.carrer" id="carrer" type="text" class="mt-1 block w-full" required />
                </div>
                <div class="mb-4">
                    <label for="hora_obertura" class="block text-sm font-medium text-gray-700">Hora d'Obertura</label>
                    <input v-model="form.hora_obertura" id="hora_obertura" type="time" class="mt-1 block w-full" required />
                </div>
                <div class="mb-4">
                    <label for="hora_tancament" class="block text-sm font-medium text-gray-700">Hora de Tancament</label>
                    <input v-model="form.hora_tancament" id="hora_tancament" type="time" class="mt-1 block w-full" required />
                </div>

                <div class="mt-8">
                    <h2 class="text-xl font-bold mb-4">Editar Plats</h2>

                    <div v-if="form.plats.length > 0" class="mb-8">
                        <h3 class="text-lg font-bold mb-2">Plats Existents:</h3>
                        <ul class="flex flex-wrap">
                            <li v-for="(plat, index) in form.plats" :key="plat.id || index"
                                class="border m-1 p-4 rounded flex items-center justify-between"
                                :style="{ width: 'calc(50% - 0.5rem)' }">
                                <span>{{ plat.nom }} - {{ plat.preu }} €</span>
                                <button type="button" @click="removePlat(index)" class="text-red-500 hover:text-red-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </li>
                        </ul>
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
                            <ToggleButton v-model="newPlat.gluten" label="Gluten" />
                            <ToggleButton v-model="newPlat.lactics" label="Lactics" />
                            <ToggleButton v-model="newPlat.crustaci" label="Crustacis" />
                            <ToggleButton v-model="newPlat.ous" label="Ous" />
                            <ToggleButton v-model="newPlat.lupines" label="Lupines" />
                            <ToggleButton v-model="newPlat.mostassa" label="Mostassa" />
                            <ToggleButton v-model="newPlat.cacahuats" label="Cacahuets" />
                            <ToggleButton v-model="newPlat.soja" label="Soja" />
                            <ToggleButton v-model="newPlat.vegetaria" label="Vegetarià" />
                            <ToggleButton v-model="newPlat.vega" label="Vegà" />
                            <ToggleButton v-model="newPlat.carn_vermella" label="Carn Roja" />
                            <ToggleButton v-model="newPlat.kosher" label="Kosher" />
                            <ToggleButton v-model="newPlat.halal" label="Halal" />
                            <ToggleButton v-model="newPlat.keto" label="Keto" />

                        </div>
                    </div>

                    <button
                        type="button"
                        @click="addPlat"
                        :disabled="isPlatEmpty"
                        :class="{ 'bg-green-500 text-white cursor-pointer': !isPlatEmpty,
                        'bg-gray-300 text-gray-500 cursor-not-allowed': isPlatEmpty,}"
                        class="px-4 py-2 rounded">
                        Afegir Plat
                    </button>
                </div>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                    Guardar canvis
                </button>
            </form>
        </div>
    </layout>
</template>

<script setup>
import {ref, reactive, onMounted, computed} from 'vue';
import {Inertia} from '@inertiajs/inertia';
import {route} from 'ziggy-js';
import axios from 'axios';
import Layout from '@/Layouts/Layout.vue';
import {Link} from "@inertiajs/vue3";
import ToggleButton from '@/Components/CheckButton.vue';

const props = defineProps({
    restaurant: Object,
    tipusCuinaOptions: Array,
    provincias: Array,
    municipios: Array,
    plats: Array,
});

const form = reactive({
    ...props.restaurant,
    plats: props.restaurant.plats || [],
});

const selectedProvinciaId = ref(props.restaurant.municipio.provincia_id);
const municipios = ref([]);
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
    form.plats.push({...newPlat});
    // Reset newPlat
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
};

const removePlat = (index) => {
    form.plats.splice(index, 1);
};

onMounted(() => {
    fetchMunicipios();
});

const fetchMunicipios = async () => {
    try {
        const response = await axios.get(route('get.municipios', {provincia_id: selectedProvinciaId.value}));
        municipios.value = response.data;
    } catch (error) {
        console.error("Error fetching municipios:", error);
    }
};


const submitAdminForm = () => {
    const platsToSend = form.plats.map(plat => {
        if (plat.id) {
            // Plat existent
            return {
                id: plat.id,
                nom: plat.nom,
                descripcio: plat.descripcio,
                preu: plat.preu,
                gluten: plat.gluten,
                lactics: plat.lactics,
                crustaci: plat.crustaci,
                ous: plat.ous,
                lupines: plat.lupines,
                mostassa: plat.mostassa,
                cacahuats: plat.cacahuats,
                soja: plat.soja,
                vegetaria: plat.vegetaria,
                vega: plat.vega,
                carn_vermella: plat.carn_vermella,
                kosher: plat.kosher,
                halal: plat.halal,
                keto: plat.keto,
            };
        } else {
            // Nou plat
            return {
                nom: plat.nom,
                descripcio: plat.descripcio,
                preu: plat.preu,
                gluten: plat.gluten,
                lactics: plat.lactics,
                crustaci: plat.crustaci,
                ous: plat.ous,
                lupines: plat.lupines,
                mostassa: plat.mostassa,
                cacahuats: plat.cacahuats,
                soja: plat.soja,
                vegetaria: plat.vegetaria,
                vega: plat.vega,
                carn_vermella: plat.carn_vermella,
                kosher: plat.kosher,
                halal: plat.halal,
                keto: plat.keto,
            };
        }
    });

    Inertia.put(route('restaurants.update', { restaurant: form.id }), {
...form, plats: form.plats,
    }, {
        onSuccess: () => {
            Inertia.visit(route('restaurants.show', { id: form.id }));
        },
        onError: (errors) => {
            console.error("Error updating restaurant:", errors);
        },
    });
};



</script>
