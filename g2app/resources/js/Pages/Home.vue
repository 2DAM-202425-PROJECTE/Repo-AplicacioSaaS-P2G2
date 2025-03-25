<script setup>
import { onMounted } from "vue";
import Typewriter from "typewriter-effect/dist/core";
import Layout from "@/Layouts/Layout.vue";

onMounted(() => {
    new Typewriter("#animated-title", {
        strings: ["La Solució Integral per a Restaurants"],
        autoStart: true,
        loop: true,
        cursor: "_",
        delay: 75,
    });
});
</script>


<template>
    <Layout>
        <div class="home-container">
            <div class="hero">
                <div class="overlay">
                    <h1 id="animated-title" class="title"></h1>
                    <p class="subtitle animate__animated animate__fadeIn animate__delay-2s">
                        Gestiona el teu restaurant amb facilitat i permet als teus clients fer reserves de manera instantània
                    </p>
                    <div class="cta-buttons animate__animated animate__fadeIn animate__delay-3s">
                        <a href="/restaurants" class="cta-btn user-btn">
                            Reserva Ara
                        </a>
                        <a href="/configuracio" class="cta-btn admin-btn">
                            Accedeix com a Gestor
                        </a>
                    </div>

                </div>
            </div>

            <section class="features">
                <div class="feature animate__animated animate__fadeIn animate__delay-1s">
                    <h2 class="feature-title">Gestió Eficaç de Restaurants</h2>
                    <p>Optimitza la gestió del teu restaurant amb la nostra aplicació. Gestiona taules, reserves i personal de manera eficient.</p>
                </div>
                <div class="feature animate__animated animate__fadeIn animate__delay-2s">
                    <h2 class="feature-title">Reserva en Línia per a Clients</h2>
                    <p>Els teus clients poden fer reserves de taules en temps real, directament des de la nostra app.</p>
                </div>
            </section>

            <section class="about">
                <h2 class="feature-title">Com Funciona?</h2>
                <div class="steps">
                    <div class="step animate__animated animate__fadeIn animate__delay-1s">
                        <h3 class="step-title">1. Registra el teu Restaurant</h3>
                        <p>Fes-ho fàcilment i personalitza el teu espai per a una gestió òptima.</p>
                    </div>
                    <div class="step animate__animated animate__fadeIn animate__delay-2s">
                        <h3 class="step-title">2. Gestiona les Teves Reserves</h3>
                        <p>Visualitza les reserves en temps real i assigna taules per una gestió més eficient.</p>
                    </div>
                    <div class="step animate__animated animate__fadeIn animate__delay-3s">
                        <h3 class="step-title">3. Client fa la Reserva</h3>
                        <p>Els clients poden veure la disponibilitat i reservar taules des de l'app amb només uns clics.</p>
                    </div>
                </div>
            </section>
        </div>
    </Layout>
</template>

<style scoped>
@import 'animate.css';

.home-container {
    font-family: 'Poppins', sans-serif;
    background-color: #f8f9fa;
}

.hero {
    background-image: url('/bg.jpg');
    background-size: cover;
    background-position: center;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    text-align: center;
    color: white;
}

.overlay {
    background: rgba(0, 0, 0, 0.4);
    padding: 20px;
}

.title {
    font-size: 4rem;
    margin-bottom: 10px;
    font-weight: 700;
}

.subtitle {
    font-size: 1.6rem;
    margin-bottom: 20px;
}

.cta-buttons {
    display: flex;
    justify-content: center;
    gap: 30px;
}

.cta-btn {
    padding: 15px 30px;
    font-size: 1.2rem;
    border: none;
    cursor: pointer;
    border-radius: 8px;
    transition: all 0.3s ease-in-out;
}

.user-btn {
    background-color: #FF5A5F;
    color: white;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.user-btn:hover {
    background-color: #d84d54;
    transform: translateY(-3px);
}

.admin-btn {
    background-color: #00B140;
    color: white;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.admin-btn:hover {
    background-color: #00a135;
    transform: translateY(-3px);
}

.features {
    display: flex;
    justify-content: space-around;
    padding: 50px 0;
    background-color: #fff;
}

.feature {
    width: 45%;
    text-align: center;
}

.feature-title {
    font-size: 2.4rem;
    margin-bottom: 10px;
    color: #FF5A5F;
}

.about {
    background-color: #f1f1f1;
    padding: 50px 20px;
    text-align: center;
}

.steps {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
}

.step {
    width: 30%;
    padding: 25px;
    margin: 15px;
    background-color: #fff;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 12px;
}

.step-title {
    font-size: 2rem;
    margin-bottom: 10px;
    color: #00B140;
}
</style>
