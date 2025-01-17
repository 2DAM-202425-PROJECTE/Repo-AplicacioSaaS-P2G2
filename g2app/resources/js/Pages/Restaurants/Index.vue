<template>
    <app-layout>
        <h1>Restaurants</h1>
        <ul>
            <li v-for="restaurant in restaurants" :key="restaurant.id_restaurant">
                {{ restaurant.nom }}
            </li>
        </ul>
        <Link href="/Create">Create New Restaurant</Link>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/Layout.vue';

export default {
    props: {
        restaurants: Array,
    },
    components: {
        AppLayout,
    },
}
</script>
