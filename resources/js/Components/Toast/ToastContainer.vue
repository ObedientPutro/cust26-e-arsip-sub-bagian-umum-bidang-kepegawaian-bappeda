<script setup>
import { ref, watchEffect } from 'vue';
import Toast from './Toast.vue';

const props = defineProps({
    flash: Object
});

const toasts = ref([]);

const addToast = (message, type) => {
    const toast = { id: Date.now(), message, type };
    toasts.value.push(toast);
};

const removeToast = (id) => {
    toasts.value = toasts.value.filter(t => t.id !== id);
};

watchEffect(() => {
    if (props.flash.success) {
        addToast(props.flash.success, 'success');
        props.flash.success = null;
    }
    if (props.flash.error) {
        addToast(props.flash.error, 'error');
        props.flash.error = null;
    }
});
</script>

<template>
    <div class="fixed top-4 right-4 z-50 flex flex-col gap-2">
        <Toast
            v-for="toast in toasts"
            :key="toast.id"
            :message="toast.message"
            :type="toast.type"
            @remove="removeToast(toast.id)"
        />
    </div>
</template>
