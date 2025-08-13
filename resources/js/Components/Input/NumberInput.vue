<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: [Number, null],
    required: false,
});

const input = ref(null);

/**
 * Fungsi ini akan dipanggil setiap kali pengguna mengetik di dalam input.
 * Tujuannya adalah untuk menghapus karakter apa pun yang bukan angka.
 */
const handleInput = (event) => {
    const rawValue = event.target.value;

    if (rawValue === '' || rawValue === null) {
        model.value = null;
        return;
    }

    const sanitizedValue = rawValue.replace(/[^0-9]/g, '');

    model.value = Number(sanitizedValue);
    event.target.value = sanitizedValue;
};

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        type="text"
        inputmode="numeric"
        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 sm:text-sm/6"
        :value="model"
        @input="handleInput"
        ref="input"
    />
</template>
