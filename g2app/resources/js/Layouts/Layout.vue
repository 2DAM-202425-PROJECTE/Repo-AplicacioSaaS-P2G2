<template>
    <div class="app-layout" ref="appLayout">
        <!-- Barra de navegación -->
        <nav class="navbar" :class="{ 'navbar-scrolled': isScrolled }">
            <div class="container">
                <div class="navbar-content">
                    <Link href="/" class="navbar-logo" @click="resetView">
                        <div class="logo-icon">🍽️</div>
                        <div class="logo-text">TableTab</div>
                    </Link>

                    <div class="navbar-menu" :class="{ 'menu-open': isMenuOpen }">
                        <Link :href="route('home')" class="nav-link" :class="{ 'active': isRoute('home') }" @click="resetView">
                            <span class="nav-icon">🏠</span>
                            <span class="nav-text">Inici</span>
                        </Link>

                        <Link :href="route('restaurants.index')" class="nav-link" :class="{ 'active': isRoute('restaurants.index') }" @click="resetView">
                            <span class="nav-icon">🍽️</span>
                            <span class="nav-text">Restaurants</span>
                        </Link>

                        <Link v-if="isAuthenticated" :href="route('user.reserves')" class="nav-link" :class="{ 'active': isRoute('user.reserves') }" @click="resetView">
                            <span class="nav-icon">📅</span>
                            <span class="nav-text">Reserves</span>
                        </Link>

                        <Link :href="route('notificacions.index')" class="nav-link" :class="{ 'active': isRoute('notificacions.index') }" @click="resetView">
                            <span class="nav-icon">🔔</span>
                            <span class="nav-text">Notificacions</span>
                        </Link>

                        <div class="theme-toggle">
                            <button @click="toggleTheme" class="theme-button">
                                <span v-if="isDarkMode" class="theme-icon">☀️</span>
                                <span v-else class="theme-icon">🌙</span>
                            </button>
                        </div>
                    </div>

                    <div class="navbar-actions">
                        <template v-if="isAuthenticated">
                            <div class="user-dropdown" ref="dropdown">
                                <button @click="toggleDropdown" class="user-button">
                                    <div class="user-avatar">{{ userInitials }}</div>
                                    <span class="user-name">{{ userName }}</span>
                                    <span class="dropdown-arrow" :class="{ 'arrow-up': isOpen }">▼</span>
                                </button>

                                <div v-if="isOpen" class="dropdown-menu">
                                    <a href="/configuracio" class="dropdown-item">
                                        <span class="dropdown-icon">⚙️</span>
                                        <span>Configuració</span>
                                    </a>

                                    <div class="dropdown-divider"></div>

                                    <button @click="confirmLogout" class="dropdown-item logout-item">
                                        <span class="dropdown-icon">🚪</span>
                                        <span>Tancar Sessió</span>
                                    </button>
                                </div>
                            </div>
                        </template>

                        <template v-else>
                            <Link :href="route('login')" class="auth-button login-button" @click="resetView">
                                <span>Iniciar Sessió</span>
                            </Link>

                            <Link :href="route('register')" class="auth-button register-button" @click="resetView">
                                <span>Registrar-se</span>
                            </Link>
                        </template>

                        <button @click="toggleMenu" class="menu-toggle">
              <span class="menu-icon" :class="{ 'menu-open': isMenuOpen }">
                <span class="menu-line"></span>
              </span>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Modal de confirmación de logout -->
        <div v-if="showLogoutConfirm" class="logout-modal-overlay">
            <div class="logout-modal">
                <h3 class="modal-title">Estàs segur que vols tancar sessió?</h3>
                <div class="modal-actions">
                    <button @click="cancelLogout" class="modal-button cancel-button">
                        Cancel·lar
                    </button>
                    <button @click="handleLogout" class="modal-button confirm-button">
                        D'acord
                    </button>
                </div>
            </div>
        </div>

        <!-- Contenido principal -->
        <main style="padding-top: 70px;">
            <slot></slot>
        </main>

        <!-- Pie de página -->
        <footer class="footer">
            <div class="container">
                <div class="footer-content">
                    <div class="footer-brand">
                        <div class="footer-logo">
                            <div class="logo-icon">🍽️</div>
                            <div class="logo-text">TableTab</div>
                        </div>
                        <p class="footer-tagline">La plataforma líder en gestió de reserves per a restaurants</p>
                    </div>

                    <div class="footer-links">
                        <div class="footer-column">
                            <h3 class="footer-title">Navegació</h3>
                            <ul class="footer-menu">
                                <li><Link :href="route('home')" class="footer-link" @click="resetView">Inici</Link></li>
                                <li><Link :href="route('restaurants.index')" class="footer-link" @click="resetView">Restaurants</Link></li>
                            </ul>
                        </div>

                        <div class="footer-column">
                            <h3 class="footer-title">Contacte</h3>
                            <ul class="footer-menu">
                                <li><a href="mailto:info@tabletab.com" class="footer-link">info@tabletab.com</a></li>
                                <li><a href="tel:+34932123456" class="footer-link">+34 932 12 34 56</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="footer-bottom">
                    <p class="copyright">© {{ currentYear }} TableTab. Tots els drets reservats.</p>
                </div>
            </div>
        </footer>
    </div>
    
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch, nextTick } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import axios from 'axios';

// Referencias para el DOM
const appLayout = ref(null);
const mainContent = ref(null);

// Estado de la navegación
const isScrolled = ref(false);
const isMenuOpen = ref(false);
const isOpen = ref(false);
const showLogoutConfirm = ref(false);
const isDarkMode = ref(false);
const dropdown = ref(null);
const isNavigating = ref(false);

// Obtener información del usuario autenticado
const page = usePage();
const isAuthenticated = computed(() => page.props.auth && page.props.auth.user !== null);
const userName = computed(() => isAuthenticated.value ? page.props.auth.user.name : '');
const userInitials = computed(() => {
    if (!isAuthenticated.value) return '';
    return userName.value.split(' ').map(n => n[0]).join('').toUpperCase();
});

// Comprobar si la ruta actual coincide con la proporcionada
const isRoute = (routeName) => {
    return route().current(routeName);
};

// Año actual para el copyright
const currentYear = computed(() => new Date().getFullYear());

// Función para resetear la vista al navegar
const resetView = () => {
    isNavigating.value = true;
    // Cerrar menús si están abiertos
    isMenuOpen.value = false;
    isOpen.value = false;

    // Asegurar que el scroll se resetea
    setTimeout(() => {
        window.scrollTo(0, 0);

        // Forzar un reflow/repaint para asegurar que el contenido se muestra correctamente
        if (mainContent.value) {
            mainContent.value.style.display = 'none';
            // Forzar un reflow
            void mainContent.value.offsetHeight;
            mainContent.value.style.display = '';
        }

        isNavigating.value = false;
    }, 0);
};

// Manejar el scroll para cambiar el estilo de la barra de navegación
const handleScroll = () => {
    isScrolled.value = window.scrollY > 50;
};

// Alternar el menú móvil
const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
    if (isMenuOpen.value) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
};

// Alternar el menú desplegable del usuario
const toggleDropdown = () => {
    isOpen.value = !isOpen.value;
};

// Cerrar el menú desplegable al hacer clic fuera de él
const handleClickOutside = (event) => {
    if (dropdown.value && !dropdown.value.contains(event.target)) {
        isOpen.value = false;
    }
};

// Alternar el tema claro/oscuro
const toggleTheme = () => {
    isDarkMode.value = !isDarkMode.value;
    document.documentElement.classList.toggle('dark-theme', isDarkMode.value);
    localStorage.setItem('theme', isDarkMode.value ? 'dark' : 'light');
};

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

// Solución para el problema de scroll: desplazar al inicio cuando cambia la página
watch(() => page.url, (newUrl, oldUrl) => {
    if (newUrl !== oldUrl) {
        resetView();

        // Solución adicional: forzar un reflow después de que el DOM se actualice
        nextTick(() => {
            if (document.body) {
                document.body.style.display = 'none';
                void document.body.offsetHeight; // Forzar reflow
                document.body.style.display = '';
            }

            // Asegurar que el scroll está en la parte superior
            window.scrollTo(0, 0);
        });
    }
}, { immediate: true });

// Alternativa: escuchar eventos de navegación de Inertia
router.on('start', () => {
    isNavigating.value = true;
});

router.on('finish', () => {
    resetView();
    isNavigating.value = false;
});

// Configurar los event listeners al montar el componente
onMounted(() => {
    window.addEventListener('scroll', handleScroll);
    document.addEventListener('click', handleClickOutside);

    // Comprobar si hay un tema guardado en localStorage
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        isDarkMode.value = true;
        document.documentElement.classList.add('dark-theme');
    }

    // Asegurar que la página comienza desde arriba al cargar
    window.scrollTo(0, 0);

    // Solución para el problema de visualización: forzar un reflow
    if (mainContent.value) {
        mainContent.value.style.display = 'none';
        void mainContent.value.offsetHeight; // Forzar reflow
        mainContent.value.style.display = '';
    }

    // Solución adicional: corregir el ancho de la ventana
    document.documentElement.style.width = '100%';
    document.body.style.width = '100%';

    // Solución para problemas de CSS
    const style = document.createElement('style');
    style.textContent = `
    html, body {
      width: 100% !important;
      overflow-x: hidden !important;
      position: relative !important;
    }
    .app-layout {
      width: 100% !important;
      overflow-x: hidden !important;
      position: relative !important;
    }
    .main-content {
      width: 100% !important;
      overflow-x: hidden !important;
      position: relative !important;
    }
  `;
    document.head.appendChild(style);
});

// Limpiar los event listeners al desmontar el componente
onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
    document.removeEventListener('click', handleClickOutside);
});
</script>

<style>
/* Variables CSS para el tema claro */
:root {
    --primary-color: #FF5A5F;
    --primary-hover: #e5484d;
    --text-color: #333;
    --text-light: #666;
    --bg-color: #fff;
    --bg-light: #f8f9fa;
    --border-color: #eee;
    --shadow-color: rgba(0, 0, 0, 0.05);
    --shadow-hover: rgba(0, 0, 0, 0.1);
}

/* Variables CSS para el tema oscuro */
.dark-theme {
    --primary-color: #FF5A5F;
    --primary-hover: #ff8a8e;
    --text-color: #f1f1f1;
    --text-light: #aaa;
    --bg-color: #121212;
    --bg-light: #1e1e1e;
    --border-color: #333;
    --shadow-color: rgba(0, 0, 0, 0.2);
    --shadow-hover: rgba(0, 0, 0, 0.4);
}

/* Estilos globales */
html, body {
    width: 100% !important;
    overflow-x: hidden !important;
    position: relative !important;
    margin: 0 !important;
    padding: 0 !important;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: var(--text-color);
    background-color: var(--bg-light);
    transition: background-color 0.3s ease, color 0.3s ease;
}

html {
    scroll-behavior: smooth; /* Añadido para suavizar el desplazamiento */
}

.layout-container {
    display: block; /* Canvia de flex o grid a block si no és necessari */
    width: 100%; /* Assegura que ocupa tota l'amplada */
    max-width: none; /* Elimina qualsevol limitació d'amplada */
    margin: 0 auto; /* Centra el contingut si cal */
    padding: 0; /* Ajusta el padding si cal */
}

.app-layout {
    width: 100% !important;
    overflow-x: hidden !important;
    position: relative !important;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    width: 100%;
    box-sizing: border-box;
}

/* Barra de navegación */
.navbar {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background-color: var(--bg-color);
    z-index: 1000;
    transition: all 0.3s ease;
    box-shadow: 0 2px 10px var(--shadow-color);
}

.navbar-scrolled {
    box-shadow: 0 5px 15px var(--shadow-color);
}

.navbar-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 70px;
}

.navbar-logo {
    display: flex;
    align-items: center;
    text-decoration: none;
    color: var(--text-color);
}

.logo-icon {
    font-size: 1.5rem;
    margin-right: 10px;
}

.logo-text {
    font-size: 1.3rem;
    font-weight: 700;
}

.navbar-menu {
    display: flex;
    align-items: center;
}

.nav-link {
    display: flex;
    align-items: center;
    padding: 0 15px;
    height: 70px;
    color: var(--text-color);
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
}

.nav-link:hover {
    color: var(--primary-color);
}

.nav-link.active {
    color: var(--primary-color);
}

.nav-link.active::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 15px;
    right: 15px;
    height: 3px;
    background-color: var(--primary-color);
}

.nav-icon {
    margin-right: 8px;
}

.navbar-actions {
    display: flex;
    align-items: center;
}

.auth-button {
    padding: 8px 15px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    margin-left: 10px;
}

.login-button {
    background-color: transparent;
    color: var(--text-color);
    border: 1px solid var(--border-color);
}

.login-button:hover {
    background-color: var(--bg-light);
}

.register-button {
    background-color: var(--primary-color);
    color: white;
}

.register-button:hover {
    background-color: var(--primary-hover);
}

.user-dropdown {
    position: relative;
}

.user-button {
    display: flex;
    align-items: center;
    background: none;
    border: none;
    cursor: pointer;
    padding: 8px 15px;
    border-radius: 30px;
    transition: all 0.3s ease;
    color: var(--text-color);
}

.user-button:hover {
    background-color: var(--bg-light);
}

.user-avatar {
    width: 32px;
    height: 32px;
    background-color: var(--primary-color);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    margin-right: 10px;
}

.dropdown-arrow {
    margin-left: 8px;
    font-size: 0.8rem;
    transition: transform 0.3s ease;
}

.arrow-up {
    transform: rotate(180deg);
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    background-color: var(--bg-color);
    border-radius: 10px;
    box-shadow: 0 5px 20px var(--shadow-color);
    min-width: 200px;
    z-index: 1000;
    margin-top: 10px;
    overflow: hidden;
}

.dropdown-item {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    color: var(--text-color);
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    font-size: 1rem;
}

.dropdown-item:hover {
    background-color: var(--bg-light);
}

.dropdown-icon {
    margin-right: 10px;
}

.dropdown-divider {
    height: 1px;
    background-color: var(--border-color);
    margin: 5px 0;
}

.logout-item {
    color: var(--primary-color);
}

.menu-toggle {
    display: none;
    background: none;
    border: none;
    cursor: pointer;
    width: 40px;
    height: 40px;
    position: relative;
    margin-left: 15px;
}

.menu-icon {
    position: relative;
    width: 24px;
    height: 20px;
    display: inline-block;
}

.menu-line, .menu-icon::before, .menu-icon::after {
    position: absolute;
    width: 100%;
    height: 2px;
    background-color: var(--text-color);
    transition: all 0.3s ease;
}

.menu-line {
    top: 50%;
    transform: translateY(-50%);
}

.menu-icon::before, .menu-icon::after {
    content: '';
    left: 0;
}

.menu-icon::before {
    top: 0;
}

.menu-icon::after {
    bottom: 0;
}

.menu-icon.menu-open .menu-line {
    opacity: 0;
}

.menu-icon.menu-open::before {
    transform: translateY(9px) rotate(45deg);
}

.menu-icon.menu-open::after {
    transform: translateY(-9px) rotate(-45deg);
}

.theme-toggle {
    margin-left: 15px;
}

.theme-button {
    background: none;
    border: none;
    cursor: pointer;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.theme-button:hover {
    background-color: var(--bg-light);
}

.theme-icon {
    font-size: 1.2rem;
}

/* Modal de logout */
.logout-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 2000;
}

.logout-modal {
    background-color: var(--bg-color);
    border-radius: 12px;
    padding: 24px;
    width: 90%;
    max-width: 400px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.modal-title {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 20px;
    color: var(--text-color);
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}

.modal-button {
    padding: 10px 16px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
}

.cancel-button {
    background-color: var(--bg-light);
    color: var(--text-color);
}

.cancel-button:hover {
    background-color: var(--border-color);
}

.confirm-button {
    background-color: var(--primary-color);
    color: white;
}

.confirm-button:hover {
    background-color: var(--primary-hover);
}

/* Contenido principal */
.main-content {

    min-height: calc(100vh - 70px - 200px); /* Altura de la ventana - navbar - footer */
}

/* Pie de página */
.footer {
    background-color: var(--bg-color);
    color: var(--text-color);
    padding: 40px 0 20px;
    border-top: 1px solid var(--border-color);
    width: 100%;
}

.footer-content {
    display: flex;
    justify-content: space-between;
    margin-bottom: 30px;
}

.footer-brand {
    max-width: 300px;
}

.footer-logo {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.footer-tagline {
    color: var(--text-light);
    line-height: 1.5;
}

.footer-links {
    display: flex;
    gap: 60px;
}

.footer-title {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 15px;
}

.footer-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-menu li {
    margin-bottom: 10px;
}

.footer-link {
    color: var(--text-light);
    text-decoration: none;
    transition: all 0.3s ease;
}

.footer-link:hover {
    color: var(--primary-color);
}

.footer-bottom {
    padding-top: 20px;
    border-top: 1px solid var(--border-color);
    text-align: center;
}

.copyright {
    color: var(--text-light);
    font-size: 0.9rem;
}

/* Responsive */
@media (max-width: 992px) {
    .navbar-menu {
        position: fixed;
        top: 70px;
        left: 0;
        width: 100%;
        height: calc(100vh - 70px);
        background-color: var(--bg-color);
        flex-direction: column;
        align-items: flex-start;
        padding: 20px;
        transform: translateX(-100%);
        transition: transform 0.3s ease;
        z-index: 999;
        overflow-y: auto;
    }

    .navbar-menu.menu-open {
        transform: translateX(0);
    }

    .nav-link {
        width: 100%;
        height: auto;
        padding: 15px 0;
    }

    .nav-link.active::after {
        display: none;
    }

    .menu-toggle {
        display: block;
    }

    .theme-toggle {
        margin: 15px 0;
    }

    .footer-content {
        flex-direction: column;
        gap: 30px;
    }

    .footer-brand {
        max-width: 100%;
    }

    .footer-links {
        flex-wrap: wrap;
        gap: 30px;
    }
}

@media (max-width: 768px) {
    .user-name {
        display: none;
    }

    .dropdown-arrow {
        display: none;
    }
}

@media (max-width: 576px) {
    .auth-button {
        padding: 8px 12px;
        font-size: 0.9rem;
    }

    .login-button {
        display: none;
    }
}
</style>
