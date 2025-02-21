<script setup>
import { useForm } from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout.vue";

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    empresa: false,
});

const submit = () => {
    //Convertir a true false per a la base de dades
    //form.empresa = form.empresa ? true : false;

    form.post("/register", {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <layout>
    <div class="flex min-h-screen items-center justify-center bg-gray-100">
        <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-semibold text-center mb-4">Registra't</h2>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
                    <input
                        type="text"
                        id="name"
                        v-model="form.name"
                        required
                        class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    />
                    <p v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</p>
                </div>

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

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirma la contrasenya</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        required
                        class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    />
                    <p v-if="form.errors.password_confirmation" class="text-red-500 text-sm">{{ form.errors.password_confirmation }}</p>
                </div>

                <!-- Selector per a empresa o usuari -->
                <div>
                    <label for="empresa" class="block text-sm font-medium text-gray-700">Tipus d'usuari</label>
                    <select
                        id="empresa"
                        v-model="form.empresa"
                        class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    >
                        <option :value=false>Usuari</option>
                        <option :value=true>Empresa</option>
                    </select>
                    <p v-if="form.errors.empresa" class="text-red-500 text-sm">{{ form.errors.empresa }}</p>
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 disabled:bg-gray-400"
                >
                    Registra't
                </button>
            </form>

            <p class="text-sm text-center mt-4">
                Ja tens compte? <a href="/" class="text-indigo-600 hover:underline">Inicia sessió aquí</a>
            </p>
        </div>
    </div>
    </layout>
</template>
