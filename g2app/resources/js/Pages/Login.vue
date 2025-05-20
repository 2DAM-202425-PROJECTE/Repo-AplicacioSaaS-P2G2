<script setup>
import Layout from "@/Layouts/Layout.vue";
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";

const showPassword = ref(false);
const isLoading = ref(false);
const rememberMe = ref(false);

const form = useForm({
    email: "empresa@email.com",
    password: "password",
});

const submit = () => {
    isLoading.value = true;
    form.post("/login", {
        onFinish: () => {
            form.reset("password");
            isLoading.value = false;
        },
    });
};
</script>

<template>
    <div class="login-page">
        <div class="login-container">
            <div class="form-side">
                <div class="form-container">
                    <div class="logo-container">
                        <div class="logo">🍽️</div>
                        <h1 class="logo-text">TableTab</h1>
                    </div>

                    <h2 class="form-title">Benvingut de nou!</h2>
                    <p class="form-subtitle">Inicia sessió per accedir al teu compte</p>

                    <form @submit.prevent="submit" class="login-form">
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
                            <div class="password-header">
                                <label for="password" class="form-label">Contrasenya</label>
                                <a href="/forgot-password" class="forgot-password">Has oblidat la contrasenya?</a>
                            </div>
                            <div class="input-container">
                                <span class="input-icon">🔒</span>
                                <input
                                    :type="showPassword ? 'text' : 'password'"
                                    id="password"
                                    v-model="form.password"
                                    required
                                    class="form-input"
                                    placeholder="Introdueix la teva contrasenya"
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

                        <div class="remember-me">
                            <label class="checkbox-container">
                                <input type="checkbox" v-model="rememberMe">
                                <span class="checkmark"></span>
                                Recorda'm
                            </label>
                        </div>

                        <button
                            type="submit"
                            :disabled="form.processing || isLoading"
                            class="submit-button"
                        >
                            <span v-if="isLoading" class="loading-spinner">⟳</span>
                            <span v-else>Iniciar Sessió</span>
                        </button>
                    </form>

                    <p class="register-link">
                        No tens compte? <a href="/register" class="link">Registra't aquí</a>
                    </p>
                </div>
            </div>

            <div class="image-side">
                <div class="overlay">
                    <div class="welcome-message">
                        <h2 class="welcome-title">Gestiona el teu restaurant amb facilitat</h2>
                        <p class="welcome-text">Accedeix a totes les eines que necessites per optimitzar el teu negoci</p>
                        <div class="features-list">
                            <div class="feature-item">
                                <span class="feature-icon">✓</span>
                                <span>Gestió de reserves en temps real</span>
                            </div>
                            <div class="feature-item">
                                <span class="feature-icon">✓</span>
                                <span>Control d'ocupació i taules</span>
                            </div>
                            <div class="feature-item">
                                <span class="feature-icon">✓</span>
                                <span>Estadístiques i informes detallats</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.login-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
    padding: 20px;
}

.login-container {
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

.login-form {
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

.password-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.forgot-password {
    font-size: 0.8rem;
    color: #FF5A5F;
    text-decoration: none;
}

.forgot-password:hover {
    text-decoration: underline;
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

.remember-me {
    display: flex;
    align-items: center;
}

.checkbox-container {
    display: flex;
    align-items: center;
    position: relative;
    padding-left: 30px;
    cursor: pointer;
    font-size: 0.9rem;
    color: #555;
    user-select: none;
}

.checkbox-container input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
}

.checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 20px;
    width: 20px;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.checkbox-container:hover input ~ .checkmark {
    border-color: #ccc;
}

.checkbox-container input:checked ~ .checkmark {
    background-color: #FF5A5F;
    border-color: #FF5A5F;
}

.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

.checkbox-container input:checked ~ .checkmark:after {
    display: block;
}

.checkbox-container .checkmark:after {
    left: 7px;
    top: 3px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
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

.divider {
    display: flex;
    align-items: center;
    margin: 20px 0;
}

.divider::before,
.divider::after {
    content: "";
    flex: 1;
    border-bottom: 1px solid #ddd;
}

.divider span {
    padding: 0 10px;
    color: #777;
    font-size: 0.9rem;
}

.social-login {
    margin-bottom: 20px;
}

.social-button {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 10px;
    background-color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9rem;
    font-weight: 500;
}

.social-button:hover {
    background-color: #f8f9fa;
    border-color: #ccc;
}

.social-icon {
    margin-right: 10px;
    font-weight: 700;
    color: #4285F4;
}

.register-link {
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
    background: rgba(0, 0, 0, 0.6);
    height: 100%;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px;
}

.welcome-message {
    color: white;
    max-width: 400px;
}

.welcome-title {
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 15px;
}

.welcome-text {
    font-size: 1.1rem;
    margin-bottom: 30px;
    opacity: 0.9;
}

.features-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.feature-item {
    display: flex;
    align-items: center;
}

.feature-icon {
    width: 24px;
    height: 24px;
    background-color: rgba(255, 90, 95, 0.8);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 10px;
    font-size: 0.8rem;
}

@media (max-width: 992px) {
    .login-container {
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
}
</style>
