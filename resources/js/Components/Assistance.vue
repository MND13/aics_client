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
                    <v-card flat class="mb-12 ">

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

                        <v-card-text>

                            Ako ang {{ requester_type }}

                            <div class="d-flex  gap-3">

                                <v-radio-group v-model="requester_type">
                                    <v-radio v-for="n in ['Beneficiary', 'Representative']" :key="n" :label="`${n}`"
                                        :value="n"></v-radio>
                                </v-radio-group>


                            </div>
                            <v-row v-if="requester_type">
                                <v-col cols="12">
                                    <v-card outlined>
                                        <template v-if="requester_type == 'Beneficiary'">
                                            <v-card-title>IMPORMASYON NG BENEPISYARYO</v-card-title>
                                            <v-card-subtitle> (Beneficiary's Identifying Information) </v-card-subtitle>
                                        </template>
                                        <template v-else-if="requester_type == 'Representative'">
                                            <v-card-title>IMPORMASYON NG REPRESENTATIVE/KINATAWAN </v-card-title>
                                            <v-card-subtitle> (Representative's Identifying Information) </v-card-subtitle>
                                        </template>

                                        <div class="d-flex flex-no-wrap">
                                            <v-avatar class="ma-3 " size="125" rounded="0">
                                                <v-img :src="user.profile_pic.file_directory"></v-img>
                                            </v-avatar>
                                            <div>
                                                <v-card-title class="text-h5">
                                                    {{ user.first_name }} {{ user.middle_name }} {{ user.last_name }} {{
                                                        user.ext_name
                                                    }}
                                                </v-card-title>

                                                <v-card-subtitle>
                                                    {{ user.birth_date | formatDate }} <br />
                                                    {{ user.mobile_number }}<br />
                                                    {{ user.street_number }}<br />
                                                    {{ psgc_data.brgy_name }}
                                                    {{ psgc_data.city_name }},
                                                    {{ psgc_data.province_name }},
                                                    {{ psgc_data.region_name }}
                                                </v-card-subtitle>
                                            </div>
                                        </div>

                                        <v-card-text>
                                            <v-divider></v-divider>
                                            <v-select v-model="form.civil_status" label="Civil Status" outlined dense
                                                :items="['Single', 'Married', 'Widowed', 'Separated']"
                                                :error-messages="formErrors.civil_status">
                                            </v-select>

                                            <v-text-field v-model="form.occupation" label="Trabaho" class="mx-0" outlined
                                                dense :error-messages="formErrors.occupation"></v-text-field>


                                            <v-text-field v-model="form.monthly_salary" label="Buwanang Kita ng Pamilya"
                                                outlined dense :error-messages="formErrors.monthly_salary"></v-text-field>

                                            <v-autocomplete v-if="requester_type == 'Representative'"
                                                item-text="relationship" v-model="form.rel_beneficiary" label="Relasyon"
                                                outlined dense :items="relationships" item-value="relationship"
                                                :error-messages="formErrors.rel_beneficiary">
                                            </v-autocomplete>

                                        </v-card-text>

                                    </v-card>
                                </v-col>

                                <v-col cols="12">
                                    <v-card outlined v-if="requester_type == 'Representative'">
                                        <v-card-title>IMPORMASYON NG BENEPISYARYO </v-card-title>
                                        <v-card-subtitle>(Beneficiary's Identifying Information)</v-card-subtitle>
                                        <v-card-text>

                                            <v-row>

                                                <v-col cols="12" md="3"> <v-text-field v-model="bene.first_name"
                                                        label="First Name" class="mx-0" outlined dense
                                                        :error-messages="beneErrors.occupation"></v-text-field></v-col>
                                                <v-col cols="12" md="3"> <v-text-field v-model="bene.middle_name"
                                                        label="Middle Name" class="mx-0" outlined dense
                                                        :error-messages="beneErrors.middle_name"></v-text-field></v-col>
                                                <v-col cols="12" md="3"> <v-text-field v-model="bene.last_name"
                                                        label="Last Name" class="mx-0" outlined dense
                                                        :error-messages="beneErrors.last_name"></v-text-field></v-col>
                                                <v-col cols="12" md="3">
                                                    <v-select v-model="bene.ext_name" label="Ext Name" outlined dense
                                                        :items="suffixes" item-value="id" item-text="name"
                                                        :error-messages="beneErrors.ext_name">
                                                    </v-select>

                                                </v-col>
                                            </v-row>



                                            Address <v-checkbox v-model="same_address"
                                                label="Same as Representative"></v-checkbox>
                                            <v-row v-if="same_address">
                                                <v-col cols="12">
                                                    <strong> {{ user.street_number }}<br />
                                                        {{ psgc_data.brgy_name }}
                                                        {{ psgc_data.city_name }},
                                                        {{ psgc_data.province_name }},
                                                        {{ psgc_data.region_name }}</strong>
                                                </v-col>
                                            </v-row>

                                            <v-row v-else>

                                                <v-col cols="12"> <v-text-field v-model="bene.street_number"
                                                        label="House No./Street/Purok*" outlined dense
                                                        :error-messages="formErrors.street_number"></v-text-field>
                                                </v-col>

                                                <v-col cols="12" md="3">

                                                    <v-select label="Region" outlined dense :items="['XI']">
                                                    </v-select>
                                                </v-col>
                                                <v-col cols="12" md="3">
                                                    <v-autocomplete v-model="province_name" :loading="loading"
                                                        :items="provinces" @change="getCities()" cache-items hide-no-data
                                                        hide-details label="Province" outlined item-text="province_name"
                                                        item-value="id" dense></v-autocomplete>
                                                </v-col>
                                                <v-col cols="12" md="3">
                                                    <v-autocomplete v-model="city_name" :disabled="!cities"
                                                        :loading="loading" :items="cities" @change="getBrgys()" hide-no-data
                                                        hide-details label="City/Municipality" outlined
                                                        item-text="city_name" item-value="id" dense></v-autocomplete>
                                                </v-col>
                                                <v-col cols="12" md="3">
                                                    <v-autocomplete v-model="bene.psgc_id" :disabled="!brgys"
                                                        :loading="loading" :items="brgys" hide-no-data hide-details
                                                        label="Barangay" outlined item-text="brgy_name" item-value="id"
                                                        dense :error-messages="formErrors.psgc_id"></v-autocomplete>

                                                </v-col>

                                            </v-row>

                                            <v-row>
                                                <v-col cols="12" md="11">
                                                    <v-text-field type="date" v-model="bene.birth_date" label="Birthday*"
                                                        outlined dense :error-messages="formErrors.birth_date"
                                                        @input="calculateAge"></v-text-field>
                                                </v-col>

                                                <v-col cols="12" md="1">
                                                    <v-text-field v-model="bene.age" label="Age" outlined dense
                                                        :error-messages="formErrors.birth_date" readonly></v-text-field>

                                                </v-col>

                                                <v-col cols="12">
                                                    <v-select v-model="bene.gender" label="Sex*" outlined dense
                                                        :items="['Lalake', 'Babae']" item-value="id" item-text="name"
                                                        :error-messages="formErrors.gender">
                                                    </v-select>

                                                </v-col>

                                                <v-col cols="12">
                                                    <v-select v-model="bene.civil_status" label="Civil Status"
                                                        outlined dense
                                                        :items="['Single', 'Married', 'Widowed', 'Separated']"
                                                        :error-messages="formErrors.civil_status">
                                                    </v-select>
                                                </v-col>

                                                <v-col cols="12">

                                                    <v-text-field v-model="bene.occupation" label="Trabaho" class="mx-0"
                                                        outlined dense
                                                        :error-messages="formErrors.occupation"></v-text-field>
                                                </v-col>

                                                <v-col cols="12">
                                                    <v-text-field v-model="bene.monthly_salary"
                                                        label="Buwanang Kita ng Pamilya" outlined dense
                                                        :error-messages="formErrors.monthly_salary"></v-text-field>
                                                </v-col>
                                            </v-row>

                                        </v-card-text>

                                    </v-card>
                                </v-col>


                                <v-col cols="12">
                                    <v-card outlined>
                                        <template v-if="requester_type == 'Beneficiary'">
                                            <v-card-title>IMPORMASYON NG BENEPISYARYO</v-card-title>
                                            <v-card-subtitle> (Beneficiary's Identifying Information) </v-card-subtitle>
                                        </template>
                                        <template v-else-if="requester_type == 'Representative'">
                                            <v-card-title>IMPORMASYON NG KINATAWAN </v-card-title>
                                            <v-card-subtitle> (Representative's Identifying Information) </v-card-subtitle>
                                        </template>

                                        <div class="d-flex flex-no-wrap">
                                            <v-avatar class="ma-3 " size="125" rounded="0" v-if="user.profile_pic && user.profile_pic.file_directory">
                                                <v-img :src="user.profile_pic.file_directory"></v-img>
                                            </v-avatar>
                                            <div>
                                                <v-card-title class="text-h5">
                                                    {{ user.first_name }} {{ user.middle_name }} {{ user.last_name }} {{
                                                        user.ext_name
                                                    }}
                                                </v-card-title>

                                                <v-card-subtitle>
                                                    {{ user.birth_date | formatDate }} <br />
                                                    {{ user.mobile_number }}<br />
                                                    {{ user.street_number }}<br />
                                                    {{ psgc_data.brgy_name }}
                                                    {{ psgc_data.city_name }},
                                                    {{ psgc_data.province_name }},
                                                    {{ psgc_data.region_name }}
                                                </v-card-subtitle>
                                            </div>
                                        </div>

                                        <v-card-text>
                                            <v-divider></v-divider>
                                            <v-select v-model="form.civil_status" label="Civil Status" outlined dense
                                                :items="['Single', 'Married', 'Widowed', 'Separated']"
                                                :error-messages="formErrors.civil_status">
                                            </v-select>

                                            <v-text-field v-model="form.occupation" label="Trabaho" class="mx-0" outlined
                                                dense :error-messages="formErrors.occupation"></v-text-field>


                                            <v-text-field v-model="form.monthly_salary" label="Buwanang Kita ng Pamilya"
                                                outlined dense :error-messages="formErrors.monthly_salary"></v-text-field>

                                            <v-autocomplete v-if="requester_type == 'Representative'"
                                                item-text="relationship" v-model="form.rel_beneficiary" label="Relasyon"
                                                outlined dense :items="relationships" item-value="relationship"
                                                :error-messages="formErrors.rel_beneficiary">
                                            </v-autocomplete>

                                        </v-card-text>

                                    </v-card>
                                </v-col>

                            </v-row>


                        </v-card-text>








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
                                    <v-file-input ref="valid_id" accept="image/png, image/jpeg, image/jpg"
                                        capture="camera" :error-messages="formErrors.valid_id"
                                        v-model="form.documents[r.id]">
                                    </v-file-input>
                                    <v-progress-linear v-if="form.documents[r.id]"
                                        :value="uploadProgress"></v-progress-linear>

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
            uploadProgress: 0,
            requester_type: '',
            bene: {
                psgc_id: 0
            },
            beneErrors: {},
            province_name: "",
            city_name: "",
            cities: [],
            brgys: [],
            loading: false,
            same_address: false,
            relationships: {},
            suffixes: ["", "JR",
                "SR",
                "I",
                "II",
                "III",
                "IV",
                "V",
                "VI",
                "VII",
                "VIII",
                "IX",
                "X"],


        }
    },
    computed: {
        config() {
            return {
                headers: { 'Content-Type': 'multipart/form-data' },
                onUploadProgress: progressEvent => {
                    this.uploadProgress = Math.floor((progressEvent.loaded * 100) / progressEvent.total);
                }
            };
        },
    },
    watch: {

        same_address(val) {
            if (val == true) {
                this.bene.psgc_id = this.user.psgc_id;
                this.bene.street_number = this.user.street_number;
            } else {
                this.bene.psgc_id = "";
                this.bene.street_number = "";
            }
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

            if (this.bene && this.bene.psgc_id > 0) {
                _.each(this.bene, (value, key) => {
                    if (typeof value === "object") {
                        _.each(value, (v, k) => {
                            formData.append("beneficiary[" + key + "][" + k + "]", v);
                        });
                    } else {
                        formData.append("beneficiary[" + key + "]", value);
                    }
                });

            }

            axios.post(route("assistances.store"), formData, this.config).then(response => {

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
        },

        calculateAge: function () {
            if (!this.bene.birth_date) {
                this.bene.age = 0;
            } else {
                let currentDate = new Date();
                let birthDate = new Date(this.bene.birth_date);
                let difference = currentDate - birthDate;
                let age = Math.floor(difference / 31557600000);
                this.bene.age = age;
            }
        },

        getCities() {
            this.cities = [];
            this.brgys = [];
            this.loading = true;
            axios.get(route("api.psgc.show", { type: "cities", field: "province_name", value: this.province_name })).then(response => {
                this.cities = response.data;
                this.loading = false;
            }).catch(error => { console.log(error); this.loading = false; });
        },

        getBrgys() {
            this.loading = true;
            let fields = [{ field: "city_name", value: this.city_name }, { field: "province_name", value: this.province_name, }];
            console.log(fields);

            axios.get(route("api.psgc.show", { type: "brgy", fields })).then(response => {
                this.brgys = response.data;
                this.loading = false;
            }).catch(error => { console.log(error); this.loading = false; });
        },
        getProvinces() {
            this.loading = true;
            axios.get(route("api.psgc.show", { type: "province" })).then(response => {
                this.provinces = response.data;
                this.loading = false;
            }).catch(error => { console.log(error); this.loading = false; });
        },
        getRels() {
            axios.get(route("api.family_rel")).then(response => {
                this.relationships = response.data;
                this.loading = false;
            }).catch(error => { console.log(error); this.loading = false; });
        }

    },
    mounted() {
        this.getAssistanceTypes();
        this.getPsgc();
        this.getOffices();
        this.getProvinces();
        this.getRels();
    }
}
</script>
  


<style scoped>
table {
    table-layout: fixed;
}
</style>