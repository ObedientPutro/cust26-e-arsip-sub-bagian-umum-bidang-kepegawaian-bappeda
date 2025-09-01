<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import dayjs from "dayjs";
import {
    PencilSquareIcon,
    TrashIcon,
    DocumentTextIcon,
    ArrowLeftIcon,
} from "@heroicons/vue/24/solid";

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
    letter: Object,
});

const formatDate = (date) => dayjs(date).format('DD MMMM YYYY, HH:mm');
</script>

<template>
    <Head :title="`Detail Surat: ${letter.letter_number}`" />

    <div class="space-y-6">
        <Link :href="route('incomingLetter.index')" class="btn btn-ghost">
            <ArrowLeftIcon class="size-5" />
            Kembali ke Daftar Surat
        </Link>

        <div class="card bg-base-100 shadow-md border border-base-300 mt-4">
            <div class="card-body">
                <h2 class="card-title text-xl font-bold">Detail Surat Masuk</h2>
                <div class="divider my-2"></div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-base">
                    <div><strong>Nomor Surat:</strong> {{ letter.letter_number }}</div>
                    <div><strong>Perihal:</strong> {{ letter.subject }}</div>
                    <div><strong>Pengirim:</strong> {{ letter.sender }}</div>
                    <div><strong>Tanggal Surat:</strong> {{ formatDate(letter.letter_date) }}</div>
                    <div><strong>Diinput Oleh:</strong> {{ letter.user.name }}</div>
                </div>
                <div class="card-actions justify-end mt-4">
                    <a :href="route('document.file', letter.id)" target="_blank" class="btn btn-outline btn-primary">
                        <DocumentTextIcon class="size-5" />
                        Lihat Lampiran
                    </a>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-md border border-base-300 mt-4">
            <div class="card-body">
                <h2 class="card-title text-xl font-bold">Daftar Disposisi</h2>
                <div v-if="letter.dispositions.length > 0" class="space-y-4 mt-4">
                    <div v-for="disposition in letter.dispositions" :key="disposition.id" class="p-4 border border-base-200 rounded-lg bg-base-50">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold">"{{ disposition.instruction }}"</p>
                                <p class="text-xs text-base-content/70 mt-1">
                                    Dibuat oleh <strong>{{ disposition.user.name }}</strong> pada {{ formatDate(disposition.created_at) }}
                                </p>
                            </div>
                            <div class="flex gap-2">
                                <Link :href="route('dispositionIncomingLetter.modify', disposition.id)" class="btn btn-xs btn-outline btn-warning">
                                    <PencilSquareIcon class="size-4" />
                                </Link>
                            </div>
                        </div>
                        <div class="divider my-2"></div>
                        <div>
                            <h4 class="font-semibold text-sm mb-2">Penerima:</h4>
                            <ul class="space-y-1">
                                <li v-for="recipient in disposition.recipients" :key="recipient.id" class="flex items-center gap-2 text-sm">
                                    <div class="badge badge-sm capitalize font-bold text-white" :class="recipient.pivot.status === 'dibaca' ? 'badge-success' : 'badge-info'">
                                        {{ recipient.pivot.status }}
                                    </div>
                                    <span>{{ recipient.name }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center p-8">
                    <p class="text-base-content/70">Belum ada disposisi untuk surat ini.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
