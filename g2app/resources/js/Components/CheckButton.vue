<template>
    <button
        type="button"
        @click="toggle"
        :class="{
           'hover:bg-green-500': modelValue,
          'hover:bg-lime-200': !modelValue,
          'bg-green-600 text-white': modelValue,
          'bg-gray-200 text-gray-00': !modelValue,
          'px-2 py-1 rounded': true,}">
        {{ label }}
    </button>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
    modelValue: Boolean,
    label: String
});

const emit = defineEmits(['update:modelValue']);

const toggle = () => {
    emit('update:modelValue', !props.modelValue);
};
</script>
