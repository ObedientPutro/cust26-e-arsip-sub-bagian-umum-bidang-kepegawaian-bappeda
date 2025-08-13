<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/Input/InputError.vue';
import InputLabel from '@/Components/Input/InputLabel.vue';
import TextInput from '@/Components/Input/TextInput.vue';
import GuestLayout from "@/Layouts/GuestLayout.vue";
import {
    ArchiveBoxIcon,
} from "@heroicons/vue/24/outline";

defineOptions({
    layout: GuestLayout,
})

const form = useForm({
    username: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />

    <div class="bg-gray-100 flex items-center justify-center min-h-screen p-4 font-sans">
        <div class="shadow-2xl w-full max-w-6xl rounded-2xl overflow-hidden md:flex" data-aos="zoom-in">

            <!-- Bagian Kiri (Branding) -->
            <div class="w-full md:w-1/2 bg-gradient-to-br from-blue-500 to-blue-700 text-white p-12 flex flex-col justify-center text-center">
                <div class="z-10">
                    <div class="flex items-center justify-center pb-8">
                        <div class="bg-white p-3 rounded-full shadow-lg">
                            <ArchiveBoxIcon class="icon" />
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold mb-4" data-aos="slide-down">Selamat Datang di</h2>

                    <div class="flex items-center justify-center mb-6" data-aos="slide-down">
                        <span class="text-4xl font-bold tracking-wider">E-ARSIP BAPPEDA</span>
                    </div>

                    <p class="text-blue-100 leading-relaxed max-w-sm mx-auto" data-aos="slide-up">
                        Menyederhanakan pengelolaan dan mempercepat penemuan kembali berkas kepegawaian melalui sistem arsip digital yang andal.
                    </p>
                </div>
            </div>

            <!-- Bagian Kanan (Form Login) -->
            <div class="w-full md:w-1/2 p-20 bg-white">
                <h2 class="text-3xl font-bold mb-8 text-gray-800 text-center" data-aos="zoom-out">Masuk ke Akun Anda</h2>

                <form @submit.prevent="submit" data-aos="slide-up">
                    <!-- Input Username -->
                    <div>
                        <InputLabel for="username" value="Username" />
                        <TextInput
                            id="username"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.username"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Masukkan username Anda"
                        />
                        <InputError class="mt-2" :message="form.errors.username" />
                    </div>

                    <!-- Input Password -->
                    <div class="mt-4">
                        <InputLabel for="password" value="Password" />
                        <TextInput
                            id="password"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                            placeholder="Masukkan password Anda"
                        />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="pt-6">
                        <button
                            class="w-full inline-flex items-center justify-center px-4 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-lg text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing">
                            Masuk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>
.icon {
    @apply size-8;
    @apply text-blue-700;
}
</style>
