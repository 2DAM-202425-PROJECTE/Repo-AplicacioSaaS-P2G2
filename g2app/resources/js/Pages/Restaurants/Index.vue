<template>
    <layout>
        <div class="max-w-5xl mx-auto bg-[#1B1B1B] p-10 rounded-xl shadow-lg border border-[#3D3D3D] text-white">
            <h1 class="text-4xl font-extrabold text-gold-500 mb-6 border-b border-[#3D3D3D] pb-4">Restaurants</h1>

            <form @submit.prevent="searchRestaurants" class="mb-6">
                <input v-model="search" type="text" placeholder="Cerca restaurants..." class="input-field" />
                <select v-model="tipusCuina" class="input-field mt-4">
                    <option value="">Tots els tipus</option>
                    <option v-for="option in tipusCuinaOptions" :key="option" :value="option">{{ option }}</option>
                </select>
                <button type="submit" class="btn mt-4">Cercar</button>
            </form>

            <ul class="list-none space-y-4">
                <li
                    v-for="restaurant in restaurants"
                    :key="restaurant.id"
                    class="restaurant-card cursor-pointer"
                    @click="goToRestaurant(restaurant.id)"
                >
                    <h2 class="text-2xl font-bold text-gold-500">{{ restaurant.nom }}</h2>
                    <p class="text-gray-400">{{ restaurant.descripcio }}</p>
                    <p class="text-gray-400">📞 {{ restaurant.telefon }}</p>
                    <p class="text-gray-400">🍽 {{ restaurant.tipus_cuina }}</p>
                    <p class="text-gray-400">🕒 {{ restaurant.hora_obertura }} - {{ restaurant.hora_tancament }}</p>
                </li>
            </ul>

            <div class="mt-4">
                <button @click="showCreateForm = !showCreateForm" class="btn mt-6">
                    {{ showCreateForm ? 'Cancel·lar' : 'Crear Restaurant' }}
                </button>
                <div v-if="showCreateForm" class="mt-4">
                    <Create :tipusCuinaOptions="tipusCuinaOptions" />
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Layouts/Layout.vue';
import Create from './Create.vue';
import { route } from "ziggy-js";

export default {
    data() {
        return {
            search: this.$page.props.search || '',
            tipusCuina: this.$page.props.tipusCuina || '',
            showCreateForm: false,
        };
    },
    components: {
        Layout,
        Create,
    },
    props: {
        tipusCuinaOptions: {
            type: Array,
            required: true,
        },
        restaurants: Array,
    },
    methods: {
        searchRestaurants() {
            this.$inertia.get(route('restaurants.index'), { search: this.search, tipus_cuina: this.tipusCuina });
        },
        goToRestaurant(id) {
            this.$inertia.get(route('restaurants.show', { id }));
        },
    }
}
</script>

<style scoped>
.input-field {
    @apply mt-2 block w-full border-gray-500 bg-[#1e1e1e] rounded-md shadow-sm focus:border-[#c9a14a] focus:ring focus:ring-[#c9a14a] focus:ring-opacity-50 p-3 text-lg text-white placeholder-gray-400;
    transition: all 0.3s ease-in-out;
}

.input-field:focus {
    background-color: #292929;
}

.btn {
    display: inline-block;
    margin-top: 15px;
    padding: 12px 24px;
    background-color: #ff9800;
    color: white;
    font-size: 18px;
    font-weight: bold;
    border-radius: 8px;
    transition: background 0.3s ease;
}

.btn:hover {
    background-color: #e68900
}

.restaurant-card {
    display: block;
    background: #2A2A2A;
    border-radius: 10px;
    padding: 15px;
    transition: transform 0.2s ease, background 0.2s ease;
}

.restaurant-card:hover {
    background: #3A3A3A;
    transform: scale(1.02);
}
</style>
