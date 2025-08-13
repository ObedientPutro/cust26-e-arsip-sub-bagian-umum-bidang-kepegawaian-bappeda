<script setup>
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
    method: {
        type: String,
        default: 'get',
    },
});

const classes = computed(() =>
    props.active
        ? 'sidebar-link-active'
        : 'sidebar-link-normal'
);
const handleClick = () => {
    if (props.method.toLowerCase() === 'post') {
        router.post(props.href);
    }
};
</script>

<template>
    <a v-if="method === 'post'" @click="handleClick" :class="`sidebar-link ${classes}`">
        <div class="text-center sidebar-link-icon">
            <slot name="icon" />
        </div>
        <div class="my-auto font-black">
            <slot name="menu" />
        </div>
    </a>
    <a v-else :href="href" :class="`sidebar-link ${classes}`">
        <div class="text-center sidebar-link-icon">
            <slot name="icon" />
        </div>
        <div class="my-auto font-black">
            <slot name="menu" />
        </div>
    </a>
</template>

<style scoped>
.sidebar-link {
    @apply flex cursor-pointer items-center space-x-3 p-4 border-transparent rounded-md;
}
.sidebar-link-normal {
    @apply text-black hover:bg-blue-500 hover:text-white transition-all ease-in-out duration-150;
}
.sidebar-link-active {
    @apply text-blue-500 hover:bg-blue-500 hover:text-white transition-all ease-in-out duration-150;
}
.sidebar-link-icon {
    @apply size-5;
}
</style>
