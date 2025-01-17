<template>
    <div>
        <header>
            <!-- Header content -->
            <nav>
                <Link href="/">Home</Link>
                <Link href="/restaurants">Restaurants</Link>
            </nav>
        </header>
        <main>
            <slot></slot>
        </main>
        <footer>
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
header {
    background-color: #f8f9fa;
    padding: 1rem;
    text-align: center;
    border-bottom: 1px solid #dee2e6;
}

nav a {
    margin: 0 1rem;
    text-decoration: none;
    color: #007bff;
}

nav a:hover {
    text-decoration: underline;
}
</style>
