<template>
    <layout>
        <h1 class="text-2xl font-bold mb-4">Restaurants:</h1>
        <ul class="list pl-5">
            <li v-for="restaurant in restaurants" :key="restaurant.id_restaurant" class="mb-2">
                {{ restaurant.nom }}
            </li>
        </ul>
        <a href="/Create" class="mt-4 inline-block text-blue-500 hover:text-blue-700">Create New Restaurant</a>
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
