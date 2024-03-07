<template>
    <div class="row">
        <div class="col-md-4">
            <v-card flat>
                <v-card-title>Charging</v-card-title>
                <v-card-text>

                    <form @submit.prevent="submitForm" enctype="multipart/form-data" id="charging" ref="charging">

                        <v-select v-model="form.fund_source_id" :items="fund_sources" @change="viewTxn()"
                            item-value="id" item-text="name" label="Fund Source"
                            :error-messages="formErrors.fund_source_id">
                        </v-select>

                        <v-text-field v-model="form.amount" label="Amount"
                            :error-messages="formErrors.amount"></v-text-field>

                        <v-select label="Txn Type" :items="txn_type" item-value="value" item-text="name"
                            v-model="form.movement" :error-messages="formErrors.movement">
                        </v-select>
                        
                        <v-text-field label="Remarks" v-model="form.remarks" :error-messages="formErrors.remarks">
                        </v-text-field>

                        <br>
                        <v-btn type="submit" large class="mt-2 --white-text" color="primary" :disabled="submit"
                            v-if="hasRoles(['super-admin'])">
                            SUBMIT
                        </v-btn>
                    </form>
                </v-card-text>

            </v-card>
        </div>
        <div class="col-md-8">

            <v-data-table dense :items-per-page="10" :items="transactions" :headers="txn_headers">
                <template v-slot:item.created_at="{ item }">
                    {{ item.created_at | formatDateOnly }}
                </template>
            </v-data-table>



        </div>
    </div>
</template>

<script>

import userMixin from './../Mixin/userMixin.js'
import authMixin from './../Mixin/authMixin.js'
import CurrencyInput from './CurrencyInput'


import { debounce, cloneDeep } from 'lodash'
export default {
    mixins: [userMixin, authMixin],
    components: { CurrencyInput },

    data() {
        return {
            fund_sources: [],
            form: {},
            submit: false,
            data: [],
            txn_headers: [
                { text: 'Remarks', value: 'memo' },
                { text: 'Withdrawal', value: 'debit' },
                { text: 'Allocation', value: 'credit' },
                { text: 'Date', value: 'created_at' },
            ],
            loading: false,
            txn_type: [
                { name: "Allocation", value: 1 },
                { name: "Withdrawal", value: -1 }
            ],
            transactions: [],
            formErrors: {},

        };
    },
    methods:
    {
        getFundSrc() {

            axios.get(route("api.fund_src")).then(response => {
                this.fund_sources = response.data;
            }).catch(error => console.log(error))
        },
        submitForm: debounce(function () {

            axios.post(route("charging.create"), this.form).then(response => {
                alert(response.data.message);
                this.$refs.charging.reset();
                this.viewTxn();
            }).catch(err => {
                this.submit = false;
                this.formErrors = err.response.data.errors
            });

        }, 250),

        getBalance() {
            axios.get(route()).then(res => {
                console.log(res);
            }).catch(err => console.log(err));

        },
        viewTxn() {

            axios.get(route("charging.txn", this.form.fund_source_id)).then(response => {
                this.transactions = response.data;
                this.dialog_txn = true;
            }).catch(err => console.log(err));
        }

    },
    mounted() {
        this.getFundSrc();
        this.getLogs();
    }
}
</script>

<style></style>