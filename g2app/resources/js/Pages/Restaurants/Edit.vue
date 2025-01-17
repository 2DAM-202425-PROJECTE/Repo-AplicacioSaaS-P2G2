<template>
    <div>
        <h1>Edit Restaurant</h1>
        <form @submit.prevent="submit">
            <!-- Form fields for restaurant attributes -->
            <button type="submit">Update</button>
        </form>
    </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';

export default {
    props: {
        restaurant: Object,
    },
    methods: {
        submit() {
            Inertia.put(`/restaurants/${this.restaurant.id_restaurant}`, this.form);
        },
    },
    data() {
        return {
            form: {
                id_ubicacio: this.restaurant.id_ubicacio,
                nom: this.restaurant.nom,
                horari: this.restaurant.horari,
                descripcio: this.restaurant.descripcio,
                telefon: this.restaurant.telefon,
                tipus_cuina: this.restaurant.tipus_cuina,
            },
        };
    },
}
</script>
