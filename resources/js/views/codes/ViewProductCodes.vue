<template>
    <v-container>
        Product Codes
    </v-container>
</template>

<script>
    export default {
        name: "ViewProductCodes",

        mounted() {
            //make drawer link active
            this.$store.dispatch('SET_DRAWER_LINKS', 'productCodes');
        },
    }
</script>

<style scoped>

</style>
