<script setup>
import { computed } from "vue";

const props = defineProps({
    title: {
        required: true,
        type: String,
    },
    value: {
        required: true,
        type: String,
    },
    color: {
        default: "blue",
        type: String,
    }
});

const backgroundColorClass = computed(() => {
    switch (props.color) {
        case "blue":
            return "bg-blue-100";
        case "green":
            return "bg-green-100";
        case "yellow":
            return "bg-yellow-100";
        case "purple":
            return "bg-purple-100";
    }
});

const textColorClass = computed(() => {
    switch (props.color) {
        case "blue":
            return "text-blue-600";
        case "green":
            return "text-green-600";
        case "yellow":
            return "text-yellow-600";
        case "purple":
            return "text-purple-600";
    }
});
</script>

<template>
    <div class="card bg-base-100 shadow-md">
        <div class="card-body flex-row items-center">
            <div :class="`p-2 ${backgroundColorClass} rounded-full`">
                <div :class="`icon ${textColorClass}`">
                    <slot name="icon"/>
                </div>
            </div>
            <div class="ml-4">
                <h2 class="card-title text-gray-500">{{ title }}</h2>
                <p class="text-2xl font-bold text-gray-800">{{ value }}</p>
            </div>
        </div>
    </div>
</template>

<style scoped>
.icon {
    @apply size-8;
}
</style>
