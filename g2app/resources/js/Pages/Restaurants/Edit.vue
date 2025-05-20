<template>
    <layout>
        <div class="edit-restaurant-page">
            <div class="edit-container">
                <div class="page-header">
                    <div class="header-content">
                        <Link :href="route('restaurants.show', { id: restaurant.id })" class="back-button">
                            <span class="back-icon">←</span>
                            <span>Tornar al Restaurant</span>
                        </Link>
                        <h1 class="page-title">Editar Restaurant</h1>
                        <p class="page-subtitle">Actualitza la informació del teu restaurant</p>
                    </div>
                    <div class="restaurant-preview">
                        <img :src="`/storage/${restaurant.profile_image}`" alt="Restaurant" class="restaurant-image" />
                    </div>
                </div>

                <form @submit.prevent="submitAdminForm" class="edit-form">
                    <div class="form-sections">
                        <!-- Sección de información básica -->
                        <div class="form-section">
                            <h2 class="section-title">
                                <span class="section-icon">📋</span>
                                Informació Bàsica
                            </h2>

                            <div class="form-group">
                                <label for="nom" class="form-label">Nom del Restaurant</label>
                                <input
                                    v-model="form.nom"
                                    id="nom"
                                    type="text"
                                    class="form-input"
                                    required
                                />
                            </div>

                            <div class="form-group">
                                <label for="descripcio" class="form-label">Descripció</label>
                                <textarea
                                    v-model="form.descripcio"
                                    id="descripcio"
                                    class="form-textarea"
                                    required
                                ></textarea>
                            </div>

                            <div class="form-group">
                                <label for="telefon" class="form-label">Telèfon de Contacte</label>
                                <input
                                    v-model="form.telefon"
                                    id="telefon"
                                    type="text"
                                    class="form-input"
                                    required
                                />
                            </div>

                            <div class="form-group">
                                <label for="tipus_cuina" class="form-label">Tipus de Cuina</label>
                                <select
                                    v-model="form.tipus_cuina"
                                    id="tipus_cuina"
                                    class="form-select"
                                    required
                                >
                                    <option v-for="option in tipusCuinaOptions" :key="option" :value="option">
                                        {{ option }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Sección de ubicación -->
                        <div class="form-section">
                            <h2 class="section-title">
                                <span class="section-icon">📍</span>
                                Ubicació
                            </h2>

                            <div class="form-group">
                                <label for="provincia" class="form-label">Província</label>
                                <select
                                    v-model="selectedProvinciaId"
                                    @change="fetchMunicipios"
                                    id="provincia"
                                    class="form-select"
                                    required
                                >
                                    <option v-for="provincia in provincias" :key="provincia.id" :value="provincia.id">
                                        {{ provincia.name }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group" v-if="municipios.length > 0">
                                <label for="municipi" class="form-label">Municipi</label>
                                <select
                                    v-model="form.municipio_id"
                                    id="municipi"
                                    class="form-select"
                                    required
                                >
                                    <option value="" disabled>Selecciona un municipi</option>
                                    <option v-for="municipio in municipios" :key="municipio.id" :value="municipio.id">
                                        {{ municipio.name }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="carrer" class="form-label">Adreça</label>
                                <input
                                    v-model="form.carrer"
                                    id="carrer"
                                    type="text"
                                    class="form-input"
                                    required
                                />
                            </div>
                        </div>

                        <!-- Sección de horarios e imagen -->
                        <div class="form-section">
                            <h2 class="section-title">
                                <span class="section-icon">🕒</span>
                                Horaris i Imatge
                            </h2>

                            <div class="form-row">
                                <div class="form-group half">
                                    <label for="hora_obertura" class="form-label">Hora d'Obertura</label>
                                    <input
                                        v-model="form.hora_obertura"
                                        id="hora_obertura"
                                        type="time"
                                        class="form-input"
                                        required
                                    />
                                </div>

                                <div class="form-group half">
                                    <label for="hora_tancament" class="form-label">Hora de Tancament</label>
                                    <input
                                        v-model="form.hora_tancament"
                                        id="hora_tancament"
                                        type="time"
                                        class="form-input"
                                        required
                                    />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="profile_image" class="form-label">Canviar Imatge Principal</label>
                                <div class="file-upload-container">
                                    <input
                                        id="profile_image"
                                        type="file"
                                        class="file-input"
                                        @change="HandleFileUpload"
                                    />
                                    <div class="file-upload-button">
                                        <span class="upload-icon">📷</span>
                                        <span>Selecciona una nova imatge</span>
                                    </div>
                                    <span v-if="form.profile_image && typeof form.profile_image !== 'string'" class="file-name">
                                        {{ form.profile_image.name }}
                                    </span>
                                </div>
                                <p class="form-hint">Deixa en blanc per mantenir la imatge actual</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <Link :href="route('restaurants.show', { id: restaurant.id })" class="cancel-button">
                            Cancel·lar
                        </Link>
                        <button type="submit" class="submit-button" :disabled="isSubmitting">
                            <span v-if="isSubmitting" class="loading-spinner"></span>
                            <span v-else>Guardar Canvis</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </layout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { route } from 'ziggy-js';
import axios from 'axios';
import Layout from '@/Layouts/Layout.vue';
import { Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    restaurant: Object,
    tipusCuinaOptions: Array,
    provincias: Array,
    municipios: Array,
    plats: Array,
});

const form = useForm({
    nom: props.restaurant.nom,
    descripcio: props.restaurant.descripcio,
    telefon: props.restaurant.telefon,
    profile_image: props.restaurant.profile_image,
    tipus_cuina: props.restaurant.tipus_cuina,
    municipio_id: props.restaurant.municipio_id,
    carrer: props.restaurant.carrer,
    hora_obertura: props.restaurant.hora_obertura,
    hora_tancament: props.restaurant.hora_tancament,
    plats: props.restaurant.plats || [],
});

const HandleFileUpload = (event) => {
    form.profile_image = event.target.files[0];
};

const selectedProvinciaId = ref(props.restaurant.municipio.provincia_id);
const municipios = ref([]);
const isSubmitting = ref(false);

const fetchMunicipios = async () => {
    try {
        const response = await axios.get(route('get.municipios', {provincia_id: selectedProvinciaId.value}));
        municipios.value = response.data;
    } catch (error) {
        console.error("Error fetching municipios:", error);
    }
};

const submitAdminForm = () => {
    isSubmitting.value = true;

    form.transform(data => {
        let formData = new FormData();
        for (let key in data) {
            formData.append(key, data[key] === null ? '' : data[key]);
        }
        return formData;
    }).post(route('restaurants.update', { restaurant: props.restaurant.id }), {
        onSuccess: () => {
            isSubmitting.value = false;
            Inertia.visit(route('restaurants.show', { id: props.restaurant.id }));
        },
        onError: (errors) => {
            isSubmitting.value = false;
            console.error("Error updating restaurant:", errors);
        },
    });
};

onMounted(() => {
    fetchMunicipios();
});
</script>

<style scoped>
.edit-restaurant-page {
    background-color: #f8f9fa;
    min-height: 100vh;
    padding: 40px 20px;
}

.edit-container {
    max-width: 1000px;
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
    display: flex;
    justify-content: space-between;
    align-items: center;
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

.restaurant-preview {
    display: flex;
    align-items: center;
    justify-content: center;
}

.restaurant-image {
    width: 120px;
    height: 120px;
    border-radius: 12px;
    object-fit: cover;
    border: 3px solid white;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.edit-form {
    padding: 40px;
}

.form-sections {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin-bottom: 40px;
}

.form-section {
    background-color: #f9f9f9;
    border-radius: 12px;
    padding: 25px;
}

.section-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
}

.section-icon {
    margin-right: 10px;
    font-size: 1.5rem;
}

.form-group {
    margin-bottom: 20px;
}

.form-row {
    display: flex;
    gap: 20px;
}

.form-group.half {
    flex: 1;
}

.form-label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #555;
}

.form-input, .form-select, .form-textarea {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-textarea {
    min-height: 120px;
    resize: vertical;
}

.form-input:focus, .form-select:focus, .form-textarea:focus {
    border-color: #FF5A5F;
    box-shadow: 0 0 0 2px rgba(255, 90, 95, 0.2);
    outline: none;
}

.form-hint {
    font-size: 0.85rem;
    color: #777;
    margin-top: 5px;
}

.file-upload-container {
    position: relative;
    margin-bottom: 10px;
}

.file-input {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    z-index: 2;
}

.file-upload-button {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    background-color: #f1f1f1;
    border: 1px dashed #ccc;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.file-upload-button:hover {
    background-color: #e9e9e9;
}

.upload-icon {
    margin-right: 10px;
    font-size: 1.2rem;
}

.file-name {
    display: block;
    margin-top: 8px;
    font-size: 0.9rem;
    color: #555;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
}

.cancel-button, .submit-button {
    padding: 12px 25px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
}

.cancel-button {
    background-color: #f1f1f1;
    color: #555;
    border: 1px solid #ddd;
}

.cancel-button:hover {
    background-color: #e5e5e5;
}

.submit-button {
    background-color: #FF5A5F;
    color: white;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
}

.submit-button:hover:not(:disabled) {
    background-color: #e5484d;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(255, 90, 95, 0.3);
}

.submit-button:disabled {
    background-color: #ffb5b8;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
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

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        text-align: center;
    }

    .restaurant-preview {
        margin-top: 20px;
    }

    .form-sections {
        grid-template-columns: 1fr;
    }

    .form-row {
        flex-direction: column;
        gap: 20px;
    }

    .form-actions {
        flex-direction: column-reverse;
    }

    .cancel-button, .submit-button {
        width: 100%;
    }
}
</style>
