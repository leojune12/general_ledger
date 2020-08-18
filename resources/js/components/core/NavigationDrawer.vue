<template>
    <v-navigation-drawer
        v-model="drawer"
        :clipped="$vuetify.breakpoint.mdAndUp"
        app
    >
        <v-list dense>
            <!--<template v-for="item in items">
                <v-row
                    v-if="item.heading"
                    :key="item.heading"
                    align="center"
                >
                    <v-col cols="6">
                        <v-subheader v-if="item.heading">
                            {{ item.heading }}
                        </v-subheader>
                    </v-col>
                    <v-col
                        cols="6"
                        class="text-center"
                    >
                        <a
                            href="#!"
                            class="body-2 black&#45;&#45;text"
                        >EDIT</a>
                    </v-col>
                </v-row>
                <v-list-group
                    v-else-if="item.children"
                    :key="item.text"
                    v-model="item.model"
                    :prepend-icon="item.model ? item.icon : item['icon-alt']"
                    append-icon=""
                >
                    <template v-slot:activator>
                        <v-list-item-content>
                            <v-list-item-title>
                                {{ item.text }}
                            </v-list-item-title>
                        </v-list-item-content>
                    </template>
                    <v-list-item
                        v-for="(child, i) in item.children"
                        :key="i"
                        link
                    >
                        <v-list-item-action v-if="child.icon">
                            <v-icon>{{ child.icon }}</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>
                                {{ child.text }}
                            </v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list-group>
                <v-list-item
                    v-else
                    :key="item.text"
                    link
                >
                    <v-list-item-action>
                        <v-icon>{{ item.icon }}</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>
                            {{ item.text }}
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </template>-->
            <!--<v-list-item
                link
                :class="{ 'v-list-item&#45;&#45;active': drawerLinks.dashboard }"
                href="/dashboard"
                color="primary"
            >
                <v-list-item-action>
                    <v-icon>mdi-view-dashboard</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title>
                        Dashboard
                    </v-list-item-title>
                </v-list-item-content>
            </v-list-item>-->

            <v-list-item
                link
                :class="{ 'v-list-item--active': drawerLinks.ledger }"
                href="/ledger"
                color="primary"
            >
                <v-list-item-action>
                    <v-icon>mdi-view-list</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title>
                        Ledger
                    </v-list-item-title>
                </v-list-item-content>
            </v-list-item>

            <v-list-item
                link
                :class="{ 'v-list-item--active': drawerLinks.reports }"
                href="/reports"
                color="primary"
            >
                <v-list-item-action>
                    <v-icon>mdi-chart-bar</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title>
                        Reports
                    </v-list-item-title>
                </v-list-item-content>
            </v-list-item>

            <v-list-group
                prepend-icon="mdi-cog"
                no-action
                :value="drawerLinks.accountCodes || drawerLinks.projectCodes"
            >
                <template v-slot:activator>
                    <v-list-item-content>
                        <v-list-item-title>Manage</v-list-item-title>
                    </v-list-item-content>
                </template>

                <v-list-item
                    href="/account-codes"
                    :class="{ 'v-list-item--active': drawerLinks.accountCodes }"
                >
                    <v-list-item-content>
                        <v-list-item-title>Account Codes</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item
                    href="/project-codes"
                    :class="{ 'v-list-item--active': drawerLinks.projectCodes }"
                >
                    <v-list-item-content>
                        <v-list-item-title>Project Codes</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list-group>
        </v-list>
    </v-navigation-drawer>
</template>

<script>
    export default {
        name: "NavigationDrawer",

        data() {
            return {
                items: [
                    { icon: 'mdi-contacts', text: 'Contacts' },
                    { icon: 'mdi-history', text: 'Frequently contacted' },
                    { icon: 'mdi-content-copy', text: 'Duplicates' },
                    {
                        icon: 'mdi-chevron-up',
                        'icon-alt': 'mdi-chevron-down',
                        text: 'Labels',
                        model: true,
                        children: [
                            { icon: 'mdi-plus', text: 'Create label' },
                        ],
                    },
                    {
                        icon: 'mdi-chevron-up',
                        'icon-alt': 'mdi-chevron-down',
                        text: 'More',
                        model: false,
                        children: [
                            { text: 'Import' },
                            { text: 'Export' },
                            { text: 'Print' },
                            { text: 'Undo changes' },
                            { text: 'Other contacts' },
                        ],
                    },
                    { icon: 'mdi-cog', text: 'Settings' },
                    { icon: 'mdi-message', text: 'Send feedback' },
                    { icon: 'mdi-help-circle', text: 'Help' },
                    { icon: 'mdi-cellphone-link', text: 'App downloads' },
                    { icon: 'mdi-keyboard', text: 'Go to the old version' },
                ],

                drawerLinks: this.$store.state.drawerLinks,
            }
        },

        computed: {
            drawer: {
                get () {
                    return this.$store.state.drawer
                },
                set (val) {
                    this.$store.commit('SET_DRAWER_MUTATION', val)
                },
            },
        },
    }
</script>

<style scoped>

</style>
