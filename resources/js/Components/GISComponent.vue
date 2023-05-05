<template>
  <form @submit.prevent="submitForm" enctype="multipart/form-data" id="GIS">
    <div class="container-fluid">

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

          <h2 style="font-family: 'Arial black', sans-serif; margin-bottom: 0px">
            GENERAL INTAKE SHEET<br />
          </h2>

        </div>
      </div>


      <div class="card mt-2">
        <div class="card-title">
          NAIS HINGIIN NA TULONG (Assistance Requested)
          <span color="red"></span>
        </div>
        <div class="card-body">
          <div class="col-md-12">
            <b>{{ dialog_data.aics_type.name }}</b>
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
                  Apelyido <small>(Last name) <span color="red"></span></small> <br>
                </label>
                {{ dialog_data.aics_client.last_name }}
              </div>

              <div class="col-md-3">
                <label for="first_name">
                  Unang Pangalan
                  <small>(First name) <span color="red"></span></small></label>
                {{ dialog_data.aics_client.first_name }}
              </div>

              <div class="col-md-3">
                <label for="middle_name">Gitnang Pangalan <small>(Middle name)</small></label>
                {{ dialog_data.aics_client.middle_name }}



              </div>

              <div class="col-md-3">
                <label for="ext_name">Ext <small>(Sr.,Jr., II, III)</small></label><br />
                {{ dialog_data.aics_client.ext_name }}
              </div>
            </div>

            <div class="row mt-2">
              <div class="col-md-12">
                <label for="street_number">House No./Street/Purok
                  <small>(Ex. 123 Sun St.)</small>
                  <span color="red"></span>
                </label>
                {{ dialog_data.aics_client.street_number }}
              </div>
            </div>
            <div class="row mt-2">
              <div class="col-md-3">
                <label>Region <small>(Ex. NCR)</small>
                  <span color="red"></span>
                </label>

                {{ dialog_data.aics_client.psgc.region_name }}

              </div>

              <div class="col-md-3">
                <label>Province/District <small>(Ex. Dis. III)</small>
                  <span color="red"></span>
                </label>

                {{ dialog_data.aics_client.psgc.province_name }}


              </div>

              <div class="col-md-3">
                <label>
                  City/Municipality <small>(Ex. Quezon City)</small>
                  <span color="red"></span>
                </label>

                {{ dialog_data.aics_client.psgc.city_name }}


              </div>

              <div class="col-md-3">
                <label>Barangay
                  <small>(Ex. Batasan Hills)</small>
                  <span style="color:red;">*</span>
                </label>

                {{ dialog_data.aics_client.psgc.brgy_name }}


              </div>
            </div>

            <div class="row mt-2">
              <div class="col-md-3">
                <label for="mobile_number">Telepono <small>(Mobile Number)</small></label>
                {{ dialog_data.aics_client.mobile_number }}
              </div>

              <div class="col-md-3">
                <label for="birth_date">Kapanganakan <small>(Birthdate)</small></label>
                {{ dialog_data.aics_client.birth_date }}
              </div>

              <div class="col-md-3">
                <label for="age">Edad <small>(Age)</small></label>
                {{ dialog_data.age }}
              </div>

              <div class="col-md-3">
                <label for="gender">Kasarian <small>(gender)</small></label>


                {{ dialog_data.aics_client.gender }}


              </div>
            </div>

            <div class="row mt-2">
              <div class="col-md-3">
                <label for="occupation">Trabaho <small> (Occupation)</small> </label>

                {{ dialog_data.occupation }}


              </div>
              <div class="col-md-3">
                <label for="monthly_salary">Buwanang Kita <small> (Monthly Salary) </small>
                  <span color="red"></span></label>
                {{ dialog_data.monthly_salary }}
              </div>

              <div class="col-md-3">
                <label for="civil_status">Civil Status</label>
                {{ dialog_data.civil_status }}
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
              Assessment Option
              <select name="" id="" class="form-control" v-model="selected_assessment_option">
                <option :value="o" v-for="(o, i) in assessment_options" :key="i">{{ o.option }}</option>
              </select>
              Social Worker's Assessment
              <textarea name="" id="" v-model="form.assessment" class="form-control" cols="30" rows="10"></textarea>


            </div>
          </div>
        </div>
      </div>

      <br />

      <div class="card mt-2">
        <div class="card-title">
          ASSESSMENT INFORMATION
          <span color="red"></span>
        </div>
        <div class="card-body">

          <div class="row">

            <div class="col-md-3">
              Mode of Admission
              <select id="mode_of_admission" v-model="form.mode_of_admission" class="form-control">
                <option :value="e" v-for="(e, i) in ['Walk-in']" :key="i">
                  {{ e }}
                </option>
              </select>


            </div>
          </div>
          <div class="row">

            <div class="col-md-6">
              Purpose
              <input type="text" v-model="form.purpose" class="form-control">
            </div>

            <div class="col-md-2">
              Amount
              <input type="text" v-model="form.amount" class="form-control">
            </div>

            <div class="col-md-2">
              Mode of Assistance

              <select name="" id="" class="form-control" v-model="form.mode_of_assistance">
                <option :value="e" v-for="e in ['CAV', 'GL']" :key="e">{{ e }}</option>
              </select>
            </div>

            <div class="col-md-2">
              Fund Source
              <input type="text" v-model="form.fund_source" class="form-control">
            </div>
          </div>

          <div class="row">

            <div class="col-md-6">
              Interviewed by
              <input type="text" class="form-control" v-model="form.interviewed_by" />
            </div>
            <div class="col-md-6">
              Approved by
              <input type="text" class="form-control" v-model="form.approved_by" />

            </div>
          </div>



        </div>



      </div>


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
      form: {},
      assistance_types: {},
      psgc: {},
      regions: {},

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
      assessment_options: [],
      selected_assessment_option: {}
    };
  },
  watch: {
    "form.subcategory_id": function (newVal, oldVal) {
      if (newVal != 8) {
        this.form.subcategory_others = "";
      }
    },
    dialog_data(e) {
      this.resetForm();
      this.form.assistance_id = e.id;
      //this.form = e;

    },
    selected_assessment_option(val) {
      //console.log(val);
      this.form.assessment = val.template;

    }
    /*beneficiary_region_selector(newVal, oldVal) {
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
    },*/
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

      axios
        .post(route("api.assessment.create", this.dialog_data.id), this.form)
        .then((response) => {
          this.submit = false;
          console.log(response.data);
          this.setDialogCreate(false);
          // alert(`${response.data.message}! Client number: ${response.data.client.payroll_client.sequence}`);
          this.getList();

        })
        .catch((error) => {
          this.submit = false;
          console.log(error);
          if (error.response && error.response.status == 422) {
            alert("Kumpletohin ang form. \nPlease complete the form.");
            this.validationErrors = error.response.data.errors;
          }
        });

    }, 250),
    resetForm() {
      this.form = {};
    },

    isEmpty(value) {
      return _.isEmpty(value);
    },

    getAssistanceTypes() {
      axios.get(route("api.aics.assistance-types")).then((response) => {
        this.assistance_types = response.data;
      });
    },

    getCategories() {
      axios.get(route("api.categories")).then((response) => {
        this.categories = response.data.categories;
        this.subcategories = response.data.subcategory;
      });
    },
    getAssessmentOpts() {
      axios.get(route("api.assessment_opts")).then((response) => {
        //console.log( response.data);
        this.assessment_options = response.data;
      });
    }
  },
  mounted() {
    //this.form = cloneDeep(this.dialog_data);
    this.form.assistance_id = this.dialog_data.id;
    this.getAssistanceTypes();
    this.getCategories();
    this.getAssessmentOpts();



  },
};
</script>

<style></style>


