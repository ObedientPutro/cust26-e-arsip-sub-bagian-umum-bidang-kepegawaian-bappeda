<script setup>
import { ref, onMounted } from 'vue';
const emit = defineEmits(['remove']);

const props = defineProps({
    message: String,
    type: {
        type: String,
        default: 'success',
    },
    duration: {
        type: Number,
        default: 3000,
    }
});

const visible = ref(false);
const toastClass = ref(props.type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white');

onMounted(() => {
    visible.value = true;

    setTimeout(() => {
        visible.value = false;
    }, props.duration);
});

const onAfterLeave = () => {
    emit('remove');
};
</script>

<template>
    <transition name="slide-fade" @after-leave="onAfterLeave">
        <div v-if="visible" class="p-4 rounded-lg shadow-lg w-96 mb-4" :class="toastClass">
            {{ message }}
        </div>
    </transition>
</template>

<style scoped>
.slide-fade-enter-active,
.slide-fade-leave-active {
    transition: all 0.5s ease-out;
}

.slide-fade-enter-from {
    opacity: 0;
    transform: translateX(100%);
}

.slide-fade-enter-to {
    opacity: 1;
    transform: translateX(0);
}

.slide-fade-leave-from {
    opacity: 1;
    transform: translateX(0);
}

.slide-fade-leave-to {
    opacity: 0;
    transform: translateX(100%);
}
</style>
