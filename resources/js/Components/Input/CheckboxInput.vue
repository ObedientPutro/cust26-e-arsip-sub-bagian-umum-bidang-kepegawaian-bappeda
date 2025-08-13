<script setup>
import { computed } from 'vue';

const emit = defineEmits(['update:checked']);

const props = defineProps({
    checked: {
        type: [Array, Boolean],
        required: true,
    },
    id: {
        type: [String, Number],
        required: true,
    },
    name: {
        type: String,
        required: true,
    },
    disabled: {
        type: Boolean,
        default: false,
    }
});

const proxyChecked = computed({
    get() {
        return props.checked;
    },

    set(val) {
        emit('update:checked', val);
    },
});
</script>

<template>
    <label class="cursor-pointer">
        <input
            type="checkbox"
            :value="id"
            v-model="proxyChecked"
            class="hidden"
            :disabled="disabled"
        />
        <div :class="[
                'px-4 py-2 rounded-md transition-all text-white font-black capitalize lg:hover:bg-blue-700',
                proxyChecked ? 'bg-blue-500' : 'bg-gray-400'
            ]">
            {{ name }}
        </div>
    </label>
</template>
