<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, useForm, router, Link} from "@inertiajs/vue3";
import { ref, watch, computed } from "vue"; // 1. Pastikan 'computed' di-import
import { debounce } from "lodash";
import TextareaInput from "@/Components/Input/TextareaInput.vue";
import InputLabel from "@/Components/Input/InputLabel.vue";
import InputError from "@/Components/Input/InputError.vue";
import Pagination from "@/Components/Pagination.vue";
import {
    TrashIcon,
    MagnifyingGlassIcon,
} from "@heroicons/vue/24/solid";

defineOptions({
    layout: AuthenticatedLayout,
});

const props = defineProps({
    disposition: Object,
    letter: Object,
    users: Object,
    filters: Object,
    errors: Object,
});

const isEditMode = computed(() => !!props.disposition);

const form = useForm({
    _method: isEditMode.value ? 'PATCH' : 'POST',
    instruction: props.disposition?.instruction || "",
    recipients: props.disposition?.recipients.map(r => r.id) || [],
});

const selectedUsers = ref(props.disposition?.recipients || []);
const search = ref(props.filters.search || "");

const availableUsers = computed(() => {
    return props.users.data.filter(user =>
        !selectedUsers.value.some(selected => selected.id === user.id)
    );
});

const addUser = (user) => {
    if (!selectedUsers.value.some(u => u.id === user.id)) {
        selectedUsers.value.push(user);
        form.recipients.push(user.id);
    }
};

const removeUser = (userToRemove) => {
    selectedUsers.value = selectedUsers.value.filter(u => u.id !== userToRemove.id);
    form.recipients = form.recipients.filter(id => id !== userToRemove.id);
};

watch(search, debounce((value) => {
    const url = isEditMode.value
        ? route('dispositionIncomingLetter.modify', props.disposition.id)
        : route('dispositionIncomingLetter.new', props.letter.id);
    router.get(url, { search: value }, { preserveState: true, replace: true });
}, 300));

const submitForm = () => {
    const url = isEditMode.value
        ? route('dispositionIncomingLetter.update', props.disposition.id)
        : route('dispositionIncomingLetter.save', props.letter.id);
    form.post(url);
};
</script>

<template>
    <Head :title="isEditMode ? 'Ubah Disposisi' : 'Buat Disposisi'" />

    <div class="bg-base-100 overflow-hidden shadow-md rounded-lg p-6">
        <form @submit.prevent="submitForm">
            <div class="p-4 border border-base-300 rounded-lg mb-6 bg-base-200">
                <h3 class="font-bold text-lg">{{ isEditMode ? 'Ubah Disposisi untuk Surat' : 'Buat Disposisi untuk Surat' }}</h3>
                <p class="text-sm"><strong>Nomor:</strong> {{ letter.letter_number }}</p>
                <p class="text-sm"><strong>Perihal:</strong> {{ letter.subject }}</p>
                <p class="text-sm"><strong>Pengirim:</strong> {{ letter.sender }}</p>
            </div>

            <div class="mb-6">
                <InputLabel for="instruction" value="Instruksi / Catatan Disposisi" class="font-bold" />
                <TextareaInput
                    id="instruction"
                    v-model="form.instruction"
                    class="mt-1 block w-full"
                    placeholder="Tuliskan instruksi disposisi di sini..."
                    rows="4"
                />
                <InputError :message="errors.instruction" class="mt-2" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-4 border border-base-300 rounded-lg">
                    <h4 class="font-bold mb-2">Pilih Penerima</h4>
                    <label class="input input-bordered flex items-center gap-2 w-full mb-4">
                        <input v-model="search" type="text" class="grow" placeholder="Cari pegawai..." />
                        <MagnifyingGlassIcon class="size-5 text-gray-400" />
                    </label>

                    <div class="space-y-2 max-h-60 overflow-y-auto">
                        <div v-for="user in availableUsers" :key="user.id" class="flex justify-between items-center p-2 rounded-md hover:bg-base-200">
                            <span>{{ user.name }}</span>
                            <button @click="addUser(user)" type="button" class="btn btn-xs btn-outline btn-primary">Tambah</button>
                        </div>
                        <p v-if="availableUsers.length === 0" class="text-center text-sm text-gray-500">
                            {{ users.data.length > 0 ? 'Semua pegawai yang cocok telah dipilih.' : 'Tidak ada data.' }}
                        </p>
                    </div>

                    <Pagination :links="users.links" class="mt-4" />
                </div>

                <div class="p-4 border border-blue-400 bg-blue-50 rounded-lg">
                    <h4 class="font-bold mb-2">Penerima Terpilih ({{ selectedUsers.length }})</h4>
                    <div v-if="selectedUsers.length > 0" class="space-y-2 max-h-96 overflow-y-auto">
                        <div v-for="user in selectedUsers" :key="user.id" class="flex justify-between items-center p-2 rounded-md bg-white shadow-sm">
                            <span>{{ user.name }}</span>
                            <button @click="removeUser(user)" type="button" class="btn btn-xs btn-ghost text-error">
                                <TrashIcon class="size-4" />
                            </button>
                        </div>
                    </div>
                    <div v-else class="flex items-center justify-center h-full text-center">
                        <p class="text-sm text-gray-500">Belum ada penerima dipilih.<br/>Klik "Tambah" pada daftar di sebelah kiri.</p>
                    </div>
                </div>
            </div>

            <InputError :message="errors.recipients" class="mt-2" />

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <Link :href="route('incomingLetter.index')" class="btn btn-ghost">Batal</Link>
                <button type="submit" class="btn text-white bg-blue-600 hover:bg-blue-700" :disabled="form.processing">
                    <span v-if="form.processing" class="loading loading-spinner"></span>
                    {{ isEditMode ? 'Perbarui Disposisi' : 'Kirim Disposisi' }}
                </button>
            </div>

        </form>
    </div>
</template>
