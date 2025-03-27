<template>
    <div class="text-left space-y-4">
        <p><strong>Nom:</strong> {{ user.name }} <PrimaryButton @click="editName" class="ml-2 text-xs">Edita</PrimaryButton></p>
        <p><strong>Email:</strong> {{ user.email }} <PrimaryButton @click="editEmail" class="ml-2 text-xs">Edita</PrimaryButton></p>
        <!-- Mostrar Tipus de compte -->
        <div class="flex items-center space-x-2">
            <p><strong>Tipus de compte:</strong></p>
            <span :class="isEmpresa() ? 'text-green-500' : 'text-blue-500'" class="font-semibold">
                {{ isEmpresa() ? 'Empresa' : 'Usuari' }}
            </span>
        </div>

        <!-- Modificació de contrasenya -->
        <div class="border-b pb-4">
            <h2 class="text-xl font-semibold text-gray-700">Seguretat</h2>
            <div class="mt-4">
                <div class="flex justify-between items-center">
                    <p><strong>Contrasenya:</strong></p>
                    <p class="text-gray-800">**********</p>
                    <PrimaryButton @click="editPassword" class="bg-transparent text-blue-500 hover:bg-blue-50">Modificar</PrimaryButton>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { defineProps } from 'vue';

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

function isEmpresa(){
    return props.user.empresa === 1;
}

const editName = () => {
    // Logica per editar el nom
    console.log("Editant nom...");
};

const editEmail = () => {
    // Logica per editar el correu electrònic
    console.log("Editant correu electrònic...");
};

function editPassword() {
    // Aquí es pot afegir la lògica per modificar la contrasenya
    console.log("Editant Contrassenya...");

}
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
