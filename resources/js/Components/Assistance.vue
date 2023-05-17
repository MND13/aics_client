<template>
    <v-stepper v-model="e1">
        <v-stepper-header>
            <v-stepper-step :complete="e1 > 1" step="1">
                Assistance Type
            </v-stepper-step>

            <v-divider></v-divider>

            <v-stepper-step :complete="e1 > 2" step="2">
                Intake Sheet
            </v-stepper-step>

            <v-divider></v-divider>

            <v-stepper-step step="3">
                Prefferred Schedule & Office
            </v-stepper-step>

            <v-stepper-step step="4">
                Document Upload
            </v-stepper-step>
        </v-stepper-header>

        <v-stepper-items>
            <v-stepper-content step="1">
                <v-card flat class="mb-12">

                    NAIS HINGIIN NA TULONG (Assistance Requested)

                    <select name="assistance_type" v-model="form.aics_type_id" class="form-control"
                        @change="GetRequirements()">
                        <option :value="e.id" v-for="e in assistance_types" :key="e.id">
                            {{ e.name }}
                        </option>
                    </select>

                    <br>

                    <v-alert v-if="requirements.length > 0" icon="mdi mdi-alert-outline" prominent text type="info">

                        <h6>PLEASE PREPARE THE FOLLOWING REQUIREMENTS: </h6>
                        <ul>
                            <li v-for="r in requirements[0].requirements" :key="r.id" style="list-style: number;">
                                <span v-if="r.is_required">(REQUIRED)</span>
                                <span v-else>(OPTIONAL)</span>
                                {{ r.name }}
                            </li>
                        </ul>


                    </v-alert>




                </v-card>

                <v-btn color="primary" :disabled="form.aics_type_id && form.aics_type_id > 0 ? false : true"
                    @click="e1 = 2">
                    Continue
                </v-btn>


            </v-stepper-content>

            <v-stepper-content step="2">
                <v-card class="mb-12" flat>
                    <b> IMPORMASYON NG BENEPISYARYO (Beneficiary's Identifying Information)</b>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Last name </th>
                                <th>First name </th>
                                <th>Middle name </th>
                                <th>Ext name </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ user.last_name }}</td>
                                <td>{{ user.first_name }}</td>
                                <td>{{ user.middle_name }}</td>
                                <td>{{ user.ext_name }}</td>

                            </tr>
                        </tbody>
                    </table>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Street Address</th>
                                <th>Region</th>
                                <th>Province</th>
                                <th>City/Muni</th>
                                <th>Barangay</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ user.street_number }}</td>
                                <td>{{ psgc_data.region_name }}</td>
                                <td>{{ psgc_data.province_name }}</td>
                                <td>{{ psgc_data.city_name }}</td>
                                <td>{{ psgc_data.brgy_name }}</td>


                            </tr>
                        </tbody>
                    </table>


                    <table class="table table-bordered">
                        <thead>
                            <tr>

                                <th>Birthdate</th>
                                <th>Gender</th>
                                <th>Telephone/Mobile No.</th>
                                <th>Trabaho</th>
                                <th>Buwanang Kita</th>
                                <th>Civil Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ user.birth_date }}</td>
                                <td>{{ user.gender }}</td>
                                <td><input type="text" name="" v-model="form.mobile_number" id="" class="form-control"></td>
                                <td><input type="text" name="" v-model="form.occupation" id="" class="form-control"></td>
                                <td><input type="text" name="" v-model="form.monthly_salary" id="" class="form-control">
                                    
                                </td>
                                <td>
                                    <select v-model="form.civil_status" name="civil_status" id="civil_status" class="form-control">
                                    <option :value="e" v-for="e in ['Single','Married','Widowed','Separated']" :key="e"> {{e}}</option>
                                    </select>
                                </td>

                            </tr>
                        </tbody>
                    </table>




                </v-card>

                <v-btn color="primary" @click="e1 = 3">
                    Continue
                </v-btn>

                <v-btn text @click="e1 = 1">
                    Cancel
                </v-btn>
            </v-stepper-content>

            <v-stepper-content step="3">
                <v-card class="mb-12" flat>

                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>Preferred Schedule

                                    <input type="date" v-model="form.schedule" name="" id="" class="form-control" max="">
                                </td>

                            </tr>
                            <tr>
                                <td>Select Office nearest to your convenience: <br>


                                    <ul style="    list-style: none; padding: 10px;">
                                        <li v-for="(e, i) in offices" :key="i">
                                            <input type="radio" name="" v-model="form.office_id" id="" v-bind:value="e.id">
                                            <b>{{ e.name }} </b><br> {{ e.address }}
                                            <hr>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        </tbody>
                    </table>



                </v-card>

                <v-btn color="primary" :disabled="form.office_id > 0 && form.schedule ? false : true" @click="e1 = 4">
                    Continue
                </v-btn>

                <v-btn text @click="e1 = 2">
                    Cancel
                </v-btn>


            </v-stepper-content>

            <v-stepper-content step="4">
                <v-card class="mb-12" flat>
                    Upload Files

                    <ul v-if="requirements.length > 0">
                        <li v-for="r in requirements[0].requirements" :key="r.id" style="list-style: number;">
                            <label for="" class="form-label">


                                <span v-if="r.is_required" style="color:red">(REQUIRED)</span>
                                <span v-else>(OPTIONAL)</span>

                                {{ r.name }}
                            </label><br>

                            <input type="file" @input="onFileChange(r.id, $event)"
                                accept="application/pdf,image/jpeg,image/png" :required="r.is_required" />
                            <hr>
                        </li>
                    </ul>


                </v-card>

                <v-btn color="primary" @click="submit">
                    SUBMIT
                </v-btn>

                <v-btn text @click="e1 = 3">
                    Cancel
                </v-btn>


            </v-stepper-content>

        </v-stepper-items>
    </v-stepper>
</template>
<script>
import userMixin from './../Mixin/userMixin.js'
import authMixin from './../Mixin/authMixin.js'

export default {
    mixins: [userMixin, authMixin],
    data() {
        return {
            e1: 1,
            assistance_types: {

            },
            form: {
                documents: [],
                mobile_number: this.user.mobile_number,
            },
            requirements: {},
            validationErrors: {
                client: {},
                beneficiary: {},
                assistance: {},
            },
            psgc_data: {},
            file: [],
            offices: [],
        }
    },
    methods:
    {

        GetRequirements() {
            this.form.documents = {};
            if (this.validationErrors.assistance) {
                this.validationErrors.assistance = {};
            }

            this.requirements = this.assistance_types.filter((x) => {
                if (x.id === this.form.aics_type_id) {
                    return x.requirements;
                }
            });
        },

        getAssistanceTypes() {
            axios.get(route("api.aics.assistance-types")).then((response) => {
                this.assistance_types = response.data;
            });
        },

        getPsgc() {
            axios.get(route("api.psgc.show", { type: "id", _query: { "id": this.user.psgc_id } }),).then(response => {

                this.psgc_data = response.data;
            }).catch(error => console.log(error));
        },

        submit() {
            this.form;

            let formData = new FormData();

            _.each(this.form, (value, key) => {
                if (typeof value === "object") {
                    _.each(value, (v, k) => {
                        formData.append("assistance[" + key + "][" + k + "]", v);
                    });
                } else {
                    formData.append("assistance[" + key + "]", value);
                }
            });

            axios.post(route("assistances.store"), formData).then(response => {
                console.log(response.data);
                alert(response.data.message);
                if( response.data.message =="Saved")
                {
                    document.location.href="/";
                }
            }).catch(error => console.log(error));
        },

        onFileChange(i, e) {

            console.log("onFileChange");
            console.log("onFileChange" + i);

            this.form.documents[i] = e.target.files[0];
            console.log(this.form.documents);
        },
        getOffices() {
            axios.get(route("api.offices.index")).then(response => {
                this.offices = response.data;
                console.log(response.data);
            }).catch(error => console.log(error));
        }

    },
    mounted() {
        this.getAssistanceTypes();
        this.getPsgc();
        this.getOffices();
    }
}
</script>
  
<style>
table {
    table-layout: fixed;
}
</style>