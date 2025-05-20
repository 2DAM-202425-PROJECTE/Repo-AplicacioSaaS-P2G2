<script setup>
import { onMounted, ref } from "vue";
import Typewriter from "typewriter-effect/dist/core";
import Layout from "@/Layouts/Layout.vue";

// Referencia para el efecto de parallax
const heroSection = ref(null);

onMounted(() => {
    // Inicializar el efecto de escritura
    const typewriter = new Typewriter("#animated-title", {
        autoStart: true,
        loop: false,
        cursor: "_",
        delay: 75,
    });

    typewriter
        .typeString("La Solució Integral per a Restaurants")
        .start();

    // Efecto parallax en el scroll
    window.addEventListener('scroll', () => {
        if (heroSection.value) {
            const scrollPosition = window.scrollY;
            heroSection.value.style.backgroundPositionY = `${scrollPosition * 0.5}px`;
        }
    });
});
</script>

<template>
    <Layout>
        <div class="home-container">
            <!-- Hero Section con efecto parallax -->
            <div ref="heroSection" class="hero">
                <div class="overlay">
                    <div class="content-container">
                        <h1 id="animated-title" class="title"></h1>
                        <p class="subtitle animate__animated animate__fadeIn animate__delay-2s">
                            Gestiona el teu restaurant amb facilitat i permet als teus clients fer reserves de manera instantània
                        </p>
                        <div class="cta-buttons animate__animated animate__fadeIn animate__delay-3s">
                            <a href="/images/restaurants" class="cta-btn user-btn">
                                <span class="icon">🍽️</span> Reserva Ara
                            </a>
                            <a href="/configuracio" class="cta-btn admin-btn">
                                <span class="icon">⚙️</span> Accedeix com a Gestor
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <section class="features">
                <div class="container">
                    <div class="feature-card animate__animated animate__fadeIn animate__delay-1s">
                        <div class="feature-icon">🏆</div>
                        <h2 class="feature-title">Gestió Eficaç de Restaurants</h2>
                        <p>Optimitza la gestió del teu restaurant amb la nostra aplicació. Gestiona taules, reserves i personal de manera eficient.</p>
                    </div>
                    <div class="feature-card animate__animated animate__fadeIn animate__delay-2s">
                        <div class="feature-icon">📱</div>
                        <h2 class="feature-title">Reserva en Línia per a Clients</h2>
                        <p>Els teus clients poden fer reserves de taules en temps real, directament des de la nostra app.</p>
                    </div>
                    <div class="feature-card animate__animated animate__fadeIn animate__delay-3s">
                        <div class="feature-icon">📊</div>
                        <h2 class="feature-title">Analítiques i Informes</h2>
                        <p>Accedeix a informes detallats sobre l'ocupació, les reserves i el rendiment del teu negoci.</p>
                    </div>
                </div>
            </section>

            <!-- How It Works Section -->
            <section class="how-it-works">
                <div class="container">
                    <h2 class="section-title">Com Funciona?</h2>
                    <div class="steps">
                        <div class="step animate__animated animate__fadeIn animate__delay-1s">
                            <div class="step-number">1</div>
                            <h3 class="step-title">Registra el teu Restaurant</h3>
                            <p>Fes-ho fàcilment i personalitza el teu espai per a una gestió òptima.</p>
                        </div>
                        <div class="step animate__animated animate__fadeIn animate__delay-2s">
                            <div class="step-number">2</div>
                            <h3 class="step-title">Gestiona les Teves Reserves</h3>
                            <p>Visualitza les reserves en temps real i assigna taules per una gestió més eficient.</p>
                        </div>
                        <div class="step animate__animated animate__fadeIn animate__delay-3s">
                            <div class="step-number">3</div>
                            <h3 class="step-title">Client fa la Reserva</h3>
                            <p>Els clients poden veure la disponibilitat i reservar taules des de l'app amb només uns clics.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Testimonials Section -->
            <section class="testimonials">
                <div class="container">
                    <h2 class="section-title">El que diuen els nostres clients</h2>
                    <div class="testimonial-grid">
                        <div class="testimonial-card">
                            <div class="quote">"</div>
                            <p class="testimonial-text">Des que utilitzem aquesta plataforma, hem augmentat les nostres reserves en un 30% i hem millorat l'eficiència del nostre personal.</p>
                            <div class="testimonial-author">
                                <div class="author-name">Maria Garcia</div>
                                <div class="author-role">Propietària, Restaurant La Terrassa</div>
                            </div>
                        </div>
                        <div class="testimonial-card">
                            <div class="quote">"</div>
                            <p class="testimonial-text">La facilitat d'ús i les funcionalitats avançades fan d'aquesta eina la millor inversió que hem fet per al nostre restaurant.</p>
                            <div class="testimonial-author">
                                <div class="author-name">Joan Martí</div>
                                <div class="author-role">Gerent, Bistro Català</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="cta-section">
                <div class="container">
                    <h2 class="cta-title">Preparat per transformar el teu restaurant?</h2>
                    <p class="cta-text">Uneix-te a centenars de restaurants que ja confien en nosaltres</p>
                    <div class="cta-buttons">
                        <a href="/register" class="cta-btn primary-btn">Comença Ara</a>
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
    color: #333;
}

/* Hero Section */
.hero {
    background-image: url('/bg.jpg');
    background-size: cover;
    background-position: center;
    height: 100vh;
    position: relative;
    color: white;
}

.overlay {
    background: rgba(0, 0, 0, 0.5);
    height: 100%;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
}

.content-container {
    max-width: 1200px;
    padding: 0 20px;
}

.title {
    font-size: 4.5rem;
    margin-bottom: 20px;
    font-weight: 800;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
}

.subtitle {
    font-size: 1.8rem;
    margin-bottom: 40px;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.4;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
}

.cta-buttons {
    display: flex;
    justify-content: center;
    gap: 30px;
    flex-wrap: wrap;
}

.cta-btn {
    padding: 16px 32px;
    font-size: 1.2rem;
    border: none;
    cursor: pointer;
    border-radius: 50px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.icon {
    margin-right: 10px;
    font-size: 1.4rem;
}

.user-btn {
    background-color: #FF5A5F;
    color: white;
}

.user-btn:hover {
    background-color: #e5484d;
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
}

.admin-btn {
    background-color: #00B140;
    color: white;
}

.admin-btn:hover {
    background-color: #009a38;
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
}

/* Features Section */
.features {
    padding: 100px 0;
    background-color: #fff;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.feature-card {
    width: 30%;
    background-color: #fff;
    border-radius: 15px;
    padding: 40px 30px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    margin-bottom: 30px;
}

.feature-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
}

.feature-icon {
    font-size: 3rem;
    margin-bottom: 20px;
}

.feature-title {
    font-size: 1.8rem;
    margin-bottom: 15px;
    color: #333;
    font-weight: 700;
}

/* How It Works Section */
.how-it-works {
    padding: 100px 0;
    background-color: #f8f9fa;
}

.section-title {
    text-align: center;
    font-size: 2.5rem;
    margin-bottom: 60px;
    color: #333;
    font-weight: 800;
    position: relative;
    padding-bottom: 20px;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 4px;
    background: linear-gradient(90deg, #FF5A5F, #00B140);
    border-radius: 2px;
}

.steps {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
}

.step {
    width: 30%;
    background-color: #fff;
    border-radius: 15px;
    padding: 40px 30px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    position: relative;
    margin-bottom: 30px;
}

.step-number {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #FF5A5F, #FF8A8E);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0 auto 20px;
    box-shadow: 0 5px 15px rgba(255, 90, 95, 0.3);
}

.step:nth-child(2) .step-number {
    background: linear-gradient(135deg, #00B140, #00D452);
    box-shadow: 0 5px 15px rgba(0, 177, 64, 0.3);
}

.step:nth-child(3) .step-number {
    background: linear-gradient(135deg, #0066FF, #5B9AFF);
    box-shadow: 0 5px 15px rgba(0, 102, 255, 0.3);
}

.step-title {
    font-size: 1.6rem;
    margin-bottom: 15px;
    color: #333;
    font-weight: 700;
}

/* Testimonials Section */
.testimonials {
    padding: 100px 0;
    background-color: #fff;
}

.testimonial-grid {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
}

.testimonial-card {
    width: 48%;
    background-color: #f8f9fa;
    border-radius: 15px;
    padding: 40px;
    margin-bottom: 30px;
    position: relative;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
}

.quote {
    font-size: 5rem;
    position: absolute;
    top: 20px;
    left: 20px;
    color: rgba(0, 0, 0, 0.05);
    font-family: 'Georgia', serif;
}

.testimonial-text {
    font-size: 1.2rem;
    line-height: 1.6;
    margin-bottom: 20px;
    position: relative;
    z-index: 1;
}

.testimonial-author {
    display: flex;
    flex-direction: column;
}

.author-name {
    font-weight: 700;
    font-size: 1.1rem;
    color: #333;
}

.author-role {
    color: #777;
    font-size: 0.9rem;
}

/* CTA Section */
.cta-section {
    padding: 100px 0;
    background: linear-gradient(135deg, #FF5A5F, #00B140);
    color: white;
    text-align: center;
}

.cta-title {
    font-size: 2.5rem;
    margin-bottom: 20px;
    font-weight: 800;
}

.cta-text {
    font-size: 1.2rem;
    margin-bottom: 40px;
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
}

.primary-btn {
    background-color: white;
    color: #FF5A5F;
}

.primary-btn:hover {
    background-color: #f8f9fa;
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}

.secondary-btn {
    background-color: transparent;
    color: white;
    border: 2px solid white;
}

.secondary-btn:hover {
    background-color: rgba(255, 255, 255, 0.1);
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}

/* Responsive */
@media (max-width: 992px) {
    .feature-card, .step, .testimonial-card {
        width: 48%;
    }

    .title {
        font-size: 3.5rem;
    }

    .subtitle {
        font-size: 1.5rem;
    }
}

@media (max-width: 768px) {
    .feature-card, .step, .testimonial-card {
        width: 100%;
    }

    .title {
        font-size: 2.8rem;
    }

    .subtitle {
        font-size: 1.3rem;
    }

    .cta-buttons {
        flex-direction: column;
        gap: 15px;
    }

    .cta-btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
