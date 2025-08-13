<script setup>
import { ref, computed } from 'vue';

// Props for full flexibility
const props = defineProps({
    items: {
        type: Object,
        required: true,
        validator: (items) => items.every(item => 'id' in item && ('name' in item || 'title' in item)),
    },
    selectedId: {
        type: Number,
        default: null,
    },
    placeholder: {
        type: String,
        default: 'Select an option',
    },
});

// Define model binding
const model = defineModel({
    type: [String, Number],
    required: false,
});

// Compute selected value based on `selectedId` in edit mode
const selected = computed({
    get: () => model.value ?? props.selectedId, // Use model value or fallback to selectedId
    set: (value) => model.value = value,
});

const select = ref(null);

defineExpose({
    focus: () => select.value.focus(),
});
</script>

<template>
    <select
        ref="select"
        v-model="selected"
        class="select select-bordered w-full md:w-auto rounded-md bg-white text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 sm:text-sm/6"
    >
        <option value="" disabled>{{ placeholder }}</option>
        <option
            v-for="item in items"
            :key="item.id"
            :value="item.id"
        >
            {{ item.name || item.title }}
        </option>
    </select>
</template>

<style scoped>

</style>
