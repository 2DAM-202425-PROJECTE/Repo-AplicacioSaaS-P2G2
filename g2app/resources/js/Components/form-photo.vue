
<template>
    <div class="form-photo">
        <img :src="link" :alt="altText" :style="{ width: size + 'px', height: size + 'px' }" class="photo" />
        <div v-if="editable" class="edit-button" @click="onEditClick">Edit</div>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    link: String,
    editable: Boolean,
    size: {
        type: Number,
        default: 80
    },
    altText: {
        type: String,
        default: 'Profile Photo'
    }
});

function onEditClick() {
    // Aquí pots afegir l'event per editar la foto, com obrir un selector de fitxers
    console.log('Edit photo clicked');
}
</script>

<style scoped>
.form-photo {
    position: relative;
    display: inline-block;
    border-radius: 50%;
    overflow: hidden;
}

.photo {
    object-fit: cover;
}

.edit-button {
    position: absolute;
    bottom: 0;
    right: 0;
    background-color: rgba(0, 0, 0, 0.6);
    color: white;
    padding: 4px 8px;
    font-size: 12px;
    border-radius: 50%;
    cursor: pointer;
}

.edit-button:hover {
    background-color: rgba(0, 0, 0, 0.8);
}
</style>
