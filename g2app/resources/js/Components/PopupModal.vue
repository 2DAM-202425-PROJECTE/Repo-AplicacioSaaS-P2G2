<template>
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg text-center w-96">
            <!-- Slot per al títol -->
            <div v-if="$slots.header" class="text-lg font-semibold">
                <slot name="header"></slot>
            </div>

            <!-- Slot per al cos -->
            <div class="mt-2">
                <slot name="body"></slot>
            </div>

            <!-- Slot per als botons -->
            <div class="mt-4 space-x-2">
                <slot name="footer">
                    <PrimaryButton @click="confirmCreation" class="w-full sm:w-auto">
                        Sí, crear
                    </PrimaryButton>
                    <SecondaryButton @click="$emit('close')" class="w-full sm:w-auto">
                        Cancel·lar
                    </SecondaryButton>
                </slot>
            </div>
        </div>
    </div>
</template>

<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const emit = defineEmits(['close', 'confirm']);

const confirmCreation = () => {
    emit('confirm');
};
</script>

<style scoped>
.w-full {
    width: 100%;
}
</style>
