<template>
    <div>
        <v-snackbar
            v-model="snackbar"
            :timeout="6000"
            :top="true"
            :right="true"
            :color="snackbarColor"
            transition="slide-x-reverse-transition"
            id="snackbar"
        >
            <v-icon
                v-if="snackbarColor === 'success'"
            >mdi-check</v-icon>
            <v-icon
                v-else
            >mdi-close</v-icon>
            {{ snackbarText }}
            <template v-slot:action="{ attrs }">
                <v-btn
                    text
                    v-bind="attrs"
                    @click="snackbar = false"
                >
                    Close
                </v-btn>
            </template>
        </v-snackbar>
        <!--<v-dialog
            v-model="loadingDialog"
            persistent
            width="300"
        >
            <v-card
                color="primary"
                dark
            >
                <v-card-text>
                    Please wait
                    <v-progress-linear
                        indeterminate
                        color="white"
                        class="mb-0"
                    ></v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>-->
        <v-overlay
            :value="loadingDialog"
            :z-index="9999"
        >
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
    </div>
</template>

<script>
    export default {
        name: "Miscellaneous",

        data() {
            return  {
            }
        },

        computed: {
            snackbarColor: {
                get () {
                    return this.$store.state.snackbar.color
                },
                set () {

                },
            },
            snackbarText: {
                get () {
                    return this.$store.state.snackbar.text
                },
                set () {

                },
            } ,
            snackbar: {
                get () {
                    return this.$store.state.snackbar.value
                },
                set () {
                    this.$store.dispatch('TOGGLE_SNACKBAR')
                },
            },
            loadingDialog: {
                get () {
                    return this.$store.state.loadingDialog
                },
                set () {
                    //this.$store.dispatch('TOGGLE_SNACKBAR')
                },
            }
        }
    }
</script>

<style scoped>
    #snackbar {
        z-index: 99999 !important;
    }
</style>
