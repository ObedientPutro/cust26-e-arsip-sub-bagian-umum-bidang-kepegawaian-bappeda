<script setup>
import { onMounted, onUnmounted, watch } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    closeable: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(['close']);

watch(
    () => props.show,
    (newVal) => {
        document.body.style.overflow = newVal ? 'hidden' : null;
    }
);

const close = () => {
    if (props.closeable) {
        emit('close');
    }
};

const closeOnEscape = (e) => {
    if (e.key === 'Escape' && props.show) {
        close();
    }
};

onMounted(() => {
    document.addEventListener('keydown', closeOnEscape)
    if (props.show) document.body.style.overflow = 'hidden';
});

onUnmounted(() => {
    document.removeEventListener('keydown', closeOnEscape);
});
</script>

<template>
    <Teleport to="body">

        <!-- Overlay with fade transition -->
        <Transition
            enter-active-class="transition ease-out duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="show"
                class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm z-50"
                @click="close"
            ></div>
        </Transition>


        <!-- Modal with pop animation -->
        <Transition
            enter-active-class="transition ease-out duration-300 transform"
            enter-from-class="translate-y-[100%] opacity-0"
            enter-to-class="translate-y-0 opacity-100"
            leave-active-class="transition ease-in duration-200 transform"
            leave-from-class="translate-y-0 opacity-100"
            leave-to-class="translate-y-[100%] opacity-0"
        >
            <dialog v-if="show" open class="modal modal-bottom sm:modal-middle fixed" @click.self="close">
                <div class="modal-box">
                    <!-- Close button (top-right corner with icon) -->
                    <button class="btn btn-sm btn-ghost btn-circle absolute right-2 top-2" @click="close">✕</button>

                    <!-- Content Slot -->
                    <slot />
                </div>
            </dialog>
        </Transition>

    </Teleport>
</template>
