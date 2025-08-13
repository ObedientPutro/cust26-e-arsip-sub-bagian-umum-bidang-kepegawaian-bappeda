<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    letter: Object,
});
</script>

<template>
    <Head :title="`Pratinjau: ${letter.subject}`" />

    <div class="p-6">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h1 class="text-xl font-bold">{{ letter.subject }}</h1>
                <p class="text-sm text-base-content/70">Nomor: {{ letter.letter_number }}</p>
            </div>
        </div>

        <div class="bg-base-100 rounded-lg shadow-md overflow-hidden">
            <iframe
                :src="route('document.file', { letter: letter.id })"
                class="w-full h-screen"
                frameborder="0"
                title="Pratinjau PDF"
            >
                Browser Anda tidak mendukung pratinjau PDF.
<!--                <a :href="route('document.file', { letter: letter.id })" target="_blank">Unduh PDF</a>-->
            </iframe>
        </div>
    </div>
</template>

<style scoped>

</style>
