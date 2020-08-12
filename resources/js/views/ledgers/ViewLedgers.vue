<template>
    <v-container>
        view ledgers
    </v-container>
</template>

<script>
    export default {
        name: "ViewLedgers",

        mounted() {
            //make drawer link active
            this.$store.dispatch('SET_DRAWER_LINKS', 'ledger');
        },

    }
</script>

<style scoped>

</style>
