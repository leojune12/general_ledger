<template>
    <v-container
        fluid
        class="px-md-11 py-md-5 pa-0"
    >
        <v-card
            :flat="!$vuetify.breakpoint.mdAndUp"
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
                                @click="openFilesDialog(item.id)"
                            >
                                <v-list-item-icon>
                                    <v-icon>mdi-file</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title>Files</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
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
                                        :return-value.sync="newItem.date_encoded"
                                        persistent
                                        width="290px"
                                    >
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-text-field
                                                v-model="newItem.date_encoded"
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
                                            <v-btn text color="primary" @click="saveDate">OK</v-btn>
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
                                        v-model="newItem.amount"
                                        label="Amount"
                                        required
                                        type="number"
                                        :rules="requiredRules"
                                    ></v-text-field>
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
                                            min="1950-01-01"
                                        >
                                            <v-spacer></v-spacer>
                                            <v-btn text color="primary" @click="editCalendarDialog = false">Cancel</v-btn>
                                            <v-btn text color="primary" @click="saveEditDate">OK</v-btn>
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
                                        v-model="editItem.amount"
                                        label="Amount"
                                        required
                                        type="number"
                                        :rules="amountRules"
                                    ></v-text-field>
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
                <v-card-text>Delete {{ deleteItem.description }}?</v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="green darken-1" text @click="closeDeleteDialog">Cancel</v-btn>
                    <v-btn color="red darken-1" text @click="deleteThisItem">Agree</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog
            v-model="filesDialog"
            persistent
            max-width="500"
            :fullscreen="!$vuetify.breakpoint.smAndUp"
        >
            <v-card>
                <v-card-title>
                    <span class="headline">Files</span>
                    <v-spacer></v-spacer>
                    <v-btn icon @click="closeFilesDialog">
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
                            <v-col cols="12" class="pa-0">
                                <div
                                    class="d-flex"
                                    v-if="filesLoading"
                                >
                                    <v-progress-circular
                                        indeterminate
                                        color="primary"
                                        class="mx-auto"
                                    ></v-progress-circular>
                                </div>
                                <div
                                    v-else
                                    :class="{ 'd-flex': !files.length}"
                                >
                                    <v-simple-table
                                        v-if="files.length"
                                    >
                                        <template v-slot:default>
                                            <thead>
                                                <tr>
                                                    <th class="text-left">Name</th>
                                                    <th class="text-left">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="file in files" :key="file.id">
                                                    <td>{{ file.original_name }}</td>
                                                    <td>
                                                        <div
                                                            class="d-flex"
                                                        >
                                                            <v-btn
                                                                fab
                                                                color="success"
                                                                small
                                                                dark
                                                                outlined
                                                                class="mr-2"
                                                                @click="downloadFile(file)"
                                                            >
                                                                <v-icon>mdi-download</v-icon>
                                                            </v-btn>
                                                            <v-btn
                                                                fab
                                                                color="red"
                                                                small
                                                                dark
                                                                outlined
                                                                @click="openDeleteFileDialog(file)"
                                                            >
                                                                <v-icon>mdi-delete</v-icon>
                                                            </v-btn>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </template>
                                    </v-simple-table>
                                    <p
                                        v-else
                                        class="mx-auto my-0"
                                    >No Files</p>
                                </div>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-divider
                    class="my-0"
                ></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="openAddFileDialog">Add</v-btn>
                    <v-btn color="blue darken-1" text @click="closeFilesDialog">Close</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog
            v-model="addFileDialog"
            persistent
            max-width="500"
            :fullscreen="!$vuetify.breakpoint.smAndUp"
        >
            <v-card>
                <v-card-title>
                    <span class="headline">Add Files</span>
                    <v-spacer></v-spacer>
                    <v-btn icon @click="closeAddFileDialog">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>
                <v-divider
                    class="my-0"
                ></v-divider>
                <v-card-text
                    class="px-0 px-md-5 py-0"
                >
                    <v-form
                        ref="addFileForm"
                        v-model="validAddFile"
                        @submit.prevent
                    >
                        <v-container>
                            <v-row>
                                <v-col cols="12">
                                    <v-file-input
                                        v-model="newFile"
                                        color="deep-purple accent-4"
                                        label="Files"
                                        placeholder="Select your files"
                                        prepend-icon="mdi-paperclip"
                                        outlined
                                        :show-size="1000"
                                        :rules="requiredRules"
                                    >
                                        <template v-slot:selection="{ index, text }">
                                            <v-chip
                                                color="deep-purple accent-4"
                                                dark
                                                label
                                            >
                                                {{ text }}
                                            </v-chip>
                                        </template>
                                    </v-file-input>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-form>
                </v-card-text>
                <v-divider
                    class="my-0"
                ></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="closeAddFileDialog">Close</v-btn>
                    <v-btn
                        color="blue darken-1"
                        text
                        @click="saveNewFile"
                        :disabled="!newFile"
                    >Save</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="deleteFileDialog" persistent max-width="290">
            <v-card>
                <v-card-title class="headline">Delete File</v-card-title>
                <v-card-text>Delete {{ deleteFileItem.original_name }}?</v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="green darken-1" text @click="closeDeleteFileDialog">Cancel</v-btn>
                    <v-btn color="red darken-1" text @click="deleteThisFile">Agree</v-btn>
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
        },

        data() {
            return {
                amountRules: [
                    v => (v !== null && v !== '' && v !== undefined) || 'This field is required',
                ],

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
                        text: 'Amount',
                        value: 'amount'
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
                    amount: '',
                    description: '',
                },

                editDialog: false,
                validEdit: true,
                editItem: {
                    id: '',
                    date_encoded: '',
                    account_code_id: '',
                    project_code_id: '',
                    amount: '',
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

                today: moment().format('YYYY-MM-DD'),

                filesDialog: false,
                files: [
                    {
                        name: 'image.png'
                    }
                ],

                addFileDialog: false,
                newFile: null,

                validAddFile: true,

                ledgerId: null,

                filesLoading: true,

                deleteFileDialog: false,
                deleteFileItem: {
                    original_name: ''
                },
            }
        },

        methods: {
            fetchData(payload) {
                if (payload) {
                    this.ledgers = payload
                } else {
                    axios.get('/api/ledger')
                        .then(response => {
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
                this.newItem.date_encoded = this.today
                this.createDialog = true
            },

            closeCreateDialog() {
                this.createDialog = false
                this.$refs.createForm.resetValidation()
                this.$refs.createForm.reset()
            },

            saveDate() {
                this.$refs.dateEncoded.save(this.newItem.date_encoded)
            },

            validateCreateForm() {
                if (this.$refs.createForm.validate()) {
                    this.saveNewItem()
                }
            },

            saveNewItem() {
                this.saveDate()
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
                this.editItem.amount = JSON.parse(JSON.stringify(payload.amount))
                this.editItem.description = JSON.parse(JSON.stringify(payload.description))
                this.editDialog = true
            },

            closeEditDialog() {
                this.editDialog = false
                this.$refs.editForm.resetValidation()
                this.$refs.editForm.reset()
            },

            saveEditDate() {
                this.$refs.editDateEncoded.save(this.editItem.date_encoded)
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

            openFilesDialog(payload) {
                this.filesLoading = true
                this.ledgerId = payload
                this.filesDialog = true
                axios.get('/api/medias/'+this.ledgerId)
                    .then(response => {
                        this.files = response.data.data
                        this.filesLoading = false
                    })
                    .catch(error => {
                        this.$store.dispatch('ERROR_SNACKBAR', 'Something went wrong')
                        console.log(error)
                    })
            },

            closeFilesDialog() {
                this.filesDialog = false
            },

            openAddFileDialog() {
                this.addFileDialog = true
            },

            closeAddFileDialog() {
                this.addFileDialog = false
                this.$refs.addFileForm.resetValidation()
                this.$refs.addFileForm.reset()
            },

            saveNewFile() {
                this.$store.dispatch('OPEN_LOADING_DIALOG')

                let formData = new FormData();

                formData.append('media', this.newFile);

                formData.append('ledger_id', this.ledgerId)

                axios.post('/api/medias', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                })
                    .then(response => {
                        this.files = response.data.data
                        this.$store.dispatch('SUCCESS_SNACKBAR', 'Success')
                    })
                    .catch(error => {
                        this.$store.dispatch('ERROR_SNACKBAR', 'Something went wrong')
                        console.log(error)
                    })
                    .finally(() => {
                        this.closeAddFileDialog()
                        this.$store.dispatch('CLOSE_LOADING_DIALOG')
                    });
            },

            downloadFile(file) {
                axios({
                    method: 'get',
                    url: '/api/medias/download/' + file.id,
                    responseType: 'blob'
                })
                    .then(response => {
                        this.$store.dispatch('SUCCESS_SNACKBAR', 'File downloading')
                        this.forceFileDownload(response, file)
                    })
                    .catch(error => {
                        this.$store.dispatch('ERROR_SNACKBAR', 'Something went wrong')
                        console.log(error)
                    })
            },

            forceFileDownload(response, file){
                const url = window.URL.createObjectURL(new Blob([response.data]))
                const link = document.createElement('a')
                link.href = url
                link.setAttribute('download', file.original_name)
                document.body.appendChild(link)
                link.click()
            },

            openDeleteFileDialog(file) {
                this.deleteFileItem = file
                this.deleteFileDialog = true
            },

            closeDeleteFileDialog() {
                this.deleteFileDialog = false
            },

            deleteThisFile() {
                this.$store.dispatch('OPEN_LOADING_DIALOG')
                axios.delete('/api/medias/'+this.deleteFileItem.id)
                    .then(response => {
                        this.files = response.data.data
                        this.$store.dispatch('SUCCESS_SNACKBAR', 'Success')
                    })
                    .catch(error => {
                        this.$store.dispatch('ERROR_SNACKBAR', 'Something went wrong')
                        console.log(error)
                    })
                    .finally(() => {
                        this.closeDeleteFileDialog()
                        this.$store.dispatch('CLOSE_LOADING_DIALOG')
                    });
            }

        },

    }
</script>

<style scoped>

</style>
