<script setup>
import { defineProps } from 'vue';
import Pagination from '@/Components/Pagination.vue';
import {
    PlusCircleIcon,
    MagnifyingGlassCircleIcon,
} from "@heroicons/vue/24/solid";

defineProps({
    headers: {
        type: Array,
        required: true,
    },
    items: {
        type: Object,
        required: true,
    },
    addUrl: {
        type: String,
        default: null,
    },
    canAction: {
        type: Boolean,
        default: true,
    },
    canSearch: {
        type: Boolean,
        default: true,
    },
    canPagination: {
        type: Boolean,
        default: true,
    },
    noDataShow: {
        type: Boolean,
        default: false,
    }
});
</script>

<template>
    <!-- 1. Header Kontrol Tabel -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
        <div class="w-full md:w-auto self-start">
            <slot name="header-info">
                <h2 class="card-title text-xl font-bold">Daftar Data</h2>
                <p class="text-sm text-base-content/70">Kelola data Anda.</p>
            </slot>
        </div>

        <div class="flex flex-col md:flex-row items-center gap-2 w-full md:w-auto">
            <slot name="header-actions">
                <a v-if="addUrl" :href="addUrl" class="btn btn-success text-white w-full md:w-auto">
                    <PlusCircleIcon class="icon" />
                    Tambah
                </a>
            </slot>
            <slot name="header-search">
                <label v-if="canSearch" class="input input-bordered flex items-center gap-2 w-full md:w-auto">
                    <input type="text" class="grow" placeholder="Cari..." />
                    <MagnifyingGlassCircleIcon class="icon" />
                </label>
            </slot>
        </div>
    </div>

    <!-- Tampilan Desktop (Tabel) -->
    <div class="hidden lg:block overflow-x-auto rounded-lg border border-base-200">
        <table class="table">
            <thead class="bg-base-200">
            <tr v-if="noDataShow">

            </tr>
            <tr v-else class="text-sm font-semibold text-base-content/80 uppercase">
                <th v-for="header in headers" :key="header.key" class="py-3 px-4" :class="header.class">
                    {{ header.label }}
                </th>
                <th v-if="canAction" class="text-center py-3 px-4">Aksi</th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="noDataShow">
                <slot :name="`col-no-data`" ></slot>
            </tr>
            <tr v-else v-for="(item, index) in items.data" :key="item.id" class="border-b border-base-200 hover:bg-base-100">
                <td v-for="header in headers" :key="header.key" class="px-4 py-3" :class="header.class">
                    <slot :name="`col-${header.key}`" :item="item" :index="index">
                        {{ item[header.key] || '-' }}
                    </slot>
                </td>
                <td v-if="canAction" class="text-center px-4 py-3">
                    <slot name="col-actions" :item="item"></slot>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <!-- Tampilan Mobile (Kartu) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:hidden">
        <div v-for="(item, index) in items.data" :key="item.id" class="card bg-base-100 shadow-md border border-base-200">
            <slot name="mobile-card" :item="item" :index="index"></slot>
        </div>
    </div>

    <!-- Footer Tabel (Paginasi) -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mt-6">
        <p class="text-sm text-base-content/70">
            Menampilkan <span class="font-bold">{{ items.from || 0 }}</span> - <span class="font-bold">{{ items.to || 0 }}</span> dari <span class="font-bold">{{ items.total || 0 }}</span> hasil
        </p>
        <Pagination v-if="canPagination" :links="items.links" />
    </div>
</template>

<style scoped>
.icon {
    @apply size-5;
}
</style>
