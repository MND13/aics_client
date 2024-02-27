<template>
    <v-row>
        <v-col cols="12" sm="4">

            <v-card flat>
                <v-card-title>Fund Source</v-card-title>
                <v-card-text>
                    <v-form ref="form">
                        <v-text-field v-model="formData.name" label="Name" :error-messages="formErrors.name"></v-text-field>

                        <v-text-field v-model="formData.description" label="Description"
                            :error-messages="formErrors.description"></v-text-field>

                        <v-text-field v-model="formData.legislators" label="Legislators"
                            :error-messages="formErrors.legislators"></v-text-field>


                        <v-select v-model="formData.type" label="Type" :items="types"></v-select>

                        <v-btn color="primary" class="mr-4" @click="submitForm" :disabled="submit">
                            <span>{{ formType }}</span>
                        </v-btn>

                        <v-btn color="error" class="mr-4" @click="resetForm">
                            <span>Cancel</span>
                        </v-btn>
                    </v-form>
                </v-card-text>
            </v-card>
            
           

            <!--<v-card flat>
                <v-card-title>Fund Source</v-card-title>
                <v-card-text>
                    <form action="">
                        <label for="">Name</label>
                        <input type="text" name="" v-model="formData.name" id="" class="form-control">

                        <label for="">Description</label>
                        <input type="text" name="" v-model="formData.description" id="" class="form-control">

                        <label for="">legislators</label>
                        <input type="text" name="" v-model="formData.legislators" id="" class="form-control">

                        <label for="">Type</label>
                        <select name="" id="" v-model="formData.type" class="form-control">
                            <option :value="e" v-for="e in types" :key="e">{{ e }}</option>
                        </select>
                    </form>
                </v-card-text>
            </v-card>-->


        </v-col>
        <v-col cols="12" sm="8">

            <v-card>
                <v-card-title>Fund Source Table
                    <v-spacer></v-spacer>
                    <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line
                        hide-details></v-text-field>

                </v-card-title>

                <v-data-table dense :items-per-page="20" :loading="isLoading" :headers="headers" :items="data"
                    :search="search">



                    <template v-slot:item.actions="{ item }">
                        <v-icon small class="mr-2" @click="editFsrc(item)">
                            mdi-pencil
                        </v-icon>
                        <v-icon small class="mr-2" @click="viewTxn(item)" v-if="hasRoles(['super-admin'])">
                            mdi-eye
                        </v-icon>
                        <!--<v-icon small class="mr-2" @click="deleteFsrc(item)" v-if="hasRoles(['super-admin'])">
                            mdi-delete
                        </v-icon>-->
                    </template>

                    <template v-slot:item.balance="{ item }" v-if="hasRoles(['super-admin'])">
                        {{ item.balance }}
                    </template>



                </v-data-table>




                <v-dialog v-model="dialog_txn">
                    <v-card>
                        <v-card-text>
                            <v-row v-if="transactions">


                                <v-data-table dense :items-per-page="10" :items="transactions" :headers="txn_headers">

                                </v-data-table>

                            </v-row>
                        </v-card-text>
                    </v-card>
                </v-dialog>


            </v-card>


        </v-col>

    </v-row>
</template>

<script>
import userMixin from './../Mixin/userMixin.js'
import { debounce, cloneDeep, isEmpty } from 'lodash'
import authMixin from './../Mixin/authMixin.js'

export default {
    mixins: [userMixin,authMixin],
    props: ['user'],
    data() {
        return {
            formType: "Create",
            form: {},
            data: [],
            isLoading: false,
            search: '',
            headers: [
                { text: 'Code/Name', value: 'name' },
                { text: 'Description', value: 'description' },
                { text: 'Legislators', value: 'legislators' },
                { text: 'Type', value: 'type' },
                { text: 'Current Balance', value: 'balance' },
                { text: 'Actions', value: 'actions' },

            ],
            txn_headers: [
                { text: 'Remarks', value: 'memo' },
                { text: 'Withdrawal', value: 'debit' },
                { text: 'Allocation', value: 'credit' },
                { text: 'Date', value: 'created_at' },
            ],
            types: [
                '',
                'Congressional',
                'Regular Funds and Other Referrals',
                'Senator/Governor/Mayor',
                'Party-List'
            ], formData: {},
            formErrors: {},
            submit: false,
            transactions: [],
            dialog_txn: false,

        }
    },
    methods:
    {
        getFundSrc() {
            this.isLoading = true;
            axios.get(route("api.fund_src")).then(response => {
                this.data = response.data;
                this.isLoading = false;

            }).catch(error => console.log(error))
        },
        submitForm: debounce(function () {
            if (this.formType == "Update") {
                this.updateFundSrc()
            } else {
                this.createFundSrc();
            }
        }, 250),
        createFundSrc() {
            this.submit = true;

            axios.post(route('api.fund_src.store'), this.formData)
                .then(res => {
                    this.submit = false;
                    this.getFundSrc();
                    this.resetForm();
                    alert('Fund Source has been created');
                })
                .catch(err => {


                    this.submit = false;
                    this.formErrors = err.response.data.errors
                })
        },

        editFsrc(fsrc) {
            this.formType = "Update";
            this.formData = cloneDeep(fsrc);

        },

        resetForm() {
            this.formData = {};
            this.formErrors = {};
            this.formType = "Create";
        },
        updateFundSrc() {
            this.submit = true;
            this.formErrors = {};
            axios.put(route('api.fund_src.update', this.formData.id), this.formData)
                .then(res => {
                    this.submit = false;
                    this.getFundSrc();
                    this.resetForm();
                    alert('Fund Source has been updated');
                })
                .catch(err => {
                    this.submit = false;
                    this.formErrors = err.response.data.errors
                })
        },

        deleteFsrc(fsrc) {
            if (confirm(`Are you sure you want to delete ${fsrc.name}`)) {
                axios.delete(route('api.fund_src.delete', fsrc.id))
                    .then(res => {
                        this.getFundSrc();
                    })
                    .catch(err => { })
                    ;
            }
        },
        viewTxn(item) {

            axios.get(route("charging.txn", item.id)).then(response => {
                this.transactions = response.data;
                this.dialog_txn = true;
            }).catch(err => console.log(err));
        }


    },

    mounted() {
        this.getFundSrc();
    }

}
</script>

<style></style>