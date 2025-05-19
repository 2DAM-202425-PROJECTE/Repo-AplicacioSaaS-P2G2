<template>
    <div class="flex flex-col min-h-screen">
        <header class="bg-gray-100 p-4 border-b border-gray-300 relative">
            <div class="flex justify-between items-center">
                <!-- Espaiador per mantenir la icona a la dreta -->
                <div class="w-6"></div>

                <!-- Navegació centrada -->
                <nav class="absolute left-1/2 transform -translate-x-1/2 space-x-4">
                    <Link :href="route('home')" class="text-blue-500 hover:underline">Home</Link>
                    <Link :href="route('restaurants.index')" class="text-blue-500 hover:underline">Restaurants</Link>
                    <Link :href="route('user.reserves')" class="text-blue-500 hover:underline">Reserves</Link>
                </nav>

                <!-- Icona d'usuari amb menú desplegable -->
                <div class="relative menu-container">
                    <button @click="isOpen = !isOpen" class="focus:outline-none">
                        <!-- Icona d'usuari -->
                        <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5zm0 2c-3.33 0-10 1.67-10 5v2h20v-2c0-3.33-6.67-5-10-5z"/>
                        </svg>
                    </button>
                    <!-- Menú desplegable -->
                    <div v-show="isOpen" class="absolute right-0 mt-2 w-48 bg-white border rounded-md shadow-lg">
                        <a href="/configuracio" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Perfil</a>
                        <Link v-if="hasRestaurant" :href="route('restaurant.management', { id: restaurantId })" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Gestionar Restaurant
                        </Link>
                        <button @click="confirmLogout" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Tancar sessió
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Popup de confirmació de logout -->
        <div v-if="showLogoutConfirm" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center z-50">
            <div class="bg-white p-6 rounded-md shadow-lg w-96">
                <h3 class="text-lg font-semibold mb-4">Estàs segur que vols tancar sessió?</h3>
                <div class="flex justify-end space-x-4">
                    <!-- Botó de cancel·lació -->
                    <button @click="cancelLogout" class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">Cancel·lar</button>
                    <!-- Botó de confirmar logout que envia la petició POST -->
                    <button @click="handleLogout" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        D'acord
                    </button>
                </div>
            </div>
        </div>

        <main class="flex-grow">
            <slot></slot>
        </main>

        <footer class="bg-gray-100 p-4 text-center border-t border-gray-300">
            <!-- Footer content -->
        </footer>
    </div>
</template>

<style scoped>
.menu-container {
    position: relative;
    z-index: 50; /* Assegura que el menú es mostri per sobre dels altres elements */
}

.menu-container .absolute {
    z-index: 100; /* Assegura que el menú desplegable es mostri per sobre del contenidor */
}
</style>

<script>
import { ref, onMounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import axios from 'axios';

export default {
    name: 'Layout',
    setup() {
        const isOpen = ref(false);
        const hasRestaurant = ref(false);
        const restaurantId = ref(null);
        const showLogoutConfirm = ref(false); // Controla la visibilitat del modal de confirmació

        // Mostra el modal de confirmació de logout
        const confirmLogout = () => {
            showLogoutConfirm.value = true;
            isOpen.value = false; // Tanca el menú de l'usuari
        };

        // Funció per realitzar el logout
        const handleLogout = async () => {
            try {
                //POST per fer logout
                await axios.post('/logout');

                // Redirect al login
                window.location.href = '/';
            } catch (error) {
                console.error('Logout failed', error);
            }
        };

        // Cancel·la el logout i tanca el modal
        const cancelLogout = () => {
            showLogoutConfirm.value = false;
        };

        onMounted(async () => {
            try {
                const response = await axios.get(route('user.restaurant', { userId: usePage().props.auth.user.id }));
                if (response.data.restaurant) {
                    hasRestaurant.value = true;
                    restaurantId.value = response.data.restaurant.id;
                }
            } catch (error) {
                console.error("Error fetching restaurant:", error);
            }
        });
        return {
            isOpen,
            hasRestaurant,
            restaurantId,
            showLogoutConfirm,
            confirmLogout,
            handleLogout,
            cancelLogout,
        };
    },
}

</script>

<style scoped>
</style>
