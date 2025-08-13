<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Modal from "@/Components/Modal.vue";
import DataTable from "@/Components/DataTable.vue";
import dayjs from 'dayjs';
import 'dayjs/locale/en';
import { defineProps, ref, watch } from 'vue';
import { Head, router, useForm, Link } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import {
    TrashIcon,
    PencilSquareIcon,
    MagnifyingGlassIcon,
    EyeIcon,
    UserPlusIcon,
} from "@heroicons/vue/24/solid";

defineOptions({
    layout: AuthenticatedLayout
});

const props = defineProps({
    letters: Object,
    filters: Object,
});

const deleteLetter = ref(null);
const deleteForm = useForm({});
const showModal = ref(false);
const search = ref(props.filters.search);

watch(search, debounce((value) => {
    router.get(
        route('incomingLetter.index'),
        { search: value },
        {
            preserveState: true,
            replace: true,
        }
    );
}, 300));

const confirmDelete = (letter) => {
    deleteLetter.value = letter;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    deleteLetter.value = null;
};

const performDelete = () => {
    if (deleteLetter.value) {
        deleteForm.delete(route('incomingLetter.delete', deleteLetter.value.id), {
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
    { key: 'letter_number', label: 'Nomor Surat' },
    { key: 'subject', label: 'Subjek & Pengirim', class: 'text-center' },
    { key: 'disposition_status', label: 'Status Disposisi', class: 'text-center' },
    { key: 'letter_date', label: 'Tanggal', class: 'text-center' },
];
</script>

<template>
    <Head title="Pegawai" />

    <div class="bg-base-100 overflow-hidden shadow-md rounded-lg p-6">
        <DataTable :headers="headers" :items="letters" :addUrl="route('incomingLetter.new')">
            <!-- Mendefinisikan Judul & Deskripsi Header -->
            <template #header-info>
                <h2 class="card-title text-xl font-bold">Surat Masuk</h2>
                <p class="text-sm text-base-content/70">Kelola data seluruh surat masuk.</p>
            </template>

            <!-- Implementasi slot untuk search bar -->
            <template #header-search>
                <label class="input input-bordered flex items-center gap-2 w-full md:w-auto">
                    <input v-model="search" type="text" class="grow" placeholder="Cari nomor surat, subject, pengguna..." />
                    <MagnifyingGlassIcon class="icon" />
                </label>
            </template>

            <!-- Kustomisasi Tampilan Kolom No. -->
            <template #col-no="{ index }">
                {{ letters.from + index }}
            </template>

            <!-- Kustomisasi Tampilan Kolom Nomor Surat -->
            <template #col-letter_number="{ item }">
                <div class="flex items-center gap-3">
                    <div>
                        <a
                            :href="route('document.file', item.id)"
                            target="_blank"
                            data-inertia-ignore
                            rel="noopener noreferrer"
                            class="font-bold text-primary capitalize hover:underline"
                            title="Buka Pratinjau di Tab Baru"
                        >
                            {{ item.letter_number }}
                        </a>
                        <div class="text-xs capitalize">{{ item.category.name }}, {{ item.category.classification_code }}</div>
                        <div class="text-xs opacity-70 capitalize">Ditambahkan Oleh, {{ item.user.name }}</div>
                    </div>
                </div>
            </template>

            <!-- Kustomisasi Tampilan Kolom Subjek dan Pengirim -->
            <template #col-subject="{ item }">
                <div class="flex items-center gap-3">
                    <div class="text-start">
                        <div class="font-bold capitalize">{{ item.subject }}</div>
                        <div class="text-xs opacity-70 capitalize">Dikirim oleh, {{ item.sender }}</div>
                    </div>
                </div>
            </template>

            <!-- Kustomisasi Tampilan Kolom Status -->
            <template #col-disposition_status="{ item }">
                <div v-if="item.is_disposed" class="badge badge-success badge-outline">Ditugaskan</div>
                <div v-else class="badge badge-error badge-outline">Belum Ada</div>
            </template>

            <!-- Kustomisasi Tampilan Kolom Tanggal Dibuat -->
            <template #col-letter_date="{ item }">
                <div class="text-center capitalize">{{ formatDate(item.letter_date) }}</div>
            </template>

            <!-- Mendefinisikan Tombol Aksi -->
            <template #col-actions="{ item }">
                <div class="flex justify-center gap-1">
                    <div class="tooltip" data-tip="Lihat">
                        <a :href="route('incomingLetter.view', item.id)" class="btn btn-info btn-sm text-white">
                            <EyeIcon class="icon"/>
                        </a>
                    </div>
                    <div v-if="$page.props.auth.user.role_label !== 'pegawai'" class="tooltip" data-tip="Buat Disposisi">
                        <a :href="route('dispositionIncomingLetter.new', item.id)" class="btn btn-primary btn-sm text-white">
                            <UserPlusIcon class="icon"/>
                        </a>
                    </div>
                    <div v-if="$page.props.auth.user.username === item.user.username || $page.props.auth.user.role_label === 'admin'" class="tooltip" data-tip="Ubah">
                        <a :href="route('incomingLetter.modify', item.id)" class="btn btn-warning btn-sm text-white">
                            <PencilSquareIcon class="icon"/>
                        </a>
                    </div>
                    <div v-else class="tooltip" data-tip="Ubah">
                        <a class="btn btn-warning btn-sm text-white" disabled="">
                            <PencilSquareIcon class="icon"/>
                        </a>
                    </div>
                    <div v-if="$page.props.auth.user.username === item.user.username || $page.props.auth.user.role_label === 'admin'" class="tooltip" data-tip="Hapus">
                        <button @click="confirmDelete(item)" class="btn btn-error btn-sm text-white">
                            <TrashIcon class="icon"/>
                        </button>
                    </div>
                    <div v-else class="tooltip" data-tip="Hapus">
                        <button class="btn btn-error btn-sm text-white" disabled>
                            <TrashIcon class="icon"/>
                        </button>
                    </div>
                </div>
            </template>

            <!-- Mendefinisikan Tampilan Kartu Mobile -->
            <template #mobile-card="{ item }">
                <div class="card-body p-4">
                    <div class="flex justify-between items-start gap-4">
                        <div class="flex items-center gap-3">
                            <div>
                                <div class="font-bold capitalize">{{ item.letter_number }}</div>
                                <div class="text-xs opacity-70 capitalize">{{ item.category.name }}, {{ item.category.classification_code }}</div>
                            </div>
                        </div>
                        <div class="flex gap-1">
                            <a :href="route('incomingLetter.modify', item.id)" class="btn btn-square btn-ghost btn-sm">
                                <PencilSquareIcon class="icon text-warning"/>
                            </a>
                            <button @click="confirmDelete(item)" class="btn btn-square btn-ghost btn-sm">
                                <TrashIcon class="icon text-error"/>
                            </button>
                        </div>
                    </div>
                    <div class="divider my-2"></div>
                    <dl class="text-xs space-y-1">
                        <div class="flex justify-between"><dt class="opacity-70">Subjek:</dt><dd class="font-medium">{{ item.subject }}</dd></div>
                        <div class="flex justify-between"><dt class="opacity-70">Pengirim:</dt><dd class="font-medium">{{ item.sender }}</dd></div>
                        <div class="flex justify-between items-center">
                            <dt class="opacity-70">Status:</dt>
                            <dd>
                                <div class="badge badge-xs mr-1" :class="item.is_disposed === true ? 'badge-success' : 'badge-error'"></div>
                                {{ item.is_disposed === true ? "Belum Ada" : "Ditugaskan" }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </template>
        </DataTable>
    </div>

    <!-- Delete Confirmation Modal -->
    <Modal :show="showModal" @close="closeModal">
        <div class="p-6">
            <h3 class="text-lg font-bold">Konfirmasi Penghapusan</h3>
            <p class="py-4">Yakin ingin menghapus <strong>{{ deleteLetter?.letter_number }}</strong>?</p>
            <div class="flex justify-end gap-3">
                <button class="btn" @click="closeModal">Batal</button>
                <button class="btn btn-error text-white" @click="performDelete">Hapus</button>
            </div>
        </div>
    </Modal>
</template>

<style scoped>
.icon {
    @apply size-5;
}
</style>
