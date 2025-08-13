<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TextInput from "@/Components/Input/TextInput.vue";
import InputLabel from "@/Components/Input/InputLabel.vue";
import InputError from "@/Components/Input/InputError.vue";
import FileInput from "@/Components/Input/FileInput.vue";
import { computed } from 'vue';
import {Head, Link, useForm} from "@inertiajs/vue3";
import SelectInput from "@/Components/Input/SelectInput.vue";
import DateInput from "@/Components/Input/DateInput.vue";

defineOptions({
    layout: AuthenticatedLayout,
});

const props = defineProps({
    letter: Object,
    categories: Object,
    errors: Object,
});

const form = useForm({
    letter_number: props.letter?.letter_number || "",
    subject: props.letter?.subject || "",
    sender: props.letter?.sender || "",
    letter_date: props.letter?.letter_date || "",
    category_id: props.letter?.category_id || "",
    attachment_file: null,
});

const isEditMode = computed(() => !!props.letter);

// Handle form submission
const submitForm = () => {
    const url = props.letter
        ? route("incomingLetter.update", props.letter.id)
        : route("incomingLetter.save");

    form.post(url, {
        forceFormData: true,
        preserveState: true,
        onSuccess: () => {
            form.reset();
        },
        headers: {
            'X-HTTP-Method-Override': props.letter ? 'PATCH' : 'POST',
        }
    });
};
</script>

<template>
    <pre>{{letter}}</pre>

    <Head v-if="route().current('incomingLetter.new')" title="Tambah Surat Masuk" />
    <Head v-if="route().current('incomingLetter.modify')" title="Ubah Surat Masuk" />

    <div class="bg-base-100 overflow-hidden shadow-md rounded-lg p-6">
        <form @submit.prevent="submitForm" class="mx-2">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">

                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                        <!-- Letter Number Input -->
                        <div class="sm:col-span-4">
                            <div class="mt-2">
                                <InputLabel for="letter_number" value="Nomor Surat" />

                                <TextInput
                                    id="letter_number"
                                    ref="letter_number"
                                    v-model="form.letter_number"
                                    type="text"
                                    class="mt-1 block w-full"
                                    placeholder="Masukkan Nomor Surat"
                                />

                                <InputError :message="form.errors.letter_number" class="mt-2" />
                            </div>
                        </div>

                        <!-- Date Input -->
                        <div class="sm:col-span-4">
                            <div class="mt-2">
                                <InputLabel for="letter_date" value="Tanggal Surat" />

                                <DateInput
                                    id="letter_date"
                                    ref="letter_date"
                                    v-model="form.letter_date"
                                    class="mt-1 block w-full"
                                />

                                <InputError :message="form.errors.letter_date" class="mt-2" />
                            </div>
                        </div>

                        <!-- Subject Input -->
                        <div class="sm:col-span-4">
                            <div class="mt-2">
                                <InputLabel for="subject" value="Perihal / Subjek" />

                                <TextInput
                                    id="subject"
                                    ref="subject"
                                    v-model="form.subject"
                                    type="text"
                                    class="mt-1 block w-full"
                                    placeholder="Masukkan Perihal Surat"
                                />

                                <InputError :message="form.errors.subject" class="mt-2" />
                            </div>
                        </div>

                        <!-- Sender Input -->
                        <div class="sm:col-span-4">
                            <div class="mt-2">
                                <InputLabel for="sender" value="Pengirim Surat" />

                                <TextInput
                                    id="sender"
                                    ref="sender"
                                    v-model="form.sender"
                                    type="text"
                                    class="mt-1 block w-full"
                                    placeholder="Contoh: Kementerian Dalam Negeri"
                                />

                                <InputError :message="form.errors.sender" class="mt-2" />
                            </div>
                        </div>

                        <!-- Category Input -->
                        <div class="sm:col-span-4">
                            <div class="mt-2">
                                <InputLabel for="category" value="Kategori Surat" />

                                <SelectInput
                                    class="capitalize"
                                    v-model="form.category_id"
                                    :items="categories"
                                    item-value="id"
                                    item-text="name"
                                    placeholder="Pilih Kategori"
                                />

                                <InputError :message="form.errors.category_id" class="mt-2" />
                            </div>
                        </div>

                        <div class="sm:col-span-4">
                            <div class="mt-2">
                                <InputLabel for="attachment_file" value="Unggah Lampiran (PDF)" />

                                <FileInput
                                    id="attachment_file"
                                    accept=".pdf,application/pdf"
                                    @update:modelValue="form.attachment_file = $event"
                                    class="mt-1"
                                />

                                <div v-if="letter && letter.file_path" class="text-xs mt-1">
                                    File saat ini: <a :href="`/storage/${letter.file_path}`" target="_blank" class="text-primary hover:underline">{{ letter.file_path.split('/').pop() }}</a>
                                </div>

                                <InputError :message="form.errors.attachment_file" class="mt-2" />
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <Link :href="route('incomingLetter.index')" class="btn btn-ghost">Batal</Link>
                <button type="submit" class="btn text-white bg-blue-600 hover:bg-blue-700" :disabled="form.processing">
                    <span v-if="form.processing" class="loading loading-spinner"></span>
                    {{ isEditMode ? 'Update Surat Masuk' : 'Simpan Surat Masuk' }}
                </button>
            </div>
        </form>
    </div>
</template>

<style scoped>

</style>
