<template>
    <div class="p-4">
        <h1 class="text-2xl font-bold mb-4">Notificacions</h1>
        <ul class="space-y-2">
            <li
                v-for="(notificacio, index) in notificacions"
                :key="index"
                class="p-3 rounded-lg shadow bg-white border hover:bg-gray-50 transition"
            >
                <p class="text-gray-800">{{ notificacio.missatge }}</p>
                <p class="text-sm text-gray-500">{{ notificacio.data }}</p>
            </li>
        </ul>
        <p v-if="notificacions.length === 0" class="text-gray-500">No hi ha notificacions.</p>
    </div>
</template>

<script>
export default {
    name: 'NotificacionsList',
    data() {
        return {
            notificacions: [
                { missatge: 'Has rebut una nova comanda.', data: '2025-05-08 10:34' },
                { missatge: 'Factura pagada correctament.', data: '2025-05-07 14:12' },
                { missatge: 'Nou missatge del client.', data: '2025-05-06 09:21' },
            ],
        };
    },
};
</script>
