<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from '@inertiajs/vue3';
import {
    AcademicCapIcon,
    UserIcon,
    UserGroupIcon,
} from "@heroicons/vue/24/outline";
import WelcomeBanner from "@/Components/Dashboard/WelcomeBanner.vue";
import SmallCard from "@/Components/Dashboard/SmallCard.vue";

defineOptions({
    layout: AuthenticatedLayout
})

// const props = defineProps({
//     data: {
//         required: true,
//         type: Object,
//     }
// });
</script>

<template>
    <Head title="Dashboard Admin" />

    <WelcomeBanner />

    <!-- Kartu Statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
        <!-- Kartu Jumlah Guru -->
<!--        <SmallCard title="Jumlah Guru" :value="data.teachers">-->
<!--            <template #icon>-->
<!--                <UserIcon />-->
<!--            </template>-->
<!--        </SmallCard>-->

<!--        &lt;!&ndash; Kartu Jumlah Siswa &ndash;&gt;-->
<!--        <SmallCard title="Jumlah Siswa" :value="data.students">-->
<!--            <template #icon>-->
<!--                <UserIcon />-->
<!--            </template>-->
<!--        </SmallCard>-->

<!--        &lt;!&ndash; Kartu Jumlah Kelas &ndash;&gt;-->
<!--        <SmallCard title="Jumlah Kelas" :value="data.classrooms">-->
<!--            <template #icon>-->
<!--                <UserIcon />-->
<!--            </template>-->
<!--        </SmallCard>-->
    </div>

</template>

<style scoped>

</style>
