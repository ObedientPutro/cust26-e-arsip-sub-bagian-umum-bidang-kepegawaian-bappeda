<script setup>
import AOS from "aos";
import "aos/dist/aos.css";
import { onMounted } from "vue";

onMounted(() => {
    AOS.init({ duration: 1000, once: true });
});

</script>

<template>
    <div class="bg-slate-50 font-sans text-gray-900 antialiased">
        <main>
            <slot />
        </main>
    </div>
</template>
