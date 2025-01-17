<template>
    <layout>
        <h1>Restaurants</h1>
        <ul>
            <li v-for="restaurant in restaurants" :key="restaurant.id_restaurant">
                {{ restaurant.nom }}
            </li>
        </ul>
        <Link href="/Create">Create New Restaurant</Link>
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
