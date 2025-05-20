<template>
    <layout>
        <div class="plats-page">
            <div class="plats-container">
                <div class="page-header">
                    <div class="header-content">
                        <Link :href="route('restaurants.show', { id: restaurant.id })" class="back-button">
                            <span class="back-icon">←</span>
                            <span>Tornar al Restaurant</span>
                        </Link>
                        <h1 class="page-title">Gestionar Carta</h1>
                        <p class="page-subtitle">Afegeix i edita els plats del teu restaurant: {{ restaurant.nom }}</p>
                    </div>
                </div>

                <div class="plats-content">
                    <!-- Lista de platos existentes -->
                    <div class="plats-list-section">
                        <div class="section-header">
                            <h2 class="section-title">
                                <span class="section-icon">🍽️</span>
                                Plats Existents
                            </h2>
                            <span class="plats-count">{{ form.plats.length }} plats</span>
                        </div>

                        <div v-if="form.plats.length > 0" class="plats-grid">
                            <div v-for="(plat, index) in form.plats" :key="plat.id || index" class="plat-card">
                                <div class="plat-header">
                                    <h3 class="plat-name">{{ plat.nom }}</h3>
                                    <div class="plat-price">{{ plat.preu }} €</div>
                                </div>

                                <p class="plat-description">{{ plat.descripcio }}</p>

                                <div class="plat-tags">
                                    <div v-if="getDietaryTags(plat).length > 0" class="tags-group">
                                        <span class="tags-label">Dieta:</span>
                                        <div class="tags-list">
                                            <span v-for="tag in getDietaryTags(plat)" :key="tag" class="tag dietary-tag">
                                                {{ tag }}
                                            </span>
                                        </div>
                                    </div>

                                    <div v-if="getAllergenTags(plat).length > 0" class="tags-group">
                                        <span class="tags-label">Al·lèrgens:</span>
                                        <div class="tags-list">
                                            <span v-for="tag in getAllergenTags(plat)" :key="tag" class="tag allergen-tag">
                                                {{ tag }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <button @click="removePlat(plat.id, index)" class="delete-button">
                                    <span class="delete-icon">🗑️</span>
                                </button>
                            </div>
                        </div>

                        <div v-else class="empty-plats">
                            <div class="empty-icon">🍽️</div>
                            <p class="empty-text">No hi ha plats creats per aquest restaurant.</p>
                            <p class="empty-subtext">Afegeix el teu primer plat utilitzant el formulari.</p>
                        </div>
                    </div>

                    <!-- Formulario para añadir nuevo plato -->
                    <div class="add-plat-section">
                        <div class="section-header">
                            <h2 class="section-title">
                                <span class="section-icon">➕</span>
                                Afegir Nou Plat
                            </h2>
                        </div>

                        <div class="add-plat-form">
                            <div class="form-group">
                                <label for="plat_nom" class="form-label">Nom del Plat</label>
                                <input
                                    v-model="newPlat.nom"
                                    id="plat_nom"
                                    type="text"
                                    class="form-input"
                                    placeholder="Ex: Paella Valenciana"
                                />
                            </div>

                            <div class="form-group">
                                <label for="plat_descripcio" class="form-label">Descripció del Plat</label>
                                <textarea
                                    v-model="newPlat.descripcio"
                                    id="plat_descripcio"
                                    class="form-textarea"
                                    placeholder="Descriu els ingredients i la preparació..."
                                ></textarea>
                            </div>

                            <div class="form-group">
                                <label for="plat_preu" class="form-label">Preu (€)</label>
                                <input
                                    v-model="newPlat.preu"
                                    id="plat_preu"
                                    type="number"
                                    step="0.01"
                                    class="form-input"
                                    placeholder="Ex: 12.95"
                                />
                            </div>

                            <div class="form-group">
                                <label class="form-label">Opcions Dietètiques</label>
                                <div class="toggle-grid">
                                    <ToggleButton v-model="newPlat.vegetaria" label="Vegetarià" />
                                    <ToggleButton v-model="newPlat.vega" label="Vegà" />
                                    <ToggleButton v-model="newPlat.kosher" label="Kosher" />
                                    <ToggleButton v-model="newPlat.halal" label="Halal" />
                                    <ToggleButton v-model="newPlat.keto" label="Keto" />
                                    <ToggleButton v-model="newPlat.carn_vermella" label="Carn Vermella" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Al·lèrgens (marcar si conté)</label>
                                <div class="toggle-grid">
                                    <ToggleButton v-model="newPlat.gluten" label="Gluten" />
                                    <ToggleButton v-model="newPlat.lactics" label="Lactics" />
                                    <ToggleButton v-model="newPlat.crustaci" label="Crustacis" />
                                    <ToggleButton v-model="newPlat.ous" label="Ous" />
                                    <ToggleButton v-model="newPlat.lupines" label="Lupines" />
                                    <ToggleButton v-model="newPlat.mostassa" label="Mostassa" />
                                    <ToggleButton v-model="newPlat.cacahuats" label="Cacahuets" />
                                    <ToggleButton v-model="newPlat.soja" label="Soja" />
                                </div>
                            </div>

                            <div class="form-actions">
                                <button
                                    type="button"
                                    @click="addPlat"
                                    :disabled="isPlatEmpty"
                                    class="add-button"
                                >
                                    <span v-if="isSubmitting" class="loading-spinner"></span>
                                    <span v-else>Afegir Plat</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </layout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { route } from 'ziggy-js';
import Layout from '@/Layouts/Layout.vue';
import { Link } from "@inertiajs/vue3";
import ToggleButton from '@/Components/CheckButton.vue';
import axios from 'axios';

const props = defineProps({
    restaurant: Object,
});

const form = reactive({
    plats: props.restaurant.plats || [],
});

const newPlat = reactive({
    nom: '',
    descripcio: '',
    preu: null,
    gluten: false,
    lactics: false,
    crustaci: false,
    ous: false,
    lupines: false,
    mostassa: false,
    cacahuats: false,
    soja: false,
    vegetaria: false,
    vega: false,
    carn_vermella: false,
    kosher: false,
    halal: false,
    keto: false,
});

const isSubmitting = ref(false);
const isPlatEmpty = computed(() => {
    return !newPlat.nom || !newPlat.preu;
});

const getDietaryTags = (plat) => {
    const tags = [];
    if (plat.vegetaria) tags.push('Vegetarià');
    if (plat.vega) tags.push('Vegà');
    if (plat.kosher) tags.push('Kosher');
    if (plat.halal) tags.push('Halal');
    if (plat.keto) tags.push('Keto');
    if (plat.carn_vermella) tags.push('Carn Vermella');
    return tags;
};

const getAllergenTags = (plat) => {
    const tags = [];
    if (plat.gluten) tags.push('Gluten');
    if (plat.lactics) tags.push('Lactics');
    if (plat.crustaci) tags.push('Crustacis');
    if (plat.ous) tags.push('Ous');
    if (plat.lupines) tags.push('Lupines');
    if (plat.mostassa) tags.push('Mostassa');
    if (plat.cacahuats) tags.push('Cacahuets');
    if (plat.soja) tags.push('Soja');
    return tags;
};

const addPlat = () => {
    if (isPlatEmpty.value) return;

    isSubmitting.value = true;

    axios.post(route('restaurants.plats.store', {restaurant: props.restaurant.id}), newPlat)
        .then(response => {
            form.plats.push(response.data);

            // Reset form
            Object.assign(newPlat, {
                nom: '',
                descripcio: '',
                preu: null,
                gluten: false,
                lactics: false,
                crustaci: false,
                ous: false,
                lupines: false,
                mostassa: false,
                cacahuats: false,
                soja: false,
                vegetaria: false,
                vega: false,
                carn_vermella: false,
                kosher: false,
                halal: false,
                keto: false,
            });

            isSubmitting.value = false;
        })
        .catch(error => {
            console.error("Error creating plat:", error);
            isSubmitting.value = false;
        });
};

const removePlat = (platId, index) => {
    if (confirm('Estàs segur que vols eliminar aquest plat?')) {
        axios.delete(route('restaurants.plats.destroy', {restaurant: props.restaurant.id, plat: platId}))
            .then(() => {
                form.plats.splice(index, 1);
            })
            .catch(error => {
                console.error("Error deleting plat:", error);
            });
    }
};
</script>

<style scoped>
.plats-page {
    background-color: #f8f9fa;
    min-height: 100vh;
    padding: 40px 20px;
}

.plats-container {
    max-width: 1200px;
    margin: 0 auto;
    background-color: white;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.page-header {
    background: linear-gradient(135deg, #FF5A5F, #FF8A8E);
    color: white;
    padding: 40px;
}

.back-button {
    display: inline-flex;
    align-items: center;
    color: white;
    background-color: rgba(255, 255, 255, 0.2);
    padding: 8px 15px;
    border-radius: 30px;
    margin-bottom: 15px;
    transition: all 0.3s ease;
}

.back-button:hover {
    background-color: rgba(255, 255, 255, 0.3);
    transform: translateX(-5px);
}

.back-icon {
    margin-right: 8px;
    font-size: 1.2rem;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 10px;
}

.page-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
}

.plats-content {
    padding: 40px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
    display: flex;
    align-items: center;
}

.section-icon {
    margin-right: 10px;
    font-size: 1.5rem;
}

.plats-count {
    background-color: #FF5A5F;
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
}

.plats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
    max-height: 600px;
    overflow-y: auto;
    padding-right: 10px;
}

.plat-card {
    background-color: #f9f9f9;
    border-radius: 12px;
    padding: 20px;
    position: relative;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.plat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.plat-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 10px;
}

.plat-name {
    font-size: 1.2rem;
    font-weight: 700;
    color: #333;
    margin: 0;
    padding-right: 30px;
}

.plat-price {
    background-color: #00B140;
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
    white-space: nowrap;
}

.plat-description {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 15px;
    line-height: 1.4;
    max-height: 60px;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
}

.plat-tags {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.tags-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.tags-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: #555;
}

.tags-list {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
}

.tag {
    font-size: 0.75rem;
    padding: 3px 8px;
    border-radius: 15px;
    white-space: nowrap;
}

.dietary-tag {
    background-color: #e1f5fe;
    color: #0288d1;
}

.allergen-tag {
    background-color: #ffebee;
    color: #e53935;
}

.delete-button {
    position: absolute;
    top: 15px;
    right: 15px;
    background: none;
    border: none;
    cursor: pointer;
    font-size: 1.2rem;
    opacity: 0.6;
    transition: all 0.3s ease;
}

.delete-button:hover {
    opacity: 1;
    transform: scale(1.2);
}

.empty-plats {
    background-color: #f9f9f9;
    border-radius: 12px;
    padding: 40px;
    text-align: center;
}

.empty-icon {
    font-size: 3rem;
    margin-bottom: 15px;
}

.empty-text {
    font-size: 1.2rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
}

.empty-subtext {
    color: #666;
}

.add-plat-form {
    background-color: #f9f9f9;
    border-radius: 12px;
    padding: 25px;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #555;
}

.form-input, .form-textarea {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-textarea {
    min-height: 100px;
    resize: vertical;
}

.form-input:focus, .form-textarea:focus {
    border-color: #FF5A5F;
    box-shadow: 0 0 0 2px rgba(255, 90, 95, 0.2);
    outline: none;
}

.toggle-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 10px;
}

.form-actions {
    margin-top: 30px;
}

.add-button {
    width: 100%;
    padding: 12px;
    background-color: #FF5A5F;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.add-button:hover:not(:disabled) {
    background-color: #e5484d;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(255, 90, 95, 0.3);
}

.add-button:disabled {
    background-color: #ffb5b8;
    cursor: not-allowed;
}

.loading-spinner {
    width: 20px;
    height: 20px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 1s linear infinite;
    margin-right: 10px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

@media (max-width: 992px) {
    .plats-content {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .plats-grid {
        grid-template-columns: 1fr;
    }

    .toggle-grid {
        grid-template-columns: 1fr 1fr;
    }
}
</style>
