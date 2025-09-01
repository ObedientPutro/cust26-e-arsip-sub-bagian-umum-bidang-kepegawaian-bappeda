<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from '@inertiajs/vue3';
import WelcomeBanner from "@/Components/Dashboard/WelcomeBanner.vue";
import SmallCard from "@/Components/Dashboard/SmallCard.vue";
import DataTable from "@/Components/DataTable.vue";
import DashboardChart from "@/Pages/Dashboard/Partial/DashboardChart.vue";
import {
    UsersIcon, InboxArrowDownIcon, PaperAirplaneIcon, DocumentDuplicateIcon, CheckCircleIcon
} from "@heroicons/vue/24/solid";

defineOptions({
    layout: AuthenticatedLayout
})

const props = defineProps({
    dashboardData: Object,
    categories: Array,
    filters: Object,
});

const unreadHeaders = [
    { key: 'from', label: 'Dari Surat' },
    { key: 'instruction', label: 'Instruksi' },
];
const readHeaders = [
    { key: 'from', label: 'Dari Surat' },
    { key: 'instruction', label: 'Instruksi' },
];
</script>

<template>
    <Head title="Dashboard Admin" />

    <WelcomeBanner />

    <div v-if="$page.props.auth.user.role === 'admin'" class="mt-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <SmallCard title="Jumlah Pengguna" :value="dashboardData.stats.users" color="blue">
                <template #icon><UsersIcon /></template>
            </SmallCard>
            <SmallCard title="Jumlah Klasifikasi" :value="dashboardData.stats.categories" color="purple">
                <template #icon><DocumentDuplicateIcon /></template>
            </SmallCard>
            <SmallCard title="Surat Masuk" :value="dashboardData.stats.incoming_letters" color="green">
                <template #icon><InboxArrowDownIcon /></template>
            </SmallCard>
            <SmallCard title="Surat Keluar" :value="dashboardData.stats.outgoing_letters" color="yellow">
                <template #icon><PaperAirplaneIcon /></template>
            </SmallCard>
        </div>
    </div>

    <div v-if="$page.props.auth.user.role === 'pimpinan' || $page.props.auth.user.role === 'admin'" class="mt-8">
        <DashboardChart
            :chartData="dashboardData.chart"
            :categories="categories"
            :filters="filters"
        />
    </div>

    <div v-if="$page.props.auth.user.role === 'pegawai'" class="mt-8 space-y-8">
        <div class="bg-base-100 overflow-hidden shadow-md rounded-lg p-6">
            <DataTable :headers="unreadHeaders" :items="{ data: dashboardData.unread_dispositions }" :can-search="false" :can-pagination="false">
                <template #header-info>
                    <h2 class="card-title text-xl font-bold">Disposisi Belum Dibaca</h2>
                </template>
                <template #col-from="{ item }">
                    <div>
                        <div class="font-bold">{{ item.letter.subject }}</div>
                        <div class="text-xs opacity-70">Dari: {{ item.letter.sender }}</div>
                    </div>
                </template>
                <template #col-instruction="{ item }">{{ item.instruction }}</template>
                <template #col-actions="{ item }">
                    <div class="tooltip" data-tip="Tandai sudah dibaca">
                        <Link :href="route('dashboard.markAsRead', item.id)" method="patch" as="button" class="btn btn-success btn-sm text-white">
                            <CheckCircleIcon class="size-5" />
                        </Link>
                    </div>
                </template>
            </DataTable>
        </div>

        <div class="bg-base-100 overflow-hidden shadow-md rounded-lg p-6">
            <DataTable :headers="readHeaders" :items="{ data: dashboardData.read_dispositions }" :can-action="false" :can-pagination="false">
                <template #header-info>
                    <h2 class="card-title text-xl font-bold">Riwayat Disposisi Dibaca</h2>
                </template>
                <template #col-from="{ item }">
                    <div>
                        <div class="font-bold">{{ item.letter.subject }}</div>
                        <div class="text-xs opacity-70">Dari: {{ item.letter.sender }}</div>
                    </div>
                </template>
                <template #col-instruction="{ item }">{{ item.instruction }}</template>
            </DataTable>
        </div>
    </div>
</template>

<style scoped>

</style>
