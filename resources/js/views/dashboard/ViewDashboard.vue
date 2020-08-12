<template>
    <v-container>
        Dashboard
    </v-container>
</template>

<script>
    export default {
        name: "ViewDashboard",

        mounted() {
            //make drawer link active
            this.$store.dispatch('SET_DRAWER_LINKS', 'dashboard');
        },
    }
</script>

<style scoped>

</style>
