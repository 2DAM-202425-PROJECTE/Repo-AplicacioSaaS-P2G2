<!-- g2app/resources/js/Pages/Restaurants/Index.vue -->

<template>
    <layout>
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
            <h1 class="text-2xl font-bold mb-4">Restaurants:</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <Link v-for="restaurant in restaurants" :key="restaurant.id" :href="`/restaurants/${restaurant.id}`" class="bg-gray-100 p-4 shadow block text-blue-500 hover:underline hover:text-blue-700">
                    <h2 class="text-xl font-bold">{{ restaurant.nom }}</h2>
                    <p class="text-gray-700">{{ restaurant.municipio.name }}</p>
                    <p class="text-gray-700">{{ restaurant.tipus_cuina }}</p>
                </Link>
            </div>
            <div class="mt-4">
                <button @click="showCreateForm = !showCreateForm" class="bg-blue-500 text-white px-4 py-2 rounded">
                    {{ showCreateForm ? 'Cancel' : 'Crear Restaurant' }}
                </button>
                <div v-if="showCreateForm" class="mt-4">
                    <Create
                        :tipusCuinaOptions="tipusCuinaOptions"
                        :provincias="provincias"
                        :municipios="municipios"
                    />
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Layouts/Layout.vue';
import Create from './Create.vue';

export default {
    data() {
        return {
            showCreateForm: false,
            tipusCuinaOptions: [],
            provincias: [],
            municipios: [],
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
        provincias: {
            type: Array,
            required: true,
        },
        municipios: {
            type: Array,
            required: true,
        },
    },
};
</script>
