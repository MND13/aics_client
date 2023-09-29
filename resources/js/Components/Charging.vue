<template>
    <div class="row">
        <div class="col-md-4">
            <v-card flat>
                <v-card-title>Charging</v-card-title>
                <v-card-text>

                    <form @submit.prevent="submitForm" enctype="multipart/form-data" id="charging"  ref="charging">
                        Fund Source
                        <select v-model="form.fund_source_id" name="" id="" class="form-control" @change="viewTxn()">
                            <option :value="fund_source.id" v-for="fund_source in fund_sources" :key="fund_source.id">
                                {{ fund_source.name }} {{ fund_source.description }}
                            </option>
                        </select>
                        Amount

                        <CurrencyInput v-model="form.amount"
                            :options="{ currency: 'PHP', currencyDisplay: 'hidden', autoDecimalDigits: 'true' }" />

                        <!--  <input type="text" name="" id="" v-model="form.amount" class="form-control">-->

                        Txn Type
                        <select name="" id="" v-model="form.movement" class="form-control">
                            <option :value="e.value" v-for="(e, i) in txn_type" :key="i">{{ e.name }}</option>
                        </select>

                        Remarks
                        <input type="text" name="" id="" v-model="form.remarks" class="form-control">
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
            transactions:[],
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
            });

        }, 250),
        
        getAmount(item) {
            let x = item.amount * item.movement;
            return x.toLocaleString();
        },

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