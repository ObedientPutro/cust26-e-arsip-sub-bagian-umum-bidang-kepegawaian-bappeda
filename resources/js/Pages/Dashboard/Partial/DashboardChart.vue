<script setup>
import { useForm } from '@inertiajs/vue3';
import VueApexCharts from "vue3-apexcharts";
import { watch } from 'vue';
import debounce from 'lodash/debounce';

const props = defineProps({
    chartData: Object,
    categories: Array,
    filters: Object,
});

const filterForm = useForm({
    start_date: props.filters.start_date || '',
    end_date: props.filters.end_date || '',
    category_id: props.filters.category_id || '',
});

watch(
    () => [filterForm.start_date, filterForm.end_date, filterForm.category_id],
    debounce(() => {
        filterForm.get(route('dashboard'), {
            preserveState: true,
            preserveScroll: true,
            only: ['dashboardData', 'filters', 'categories'],
        });
    }, 500),
    {
        deep: true,
    }
);

const chartOptions = {
    chart: { type: 'area', toolbar: { show: false } },
    xaxis: { categories: props.chartData?.labels || [] },
    dataLabels: { enabled: false },
    stroke: { curve: 'smooth' },
};
</script>

<template>
    <div class="card bg-base-100 shadow-md">
        <div class="card-body">
            <h2 class="card-title">Tren Surat Bulan Ini</h2>

            <form @submit.prevent="applyFilters" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end mt-4 p-4 border rounded-lg">
                <div class="form-control">
                    <label class="label"><span class="label-text">Tanggal Awal</span></label>
                    <input v-model="filterForm.start_date" type="date" class="input input-bordered" />
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Tanggal Akhir</span></label>
                    <input v-model="filterForm.end_date" type="date" class="input input-bordered" />
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Kategori Surat Keluar</span></label>
                    <select v-model="filterForm.category_id" class="select select-bordered">
                        <option value="">Semua Kategori</option>
                        <option v-for="category in categories" :key="category.id" :value="category.id">
                            {{ category.name }}
                        </option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" :disabled="filterForm.processing">Terapkan</button>
            </form>

            <VueApexCharts type="area" :options="chartOptions" :series="chartData.series" />
        </div>
    </div>
</template>

<style scoped>

</style>
