<template>
    <v-row>
        <v-col cols="12" sm="4"><v-card flat>
                <v-card-title>Reports
                </v-card-title>
                <v-card-text>

                    <v-select :items="options" v-model="formData.date_option"></v-select>

                    <v-date-picker v-model="formData.date_range" v-if="formData.date_option == 'Custom Range'"
                        range></v-date-picker>

                    <v-btn dark flat @click="downloadCRIMS" :loading="isExporting">
                        Export to CRIMS
                    </v-btn>

                </v-card-text>
            </v-card></v-col>
        <v-col>
            <v-card>
                <v-card-text></v-card-text>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
import { debounce, cloneDeep } from "lodash";
import userMixin from './../Mixin/userMixin.js'
import authMixin from './../Mixin/authMixin.js'
export default {
    mixins: [userMixin, authMixin],
    data() {
        return {
            isExporting: false,
            options: ["Today", "This Week", "This Month", "3 Months Ago", "Custom Range"],
            formData: {
                date_range: []
            }
        }

    },

    methods: {
        downloadCRIMS: debounce(function () {
            this.isExporting = true;
            if (this.formData.date_option != "Custom Range") {
                this.formData.date_range = [];
            }
            axios.post(route('api.report.export'), this.formData)
                .then((res) => {
                    this.isExporting = false;
                    window.location.href = res.data.file;
                })
                .catch(err => {
                    console.warn(err);
                    this.isExporting = false;
                });
        }, 250),

    }

}
</script>

<style></style>