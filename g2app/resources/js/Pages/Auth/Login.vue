<script setup>
import { useForm } from "@inertiajs/vue3";

const form = useForm({
    email: "",
    password: "",
});

const submit = () => {
    form.post("/login", {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <div class="flex min-h-screen items-center justify-center bg-gray-100">
        <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-semibold text-center mb-4">Iniciar Sessió</h2>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input
                        type="email"
                        id="email"
                        v-model="form.email"
                        required
                        class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    />
                    <p v-if="form.errors.email" class="text-red-500 text-sm">{{ form.errors.email }}</p>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Contrasenya</label>
                    <input
                        type="password"
                        id="password"
                        v-model="form.password"
                        required
                        class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    />
                    <p v-if="form.errors.password" class="text-red-500 text-sm">{{ form.errors.password }}</p>
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 disabled:bg-gray-400"
                >
                    Iniciar Sessió
                </button>
            </form>

            <p class="text-sm text-center mt-4">
                No tens compte? <a href="/register" class="text-indigo-600 hover:underline">Registra't aquí</a>
            </p>
        </div>
    </div>
</template>
