<template>
  <form @submit.prevent="submitForm" enctype="multipart/form-data">
    <div class="container-fluid">

     <pre>{{ form }} </pre> 

      <div class="row">
        <div class="col-md-4 text-center">
          <img max-height="64" max-width="250px" src="/images/DSWD-DVO-LOGO.png" class="img-fluid" />
        </div>
        <div class="col-md-8 text-md-end text-center">
          <h1 style="
                            font-size: 2rem;
                            font-family: arial;
                            font-weight: bold;
                            margin-bottom: 0px;
                          ">
            CRISIS INTERVENTION DIVISION
          </h1>
          <p>
            IBP Road, Batasan Pambansa Complex Constitution Hills, Quezon City
          </p>
        </div>
      </div>
      <div class="row text-center">
        <div class="col-md-12">
          <img src="" />
          <!--<h1 style="font-size: 20pt">Assistance to Individuals in Crisis</h1>-->
          <h2 style="font-family: 'Arial black', sans-serif; margin-bottom: 0px">
            GENERAL INTAKE SHEET<br />
          </h2>
          <p>MAARING MAGPATULONG SUMAGOT SA DSWD PERSONNEL</p>
        </div>
      </div>

      <!--<div v-if="validationErrors">
        <ul class="alert alert-danger">
          <li
            v-for="(value, index) in validationErrors"
            :key="index"
          >
           {{index}} -  {{ value }}
          </li>
        </ul>

        <ul class="alert alert-danger">
          <li v-for="(value, index) in validationErrors.client" :key="index">
            {{ value }}
          </li>
        </ul>
      </div>-->

      <div class="card mt-2">
        <div class="card-title">
          NAIS HINGIIN NA TULONG (Assistance Requested)
          <span color="red"></span>
        </div>
        <div class="card-body">
          <div class="col-md-12">
            <select name="assistance_type" v-model="form.aics_type_id" class="form-control" @change="getRequirements">
              <option :value="e.id" v-for="e in assistance_types" :key="e.id">
                {{ e.name }}
              </option>
            </select>
          </div>
        </div>
      </div>

      <div class="card mt-2">
        <div class="card-title">
          IMPORMASYON NG BENEPISYARYO (Beneficiary's Identifying Information)
        </div>
        <div class="card-body">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-3">
                <label for="last_name">
                  Apelyido <small>(Last name) <span color="red"></span></small>
                </label>
                <input id="last_name" v-model="form.last_name" type="text" class="form-control" />
                <div v-if="validationErrors && validationErrors.last_name" style="color: red">
                  {{ validationErrors.last_name[0] }}
                </div>
              </div>

              <div class="col-md-3">
                <label for="first_name">
                  Unang Pangalan
                  <small>(First name) <span color="red"></span></small></label>
                <input id="first_name" v-model="form.first_name" type="text" class="form-control" />

                <div v-if="validationErrors && validationErrors.first_name" style="color: red">
                  {{ validationErrors.first_name[0] }}
                </div>
              </div>

              <div class="col-md-3">
                <label for="middle_name">Gitnang Pangalan <small>(Middle name)</small></label>
                <input id="middle_name" v-model="form.middle_name" type="text" class="form-control" />

                <div v-if="validationErrors && validationErrors.middle_name" style="color: red">
                  {{ validationErrors.middle_name[0] }}
                </div>
              </div>

              <div class="col-md-3">
                <label for="ext_name">Ext <small>(Sr.,Jr., II, III)</small></label><br />
                <select id="ext_name" v-model="form.ext_name" type="text" class="form-control" :class="{
                  'is-invalid':
                    validationErrors.ext_name && validationErrors.ext_name,
                }">
                  <option value=""></option>
                  <option value="JR">JR</option>
                  <option value="SR">SR</option>
                  <option value="I">I</option>
                  <option value="II">II</option>
                  <option value="III">III</option>
                  <option value="IV">IV</option>
                  <option value="V">V</option>
                  <option value="VI">VI</option>
                </select>

                <div v-if="validationErrors && validationErrors.ext_name" style="color: red">
                  {{ validationErrors.ext_name[0] }}
                </div>
              </div>
            </div>

            <div class="row mt-2">
              <div class="col-md-12">
                <label for="street_number">House No./Street/Purok
                  <small>(Ex. 123 Sun St.)</small>
                  <span color="red"></span>
                </label>
                <input id="street_number" v-model="form.street_number" class="form-control" type="text" />

                <div v-if="validationErrors && validationErrors.street_number" style="color: red">
                  {{ validationErrors.street_number[0] }}
                </div>
              </div>
            </div>
            <div class="row mt-2">
              <div class="col-md-3">
                <label>Region <small>(Ex. NCR)</small>
                  <span color="red"></span>
                </label>
                <select id="psgc_id" name="" v-model="beneficiary_region_selector" v-if="regions" class="form-control"
                  @change="getBeneficiaryPsgc">
                  <option value=""></option>
                  <option :value="e" v-for="(e, i) in regions" :key="i">
                    {{ i }}
                  </option>
                </select>

                <div v-if="validationErrors && validationErrors.psgc_id" style="color: red">
                  {{ validationErrors.psgc_id[0] }}
                </div>
              </div>

              <div class="col-md-3">
                <label>Province/District <small>(Ex. Dis. III)</small>
                  <span color="red"></span>
                </label>
                <select id="psgc_id" name="" v-model="beneficiary_province_selector" v-if="beneficiary_provinces"
                  class="form-control">
                  <option :value="e" v-for="(e, i) in beneficiary_provinces" :key="i">
                    {{ i }}
                  </option>
                </select>

                <div v-if="validationErrors && validationErrors.psgc_id" style="color: red">
                  {{ validationErrors.psgc_id[0] }}
                </div>
              </div>

              <div class="col-md-3">
                <label>
                  City/Municipality <small>(Ex. Quezon City)</small>
                  <span color="red"></span>
                </label>

                <select name="" v-model="beneficiary_city_selector" v-if="beneficiary_cities" class="form-control">
                  <option :value="e" v-for="(e, i) in beneficiary_cities" :key="i">
                    {{ i }}
                  </option>
                </select>

                <div v-if="validationErrors && validationErrors.psgc_id" style="color: red">
                  {{ validationErrors.psgc_id[0] }}
                </div>
              </div>

              <div class="col-md-3">
                <label>Barangay
                  <small>(Ex. Batasan Hills)</small>
                  <span style="color:red;">*</span>
                </label>
                <select id="psgc_id" name="" v-model="form.psgc_id" v-if="beneficiary_cities" class="form-control">
                  <option :value="e[0].id" v-for="(e, i) in beneficiary_barangays" :key="i">
                    {{ i }}
                  </option>
                </select>

                <div v-if="validationErrors && validationErrors.psgc_id" style="color: red">
                  {{ validationErrors.psgc_id[0] }}
                </div>
              </div>
            </div>

            <div class="row mt-2">
              <div class="col-md-3">
                <label for="mobile_number">Telepono
                  <small>(Mobile Number)
                    <span style="color:red;">*</span>
                  </small>
                </label>

                <input id="mobile_number" v-model="form.mobile_number" type="text" class="form-control" />

                <div v-if="validationErrors && validationErrors.mobile_number" style="color: red">
                  {{ validationErrors.mobile_number[0] }}
                </div>
              </div>

              <div class="col-md-3">
                <label for="birth_date">Kapanganakan <small>(Birthdate)</small>
                  <span color="red"></span></label>
                <input id="birth_date" v-model="form.birth_date" type="date" class="form-control" :max="max_date"
                  @input="calculateAge" required />

                <div v-if="validationErrors && validationErrors.birth_date" style="color: red">
                  {{ validationErrors.birth_date[0] }}
                </div>
              </div>

              <div class="col-md-3">
                <label for="age">Edad <small>(Age)</small>
                  <span color="red"></span>
                </label>

                <input id="age" type="text" class="form-control" :value="form.age" readonly />
              </div>

              <div class="col-md-3">
                <label for="gender">Kasarian <small>(gender)</small> <span color="red"></span></label>
                <select name="" id="" class="form-control" v-model="form.gender">
                  <option :value="gender" v-for="gender in ['Babae', 'Lalake']" :key="gender">
                    {{ gender }}
                  </option>
                </select>

                <div v-if="validationErrors && validationErrors.gender" style="color: red">
                  {{ validationErrors.gender[0] }}
                </div>
              </div>
            </div>

            <div class="row mt-2">
              <div class="col-md-3">
                <label for="occupation">Trabaho <small> (Occupation)</small> <span color="red"></span></label>
                <input id="occupation" v-model="form.occupation" type="text" class="form-control" />

                <div v-if="validationErrors && validationErrors.occupation" style="color: red">
                  {{ validationErrors.occupation[0] }}
                </div>
              </div>
              <div class="col-md-3">
                <label for="monthly_salary">Buwanang Kita <small> (Monthly Salary) </small>
                  <span color="red"></span></label>
                <input id="monthly_salary" v-model="form.monthly_salary" type="text" class="form-control" />

                <div v-if="validationErrors && validationErrors.monthly_salary" style="color: red">
                  {{ validationErrors.monthly_salary[0] }}
                </div>
              </div>

              <div class="col-md-3">
                <label for="civil_status">Civil Status <span style="color:red;">*</span></label>
                <select id="civil_status" v-model="form.civil_status" class="form-control">
                  <option :value="e" v-for="(e, i) in ['Single', 'Married', 'Common-law', 'Widowed', 'Separated']"
                    :key="i">
                    {{ e }}
                  </option>
                </select>

                <div v-if="validationErrors && validationErrors.civil_status" style="color: red">
                  {{ validationErrors.civil_status[0] }}
                </div>
              </div>

              <div class="col-md-3">
                <label for="mode_of_admission">Mode of Admission <span style="color:red;">*</span></label>
                <select id="mode_of_admission" v-model="form.mode_of_admission" class="form-control">
                  <option :value="e" v-for="(e, i) in ['Referral']" :key="i">
                    {{ e }}
                  </option>
                </select>

                <div v-if="validationErrors && validationErrors.mode_of_admission" style="color: red">
                  {{ validationErrors.mode_of_admission[0] }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4">
          <div class="card">
            <div class="card-title">Beneficiary Category</div>
            <div class="card-body">
              Target Sector

              <select v-model="form.category_id" class="form-control">
                <option></option>
                <option v-for="(e, i) in categories" :key="i" :value="e.id">
                  {{ e.category }}
                </option>
              </select>

              Specific Subcategory

              <select v-model="form.subcategory_id" class="form-control">
                <option></option>
                <option v-for="(e, i) in subcategories" :key="i" :value="e.id">
                  {{ e.subcategory }}
                </option>
              </select>

              <div class="" v-if="form.subcategory_id == 8">
                Others
                <input type="text" v-model="form.subcategory_others" class="form-control" />
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-8">
          <div class="card">
            <div class="card-title">Social Worker's Assessment</div>
            <div class="card-body">
              <textarea name="" id="" v-model="form.assessment" class="form-control" cols="30" rows="5"></textarea>

              Interviewed by
              <input type="text" class="form-control" v-model="form.interviewed_by" />
            </div>
          </div>
        </div>
      </div>

      <br />

      <div class="card">
        <div class="card-title">Select Payroll <span style="color:red;">*</span></div>
        <div class="card-body">

          <div v-if="dialog_data.payroll_client && dialog_data.payroll_client.payroll.status == 'closed'">
            <b>
              IN
              {{ dialog_data.payroll_client.payroll.title }} |
              Client No: {{ dialog_data.payroll_client.sequence }} |
              {{ dialog_data.payroll_client.payroll.amount }} |
              {{ dialog_data.payroll_client.payroll.status }}</b>


          </div>
          <div v-else>


            <div v-if="payrolls.length > 0">

              <select name="" id="" v-model="form.payroll_id" class="form-control"
                :disabled="dialog_data.payroll_client && userData.role == 'Encoder'">

                <option v-for="(p, i) in payrolls" :key="i" :value="p.id">
                  {{ p.title }} | {{ p.amount }}
                </option>
              </select>
            </div>
            <div v-else>
              NO ACTIVE PAYROLLS
            </div>

          </div>

        </div>
      </div>

      <!-- <div class="card mt-2" v-if="requirements">
        <div class="card-title">Requirements <span></span></div>

        <div class="card-body">
          <div
            v-if="!isEmpty(validationErrors.assistance)"
            class="alert alert-danger"
          >
            <ul>
              <li v-for="(e, i) in validationErrors.assistance" :key="i">
                {{ e[0] }}
              </li>
            </ul>
          </div>
          <ul
            v-if="requirements[0]"
            style="list-style: decimal; display: block"
            class="ml-3 list-group list-group-flush"
          >
            <li
              v-for="(r, i) in requirements[0].requirements.filter(
                (i) => i.is_required == 1
              )"
              :key="r.id"
              class="list-group-item"
            >
              <p>
                {{ r.name }}
                <span color="red"> {{ r.is_required ? "*" : "" }}</span>
              </p>

              <div class="alert alert-primary">
                <input
                  type="file"
                  @input="onFileChange(i, $event)"
                  accept="application/pdf,image/jpeg,image/png"
                  :required="r.is_required"
                />
              </div>
            </li>
          </ul>
        </div>
      </div>-->

      <div class="text-center col-md-12" style="padding: 10px 0px">
        <button type="submit" class="btn btn-primary btn-lg btn-lg btn-block" :disabled="submit">
          SUBMIT
        </button>
      </div>
    </div>
  </form>
</template>

<style>
.card-title {
  background: #001997;
  color: #fff;
  padding: 1pc;
}
</style>

<script>
import { debounce, cloneDeep } from 'lodash'
export default {
  props: ["dialog_data", "getList", "userData", "setDialogCreate"],
  data() {
    return {
      form: {
        aics_type_id: 8,
        mode_of_admission: "Referral",
        assessment: "The family is identified as indigent member of the barangay. Family's Income is below poverty threshold. Thus, this prompted client to seek government intervention."
      },
      assistance_types: {},
      psgc: {},
      regions: {},
      /*client_provinces: {},
      client_cities: {},
      client_barangays: {},
      client_region_selector: {},
      client_province_selector: {},
      client_city_selector: {},*/
      beneficiary_provinces: {},
      beneficiary_cities: {},
      beneficiary_barangays: {},
      beneficiary_region_selector: {},
      beneficiary_province_selector: {},
      beneficiary_city_selector: {},
      is_beneficiary: true,
      requirements: {},
      errors: {},
      validationErrors: {
        client: {},
        beneficiary: {},
        assistance: {},
      },
      max_date: new Date().toISOString().split("T")[0],

      categories: [],
      subcategories: [],
      payrolls: [],
      submit: false,
    };
  },
  watch: {
    "form.subcategory_id": function (newVal, oldVal) {
      if (newVal != 8) {
        this.form.subcategory_others = "";
      }
    },

    "form.psgc_id": function (newVal, oldVal) {
      console.log(newVal);
    },

    dialog_data(e) {
      this.resetForm();
      this.form = e;
      this.form.aics_type_id = 8;
      this.form.mode_of_admission = "Referral";
      this.form.assessment = this.form.assessment? this.form.assessment: "The family is identified as indigent member of the barangay. Family's Income is below poverty threshold. Thus, this prompted client to seek government intervention.";
      if (this.form.payroll_client) { this.form.payroll_id = this.form.payroll_client.payroll_id; }
      this.calculateAge();
      this.beneficiary_region_selector = this.regions[this.dialog_data.psgc.region_name];
      this.getBeneficiaryPsgc();
      this.beneficiary_province_selector = this.beneficiary_provinces[this.dialog_data.psgc.province_name];

    },
    beneficiary_region_selector(newVal, oldVal) {
      ((this.beneficiary_provinces = {}),
        (this.beneficiary_cities = {}),
        (this.beneficiary_barangays = {})),
        (this.beneficiary_provinces = this.groupByKey(newVal, "province_name"));
    },

    beneficiary_province_selector(newVal, oldVal) {
      ((this.beneficiary_cities = {}), (this.beneficiary_barangays = {})),
        (this.beneficiary_cities = this.groupByKey(newVal, "city_name"));

      if (this.dialog_data.psgc) {
        this.beneficiary_city_selector =
          this.beneficiary_cities[this.dialog_data.psgc.city_name];
      }
    },

    beneficiary_city_selector(newVal, oldVal) {
      (this.beneficiary_barangays = {}),
        (this.beneficiary_barangays = this.groupByKey(newVal, "brgy_name"));
    },
    /*client_region_selector(newVal, oldVal) {
      ((this.client_provinces = {}),
      (this.client_cities = {}),
      (this.client_barangays = {})),
        (this.client_provinces = this.groupByKey(newVal, "province_name"));
    },

    client_province_selector(newVal, oldVal) {
      ((this.client_cities = {}), (this.client_barangays = {})),
        (this.client_cities = this.groupByKey(newVal, "city_name"));
    },

    client_city_selector(newVal, oldVal) {
      (this.client_barangays = {}),
        (this.client_barangays = this.groupByKey(newVal, "brgy_name"));
    },*/
    /*is_beneficiary(newVal, oldVal) {
      console.log(newVal);
      if (newVal === true) {
        this.form.client = this.form.beneficiary;
        this.form.client.rel_beneficiary = "Myself";
      } else {
        this.form.client = {};
        this.form.client.rel_beneficiary = "";
      }
    },*/
  },

  methods: {
    submitForm: debounce(function () {
      this.submit = true;
      if (this.form.payroll_id) {
        axios
          .post(route("api.client.update", this.dialog_data.id), this.form)
          .then((response) => {
            this.submit = false;
            console.log(response.data);
            this.setDialogCreate(false);
            alert(`${response.data.message}! Client number: ${response.data.client.payroll_client.sequence}`);
            this.getList();
            /*if (response.data.aics_beneficiary_id) {
              alert(
                "Naisumite na ang Form. Isang kinatawan ng DSWD ang makikipag-ugnayan sa iyo, mayat-maya. \nForm submitted. A DSWD representative will contact you shortly."
              );
              this.resetForm();
            }*/
          })
          .catch((error) => {
            this.submit = false;
            console.log(error);
            if (error.response && error.response.status == 422) {
              alert("Kumpletohin ang form. \nPlease complete the form.");
              this.validationErrors = error.response.data.errors;
            }
          });
      } else {
        alert(
          "Pumili ng Payroll!"
        );
      }
    }, 250),
    resetForm() {
      this.form = {
        aics_type_id: 8,
        mode_of_admission: "Referral",
        assessment: "The family is identified as indigent member of the barangay. Family's Income is below poverty threshold. Thus, this prompted client to seek government intervention."
    
      };
      this.beneficiary_provinces = {};
      this.beneficiary_cities = {};
      this.beneficiary_barangays = {};
      this.beneficiary_region_selector = {};
      this.beneficiary_province_selector = {};
      this.beneficiary_city_selector = {};
    },

    onFileChange(i, e) {
      this.form.documents[i] = e.target.files[0];
    },

    getRequirements() {
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

    getBeneficiaryPsgc() {
      axios
        .get(route("api.psgc.show", "brgy"), {
          params: {
            field: "region_psgc",
            value: this.beneficiary_region_selector[0].region_psgc,
          },
        })
        .then((response) => {
          this.beneficiary_psgc = response.data;
          this.beneficiary_provinces = this.groupByKey(
            this.beneficiary_psgc,
            "province_name"
          );

          if (this.dialog_data.psgc) {
            this.beneficiary_province_selector =
              this.beneficiary_provinces[this.dialog_data.psgc.province_name];
            this.beneficiary_city_selector =
              this.beneficiary_cities[this.dialog_data.psgc.city_name];
          }
        });
    },

    getClientPsgc() {
      axios
        .get(route("api.psgc.show", "brgy"), {
          params: {
            field: "region_psgc",
            value: this.client_region_selector[0].region_psgc,
          },
        })
        .then((response) => {
          this.client_psgc = response.data;
          this.client_provinces = this.groupByKey(
            this.client_psgc,
            "province_name"
          );
        });
    },

    calculateAge: function () {
      if (!this.form.birth_date) {
        this.form.age = 0;
      } else {
        let currentDate = new Date();
        let birthDate = new Date(this.form.birth_date);
        let difference = currentDate - birthDate;
        let age = Math.floor(difference / 31557600000);
        this.form.age = age;
      }
    },

    groupByKey(array, key) {


      return array.reduce((hash, obj) => {
        if (obj[key] === undefined) return hash;
        return Object.assign(hash, {
          [obj[key]]: (hash[obj[key]] || []).concat(obj),
        });
      }, {});

    },

    isEmpty(value) {
      return _.isEmpty(value);
    },

    getAssistanceTypes() {
      axios.get(route("api.aics.assistance-types")).then((response) => {
        this.assistance_types = response.data;
      });
    },
    getRegions() {
      axios.get(route("api.psgc.show", "region")).then((response) => {
        this.regions = this.groupByKey(response.data, "region_name");
        if (this.dialog_data.psgc) {
          this.beneficiary_region_selector =
            this.regions[this.dialog_data.psgc.region_name];
          this.getBeneficiaryPsgc();
        }
      });
    },
    getCategories() {
      axios.get(route("api.categories")).then((response) => {
        this.categories = response.data.categories;
        this.subcategories = response.data.subcategory;
      });
    },
    getPayrolls() {
      axios
        .get(route("api.active_payrolls"))
        .then((response) => {
          this.payrolls = response.data;
        })
        .catch((error) => console.log(error));
    },
  },
  mounted() {
    this.form = cloneDeep(this.dialog_data);
    if (this.form.payroll_client) {
      this.form.payroll_id = this.form.payroll_client.payroll_id;
    }
    this.form.aics_type_id = 8;
    this.form.mode_of_admission = "Referral";
    this.form.assessment = this.form.assessment? this.form.assessment: "The family is identified as indigent member of the barangay. Family's Income is below poverty threshold. Thus, this prompted client to seek government intervention.";
    this.calculateAge();
    this.getAssistanceTypes();
    this.getRegions();
    this.getCategories();
    this.getPayrolls();

  },
};
</script>

<style></style>


