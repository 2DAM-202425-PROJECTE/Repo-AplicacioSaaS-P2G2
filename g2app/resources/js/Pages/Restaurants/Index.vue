<template>
    <layout>
        <div class="max-w-5xl mx-auto bg-[#1B1B1B] p-10 rounded-xl shadow-lg border border-[#3D3D3D] text-white">
            <h1 class="text-4xl font-extrabold text-gold-500 mb-6 border-b border-[#3D3D3D] pb-4">Restaurants</h1>
            <ul class="list-none space-y-4">
                <li v-for="restaurant in restaurants" :key="restaurant.id" class="p-4 bg-[#2A2A2A] rounded-lg shadow-md transition duration-300 hover:bg-[#3A3A3A]">
                    <a :href="`/restaurants/${restaurant.id}`" class="text-xl font-semibold text-gold-400 hover:text-gold-300 transition duration-300">
                        {{ restaurant.nom }}
                    </a>
                </li>
            </ul>
            <div class="mt-6">
                <button @click="showCreateForm = !showCreateForm"
                        class="bg-gold-500 hover:bg-gold-600 text-white font-semibold px-6 py-3 rounded-lg shadow-lg transition-all duration-300">
                    {{ showCreateForm ? 'Cancel·lar' : 'Crear Restaurant' }}
                </button>
                <div v-if="showCreateForm" class="mt-6 p-6 bg-[#2A2A2A] rounded-lg border border-[#3D3D3D] shadow-md">
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

<style scoped>
.text-gold-500 {
    color: #FFD700;
}
.text-gold-400 {
    color: #FFC300;
}
.text-gold-300 {
    color: #FFB000;
}
.bg-gold-500 {
    background-color: #FFD700;
}
.bg-gold-600 {
    background-color: #E6C200;
}
</style>
