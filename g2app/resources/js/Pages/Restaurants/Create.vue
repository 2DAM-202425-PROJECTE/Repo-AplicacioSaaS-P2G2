<template>
    <layout>
        <div class="create-restaurant-page">
            <div class="create-container">
                <div class="page-header">
                    <h1 class="page-title">Crear Nou Restaurant</h1>
                    <p class="page-subtitle">Omple el formulari per registrar el teu restaurant a la plataforma</p>
                </div>

                <form @submit.prevent="submitForm" class="create-form">
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
                                    placeholder="Introdueix el nom del restaurant"
                                    required
                                />
                            </div>

                            <div class="form-group">
                                <label for="descripcio" class="form-label">Descripció</label>
                                <textarea
                                    v-model="form.descripcio"
                                    id="descripcio"
                                    class="form-textarea"
                                    placeholder="Descriu el teu restaurant, especialitats, ambient..."
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
                                    placeholder="Ex: 93 123 45 67"
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
                                    <option value="" disabled selected>Selecciona un tipus de cuina</option>
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
                                    <option value="" disabled selected>Selecciona una província</option>
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
                                    <option value="" disabled selected>Selecciona un municipi</option>
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
                                    placeholder="Carrer, número, codi postal"
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
                                <label for="profile_image" class="form-label">Imatge Principal</label>
                                <div class="file-upload-container">
                                    <input
                                        id="profile_image"
                                        type="file"
                                        class="file-input"
                                        @change="handleFileUpload"
                                    />
                                    <div class="file-upload-button">
                                        <span class="upload-icon">📷</span>
                                        <span>Selecciona una imatge</span>
                                    </div>
                                    <span v-if="form.profile_image" class="file-name">{{ getFileName(form.profile_image) }}</span>
                                </div>
                                <p class="form-hint">Recomanem una imatge de bona qualitat que mostri l'ambient del teu restaurant</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="cancel-button" @click="goBack">Cancel·lar</button>
                        <button type="submit" class="submit-button" :disabled="isSubmitting">
                            <span v-if="isSubmitting" class="loading-spinner"></span>
                            <span v-else>Crear Restaurant</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Layouts/Layout.vue';
import { reactive, ref, onMounted } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { route } from 'ziggy-js';
import axios from 'axios';

export default {
    components: {
        Layout,
    },
    props: {
        tipusCuinaOptions: Array,
        provincias: Array,
        municipios: Array,
    },
    setup(props) {
        const form = reactive({
            nom: '',
            descripcio: '',
            telefon: '',
            tipus_cuina: '',
            municipio_id: '',
            carrer: '',
            hora_obertura: '',
            hora_tancament: '',
            profile_image: null,
        });

        const selectedProvinciaId = ref('');
        const municipios = ref([]);
        const isSubmitting = ref(false);

        onMounted(() => {
            if (props.provincias && props.provincias.length > 0) {
                selectedProvinciaId.value = props.provincias[0].id;
                fetchMunicipios();
            }
        });

        const fetchMunicipios = async () => {
            if (!selectedProvinciaId.value) return;

            try {
                const response = await axios.get(route('get.municipios', {provincia_id: selectedProvinciaId.value}));
                municipios.value = response.data;
            } catch (error) {
                console.error("Error fetching municipios:", error);
            }
        };

        const handleFileUpload = (event) => {
            form.profile_image = event.target.files[0];
        };

        const getFileName = (file) => {
            if (typeof file === 'string') return file;
            return file ? file.name : '';
        };

        const submitForm = () => {
            isSubmitting.value = true;

            // Crear un FormData para enviar archivos
            const formData = new FormData();
            for (const key in form) {
                if (form[key] !== null && form[key] !== undefined) {
                    formData.append(key, form[key]);
                }
            }

            Inertia.post(route('restaurants.store'), formData, {
                onSuccess: () => {
                    isSubmitting.value = false;
                    Inertia.visit(route('restaurants.index'));
                },
                onError: (errors) => {
                    isSubmitting.value = false;
                    console.error("Error creating restaurant:", errors);
                },
            });
        };

        const goBack = () => {
            window.history.back();
        };

        return {
            form,
            selectedProvinciaId,
            municipios,
            isSubmitting,
            fetchMunicipios,
            handleFileUpload,
            getFileName,
            submitForm,
            goBack,
        };
    },
    methods: {
        handleFileUpload(event) {
            this.form.profile_image = event.target.files[0];
        },
    },
};
</script>

<style scoped>
.create-restaurant-page {
    background-color: #f8f9fa;
    min-height: 100vh;
    padding: 40px 20px;
}

.create-container {
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
    text-align: center;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 10px;
}

.page-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    max-width: 600px;
    margin: 0 auto;
}

.create-form {
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
    to {
        transform: rotate(360deg);
    }
}

@media (max-width: 768px) {
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
