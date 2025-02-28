<template>
    <div class="flex flex-col min-h-screen">
        <header class="bg-gray-100 p-4 text-center border-b border-gray-300">
            <!-- Header content -->
            <nav class="space-x-4">
               <Link href="/" class="text-blue-500 hover:underline">Home</Link>
               <Link href="/restaurants" class="text-blue-500 hover:underline">Restaurants</Link>

            </nav>
        </header>
        <main class="flex-grow">
            <slot></slot>
        </main>
        <footer class="bg-gray-100 p-4 text-center border-t border-gray-300">
            <!-- Footer content -->
        </footer>
    </div>
</template>

<script setup>

import { Head, Link, router } from '@inertiajs/vue3';


</script>

<style scoped>
</style>
