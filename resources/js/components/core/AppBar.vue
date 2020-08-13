<template>
    <v-app-bar
        :clipped-left="$vuetify.breakpoint.mdAndUp"
        app
        color="blue darken-3"
        dark
    >
        <v-app-bar-nav-icon @click.stop="setDrawer(!drawer)"></v-app-bar-nav-icon>
        <v-toolbar-title
            style="width: 300px"
            class="ml-0 pl-4"
        >
            <span class="hidden-sm-and-down">General Ledger</span>
        </v-toolbar-title>
        <!--<v-text-field
            flat
            solo-inverted
            hide-details
            prepend-inner-icon="mdi-magnify"
            label="Search"
            class="hidden-sm-and-down"
        ></v-text-field>-->
        <v-spacer></v-spacer>
        <v-btn icon>
            <v-icon>mdi-apps</v-icon>
        </v-btn>
        <v-btn icon>
            <v-icon>mdi-bell</v-icon>
        </v-btn>
        <!--<v-btn
            icon
            large
        >
            <v-avatar
                size="32px"
                item
            >
                <v-img
                    src="https://cdn.vuetifyjs.com/images/logos/logo.svg"
                    alt="Vuetify"
                ></v-img></v-avatar>
        </v-btn>-->
        <v-menu
            offset-y
            transition="slide-y-transition"
        >
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    dark
                    icon
                    class="mr-1"
                    v-bind="attrs"
                    v-on="on"
                    ripple
                    elevation="1"
                >
                    <v-avatar
                        color="grey lighten-2"
                    >
                        <v-img
                            :src="'/storage/image/blank.png'"
                        >
                            <template v-slot:placeholder>
                                <v-skeleton-loader
                                    type="image"
                                ></v-skeleton-loader>
                            </template>
                        </v-img>
                    </v-avatar>
                </v-btn>
            </template>

            <v-list
                dense
                class="py-0"
            >
                <v-list-item
                    link
                    disabled
                >
                    <v-list-item-icon>
                        <v-icon disabled>mdi-account-circle</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>Profile</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item
                    link
                    disabled
                >
                    <v-list-item-icon>
                        <v-icon disabled>mdi-lock-reset</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>Change Password</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item
                    link
                    @click="openLogoutDialog"
                >
                    <v-list-item-icon>
                        <v-icon>mdi-logout</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>Logout</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-menu>

        <v-dialog v-model="logoutDialog" persistent max-width="290">
        <v-card>
            <v-card-title class="headline">Logout</v-card-title>
            <v-card-text>Ready to leave?</v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="green darken-1" text @click="closeLogoutDialog">Cancel</v-btn>
                <v-btn color="green darken-1" text @click="logout">Agree</v-btn>
            </v-card-actions>
        </v-card>
        </v-dialog>
    </v-app-bar>
</template>

<script>

    import { mapState, mapMutations, mapActions } from 'vuex'

    export default {
        name: "AppBar",

        computed: {
            ...mapState(['drawer']),
        },

        data() {
            return {
                logoutDialog: false,
            }
        },

        methods: {
            ...mapActions({
                setDrawer: 'SET_DRAWER',
            }),

            openLogoutDialog() {
                this.logoutDialog = true
            },

            closeLogoutDialog() {
                this.logoutDialog = false
            },

            logout() {
                document.getElementById('logout-form').submit();
            },
        },

    }
</script>

<style scoped>

</style>
