<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TextInput from "@/Components/Input/TextInput.vue";
import InputLabel from "@/Components/Input/InputLabel.vue";
import InputError from "@/Components/Input/InputError.vue";
import {Head, Link, useForm} from "@inertiajs/vue3";
import SelectInput from "@/Components/Input/SelectInput.vue";
import { computed, watch } from "vue";

defineOptions({
    layout: AuthenticatedLayout,
});

const props = defineProps({
    user: {
        required: false,
        type: Object
    },
    roles: {
        required: true,
        type: Object
    },
    errors: {
        required: true,
        type: Object
    },
});

const isEditMode = computed(() => !!props.user);

const form = useForm({
    name: props.user?.name || "",
    email: props.user?.email || "",
    username: props.user?.username || "",
    password: null,
    role: props.user?.role || "",
});

watch(() => form.username, (newValue) => {
    if (newValue) {
        let formatted = newValue.toLowerCase();

        // Ganti satu atau lebih spasi dengan satu underscore
        formatted = formatted.replace(/\s+/g, '_');

        // Hapus karakter selain huruf, angka, dan underscore
        formatted = formatted.replace(/[^a-z0-9_]/g, '');

        // Ganti dua atau lebih underscore berturut-turut dengan satu underscore
        formatted = formatted.replace(/_{2,}/g, '_');

        form.username = formatted;
    }
});

// Handle form submission
const submitForm = () => {
    const url = props.user
        ? route("user.update", props.user.id)
        : route("user.save");

    form.post(url, {
        forceFormData: true,
        preserveState: true,
        onSuccess: () => {
            form.reset();
        },
        headers: {
            'X-HTTP-Method-Override': props.user ? 'PATCH' : 'POST',
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
                                <InputLabel for="name" value="Nama Pengguna" />

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

                        <!-- Email Input -->
                        <div class="sm:col-span-4">
                            <div class="mt-2">
                                <InputLabel for="email" value="Email Pengguna" />

                                <TextInput
                                    id="email"
                                    ref="email"
                                    v-model="form.email"
                                    type="text"
                                    class="mt-1 block w-full"
                                    placeholder="Masukkan Email"
                                />

                                <InputError :message="errors.email" class="mt-2" />

                            </div>
                        </div>

                        <!-- Username Input -->
                        <div class="sm:col-span-4">
                            <div class="mt-2">
                                <InputLabel for="username" value="Username" />

                                <TextInput
                                    id="username"
                                    ref="username"
                                    v-model="form.username"
                                    type="text"
                                    class="mt-1 block w-full"
                                    placeholder="Masukkan Username"
                                />

                                <InputError :message="errors.username" class="mt-2" />

                            </div>
                        </div>

                        <!-- Password Input -->
                        <div class="sm:col-span-4">
                            <div class="mt-2">
                                <InputLabel for="password" value="Password" />

                                <TextInput
                                    id="password"
                                    ref="password"
                                    v-model="form.password"
                                    type="password"
                                    class="mt-1 block w-full"
                                    placeholder="Masukkan Password"
                                />

                                <InputError :message="errors.password" class="mt-2" />

                            </div>
                        </div>

                        <!-- Role Input -->
                        <div class="sm:col-span-4">
                            <div class="mt-2">
                                <InputLabel for="category" value="Role Pengguna" />

                                <SelectInput class="capitalize"
                                             v-model="form.role"
                                             :items="roles"
                                             placeholder="Pilih"
                                />

                                <InputError :message="errors.role" class="mt-2" />
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <Link :href="route('user.index')" class="btn btn-ghost">Batal</Link>
                <button type="submit" class="btn text-white bg-blue-600 hover:bg-blue-700" :disabled="form.processing">
                    <span v-if="form.processing" class="loading loading-spinner"></span>
                    {{ isEditMode ? 'Update Pengguna' : 'Simpan Pengguna' }}
                </button>
            </div>
        </form>
    </div>
</template>

<style scoped>

</style>
