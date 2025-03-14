<template>
    <layout>
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
            <h1 class="text-2xl font-bold mb-4">Crear Restaurant</h1>
            <form @submit.prevent="submitForm">
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
                    <h2 class="text-xl font-bold mb-4">Afegir Plats</h2>
                    <div class="mb-4">
                        <label for="plat_nom" class="block text-sm font-medium text-gray-700">Nom del Plat</label>
                        <input v-model="newPlat.nom" id="plat_nom" type="text" class="mt-1 block w-full" />
                    </div>
                    <div class="mb-4">
                        <label for="plat_descripcio" class="block text-sm font-medium text-gray-700">Descripció del Plat</label>
                        <textarea v-model="newPlat.descripcio" id="plat_descripcio" class="mt-1 block w-full"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="plat_preu" class="block text-sm font-medium text-gray-700">Preu del Plat</label>
                        <input v-model="newPlat.preu" id="plat_preu" type="number" step="0.01" class="mt-1 block w-full" />
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Al·lèrgens i Opcions Dietètiques</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" v-model="newPlat.gluten" class="form-checkbox">
                                <span class="ml-2">Gluten</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" v-model="newPlat.lactics" class="form-checkbox">
                                <span class="ml-2">Lactics</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" v-model="newPlat.crustaci" class="form-checkbox">
                                <span class="ml-2">Crustacis</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" v-model="newPlat.ous" class="form-checkbox">
                                <span class="ml-2">Ous</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" v-model="newPlat.lupines" class="form-checkbox">
                                <span class="ml-2">Lupines</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" v-model="newPlat.mostassa" class="form-checkbox">
                                <span class="ml-2">Mostassa</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" v-model="newPlat.cacahuats" class="form-checkbox">
                                <span class="ml-2">Cacahuets</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" v-model="newPlat.soja" class="form-checkbox">
                                <span class="ml-2">Soja</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" v-model="newPlat.no_vegetaria" class="form-checkbox">
                                <span class="ml-2">Vegetarià</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" v-model="newPlat.no_vega" class="form-checkbox">
                                <span class="ml-2">Vegà</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" v-model="newPlat.carn_vermella" class="form-checkbox">
                                <span class="ml-2">Carn Vermella</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" v-model="newPlat.no_kosher" class="form-checkbox">
                                <span class="ml-2">Kosher</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" v-model="newPlat.no_halal" class="form-checkbox">
                                <span class="ml-2">Halal</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" v-model="newPlat.no_keto" class="form-checkbox">
                                <span class="ml-2">Keto</span>
                            </label>
                        </div>
                    </div>

                    <button type="button" @click="addPlat" class="bg-green-500 text-white px-4 py-2 rounded">Afegir Plat</button>
                </div>

                <div v-if="form.plats.length > 0" class="mt-8">
                    <h3 class="text-lg font-bold mb-2">Plats Afegits:</h3>
                    <ul>
                        <li v-for="(plat, index) in form.plats" :key="index" class="mb-2">
                            {{ plat.nom }} - {{ plat.preu }} €
                        </li>
                    </ul>
                </div>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                    Crear Restaurant
                </button>
            </form>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Layouts/Layout.vue';
import { reactive, ref, onMounted } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { route } from 'ziggy-js';
import axios from 'axios';

export default {
    components: {
        Layout,
    },
    props: {
        tipusCuinaOptions: Array,
        provincias: Array,
        municipios: Array,
    },
    setup(props) {
        const form = reactive({
            nom: '',
            descripcio: '',
            telefon: '',
            tipus_cuina: props.tipusCuinaOptions[0],
            municipio_id: null,
            carrer: '',
            hora_obertura: '',
            hora_tancament: '',
        });

        const selectedProvinciaId = ref(props.provincias[0].id);
        const municipios = ref([]);

        onMounted(() => {
            fetchMunicipios();
        });

        const fetchMunicipios = async () => {
            try {
                const response = await axios.get(route('get.municipios', { provincia_id: selectedProvinciaId.value }));
                municipios.value = response.data;
            } catch (error) {
                console.error("Error fetching municipios:", error);
            }
        };

        const submitForm = () => {
            Inertia.post(route('restaurants.store'), form, {
                onSuccess: () => {
                    Inertia.visit(route('restaurants.index'));
                },
                onError: (errors) => {
                    console.error("Error creating restaurant:", errors);
                },
            });
        };


        const newPlat = reactive({
            nom: '',
            descripcio: '',
            preu: null,
            gluten: false,
            lactics: false,
            crustaci: false,
            ous: false,
            lupines: false,
            mostassa: false,
            cacahuats: false,
            soja: false,
            vegetaria: true,
            vega: true,
            carn_vermella: true,
            kosher: true,
            halal: true,
            keto: true,
        });

        form.plats = reactive([]);

        const addPlat = () => {
                form.plats.push({ ...newPlat });
                // Reset newPlat
                Object.assign(newPlat, {
                    nom: '',
                    descripcio: '',
                    preu: null,
                    gluten: false,
                    lactics: false,
                    crustaci: false,
                    ous: false,
                    lupines: false,
                    mostassa: false,
                    cacahuats: false,
                    soja: false,
                    vegetaria: true,
                    vega: true,
                    carn_vermella: true,
                    kosher: true,
                    halal: true,
                    keto: true,
                });
            };

        return {
            form,
            selectedProvinciaId,
            municipios,
            newPlat, // Return newPlat
            fetchMunicipios,
            addPlat,
            submitForm,
        };
    },
};
</script>
