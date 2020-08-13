<template>
    <v-container
        fluid
        class="px-11 py-5"
    >
        <v-card

        >
            <v-card-title>
                Ledger Masterlist
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
                :items="ledgers"
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
            max-width="500"
            :fullscreen="!$vuetify.breakpoint.smAndUp"
        >
            <v-card>
                <v-form
                    ref="createForm"
                    v-model="validCreate"
                    @submit.prevent
                >
                    <v-card-title>
                        <span class="headline">New Record</span>
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
                                    <v-dialog
                                        ref="dateEncoded"
                                        v-model="calendarDialog"
                                        :return-value.sync="today"
                                        persistent
                                        width="290px"
                                    >
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-text-field
                                                v-model="today"
                                                label="Date Encoded"
                                                append-icon="mdi-calendar"
                                                readonly
                                                v-bind="attrs"
                                                v-on="on"
                                                :rules="requiredRules"
                                            ></v-text-field>
                                        </template>
                                        <v-date-picker
                                            v-model="newItem.date_encoded"
                                            scrollable
                                            ref="datePicker"
                                            min="1950-01-01"
                                        >
                                            <v-spacer></v-spacer>
                                            <v-btn text color="primary" @click="calendarDialog = false">Cancel</v-btn>
                                            <v-btn text color="primary" @click="saveDate(newItem.date_encoded)">OK</v-btn>
                                        </v-date-picker>
                                    </v-dialog>
                                </v-col>
                                <v-col cols="12">
                                    <v-select
                                        :items="accountCodesItems"
                                        :rules="requiredRules"
                                        label="Account Code"
                                        v-model="newItem.account_code_id"
                                        item-text="description"
                                        item-value="id"
                                    ></v-select>
                                </v-col>
                                <v-col cols="12">
                                    <v-select
                                        :items="projectCodesItems"
                                        :rules="requiredRules"
                                        label="Project Code"
                                        v-model="newItem.project_code_id"
                                        item-text="description"
                                        item-value="id"
                                    ></v-select>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field
                                        v-model="newItem.description"
                                        :counter="150"
                                        :rules="descriptionRules"
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
            max-width="500"
            :fullscreen="!$vuetify.breakpoint.smAndUp"
        >
            <v-card>
                <v-form
                    ref="editForm"
                    v-model="validEdit"
                    @submit.prevent
                >
                    <v-card-title>
                        <span class="headline">Edit Record</span>
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
                                    <v-dialog
                                        ref="editDateEncoded"
                                        v-model="editCalendarDialog"
                                        :return-value.sync="editItem.date_encoded"
                                        persistent
                                        width="290px"
                                    >
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-text-field
                                                v-model="editItem.date_encoded"
                                                label="Date Encoded"
                                                append-icon="mdi-calendar"
                                                readonly
                                                v-bind="attrs"
                                                v-on="on"
                                                :rules="requiredRules"
                                            ></v-text-field>
                                        </template>
                                        <v-date-picker
                                            v-model="editItem.date_encoded"
                                            scrollable
                                            ref="datePicker"
                                            :max="new Date().toISOString().substr(0, 10)"
                                            min="1950-01-01"
                                        >
                                            <v-spacer></v-spacer>
                                            <v-btn text color="primary" @click="editCalendarDialog = false">Cancel</v-btn>
                                            <v-btn text color="primary" @click="saveDate(editItem.date_encoded)">OK</v-btn>
                                        </v-date-picker>
                                    </v-dialog>
                                </v-col>
                                <v-col cols="12">
                                    <v-select
                                        :items="accountCodesItems"
                                        :rules="requiredRules"
                                        label="Account Code"
                                        v-model="editItem.account_code_id"
                                        item-text="description"
                                        item-value="id"
                                    ></v-select>
                                </v-col>
                                <v-col cols="12">
                                    <v-select
                                        :items="projectCodesItems"
                                        :rules="requiredRules"
                                        label="Project Code"
                                        v-model="editItem.project_code_id"
                                        item-text="description"
                                        item-value="id"
                                    ></v-select>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field
                                        v-model="editItem.description"
                                        :counter="150"
                                        :rules="descriptionRules"
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
                <v-card-title class="headline">Delete Record</v-card-title>
                <v-card-text>Delete this record from {{ deleteItem.date_encoded }}?</v-card-text>
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

    import moment from 'moment'

    export default {
        name: "ViewLedgers",

        mounted() {
            //make drawer link active
            this.$store.dispatch('SET_DRAWER_LINKS', 'ledger');

            this.fetchData();

            this.fetchSelectItems()

            console.log(this.today)
        },

        data() {
            return {
                requiredRules: [
                    v => !!v || 'This field is required',
                ],
                descriptionRules: [
                    v => !!v || 'This field is required',
                    v => (v && v.length <= 150) || 'This field must not more than 50 characters',
                ],

                search: '',
                headers: [
                    {
                        text: 'Date Encoded',
                        align: 'start',
                        value: 'date_encoded'
                    },
                    {
                        text: 'Account Code',
                        value: 'account_code.description'
                    },
                    {
                        text: 'Project Code',
                        value: 'project_code.description'
                    },
                    {
                        text: 'Description',
                        value: 'description'
                    },
                    {
                        text: 'Actions',
                        value: 'actions',
                        sortable: false
                    }
                ],

                ledgers: [],
                loading: true,
                loadingText: 'Loading. Please wait...',

                createDialog: false,
                validCreate: true,

                newItem: {
                    date_encoded: '',
                    account_code_id: '',
                    project_code_id: '',
                    description: '',
                },

                editDialog: false,
                validEdit: true,
                editItem: {
                    id: '',
                    date_encoded: '',
                    account_code_id: '',
                    project_code_id: '',
                    description: '',
                },
                editCalendarDialog: false,

                deleteDialog: false,
                deleteItem: {
                    description: ''
                },

                calendarDialog: false,

                accountCodesItems: [],
                projectCodesItems: [],

                today: moment().format('YYYY-MM-DD')

            }
        },

        methods: {
            fetchData(payload) {
                if (payload) {
                    this.ledgers = payload
                } else {
                    axios.get('/api/ledger')
                        .then(response => {
                            console.log(response.data.data)
                            this.ledgers = response.data.data
                            this.loading = false
                        })
                        .catch(error => {
                            console.log(error)
                            this.$store.dispatch('ERROR_SNACKBAR', 'Something went wrong')
                        })
                }
            },

            fetchSelectItems() {
                axios.get('/api/account-codes')
                    .then(response => {
                        this.accountCodesItems = response.data.data
                    })
                    .catch(error => {
                        console.log(error)
                        this.$store.dispatch('ERROR_SNACKBAR', 'Something went wrong')
                    })
                axios.get('/api/project-codes')
                    .then(response => {
                        this.projectCodesItems = response.data.data
                    })
                    .catch(error => {
                        console.log(error)
                        this.$store.dispatch('ERROR_SNACKBAR', 'Something went wrong')
                    })
            },

            openCreateDialog() {
                this.createDialog = true
            },

            closeCreateDialog() {
                this.createDialog = false
                this.$refs.createForm.resetValidation()
                this.$refs.createForm.reset()
            },

            saveDate(date) {
                this.$refs.dateEncoded.save(date)
            },

            validateCreateForm() {
                if (this.$refs.createForm.validate()) {
                    this.saveNewItem()
                }
            },

            saveNewItem() {
                this.$store.dispatch('OPEN_LOADING_DIALOG')
                axios.post('/api/ledger', this.newItem)
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
                this.editItem.id = JSON.parse(JSON.stringify(payload.id))
                this.editItem.date_encoded = JSON.parse(JSON.stringify(payload.date_encoded))
                this.editItem.account_code_id = JSON.parse(JSON.stringify(payload.account_code.id))
                this.editItem.project_code_id = JSON.parse(JSON.stringify(payload.project_code.id))
                this.editItem.description = JSON.parse(JSON.stringify(payload.description))
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
                axios.put('/api/ledger/'+this.editItem.id, this.editItem)
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
                axios.delete('/api/ledger/'+this.deleteItem.id)
                    .then(response => {
                        this.fetchData()
                        this.$store.dispatch('SUCCESS_SNACKBAR', 'Success')
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
        }

    }
</script>

<style scoped>

</style>
