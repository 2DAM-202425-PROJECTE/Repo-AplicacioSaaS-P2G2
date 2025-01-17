<template>
    <div class="flex flex-col min-h-screen">
        <header class="bg-gray-100 p-4 text-center border-b border-gray-300">
            <!-- Header content -->
            <nav class="space-x-4">
                <a href="/" class="text-blue-500 hover:underline">Home</a>
                <a href="/restaurants" class="text-blue-500 hover:underline">Restaurants</a>
            </nav>
        </header>
        <main class="flex-grow p-4">
            <slot></slot>
        </main>
        <footer class="bg-gray-100 p-4 text-center border-t border-gray-300">
            <!-- Footer content -->
        </footer>
    </div>
</template>

<script>
export default {
    name: 'Layout',
}
</script>

<style scoped>
</style>
