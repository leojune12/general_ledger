<template>
    <v-container
        fluid
        class="px-md-11 py-md-5 pa-0"
    >
        <v-card
            :flat="!$vuetify.breakpoint.mdAndUp"
        >
            <v-card-title>
                Reports
                <export-excel
                    class="ml-2 pa-0"
                    :data="selected"
                    :fields="excelFields"
                    worksheet="Worksheet"
                    :name="excelFileName"
                >
                    <v-btn
                        color="success"
                        outlined
                        :disabled="disableExportButton"
                    >
                        <v-icon>mdi-download</v-icon>Export Selected({{ selectedCount }})
                    </v-btn>
                </export-excel>
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
                v-model="selected"
                :headers="headers"
                :items="ledgers"
                :search="search"
                :loading="loading"
                :loading-text="loadingText"
                class="mb-16"
                show-select
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
                            <!--<v-list-item
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
                            </v-list-item>-->
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
            @click="openFilterDialog"
        >
            <v-icon>mdi-filter-plus</v-icon>
        </v-btn>

        <v-dialog
            v-model="filterDialog"
            persistent
            max-width="500"
            :fullscreen="!$vuetify.breakpoint.smAndUp"
        >
            <v-card>
                <v-form
                    ref="filterForm"
                    v-model="validCreate"
                    @submit.prevent
                >
                    <v-card-title>
                        <span class="headline">Filter Records</span>
                        <v-spacer></v-spacer>
                        <v-btn icon @click="closeFilterDialog">
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
                                <v-col cols="12" md="6">
                                    <v-dialog
                                        ref="dateEncodedFrom"
                                        v-model="calendarFromDialog"
                                        :return-value.sync="filter.date_encoded_from"
                                        persistent
                                        width="290px"
                                    >
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-text-field
                                                v-model="filter.date_encoded_from"
                                                label="Encoded From"
                                                append-icon="mdi-calendar"
                                                readonly
                                                v-bind="attrs"
                                                v-on="on"
                                                :rules="calendarFromRules"
                                            ></v-text-field>
                                        </template>
                                        <v-date-picker
                                            v-model="filter.date_encoded_from"
                                            scrollable
                                            ref="datePickerFrom"
                                            min="1950-01-01"
                                        >
                                            <v-spacer></v-spacer>
                                            <v-btn text color="primary" @click="calendarFromDialog = false">Cancel</v-btn>
                                            <v-btn text color="primary" @click="saveDateFrom">OK</v-btn>
                                        </v-date-picker>
                                    </v-dialog>
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-dialog
                                        ref="dateEncodedTo"
                                        v-model="calendarToDialog"
                                        :return-value.sync="filter.date_encoded_to"
                                        persistent
                                        width="290px"
                                    >
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-text-field
                                                v-model="filter.date_encoded_to"
                                                label="Encoded To"
                                                append-icon="mdi-calendar"
                                                readonly
                                                v-bind="attrs"
                                                v-on="on"
                                                :rules="calendarToRules"
                                            ></v-text-field>
                                        </template>
                                        <v-date-picker
                                            v-model="filter.date_encoded_to"
                                            scrollable
                                            ref="datePickerTo"
                                            min="1950-01-01"
                                        >
                                            <v-spacer></v-spacer>
                                            <v-btn text color="primary" @click="calendarToDialog = false">Cancel</v-btn>
                                            <v-btn text color="primary" @click="saveDateTo">OK</v-btn>
                                        </v-date-picker>
                                    </v-dialog>
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-select
                                        :items="accountCodesItems"
                                        label="Account Code"
                                        v-model="filter.account_code_id"
                                        item-text="description"
                                        item-value="id"
                                        clearable
                                    ></v-select>
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-select
                                        :items="projectCodesItems"
                                        label="Project Code"
                                        v-model="filter.project_code_id"
                                        item-text="description"
                                        item-value="id"
                                        clearable
                                    ></v-select>
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-text-field
                                        v-model="filter.amount_from"
                                        label="Amount From"
                                        required
                                        type="number"
                                        :rules="amountFromRules"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-text-field
                                        v-model="filter.amount_to"
                                        label="Amount To"
                                        required
                                        type="number"
                                        :rules="amountToRules"
                                    ></v-text-field>
                                </v-col>
                                <!--<v-col cols="12">
                                    <v-text-field
                                        v-model="newItem.description"
                                        :counter="150"
                                        :rules="descriptionRules"
                                        label="Description"
                                        required
                                    ></v-text-field>
                                </v-col>-->
                            </v-row>
                        </v-container>
                    </v-card-text>
                    <v-divider
                        class="my-0"
                    ></v-divider>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" text @click="closeFilterDialog">Close</v-btn>
                        <v-btn color="red darken-1" text @click="resetFilterDialog">Reset</v-btn>
                        <v-btn color="blue darken-1" text @click="validateFilterForm">Filter</v-btn>
                    </v-card-actions>
                </v-form>
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
                                                        <!--<v-btn
                                                            fab
                                                            color="red"
                                                            small
                                                            dark
                                                            outlined
                                                            @click="openDeleteFileDialog(file)"
                                                        >
                                                            <v-icon>mdi-delete</v-icon>
                                                        </v-btn>-->
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
                    <!--<v-btn color="blue darken-1" text @click="openAddFileDialog">Add</v-btn>-->
                    <v-btn color="blue darken-1" text @click="closeFilesDialog">Close</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

    </v-container>
</template>

<script>

    import moment from 'moment'

    export default {
        name: "ViewReports",

        mounted() {
            //make drawer link active
            this.$store.dispatch('SET_DRAWER_LINKS', 'reports');

            this.fetchData()

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

                calendarFromRules: [
                    v => ((this.filter.date_encoded_from === '' && this.filter.date_encoded_to === '') || (this.filter.date_encoded_from !== '' && this.filter.date_encoded_to !== '') || (this.filter.date_encoded_from !== '' && this.filter.date_encoded_to === '')) || 'This field is required'
                ],

                calendarToRules: [
                    v => ((this.filter.date_encoded_to === '' && this.filter.date_encoded_from === '') || (this.filter.date_encoded_to !== '' && this.filter.date_encoded_from !== '') || (this.filter.date_encoded_to !== '' && this.filter.date_encoded_from === '')) || 'This field is required'
                ],

                amountFromRules: [
                    v => ((this.filter.amount_from === '' && this.filter.amount_to === '') || (this.filter.amount_from !== '' && this.filter.amount_to !== '') || (this.filter.amount_from !== '' && this.filter.amount_to === '')) || 'This field is required'
                ],

                amountToRules: [
                    v => ((this.filter.amount_to === '' && this.filter.amount_from === '') || (this.filter.amount_to !== '' && this.filter.amount_from !== '') || (this.filter.amount_to !== '' && this.filter.amount_from === '')) || 'This field is required'
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

                excelFields: {
                    'Date Encoded': 'date_encoded',
                    'Account Code': 'account_code.description',
                    'Project Code': 'project_code.description',
                    'Amount': 'amount',
                    'Description': 'description'
                },

                excelFileName: 'ledger_report_'+moment().format('YYYY-MM-DD_hh-mm-ss'),

                ledgers: [],
                loading: true,
                loadingText: 'Loading. Please wait...',

                filterDialog: false,
                validCreate: true,

                filter: {
                    date_encoded_from: '',
                    date_encoded_to: '',
                    account_code_id: '',
                    project_code_id: '',
                    amount_from: '',
                    amount_to: ''
                },

                calendarFromDialog: false,
                calendarToDialog: false,

                accountCodesItems: [
                    {
                        id: '',
                        description: 'All'
                    }
                ],
                projectCodesItems: [
                    {
                        id: '',
                        description: 'All'
                    }
                ],

                filesDialog: false,
                filesLoading: true,

                selected: [],

            }
        },

        computed: {
            disableExportButton() {
                if (this.selected.length > 0) {
                    return false
                } else {
                    return true
                }

            },

            selectedCount() {
                return this.selected.length
            }
        },

        methods: {
            fetchData(payload) {
                if (payload) {
                    this.ledgers = payload
                } else {
                    axios.get('/api/reports')
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

            openFilterDialog() {
                this.filterDialog = true
            },

            closeFilterDialog() {
                this.filterDialog = false
            },

            validateFilterForm() {
                if (this.$refs.filterForm.validate()) {
                    this.filterData()
                }
            },

            saveDateFrom() {
                this.$refs.dateEncodedFrom.save(this.filter.date_encoded_from)
            },

            saveDateTo() {
                this.$refs.dateEncodedTo.save(this.filter.date_encoded_to)
            },

            resetFilterDialog() {
                this.filter.date_encoded_from = ''
                this.filter.date_encoded_to = ''
                this.filter.account_code_id = ''
                this.filter.project_code_id = ''
                this.filter.amount_from = ''
                this.filter.amount_to = ''

                this.$refs.filterForm.resetValidation()
            },

            filterData() {
                this.$store.dispatch('OPEN_LOADING_DIALOG')
                axios.post('/api/reports-filter', this.filter)
                    .then(response => {
                        //clear selected variable
                        this.selected = []
                        this.fetchData(response.data.data)
                        this.$store.dispatch('SUCCESS_SNACKBAR', 'Success')
                    })
                    .catch(error => {
                        this.$store.dispatch('ERROR_SNACKBAR', 'Something went wrong')
                        console.log(error)
                    })
                    .finally(() => {
                        this.closeFilterDialog()
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

        }
    }
</script>

<style scoped>

</style>
