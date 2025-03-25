<template>
    <div class="text-left space-y-4">
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
                {{ isEmpresa() ? 'Empresa' : 'Usuari' }}
            </span>
        </div>
        -->

        <!-- Modificació de contrasenya -->
        <div class="border-b pb-4">
            <h2 class="text-xl font-semibold text-gray-700">Seguretat</h2>
            <div class="mt-4">
                <div class="flex justify-between items-center">
                    <p><strong>Contrasenya:</strong></p>
                    <p class="text-gray-800">**********</p>
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
                        <input v-model="currentPassword" type="password" class="border p-2 rounded w-full">
                    </div>
                    <div>
                        <label class="block text-gray-700">Nova Contrasenya</label>
                        <input v-model="newPassword" type="password" class="border p-2 rounded w-full">
                    </div>
                    <div>
                        <label class="block text-gray-700">Confirmar Contrasenya</label>
                        <input v-model="confirmPassword" type="password" class="border p-2 rounded w-full">
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

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});
const isEmpresa = () => {
    return props.user?.role === 'empresa';
};
// Control d'edició
const editingName = ref(false);
const editingEmail = ref(false);
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
