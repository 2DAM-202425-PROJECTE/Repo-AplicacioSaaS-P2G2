<template>
    <div class="flex flex-col min-h-screen">
        <header class="bg-[#1B1B1B] p-6 text-center border-b border-[#3D3D3D] shadow-lg">
            <nav class="flex justify-center space-x-6">
                <a href="/" class="text-gold-500 text-lg font-semibold hover:text-gold-400 transition duration-300">Home</a>
                <!--<a href="/restaurants" class="text-gold-500 text-lg font-semibold hover:text-gold-400 transition duration-300">Restaurants</a>-->
            </nav>
        </header>
        <main class="flex-grow p-6 bg-[#1B1B1B] text-white">
            <slot></slot>
        </main>
        <footer class="bg-[#1B1B1B] p-4 text-center border-t border-[#3D3D3D] text-gold-500 text-sm">
            &copy; 2025 - Tots els drets reservats
        </footer>
    </div>
</template>

<script>
export default {
    name: 'Layout',
}
</script>

<style scoped>
.text-gold-500 {
    color: #FFD700;
}

.text-gold-400 {
    color: #FFC300;
}
</style>
