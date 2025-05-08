<template>
    <div class="text-left space-y-4">
        <!-- Eliminar compte -->
        <div class="mt-4">
            <Link :href="route('users.destroy', { id: user.id })" class="bg-red-500 text-white px-4 py-2 rounded">
                ELIMINAR L'USUARI
            </Link>
        </div>

        <!-- Editar Nom -->
        <div>
            <p v-if="!editingName"><strong>Nom:</strong> {{ user.name }}
                <PrimaryButton @click="editingName = true" class="ml-2 text-xs">Edita</PrimaryButton>
            </p>
            <div v-else>
                <input v-model="newName" type="text" class="border p-1 rounded">
                <PrimaryButton @click="saveName" class="ml-2 text-xs">Guardar</PrimaryButton>
                <SecondaryButton @click="editingName = false" class="ml-2 text-xs">Cancel·la</SecondaryButton>
            </div>
        </div>

        <!-- Editar Correu -->
        <div>
            <p v-if="!editingEmail"><strong>Email:</strong> {{ user.email }}
                <PrimaryButton @click="editingEmail = true" class="ml-2 text-xs">Edita</PrimaryButton>
            </p>
            <div v-else>
                <input v-model="newEmail" type="email" class="border p-1 rounded">
                <PrimaryButton @click="saveEmail" class="ml-2 text-xs">Guardar</PrimaryButton>
                <SecondaryButton @click="editingEmail = false" class="ml-2 text-xs">Cancel·la</SecondaryButton>
            </div>
        </div>
        <!-- MOSTRAR Tipos de conte
        <div>
            <p><strong>Tipus de compte:</strong></p>
            <span :class="isEmpresa() ? 'text-green-500' : 'text-blue-500'" class="font-semibold">
                {{ isEmpresa() ? 'Empresa' : 'User' }}
            </span>
        </div>
        -->

        <!-- Modificació de contrasenya -->
        <div class="border-b pb-4">
            <h2 class="text-xl font-semibold text-gray-700">Seguretat</h2>
            <div class="mt-4">
                <div class="flex justify-between items-center">
                    <p><strong>Contrasenya:</strong></p>
                    <p class="text-gray-800">
                        <span v-if="showPassword" class="font-semibold text-red-500">No es pot mostrar per seguretat</span>
                        <span v-else class="font-semibold text-gray-800">* * * * * * * * * * * * * * * *</span>
                    </p>
                    <button
                        @click="showPassword = !showPassword"
                        class="ml-2 px-3 py-1 text-xs font-semibold rounded-full transition-all duration-300"
                        :class="showPassword ? 'bg-red-500 text-white hover:bg-red-600' : 'bg-green-500 text-white hover:bg-green-600'"
                    >
                        {{ showPassword ? 'Ocultar ' : ' Mostrar' }}
                    </button>

                    <PrimaryButton @click="showPasswordModal = true" class="ml-2 text-xs">Modificar</PrimaryButton>
                </div>
            </div>
        </div>

        <!-- Popup per modificar la contrasenya -->
        <PopupModal v-if="showPasswordModal" @close="showPasswordModal = false" @confirm="updatePassword">
            <template #header>Canvia la contrasenya</template>
            <template #body>
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-700">Contrasenya Actual</label>
                        <div class="relative">
                            <input v-model="currentPassword" :type="showCurrentPassword ? 'text' : 'password'" class="border p-2 rounded w-full">
                            <button @click="showCurrentPassword = !showCurrentPassword" class="absolute right-2 top-2 text-xs text-blue-500">
                                {{ showCurrentPassword ? 'Ocultar' : 'Mostrar' }}
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-gray-700">Nova Contrasenya</label>
                        <div class="relative">
                            <input v-model="newPassword" :type="showNewPassword ? 'text' : 'password'" class="border p-2 rounded w-full">
                            <button @click="showNewPassword = !showNewPassword" class="absolute right-2 top-2 text-xs text-blue-500">
                                {{ showNewPassword ? 'Ocultar' : 'Mostrar' }}
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-gray-700">Confirmar Contrasenya</label>
                        <div class="relative">
                            <input v-model="confirmPassword" :type="showConfirmPassword ? 'text' : 'password'" class="border p-2 rounded w-full">
                            <button @click="showConfirmPassword = !showConfirmPassword" class="absolute right-2 top-2 text-xs text-blue-500">
                                {{ showConfirmPassword ? 'Ocultar' : 'Mostrar' }}
                            </button>
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <PrimaryButton @click="updatePassword">Guardar</PrimaryButton>
                <SecondaryButton @click="showPasswordModal = false">Cancel·la</SecondaryButton>
            </template>
        </PopupModal>
    </div>
</template>

<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PopupModal from '@/Components/PopupModal.vue';
import { ref, defineProps } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import {route} from "ziggy-js";

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
    business: {
        type: Object,
        required: false, // Pot ser `null` si l'usuari no té negoci
    },
});
const isEmpresa = () => {
    return props.user?.role === 'empresa';
};
// Control d'edició
const editingName = ref(false);
const editingEmail = ref(false);
const showPassword = ref(false);
const showCurrentPassword = ref(false);
const showNewPassword = ref(false);
const showConfirmPassword = ref(false);
const showPasswordModal = ref(false);
const newName = ref(props.user.name);
const newEmail = ref(props.user.email);
const currentPassword = ref('');
const newPassword = ref('');
const confirmPassword = ref('');

// Funció per desar el nom
const saveName = () => {
    Inertia.post('/user/update', { name: newName.value }, { preserveScroll: true });
    editingName.value = false;
};

// Funció per desar el correu electrònic
const saveEmail = () => {
    Inertia.post('/user/update', { email: newEmail.value }, { preserveScroll: true });
    editingEmail.value = false;
};

// Funció per actualitzar la contrasenya
const updatePassword = () => {
    Inertia.post('/user/update-password', {
        current_password: currentPassword.value,
        password: newPassword.value,
        password_confirmation: confirmPassword.value,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            showPasswordModal.value = false;
            currentPassword.value = '';
            newPassword.value = '';
            confirmPassword.value = '';
        },
    });
};

</script>


<style scoped>
.text-left {
    text-align: left;
}

.space-y-4 {
    margin-bottom: 1rem;
}

.ml-2 {
    margin-left: 0.5rem;
}

.text-xs {
    font-size: 0.75rem;
}
</style>
