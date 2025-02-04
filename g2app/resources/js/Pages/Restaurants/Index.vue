<template>
    <layout>
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
            <h1 class="text-2xl font-bold mb-4">Restaurants:</h1>
            <ul class="list-disc pl-5 space-y-2">
                <li v-for="restaurant in restaurants" :key="restaurant.id" class="mb-2">
                    <a :href="`/restaurants/${restaurant.id}`" class="text-blue-500 hover:underline hover:text-blue-700 transition duration-300 ease-in-out">
                        {{ restaurant.nom }}
                    </a>
                </li>
            </ul>
            <div class="mt-4">
                <button @click="showCreateForm = !showCreateForm" class="bg-blue-500 text-white px-4 py-2 rounded">
                    {{ showCreateForm ? 'Cancel' : 'Crear Restaurant' }}
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

export default {
    data() {
        return {
            showCreateForm: false,
            tipusCuinaOptions: [],
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

}
</script>
