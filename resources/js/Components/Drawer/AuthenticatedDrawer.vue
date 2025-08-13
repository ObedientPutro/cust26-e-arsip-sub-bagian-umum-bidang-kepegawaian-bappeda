<script setup>
import SidebarLink from "@/Components/SidebarLink.vue";
import {
    ArrowLeftCircleIcon,
    HomeIcon,
    DocumentArrowUpIcon,
    DocumentPlusIcon,
    UserGroupIcon,
    FolderPlusIcon,
} from "@heroicons/vue/24/solid";
import {
    ArchiveBoxIcon,
} from "@heroicons/vue/24/outline";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const auth = computed(() => usePage().props.auth);
</script>

<template>
    <aside class="w-64 bg-white shadow-md h-full flex flex-col justify-between">
        <!-- Bagian Atas: Logo dan Nama -->
        <div class="px-6 py-4 border-b border-base-content/10">
            <div class="flex items-center space-x-3">
                <ArchiveBoxIcon class="icon" />
                <span class="text-xl font-bold uppercase text-blue-700">{{ appName }}</span>
            </div>
        </div>

        <!-- Bagian Tengah: Menu Navigasi -->
        <nav class="flex-1 p-4">
            <ul class="menu p-0 w-full">
                <li>
                    <SidebarLink :href="route('dashboard')" :active="route().current('dashboard')">
                        <template #icon>
                            <HomeIcon />
                        </template>
                        <template #menu>
                            Dashboard
                        </template>
                    </SidebarLink>
                </li>
                <li>
                    <SidebarLink :href="route('home')" :active="route().current('home')">
                        <template #icon>
                            <DocumentPlusIcon />
                        </template>
                        <template #menu>
                            Surat Masuk
                        </template>
                    </SidebarLink>
                </li>
                <li>
                    <SidebarLink :href="route('home')" :active="route().current('home')">
                        <template #icon>
                            <DocumentArrowUpIcon />
                        </template>
                        <template #menu>
                            Surat Keluar
                        </template>
                    </SidebarLink>
                </li>
                <li>
                    <SidebarLink :href="route('user.index')" :active="route().current('user.*')">
                        <template #icon>
                            <UserGroupIcon />
                        </template>
                        <template #menu>
                            Pengguna
                        </template>
                    </SidebarLink>
                </li>
                <li>
                    <SidebarLink :href="route('home')" :active="route().current('home')">
                        <template #icon>
                            <FolderPlusIcon />
                        </template>
                        <template #menu>
                            Klasifikasi Surat
                        </template>
                    </SidebarLink>
                </li>
            </ul>
        </nav>

        <!-- Bagian Bawah: Logout (Opsional) -->
        <div class="p-4 border-t border-base-content/10">
            <ul class="menu p-0 text-base font-medium w-full">
                <li>
                    <SidebarLink :href="route('logout')" method="post">
                        <template #icon>
                            <ArrowLeftCircleIcon />
                        </template>
                        <template #menu>
                            Logout
                        </template>
                    </SidebarLink>
                </li>
            </ul>
        </div>
    </aside>
</template>

<style scoped>
.icon {
    @apply size-7;
    @apply text-blue-700;
}
</style>
