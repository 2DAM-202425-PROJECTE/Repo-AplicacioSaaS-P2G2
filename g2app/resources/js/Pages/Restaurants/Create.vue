<template>
    <div>
        <h1>Create Restaurant</h1>
        <form @submit.prevent="submit">
            <!-- Form fields for restaurant attributes -->
            <button type="submit">Create</button>
        </form>
    </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';

export default {
    methods: {
        submit() {
            Inertia.post('/restaurants', this.form);
        },
    },
    data() {
        return {
            form: {
                id_ubicacio: '',
                nom: '',
                horari: '',
                descripcio: '',
                telefon: '',
                tipus_cuina: [],
            },
        };
    },
}
</script>
