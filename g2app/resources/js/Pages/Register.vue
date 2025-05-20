<script setup>
import { useForm } from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout.vue";
import { ref } from "vue";

const showPassword = ref(false);
const showConfirmPassword = ref(false);
const isLoading = ref(false);
const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    empresa: false,
});

const submit = () => {
    isLoading.value = true;
    form.post("/register", {
        onFinish: () => {
            form.reset("password", "password_confirmation");
            isLoading.value = false;
        },
    });
};
</script>

<template>
    <div class="register-page">
        <div class="register-container">
            <div class="form-side">
                <div class="form-container">
                    <div class="logo-container">
                        <div class="logo">🍽️</div>
                        <h1 class="logo-text">TableTab</h1>
                    </div>

                    <h2 class="form-title">Crea el teu compte</h2>
                    <p class="form-subtitle">Uneix-te a la nostra plataforma de gestió de restaurants</p>

                    <form @submit.prevent="submit" class="register-form">
                        <div class="form-group">
                            <label for="name" class="form-label">Nom complet</label>
                            <div class="input-container">
                                <span class="input-icon">👤</span>
                                <input
                                    type="text"
                                    id="name"
                                    v-model="form.name"
                                    required
                                    class="form-input"
                                    placeholder="Introdueix el teu nom"
                                />
                            </div>
                            <p v-if="form.errors.name" class="error-message">{{ form.errors.name }}</p>
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">Correu electrònic</label>
                            <div class="input-container">
                                <span class="input-icon">✉️</span>
                                <input
                                    type="email"
                                    id="email"
                                    v-model="form.email"
                                    required
                                    class="form-input"
                                    placeholder="exemple@correu.com"
                                />
                            </div>
                            <p v-if="form.errors.email" class="error-message">{{ form.errors.email }}</p>
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label">Contrasenya</label>
                            <div class="input-container">
                                <span class="input-icon">🔒</span>
                                <input
                                    :type="showPassword ? 'text' : 'password'"
                                    id="password"
                                    v-model="form.password"
                                    required
                                    class="form-input"
                                    placeholder="Mínim 8 caràcters"
                                />
                                <button
                                    @click.prevent="showPassword = !showPassword"
                                    class="toggle-password"
                                    type="button"
                                >
                                    {{ showPassword ? '👁️' : '👁️‍🗨️' }}
                                </button>
                            </div>
                            <p v-if="form.errors.password" class="error-message">{{ form.errors.password }}</p>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Confirma la contrasenya</label>
                            <div class="input-container">
                                <span class="input-icon">🔒</span>
                                <input
                                    :type="showConfirmPassword ? 'text' : 'password'"
                                    id="password_confirmation"
                                    v-model="form.password_confirmation"
                                    required
                                    class="form-input"
                                    placeholder="Confirma la teva contrasenya"
                                />
                                <button
                                    @click.prevent="showConfirmPassword = !showConfirmPassword"
                                    class="toggle-password"
                                    type="button"
                                >
                                    {{ showConfirmPassword ? '👁️' : '👁️‍🗨️' }}
                                </button>
                            </div>
                            <p v-if="form.errors.password_confirmation" class="error-message">{{ form.errors.password_confirmation }}</p>
                        </div>

                        <div class="form-group">
                            <label for="empresa" class="form-label">Tipus d'usuari</label>
                            <div class="user-type-selector">
                                <div
                                    class="user-type-option"
                                    :class="{ 'selected': !form.empresa }"
                                    @click="form.empresa = false"
                                >
                                    <div class="option-icon">👤</div>
                                    <div class="option-text">
                                        <div class="option-title">Client</div>
                                        <div class="option-description">Vull fer reserves</div>
                                    </div>
                                </div>
                                <div
                                    class="user-type-option"
                                    :class="{ 'selected': form.empresa }"
                                    @click="form.empresa = true"
                                >
                                    <div class="option-icon">🏢</div>
                                    <div class="option-text">
                                        <div class="option-title">Empresa</div>
                                        <div class="option-description">Tinc un restaurant</div>
                                    </div>
                                </div>
                            </div>
                            <p v-if="form.errors.empresa" class="error-message">{{ form.errors.empresa }}</p>
                        </div>

                        <button
                            type="submit"
                            :disabled="form.processing || isLoading"
                            class="submit-button"
                        >
                            <span v-if="isLoading" class="loading-spinner">⟳</span>
                            <span v-else>Registra't</span>
                        </button>
                    </form>

                    <p class="login-link">
                        Ja tens compte? <a href="/" class="link">Inicia sessió aquí</a>
                    </p>
                </div>
            </div>

            <div class="image-side">
                <div class="overlay">
                    <div class="testimonial">
                        <div class="quote">"</div>
                        <p class="testimonial-text">Aquesta plataforma ha revolucionat la manera en què gestionem el nostre restaurant. Les reserves en línia han augmentat un 40% des que la utilitzem!</p>
                        <div class="testimonial-author">
                            <div class="author-name">Carles Puigdemont</div>
                            <div class="author-role">Propietari, Restaurant La Masia</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.register-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
    padding: 20px;
}

.register-container {
    display: flex;
    width: 100%;
    max-width: 1200px;
    min-height: 600px;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
}

.form-side {
    flex: 1;
    background-color: white;
    padding: 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.form-container {
    max-width: 450px;
    margin: 0 auto;
    width: 100%;
}

.logo-container {
    display: flex;
    align-items: center;
    margin-bottom: 30px;
}

.logo {
    font-size: 2rem;
    margin-right: 10px;
}

.logo-text {
    font-size: 1.8rem;
    font-weight: 700;
    color: #333;
    margin: 0;
}

.form-title {
    font-size: 2.2rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 10px;
}

.form-subtitle {
    font-size: 1rem;
    color: #666;
    margin-bottom: 30px;
}

.register-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-label {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 8px;
    color: #555;
}

.input-container {
    position: relative;
    display: flex;
    align-items: center;
}

.input-icon {
    position: absolute;
    left: 15px;
    font-size: 1rem;
}

.form-input {
    width: 100%;
    padding: 15px 15px 15px 45px;
    border: 1px solid #ddd;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-input:focus {
    border-color: #FF5A5F;
    box-shadow: 0 0 0 2px rgba(255, 90, 95, 0.2);
    outline: none;
}

.toggle-password {
    position: absolute;
    right: 15px;
    background: none;
    border: none;
    cursor: pointer;
    font-size: 1rem;
}

.error-message {
    color: #FF5A5F;
    font-size: 0.8rem;
    margin-top: 5px;
}

.user-type-selector {
    display: flex;
    gap: 15px;
}

.user-type-option {
    flex: 1;
    border: 2px solid #ddd;
    border-radius: 10px;
    padding: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
}

.user-type-option.selected {
    border-color: #FF5A5F;
    background-color: rgba(255, 90, 95, 0.05);
}

.option-icon {
    font-size: 1.5rem;
    margin-right: 10px;
}

.option-title {
    font-weight: 600;
    font-size: 0.9rem;
}

.option-description {
    font-size: 0.8rem;
    color: #666;
}

.submit-button {
    background-color: #FF5A5F;
    color: white;
    border: none;
    border-radius: 10px;
    padding: 15px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    justify-content: center;
    align-items: center;
}

.submit-button:hover {
    background-color: #e5484d;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(255, 90, 95, 0.3);
}

.submit-button:disabled {
    background-color: #ccc;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.loading-spinner {
    animation: spin 1s linear infinite;
    display: inline-block;
    font-size: 1.2rem;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.login-link {
    text-align: center;
    margin-top: 20px;
    font-size: 0.9rem;
    color: #666;
}

.link {
    color: #FF5A5F;
    font-weight: 600;
    text-decoration: none;
}

.link:hover {
    text-decoration: underline;
}

.image-side {
    flex: 1;
    background-image: url('/bg.jpg');
    background-size: cover;
    background-position: center;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.overlay {
    background: rgba(0, 0, 0, 0.5);
    height: 100%;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px;
}

.testimonial {
    background-color: rgba(255, 255, 255, 0.9);
    padding: 30px;
    border-radius: 15px;
    max-width: 400px;
    position: relative;
}

.quote {
    font-size: 4rem;
    position: absolute;
    top: -20px;
    left: 10px;
    color: rgba(255, 90, 95, 0.2);
    font-family: 'Georgia', serif;
}

.testimonial-text {
    font-size: 1.1rem;
    line-height: 1.6;
    margin-bottom: 20px;
    color: #333;
}

.testimonial-author {
    display: flex;
    flex-direction: column;
}

.author-name {
    font-weight: 700;
    font-size: 1rem;
    color: #333;
}

.author-role {
    color: #666;
    font-size: 0.9rem;
}

@media (max-width: 992px) {
    .register-container {
        flex-direction: column;
    }

    .image-side {
        display: none;
    }
}

@media (max-width: 576px) {
    .form-side {
        padding: 20px;
    }

    .user-type-selector {
        flex-direction: column;
    }
}
</style>
