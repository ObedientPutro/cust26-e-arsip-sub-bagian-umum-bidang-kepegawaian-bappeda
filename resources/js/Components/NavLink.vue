<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
        default: false,
    },
    underline: {
        type: Boolean,
        default: true,
    }
});

const classes = computed(() => {
    let baseClasses = 'transition-colors duration-200';

    if (props.active) {
        baseClasses += ' text-green-600 font-semibold';
        if (props.underline) {
            baseClasses += ' nav-active';
        }
    } else {
        baseClasses += ' text-gray-500 font-medium hover:text-green-600';
    }

    return baseClasses;
});
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>

<style scoped>
.nav-active {
    position: relative;
}
.nav-active::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 3px;
    background-color: #16a34a; /* Kode warna untuk text-green-600 */
    bottom: -8px;
    left: 0;
    border-radius: 2px;
}
</style>
