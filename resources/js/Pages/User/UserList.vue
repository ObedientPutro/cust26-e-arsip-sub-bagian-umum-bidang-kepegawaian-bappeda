<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Modal from "@/Components/Modal.vue";
import DataTable from "@/Components/DataTable.vue";
import dayjs from 'dayjs';
import 'dayjs/locale/en';
import { defineProps, ref, watch } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import {
    TrashIcon,
    PencilSquareIcon,
    MagnifyingGlassIcon,
} from "@heroicons/vue/24/solid";

defineOptions({
    layout: AuthenticatedLayout
});

const props = defineProps({
    users: {
        required: true,
        type: Object
    },
    filters: {
        required: true,
        type: Object
    },
});

const deleteUser = ref(null);
const deleteForm = useForm({});
const showModal = ref(false);
const search = ref(props.filters.search);

watch(search, debounce((value) => {
    router.get(
        route('user.index'),
        { search: value },
        {
            preserveState: true,
            replace: true,
        }
    );
}, 300));

const confirmDelete = (user) => {
    deleteUser.value = user;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    deleteUser.value = null;
};

const performDelete = () => {
    if (deleteUser.value) {
        deleteForm.delete(route('user.delete', deleteUser.value.id), {
            onSuccess: () => {
                closeModal();
            },
        });
    }
};

const formatDate = (dateString) => {
    return dayjs(dateString).format('ddd, DD MMM YYYY');
};

const headers = [
    { key: 'no', label: 'No.', class: 'text-center' },
    { key: 'name', label: 'Nama', class: 'text-center' },
    { key: 'username', label: 'Akun Pengguna', class: 'text-center' },
    { key: 'email', label: 'Email', class: 'text-center' },
    { key: 'created_at', label: 'Tanggal Ditambahkan', class: 'text-center' },
];
</script>

<template>
    <Head title="Akun" />

    <div class="bg-base-100 overflow-hidden shadow-md rounded-lg p-6">
        <DataTable :headers="headers" :items="users" :addUrl="route('user.new')">
            <!-- Mendefinisikan Judul & Deskripsi Header -->
            <template #header-info>
                <h2 class="card-title text-xl font-bold">Daftar Akun Pengguna</h2>
                <p class="text-sm text-base-content/70">Kelola data seluruh akun pengguna.</p>
            </template>

            <!-- Implementasi slot untuk search bar -->
            <template #header-search>
                <label class="input input-bordered flex items-center gap-2 w-full md:w-auto">
                    <input v-model="search" type="text" class="grow" placeholder="Cari username, nama, role..." />
                    <MagnifyingGlassIcon class="icon" />
                </label>
            </template>

            <!-- Kustomisasi Tampilan Kolom No. -->
            <template #col-no="{ index }">
                {{ users.from + index }}
            </template>

            <!-- Kustomisasi Tampilan Kolom Username -->
            <template #col-username="{ item }">
                <div class="flex gap-3">
                    <div class="w-full">
                        <div class="">{{ item.username }}</div>
                        <div class="text-xs opacity-70 capitalize">{{ item.role_label }}</div>
                    </div>
                </div>
            </template>

            <!-- Kustomisasi Tampilan Kolom Tanggal Dibuat -->
            <template #col-created_at="{ item }">
                <div class="text-center capitalize">{{ formatDate(item.created_at) }}</div>
            </template>

            <!-- Mendefinisikan Tombol Aksi -->
            <template #col-actions="{ item }">
                <div class="flex justify-center gap-1">
                    <div class="tooltip" data-tip="Ubah">
                        <a :href="route('user.modify', item.id)" class="btn btn-warning btn-sm text-white">
                            <PencilSquareIcon class="icon"/>
                        </a>
                    </div>
                    <div class="tooltip" data-tip="Hapus">
                        <button @click="confirmDelete(item)" class="btn btn-error btn-sm text-white">
                            <TrashIcon class="icon"/>
                        </button></div>
                </div>
            </template>

            <!-- Mendefinisikan Tampilan Kartu Mobile -->
            <template #mobile-card="{ item }">
                <div class="card-body p-4">
                    <div class="flex justify-between items-start gap-4">
                        <div class="flex items-center gap-3">
                            <div>
                                <div class="font-bold capitalize">{{ item.name }}</div>
                                <div class="text-xs opacity-70 capitalize">{{ item.email }}</div>
                            </div>
                        </div>
                        <div class="flex gap-1">
                            <a :href="route('user.modify', item.id)" class="btn btn-square btn-ghost btn-sm">
                                <PencilSquareIcon class="icon text-warning"/>
                            </a>
                            <button @click="confirmDelete(item)" class="btn btn-square btn-ghost btn-sm">
                                <TrashIcon class="icon text-error"/>
                            </button>
                        </div>
                    </div>
                    <div class="divider my-2"></div>
                    <dl class="text-xs space-y-1">
                        <div class="flex justify-between"><dt class="opacity-70">Username:</dt><dd class="font-medium">{{ item.name }}</dd></div>
                        <div class="flex justify-between"><dt class="opacity-70">Role:</dt><dd class="font-medium capitalize">{{ item.role_label }}</dd></div>
                        <div class="flex justify-between"><dt class="opacity-70">Tanggal:</dt><dd>{{ formatDate(item.created_at) }}</dd></div>
                    </dl>
                </div>
            </template>
        </DataTable>
    </div>

    <!-- Delete Confirmation Modal -->
    <Modal :show="showModal" @close="closeModal">
        <div class="p-6">
            <h3 class="text-lg font-bold">Konfirmasi Penghapusan</h3>
            <p class="py-4">Yakin ingin menghapus <strong>{{ deleteUser?.name }}</strong>?</p>
            <div class="flex justify-end gap-3">
                <button class="btn" @click="closeModal">Batal</button>
                <button class="btn btn-error" @click="performDelete">Hapus</button>
            </div>
        </div>
    </Modal>
</template>

<style scoped>
.icon {
    @apply size-5;
}
</style>
