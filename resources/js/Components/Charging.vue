<template>
    <div class="row">
        <div class="col-md-4">
            <v-card flat>
                <v-card-title>Charging</v-card-title>
                <v-card-text>

                    <form @submit.prevent="submitForm" enctype="multipart/form-data" id="charging">
                        Fund Source
                        <select v-model="form.fund_source_id" name="" id="" class="form-control">
                            <option :value="fund_source.id" v-for="fund_source in fund_sources" :key="fund_source.id">
                                {{ fund_source.name }} {{ fund_source.description }}
                            </option>
                        </select>
                        Amount

                        <CurrencyInput v-model="form.amount" :options="{ currency: 'PHP', currencyDisplay: 'hidden', autoDecimalDigits: 'true' }" />

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

            <v-data-table dense :items-per-page="10" :headers="headers" :items="data" :loading="loading"
                class="elevation-1">

                <template v-slot:item.created_at="{ item }">
                    {{ item.created_at | formatDate }}
                </template>

                <template v-slot:item.remarks="{ item }">
                    <div v-if="item.assessment">
                        {{ item.assessment.mode_of_assistance }}
                        {{ item.assessment.assistance.aics_client.full_name }}

                        <span v-if="item.assessment.provider">
                            {{ item.assessment.provider.company_name }}
                        </span>


                    </div>
                    <div class="">
                        {{ item.remarks }}
                    </div>


                </template>

                <template v-slot:item.amount="{ item }">
                    <div :class="item.movement > 0 ? 'green--text' : 'red--text'">
                        {{ getAmount(item) }}
                    </div>
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
            headers: [

                { text: 'Date', value: 'created_at' },
                { text: 'Office', value: 'fund_source.name' },
                /*{ text: 'Description', value: 'fund_source.description' },
                { text: 'Legislator', value: 'fund_source.legislators' },
                { text: 'Type', value: 'fund_source.type' },*/
                { text: 'Remarks', value: 'remarks', },
                { text: 'Amount', value: 'amount', align: 'end', },


            ],
            loading: false,
            txn_type: [
                { name: "Credit", value: 1 },
                { name: "Reversal", value: -1 }
            ]
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

                this.getLogs();
            });

        }, 250),
        getLogs() {
            axios.get(route("charging.index")).then(res => {

                this.data = res.data;
            }).catch(err => console.log(res));
        },

        getAmount(item) {
            let x = item.amount * item.movement;
            return x.toLocaleString();
        },

        getBalance() {
            axios.get(route()).then(res => {
                console.log(res);
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