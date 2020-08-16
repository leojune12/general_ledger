<template>
    <v-container
        fluid
        class="px-md-11 py-md-5 pa-0"
    >
        <v-card
            :flat="!$vuetify.breakpoint.mdAndUp"
        >
            <v-card-title>
                Account Codes
                <v-spacer></v-spacer>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Search"
                    single-line
                    hide-details
                    clearable
                ></v-text-field>
            </v-card-title>
            <v-data-table
                :headers="headers"
                :items="accountCodes"
                :search="search"
                :loading="loading"
                :loading-text="loadingText"
                class="mb-16"
            >
                <template v-slot:item.actions="{ item }">
                    <v-menu bottom left>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                icon
                                v-bind="attrs"
                                v-on="on"
                            >
                                <v-icon>mdi-dots-vertical</v-icon>
                            </v-btn>
                        </template>

                        <v-list
                            dense
                            class="py-0"
                        >
                            <v-list-item
                                @click="openEditDialog(item)"
                            >
                                <v-list-item-icon>
                                    <v-icon>mdi-pencil</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title>Edit</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item
                                @click="openDeleteDialog(item)"
                            >
                                <v-list-item-icon>
                                    <v-icon>mdi-delete</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title>Delete</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </template>
            </v-data-table>
        </v-card>

        <v-btn
            bottom
            color="pink"
            dark
            fab
            fixed
            right
            @click="openCreateDialog"
        >
            <v-icon>mdi-plus</v-icon>
        </v-btn>

        <v-dialog
            v-model="createDialog"
            persistent
            max-width="350"
            :fullscreen="!$vuetify.breakpoint.smAndUp"
        >
            <v-card>
                <v-form
                    ref="createForm"
                    v-model="validCreate"
                    @submit.prevent
                >
                    <v-card-title>
                        <span class="headline">New Account Code</span>
                        <v-spacer></v-spacer>
                        <v-btn icon @click="closeCreateDialog">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-card-title>
                    <v-divider
                        class="my-0"
                    ></v-divider>
                    <v-card-text
                        class="px-0 px-md-5 py-0"
                    >
                        <v-container>
                            <v-row>
                                <v-col cols="12">
                                    <v-text-field
                                        v-model="newItem.description"
                                        :counter="50"
                                        :rules="requiredRules"
                                        label="Description"
                                        required
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-card-text>
                    <v-divider
                        class="my-0"
                    ></v-divider>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" text @click="closeCreateDialog">Close</v-btn>
                        <v-btn color="blue darken-1" text @click="validateCreateForm">Save</v-btn>
                    </v-card-actions>
                </v-form>
            </v-card>
        </v-dialog>

        <v-dialog
            v-model="editDialog"
            persistent
            max-width="350"
            :fullscreen="!$vuetify.breakpoint.smAndUp"
        >
            <v-card>
                <v-form
                    ref="editForm"
                    v-model="validEdit"
                    @submit.prevent
                >
                    <v-card-title>
                        <span class="headline">Edit Account Code</span>
                        <v-spacer></v-spacer>
                        <v-btn icon @click="closeEditDialog">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-card-title>
                    <v-divider
                        class="my-0"
                    ></v-divider>
                    <v-card-text
                        class="px-0 px-md-5 py-0"
                    >
                        <v-container>
                            <v-row>
                                <v-col cols="12">
                                    <v-text-field
                                        v-model="editItem.description"
                                        :counter="50"
                                        :rules="requiredRules"
                                        label="Description"
                                        required
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-card-text>
                    <v-divider
                        class="my-0"
                    ></v-divider>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" text @click="closeEditDialog">Close</v-btn>
                        <v-btn color="blue darken-1" text @click="validateEditForm">Update</v-btn>
                    </v-card-actions>
                </v-form>
            </v-card>
        </v-dialog>

        <v-dialog v-model="deleteDialog" persistent max-width="290">
            <v-card>
                <v-card-title class="headline">Delete Account Code</v-card-title>
                <v-card-text>Delete {{ deleteItem.description }}?</v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="green darken-1" text @click="closeDeleteDialog">Cancel</v-btn>
                    <v-btn color="red darken-1" text @click="deleteThisItem">Agree</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

    </v-container>
</template>

<script>
    export default {
        name: "ViewAccountCodes",

        mounted() {
            //make drawer link active
            this.$store.dispatch('SET_DRAWER_LINKS', 'accountCodes');

            this.fetchData();
        },

        data() {
            return {
                requiredRules: [
                    v => !!v || 'This field is required',
                    v => (v && v.length <= 50) || 'This field must not more than 50 characters',
                ],

                search: '',
                headers: [
                    {
                        text: 'Description',
                        align: 'start',
                        value: 'description'
                    },
                    {
                        text: 'Actions',
                        value: 'actions',
                        sortable: false
                    }
                ],

                accountCodes: [],
                loading: true,
                loadingText: 'Loading. Please wait...',

                createDialog: false,
                validCreate: true,

                newItem: {
                    description: ''
                },

                editDialog: false,
                validEdit: true,

                editItem: {
                    description: ''
                },

                deleteDialog: false,
                deleteItem: {
                    description: ''
                },

            }
        },

        methods: {
            fetchData(payload) {
                if (payload) {
                    this.accountCodes = payload
                } else {
                    axios.get('/api/account-codes')
                        .then(response => {
                            console.log(response.data.data)
                            this.accountCodes = response.data.data
                            this.loading = false
                        })
                        .catch(error => {
                            console.log(error)
                            this.$store.dispatch('ERROR_SNACKBAR', 'Something went wrong')
                        })
                }
            },

            openCreateDialog() {
                this.createDialog = true
            },

            closeCreateDialog() {
                this.createDialog = false
                this.$refs.createForm.resetValidation()
                this.$refs.createForm.reset()
            },

            validateCreateForm() {
                if (this.$refs.createForm.validate()) {
                    this.saveNewItem()
                }
            },

            saveNewItem() {
                this.$store.dispatch('OPEN_LOADING_DIALOG')
                axios.post('/api/account-codes', this.newItem)
                    .then(response => {
                        this.fetchData(response.data.data)
                        this.$store.dispatch('SUCCESS_SNACKBAR', 'Success')
                    })
                    .catch(error => {
                        this.$store.dispatch('ERROR_SNACKBAR', 'Something went wrong')
                        console.log(error)
                    })
                    .finally(() => {
                        this.closeCreateDialog()
                        this.$store.dispatch('CLOSE_LOADING_DIALOG')
                    });
            },

            openEditDialog(payload) {
                this.editItem = JSON.parse(JSON.stringify(payload))
                this.editDialog = true
            },

            closeEditDialog() {
                this.editDialog = false
                this.$refs.editForm.resetValidation()
                this.$refs.editForm.reset()
            },

            validateEditForm() {
                if (this.$refs.editForm.validate()) {
                    this.updateAccountCode()
                }
            },

            updateAccountCode() {
                this.$store.dispatch('OPEN_LOADING_DIALOG')
                axios.put('/api/account-codes/'+this.editItem.id, this.editItem)
                    .then(response => {
                        this.fetchData(response.data.data)
                        this.$store.dispatch('SUCCESS_SNACKBAR', 'Success')
                    })
                    .catch(error => {
                        this.$store.dispatch('ERROR_SNACKBAR', 'Something went wrong')
                        console.log(error)
                    })
                    .finally(() => {
                        this.closeEditDialog()
                        this.$store.dispatch('CLOSE_LOADING_DIALOG')
                    });
            },

            openDeleteDialog(payload) {
                this.deleteItem = payload
                this.deleteDialog = true
            },

            closeDeleteDialog() {
                this.deleteDialog = false
            },

            deleteThisItem() {
                this.$store.dispatch('OPEN_LOADING_DIALOG')
                axios.delete('/api/account-codes/'+this.deleteItem.id)
                    .then(response => {
                        if (response.data.deleted) {
                            this.fetchData()
                            this.$store.dispatch('SUCCESS_SNACKBAR', 'Success')
                        } else {
                            this.$store.dispatch('ERROR_SNACKBAR', 'Can\'t delete. There is a record using this code')
                        }
                    })
                    .catch(error => {
                        this.$store.dispatch('ERROR_SNACKBAR', 'Something went wrong')
                        console.log(error)
                    })
                    .finally(() => {
                        this.closeDeleteDialog()
                        this.$store.dispatch('CLOSE_LOADING_DIALOG')
                    });
            },

        },


    }
</script>

<style scoped>

</style>
