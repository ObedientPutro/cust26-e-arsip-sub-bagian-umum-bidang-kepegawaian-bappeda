<script setup>
import { onMounted, ref } from 'vue';

// Menggunakan defineModel untuk two-way data binding (v-model)
const model = defineModel({
    type: String, // Tanggal akan di-handle sebagai string (YYYY-MM-DD)
    required: true,
});

const input = ref(null);

// Fungsi untuk autofocus jika ada atributnya
onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

// Mengekspos fungsi focus agar bisa dipanggil dari parent component
defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        type="date"
        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-green-600 sm:text-sm/6"
        v-model="model"
        ref="input"
    />
</template>

<style scoped>

</style>
