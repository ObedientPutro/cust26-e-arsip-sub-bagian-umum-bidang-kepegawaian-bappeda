<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TextInput from "@/Components/Input/TextInput.vue";
import InputLabel from "@/Components/Input/InputLabel.vue";
import InputError from "@/Components/Input/InputError.vue";
import {Head, Link, useForm} from "@inertiajs/vue3";
import {computed, watch} from "vue";

defineOptions({
    layout: AuthenticatedLayout,
});

const props = defineProps({
    category: {
        required: false,
        type: Object
    },
    errors: {
        required: true,
        type: Object
    },
});

const isEditMode = computed(() => !!props.category);

const form = useForm({
    name: props.category?.name || "",
    classification_code: props.category?.classification_code || "",
});

watch(() => form.classification_code, (newValue) => {
    if (newValue) {
        let formatted = newValue.toLowerCase();

        // Ganti satu atau lebih spasi dengan satu underscore
        formatted = formatted.replace(/\s+/g, '_');

        // Hapus karakter selain huruf, angka, dan underscore
        formatted = formatted.replace(/[^a-z0-9_]/g, '');

        // Ganti dua atau lebih underscore berturut-turut dengan satu underscore
        formatted = formatted.replace(/_{2,}/g, '_');

        form.classification_code = formatted;
    }
});

// Handle form submission
const submitForm = () => {
    const url = props.category
        ? route("category.update", props.category.id)
        : route("category.save");

    form.post(url, {
        forceFormData: true,
        preserveState: true,
        onSuccess: () => {
            form.reset();
        },
        headers: {
            'X-HTTP-Method-Override': props.category ? 'PATCH' : 'POST',
        }
    });
};
</script>

<template>
    <Head title="Akun" />

    <div class="bg-base-100 overflow-hidden shadow-md rounded-lg p-6">
        <form @submit.prevent="submitForm" class="mx-2">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">

                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                        <!-- Name Input -->
                        <div class="sm:col-span-4">
                            <div class="mt-2">
                                <InputLabel for="name" value="Nama Klasifikasi Surat" />

                                <TextInput
                                    id="name"
                                    ref="name"
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    placeholder="Masukkan Nama"
                                />

                                <InputError :message="errors.name" class="mt-2" />

                            </div>
                        </div>

                        <!-- Classification Code Input -->
                        <div class="sm:col-span-4">
                            <div class="mt-2">
                                <InputLabel for="classification_code" value="Kode Klasifikasi Surat" />

                                <TextInput
                                    id="classification_code"
                                    ref="classification_code"
                                    v-model="form.classification_code"
                                    type="text"
                                    class="mt-1 block w-full"
                                    placeholder="Masukkan Kode Klasifikasi Surat"
                                />

                                <InputError :message="errors.classification_code" class="mt-2" />

                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <Link :href="route('category.index')" class="btn btn-ghost">Batal</Link>
                <button type="submit" class="btn text-white bg-blue-600 hover:bg-blue-700" :disabled="form.processing">
                    <span v-if="form.processing" class="loading loading-spinner"></span>
                    {{ isEditMode ? 'Update Klasifikasi Surat' : 'Simpan Klasifikasi Surat' }}
                </button>
            </div>
        </form>
    </div>
</template>

<style scoped>

</style>
