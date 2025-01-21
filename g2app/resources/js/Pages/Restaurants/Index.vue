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
            <a href="/restaurants/create" class="mt-4 inline-block bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">Crear Restaurant</a>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Layouts/Layout.vue';

export default {
    props: {
        restaurants: Array,
    },
    components: {
        Layout,
    },
}
</script>
