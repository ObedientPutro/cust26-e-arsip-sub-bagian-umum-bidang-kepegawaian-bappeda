<script setup>
import { ref, computed } from 'vue';
import {PhotoIcon} from "@heroicons/vue/24/solid/index.js";

const emit = defineEmits(['update:modelValue']);

const props = defineProps({
    modelValue: File,
    accept: {
        type: String,
        default: 'image/*',
    }
});

const fileInput = ref(null);
const filePreview = ref(props.preview || null);
const fileName = ref(null);

// Function to handle file selection
const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        fileName.value = file.name;
        filePreview.value = URL.createObjectURL(file);
        emit('update:modelValue', file);
    }
};

// Function to trigger file input click
const openFileDialog = () => {
    fileInput.value.click();
};

// Function to handle drag-and-drop
const handleDrop = (event) => {
    event.preventDefault();
    const file = event.dataTransfer.files[0];
    if (file) {
        fileName.value = file.name;
        filePreview.value = URL.createObjectURL(file);
        emit('update:modelValue', file);
    }
};

// Prevent default behavior for drag events
const preventDefault = (event) => {
    event.preventDefault();
};
</script>

<template>
    <div class="w-full">
        <div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10 relative cursor-pointer"
             @click="openFileDialog"
             @dragover="preventDefault"
             @dragenter="preventDefault"
             @drop="handleDrop">

            <div class="text-center">
                <PhotoIcon class="mx-auto size-12 text-gray-300" />
                <p class="mt-4 text-sm text-gray-600">Click to upload or drag and drop</p>
                <p class="text-xs text-gray-500">PNG up to 10MB</p>
            </div>
        </div>

        <input ref="fileInput" type="file" :accept="accept" class="hidden" @change="handleFileChange">
        <p v-if="fileName" class="mt-2 text-sm text-gray-500">Selected: {{ fileName }}</p>
    </div>
</template>

<style scoped>

</style>
