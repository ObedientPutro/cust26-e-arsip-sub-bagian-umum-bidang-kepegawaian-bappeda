<script setup>
import { computed } from "vue";
import {usePage} from "@inertiajs/vue3";

const user = computed(() => usePage().props.auth.user);

const todayDate = computed(() => {
    const today = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    return today.toLocaleDateString('id-ID', options);
});

const quotes = [
    "Keteraturan data adalah aset paling berharga dalam pengambilan keputusan yang cepat dan tepat.",
    "Arsip adalah memori institusi; sumber pengalaman untuk membangun masa depan yang lebih baik.",
    "Tujuan digitalisasi adalah untuk mengganti tumpukan kertas yang rumit dengan data yang mudah diakses.",
    "Sistem yang andal dapat menginspirasi ketenangan, menyalakan efisiensi, dan menanamkan budaya kerja yang teratur.",
    "Investasi pada sistem yang terorganisir selalu membayar dengan efisiensi terbaik."
];

const motivationalQuote = computed(() => {
    const randomIndex = Math.floor(Math.random() * quotes.length);
    return quotes[randomIndex];
});
</script>

<template>
    <div class="p-6 md:p-8 rounded-2xl bg-gradient-to-r from-blue-600 to-sky-600 text-white shadow-lg">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">Selamat Datang, <br/> {{ user.name }}!</h1>
                <p class="text-lg text-blue-100 mt-1 capitalize">{{ user.role_label }}</p>
            </div>
            <div class="mt-4 md:mt-0 text-left md:text-right text-lg font-semibold bg-white/20 px-4 py-2 rounded-lg">
                <p>{{ todayDate }}</p>
            </div>
        </div>
        <div class="mt-6 border-t border-white/30 pt-4">
            <p class="text-base text-blue-50 italic">"{{ motivationalQuote }}"</p>
        </div>
    </div>
</template>

<style scoped>

</style>
