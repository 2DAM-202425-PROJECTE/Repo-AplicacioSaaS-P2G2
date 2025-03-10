<template>
    <Layout>
        <div class="home-container">
            <!-- Imatge de benvinguda -->
            <div class="hero-section">
                <img src="/assets/restaurant-interior.jpg" alt="Restaurant" class="hero-image" />
                <div class="overlay">
                    <h1 class="text-5xl font-extrabold text-white drop-shadow-lg">Benvingut al gestor de restaurants!</h1>
                    <p class="text-lg text-gray-200 mt-2">Gestiona les reserves i millora l'experiència dels teus clients.</p>
                </div>
            </div>

            <!-- Valoració i accions -->
            <div class="info-section">
                <div class="rating">
                    <span class="text-3xl text-yellow-400">★ ★ ★ ★ ★</span>
                    <span class="text-gray-400 text-lg">(4.9/5)</span>
                </div>

                <router-link :to="{ name: 'restaurants.create' }" class="btn">Afegir una nova reserva</router-link>
            </div>
        </div>
    </Layout>
</template>

<script setup>
import Layout from '@/Layouts/Layout.vue';
</script>

<style scoped>
.home-container {
    text-align: center;
    margin-top: 30px;
}

.hero-section {
    position: relative;
    width: 100%;
    max-height: 400px;
    overflow: hidden;
}

.hero-image {
    width: 100%;
    height: 400px;
    object-fit: cover;
    filter: brightness(0.7);
}

.overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}

.info-section {
    margin-top: 30px;
}

.rating {
    font-size: 24px;
    margin-bottom: 15px;
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
    background-color: #e68900;
}
</style>
