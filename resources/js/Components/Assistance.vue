<template>
    <v-card flat>
        <v-alert v-for="(el, index) in formErrors " :key="index" type="error">
            <ul>
                <li v-for="(e, i) in el" :key="i">
                    {{ e }}
                </li>
            </ul>
        </v-alert>


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

                <v-stepper-step :complete="e1 > 3" step="3">
                    Prefferred Office
                </v-stepper-step>

                <v-divider></v-divider>

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




                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>Last name </th>
                                    <td>{{ user.last_name }}</td>
                                </tr>
                                <tr>
                                    <th>First name </th>
                                    <td>{{ user.first_name }}</td>
                                </tr>
                                <tr>
                                    <th>Middle name </th>
                                    <td>{{ user.middle_name }}</td>
                                </tr>
                                <tr>
                                    <th>Ext name </th>
                                    <td>{{ user.ext_name }}</td>

                                </tr>
                            </tbody>
                        </table>

                        <table class="table table-bordered">
                            <thead>
                                <tr>




                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>Street Address</th>
                                    <td>{{ user.street_number }}</td>
                                </tr>
                                <tr>
                                    <th>Region</th>
                                    <td>{{ psgc_data.region_name }}</td>
                                </tr>
                                <tr>
                                    <th>Province</th>
                                    <td>{{ psgc_data.province_name }}</td>
                                </tr>

                                <tr>
                                    <th>City/Muni</th>
                                    <td>{{ psgc_data.city_name }}</td>



                                </tr>
                                <tr>
                                    <th>Barangay</th>
                                    <td>{{ psgc_data.brgy_name }}</td>
                                </tr>
                            </tbody>
                        </table>


                        <table class="table table-bordered">
                            <thead>
                                <tr>





                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>Birthdate</th>
                                    <td>{{ user.birth_date }}</td>
                                </tr>
                                <tr>
                                    <th>Gender</th>
                                    <td>{{ user.gender }}</td>
                                </tr>
                                <tr>
                                    <th>Telephone/Mobile No.</th>
                                    <td>{{ user.mobile_number }}</td>
                                </tr>
                                <tr>
                                    <th>Trabaho</th>
                                    <td>
                                        <v-text-field v-model="form.occupation" label="Trabaho" class="mx-0" outlined dense
                                            :error-messages="formErrors.occupation"></v-text-field>
                                        <!-- <input type="text" name="" v-model="form.occupation" id="" class="form-control">-->
                                    </td>
                                </tr>
                                <tr>
                                    <th>Buwanang Kita ng Pamilya</th>
                                    <td>
                                        <v-text-field v-model="form.monthly_salary" label="Buwanang Kita ng Pamilya"
                                            outlined dense :error-messages="formErrors.monthly_salary"></v-text-field>

                                        <!--<input type="text" name="" v-model="form.monthly_salary" id="" class="form-control">-->
                                    </td>
                                </tr>
                                <tr>
                                    <th>Civil Status</th>
                                    <td>
                                        <v-select v-model="form.civil_status" label="Civil Status" outlined dense
                                            :items="['Single', 'Married', 'Widowed', 'Separated']"
                                            :error-messages="formErrors.civil_status">
                                        </v-select>

                                        <!--<select v-model="form.civil_status" name="civil_status" id="civil_status"
                                            class="form-control">
                                            <option :value="e" v-for="e in ['Single', 'Married', 'Widowed', 'Separated']"
                                                :key="e">
                                                {{ e }}</option>
                                        </select>-->
                                    </td>
                                </tr>




                            </tbody>
                        </table>




                    </v-card>

                    <v-btn color="primary" @click="e1 = 3"
                        :disabled="form.civil_status && form.monthly_salary && form.occupation ? false : true">
                        Continue
                    </v-btn>

                    <v-btn text @click="e1 = 1">
                        Back
                    </v-btn>
                </v-stepper-content>

                <v-stepper-content step="3">
                    <v-card class="mb-12" flat>

                        <table class="table ">
                            <tbody>

                                <tr>
                                    <td>

                                        <p class="mtb-2">
                                            PUMILI NG PINAKA MALAPIT NA OPISINA ( Select office nearest to your convenience
                                            )
                                        </p>

                                        <v-radio-group v-model="form.office_id">
                                            <v-radio v-for="(e, i) in offices" :key="i" :value="e.id" :disabled="e.id > 1">
                                                <template v-slot:label>

                                                    <v-list-item two-line :disabled="e.id > 1">
                                                        <v-list-item-content>
                                                            <v-list-item-title color="grey"> <b>{{ e.name
                                                            }}</b></v-list-item-title>
                                                            <v-list-item-subtitle>Located at: {{ e.address
                                                            }}</v-list-item-subtitle>
                                                        </v-list-item-content>
                                                    </v-list-item>

                                                </template>
                                            </v-radio>
                                        </v-radio-group>
                                    </td>
                                </tr>
                            </tbody>
                        </table>



                    </v-card>

                    <v-btn color="primary" :disabled="form.office_id > 0 ? false : true" @click="e1 = 4">
                        Continue
                    </v-btn>

                    <v-btn text @click="e1 = 2">
                        Back
                    </v-btn>


                </v-stepper-content>

                <v-stepper-content step="4">
                    <v-card class="mb-12" flat>
                        Upload Files

                        <!--<ul v-if="requirements.length > 0">
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
                        </ul>-->

                        <template v-if="requirements.length > 0">
                            <v-list-item two-line v-for="r in requirements[0].requirements" :key="r.id">
                                <v-list-item-content>
                                    <v-list-item-title>

                                        <v-list-item-subtitle> <span v-if="r.is_required"
                                                style="color:red">(REQUIRED)</span>
                                            <span v-else>(OPTIONAL)</span>
                                            {{ r.name }}</v-list-item-subtitle>
                                    </v-list-item-title>
                                    <v-file-input ref="valid_id" accept="image/png, image/jpeg, application/pdf"
                                        capture="camera" :error-messages="formErrors.valid_id" v-model="form.documents[r.id]">
                                    </v-file-input>
                                    <!--<v-progress-linear v-if="form.documents" :value="15" indeterminate></v-progress-linear>-->

                                </v-list-item-content>
                            </v-list-item>
                        </template>


                        <!--<ul v-if="requirements.length > 0">
                            <li v-for="r in requirements[0].requirements" :key="r.id" style="list-style: number;">
                                <label for="" class="form-label">

                                    <span v-if="r.is_required" style="color:red">(REQUIRED)</span>
                                    <span v-else>(OPTIONAL)</span>

                                    {{ r.name }}
                                </label><br>

                                <v-file-input ref="valid_id" accept="image/png, image/jpeg, application/pdf"
                                    capture="camera" :error-messages="formErrors.valid_id"
                                    v-model="valid_id"></v-file-input>
                            </li>
                        </ul>
-->

                        <div class="row">
                            <ul></ul>
                        </div>

                    </v-card>

                    <v-btn color="primary" @click="submit">
                        SUBMIT
                    </v-btn>

                    <v-btn text @click="e1 = 3">
                        Back
                    </v-btn>


                </v-stepper-content>

            </v-stepper-items>
        </v-stepper>
    </v-card>
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
            formErrors: {},
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

                alert(response.data.message);
                if (response.data.message == "Saved") {
                    document.location.href = "/";
                }
            }).catch(error => {
                console.log(error);
                this.formErrors = error.response.data.errors;

            });
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
                //console.log(response.data);
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
  


<style scoped>
table {
    table-layout: fixed;
}
</style>