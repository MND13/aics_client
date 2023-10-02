<template>
    <v-card flat outlined>
        <v-card-title>Reports <v-progress-circular :width="3" indeterminate color="primary"
                v-if="isExporting"></v-progress-circular>
        </v-card-title>
        <v-card-text>

            <v-row>
                <v-col cols="12" md="2">


                    <v-select :items="options" v-model="formData.date_option"></v-select>

                    <v-date-picker v-model="formData.date_range" v-if="formData.date_option == 'Custom Range'"
                        range></v-date-picker>


                    <v-list dense>
                        <v-list-item link dense>
                            <v-list-item-content>
                                <v-list-item-title @click="downloadCRIMS" :disabled="isExporting">
                                    Export to CRIMS
                                </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>
                </v-col>
            </v-row>

        </v-card-text>
    </v-card>
</template>

<script>
import { debounce, cloneDeep } from "lodash";

export default {

    data() {
        return {
            isExporting: false,
            options: ["Today", "This Week", "This Month", "3 Months Ago", "Custom Range"],
            formData: {}
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