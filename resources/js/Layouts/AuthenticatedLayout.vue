<script setup>
import AuthenticatedHeader from "@/Components/Header/AuthenticatedHeader.vue";
import AuthenticatedDrawer from "@/Components/Drawer/AuthenticatedDrawer.vue";
import ToastContainer from "@/Components/Toast/ToastContainer.vue";
</script>

<template>
    <div class="drawer lg:drawer-open">
        <input id="my-drawer" type="checkbox" class="drawer-toggle" />

        <div class="drawer-content flex flex-col h-screen">

            <header class="w-full bg-base-100 border-b px-4 sm:px-6 py-3 flex items-center justify-between shadow-sm z-10">
                <label for="my-drawer" class="btn btn-square btn-ghost lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-6 h-6 stroke-current"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </label>
                <AuthenticatedHeader/>
            </header>

            <main class="flex-1 overflow-y-auto bg-base-200 p-4 md:p-6 lg:p-8">
                <div class="max-w-7xl mx-auto">
                    <ToastContainer :flash="$page.props.flash" />
                    <slot />
                </div>
            </main>

        </div>

        <div class="drawer-side">
            <label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
            <AuthenticatedDrawer />
        </div>
    </div>
</template>

<style scoped>

</style>
