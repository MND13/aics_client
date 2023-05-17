<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12" v-if="hasRoles(['social-worker']) && gis_data.status == 'Serving'">
        <v-spacer></v-spacer>
        <v-btn dark @click="PrintGIS()">Print GIS</v-btn>
        <v-btn dark>Print CAV</v-btn>
        <v-btn dark>Print COE</v-btn>
        <v-btn dark>Print GL</v-btn>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3">

        <div class="card">
          <div class="card-title">SUBMISSION DATA</div>
          <div class="card-body">

            <table class="table table-bordered mt-2">

              <tbody>
                <tr>
                  <td> <label for=""> Status:</label> {{ gis_data.status }} </td>
                </tr>
                <tr>
                  <td> <label for="">Date Submitted: </label> {{ gis_data.created_at | formatDate }} </td>
                </tr>
                <tr>
                  <td> <label for=""> Requested Schedule:</label> {{ gis_data.schedule | formatDate }} </td>
                </tr>
                <tr>
                  <td v-if="gis_data.office"><label for=""> Office: </label> {{ gis_data.office.name }} <br>
                    {{ gis_data.office.address }}</td>
                </tr>
              </tbody>
            </table>


          </div>
        </div>

        <div v-if="gis_data.id">






          <table class="table table-bordered mt-2">
            <tbody v-if="gis_data.aics_documents">
              <tr class="card-title">
                <td><label for="">Attachments:</label></td>
              </tr>
              <tr v-for="(e, i) in gis_data.aics_documents" :key="i">
                <td> <a :href="e.file_directory" target="_blank">
                    {{ e.requirement.name }}</a></td>
              </tr>
            </tbody>
          </table>



        </div>
        <div v-else>
          <v-skeleton-loader type="article"></v-skeleton-loader>

        </div>

      </div>
      <div class="col-md-9">
        <form @submit.prevent="submitForm" enctype="multipart/form-data" id="GIS">

          <div class="card">
            <div class="card-title">
              NAIS HINGIIN NA TULONG (Assistance Requested)
            </div>
            <div class="card-body">
              <p v-if="gis_data.aics_type">{{ gis_data.aics_type.name }} </p>

            </div>
          </div>

          <div class="card mt-2" v-if="gis_data.aics_client">
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
                    {{ gis_data.aics_client.last_name }}
                  </div>

                  <div class="col-md-3">
                    <label for="first_name">
                      Unang Pangalan
                      <small>(First name) <span color="red"></span></small></label>
                    {{ gis_data.aics_client.first_name }}
                  </div>

                  <div class="col-md-3">
                    <label for="middle_name">Gitnang Pangalan <small>(Middle name)</small></label>
                    {{ gis_data.aics_client.middle_name }}



                  </div>

                  <div class="col-md-3">
                    <label for="ext_name">Ext <small>(Sr.,Jr., II, III)</small></label><br />
                    {{ gis_data.aics_client.ext_name }}
                  </div>
                </div>

                <div class="row mt-2">
                  <div class="col-md-12">
                    <label for="street_number">House No./Street/Purok
                      <small>(Ex. 123 Sun St.)</small>

                    </label>
                    {{ gis_data.aics_client.street_number }}
                  </div>
                </div>
                <div class="row mt-2">
                  <div class="col-md-3">
                    <label>Region <small>(Ex. NCR)</small>

                    </label>

                    {{ gis_data.aics_client.psgc.region_name }}

                  </div>

                  <div class="col-md-3">
                    <label>Province/District <small>(Ex. Dis. III)</small>

                    </label>

                    {{ gis_data.aics_client.psgc.province_name }}


                  </div>

                  <div class="col-md-3">
                    <label>
                      City/Municipality <small>(Ex. Quezon City)</small>

                    </label>

                    {{ gis_data.aics_client.psgc.city_name }}


                  </div>

                  <div class="col-md-3">
                    <label>Barangay
                      <small>(Ex. Batasan Hills)</small>

                    </label>

                    {{ gis_data.aics_client.psgc.brgy_name }}


                  </div>
                </div>

                <div class="row mt-2">
                  <div class="col-md-3">
                    <label for="mobile_number">Telepono <small>(Mobile Number)</small></label>
                    {{ gis_data.aics_client.mobile_number }}
                  </div>

                  <div class="col-md-3">
                    <label for="birth_date">Kapanganakan <small>(Birthdate)</small></label>
                    {{ gis_data.aics_client.birth_date }}
                  </div>

                  <div class="col-md-3">
                    <label for="age">Edad <small>(Age)</small></label>
                    {{ gis_data.age }}
                  </div>

                  <div class="col-md-3">
                    <label for="gender">Kasarian <small>(gender)</small></label>


                    {{ gis_data.aics_client.gender }}


                  </div>
                </div>

                <div class="row mt-2">
                  <div class="col-md-3">
                    <label for="occupation">Trabaho <small> (Occupation)</small> </label>

                    {{ gis_data.occupation }}


                  </div>
                  <div class="col-md-3">
                    <label for="monthly_salary">Buwanang Kita <small> (Monthly Salary) </small>
                    </label>
                    {{ gis_data.monthly_salary }}
                  </div>

                  <div class="col-md-3">
                    <label for="civil_status">Civil Status</label>
                    {{ gis_data.civil_status }}
                  </div>


                </div>
              </div>
            </div>
          </div>


          <div class="row" v-if="hasRoles(['social-worker', 'admin', 'super-admin']) && gis_data.status == 'Verified'">
            <div class="col-md-4">
              <div class="card">
                <div class="card-title">Beneficiary Category</div>
                <div class="card-body">
                  Target Sector

                  <select v-model="form.category_id" class="form-control"
                    :class="{ 'is-invalid': validationErrors.category_id }">
                    <option></option>
                    <option v-for="(e, i) in categories" :key="i" :value="e.id">
                      {{ e.category }}
                    </option>
                  </select>

                  <div class="invalid-feedback" v-if="validationErrors.category_id">
                    <ul>
                      <li v-for="(e, i) in validationErrors.category_id" :key="i">{{ e }}</li>
                    </ul>
                  </div>

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
                  <select id="" class="form-control" v-model="selected_assessment_option">
                    <option :value="o" v-for="(o, i) in assessment_options" :key="i">{{ o.option }}</option>
                  </select>
                  Social Worker's Assessment
                  <textarea id="" v-model="form.assessment" class="form-control" cols="30" rows="10"
                    :class="{ 'is-invalid': validationErrors.mode_of_admission }"></textarea>
                  <div class="invalid-feedback" v-if="validationErrors.assessment">
                    <ul>
                      <li v-for="(e, i) in validationErrors.assessment" :key="i">{{ e }}</li>
                    </ul>
                  </div>


                </div>

              </div>
            </div>
          </div>


          <br />

          <div class="card mt-2"  v-if="hasRoles(['social-worker', 'admin', 'super-admin']) && gis_data.status == 'Verified'">
            <div class="card-title">
              ASSESSMENT INFORMATION

            </div>
            <div class="card-body">

              <div class="row">

                <div class="col-md-3">
                  Mode of Admission
                  <select id="mode_of_admission" v-model="form.mode_of_admission" class="form-control"
                    :class="{ 'is-invalid': validationErrors.mode_of_admission }">
                    <option :value="e" v-for="(e, i) in ['Walk-in']" :key="i">
                      {{ e }}
                    </option>
                  </select>

                  <div class="invalid-feedback" v-if="validationErrors.mode_of_admission">

                    <ul>
                      <li v-for="(e, i) in validationErrors.mode_of_admission" :key="i">{{ e }}</li>
                    </ul>
                  </div>


                </div>
              </div>
              <div class="row">

                <div class="col-md-6">
                  Purpose
                  <input type="text" v-model="form.purpose" class="form-control "
                    :class="{ 'is-invalid': validationErrors.purpose }">

                  <div class="invalid-feedback" v-if="validationErrors.purpose">

                    <ul>
                      <li v-for="(e, i) in validationErrors.purpose" :key="i">{{ e }}</li>
                    </ul>
                  </div>

                </div>

                <div class="col-md-2">
                  Amount
                  <input type="text" v-model="form.amount" class="form-control"
                    :class="{ 'is-invalid': validationErrors.amount }">

                  <div class="invalid-feedback" v-if="validationErrors.amount">
                    <ul>
                      <li v-for="(e, i) in validationErrors.amount" :key="i">{{ e }}</li>
                    </ul>
                  </div>
                </div>

                <div class="col-md-2">
                  Mode of Assistance
                  <select name="" id="" class="form-control" v-model="form.mode_of_assistance"
                    :class="{ 'is-invalid': validationErrors.mode_of_assistance }">
                    <option :value="e" v-for="e in ['CAV', 'GL']" :key="e">{{ e }}</option>
                  </select>

                  <div class="invalid-feedback" v-if="validationErrors.mode_of_assistance">
                    <ul>
                      <li v-for="(e, i) in validationErrors.mode_of_assistance" :key="i">{{ e }}</li>
                    </ul>
                  </div>

                </div>

                <div class="col-md-2">
                  Fund Source
                  <!--<input type="text" v-model="form.fund_source" class="form-control"
              :class="{ 'is-invalid': validationErrors.fund_source }">-->

                  <select v-model="form.fund_source" name="" id="" class="form-control"
                    :class="{ 'is-invalid': validationErrors.fund_source }">
                    <option :value="fund_source.id" v-for="fund_source in fund_sources" :key="fund_source.id">{{
                      fund_source.name }}</option>
                  </select>

                  <div class="invalid-feedback" v-if="validationErrors.fund_source">
                    <ul>
                      <li v-for="(e, i) in validationErrors.fund_source" :key="i">{{ e }}</li>
                    </ul>
                  </div>

                </div>
              </div>

              <div class="row">

                <div class="col-md-6">
                  Interviewed by
                  <input type="text" class="form-control" v-model="form.interviewed_by"
                    :class="{ 'is-invalid': validationErrors.interviewed_by }" />

                  <div class="invalid-feedback" v-if="validationErrors.interviewed_by">
                    <ul>
                      <li v-for="(e, i) in validationErrors.interviewed_by" :key="i">{{ e }}</li>
                    </ul>
                  </div>

                </div>
                <div class="col-md-6">
                  Approved by
                  <input type="text" class="form-control" v-model="form.approved_by"
                    :class="{ 'is-invalid': validationErrors.approved_by }" />
                  <div class="invalid-feedback" v-if="validationErrors.approved_by">
                    <ul>
                      <li v-for="(e, i) in validationErrors.approved_by" :key="i">{{ e }}</li>
                    </ul>
                  </div>
                </div>
              </div>



            </div>



          </div>


          <div class="text-center col-md-12" style="padding: 10px 0px">
            <v-btn type="submit" large class="--white-text" color="primary" :disabled="submit" v-if="hasRoles(['encoder', 'social-worker']) && gis_data.status == 'Pending'">
              VERIFY
            </v-btn>
            <v-btn type="submit" large class="--white-text" color="primary" :disabled="submit" v-if="hasRoles(['social-worker']) && gis_data.status == 'Verified'">
              SUBMIT
            </v-btn>
            <!-- class="btn btn-primary btn-lg btn-lg btn-block"-->

            <v-btn v-if="gis_data.status == 'Pending'" large class="--white-text" color="error"
              @click="dialog_reject = true" :disabled="submit">
              REJECT
            </v-btn>

            <v-btn v-if="gis_data.status == 'Serving'" :to="{ name: 'coe', params: { 'uuid': gis_data.uuid } }" outlined
              large class="--white-text" color="primary" :disabled="submit">
              Certificate Of Eligibility (COE)
            </v-btn>
          </div>

          <v-dialog v-model="dialog_reject" width="50%">
            <v-card>
              <v-card-title>Reject GIS</v-card-title>
              <v-card-text>
                Reason
                <select class="form-control">
                  <option value="" v-for="(e, i) in ['Incomplete Documents']" :key="i">{{ e }}</option>
                </select>
                Message
                <textarea v-model="rejectform.message" id="" cols="10" rows="5" class="form-control"></textarea>

                <v-btn @click="RejectGIS" dark color="red">
                  Reject
                </v-btn>
              </v-card-text>

            </v-card>
          </v-dialog>







        </form>
      </div>

    </div>
  </div>
</template>

<style>
.card-title {
  color: #ffffff;
  padding: 1pc;
  background: #283593;

  border-bottom: solid 1px #d2d2d2;
  font-size: 10pt;
}
</style>

<script>
import userMixin from './../Mixin/userMixin.js'
import authMixin from './../Mixin/authMixin.js'

import { debounce, cloneDeep } from 'lodash'
export default {
  mixins: [userMixin, authMixin],
  // props: ["gis_data", "getList", "userData", "setDialogCreate"],
  data() {
    return {
      gis_data: {},
      form: {
        mode_of_admission: "Walk-in"
      },
      assistance_types: {},
      psgc: {},
      regions: {},

      errors: {},
      validationErrors: {},
      max_date: new Date().toISOString().split("T")[0],

      categories: [],
      subcategories: [],

      submit: false,
      assessment_options: [],
      selected_assessment_option: {},
      dialog_reject: false,
      rejectform: {},
      fund_sources: [],
      formType: "Create",

    };
  },
  watch: {
    "form.subcategory_id": function (newVal, oldVal) {
      if (newVal != 8) {
        this.form.subcategory_others = "";
      }
    },

    selected_assessment_option(val) {
      //console.log(val);
      this.form.assessment = val.template;

    }

  },

  methods: {
    submitForm: debounce(function () {
      this.submit = true;
      this.form.gis_id = this.gis_data.id; // FOREIGN KEY

      if (this.form.id) { //UPDATE
        this.updateAssessment();
      } else { //CREATE 
        if(this.gis_data.status == "Pending"){
          this.verifyGis();
        }else{
          this.createAssessment();
        }
      }

    }, 250),

    updateAssessment(){
      axios
      .post(route("api.assessment.update", this.form.id), this.form)
      .then((response) => {
        this.submit = false;
        alert(response.data.message);
      })
      .catch((error) => {
        this.submit = false;
        if (error.response && error.response.status == 422) {
          alert("Kumpletohin ang form. \nPlease complete the form.");
          this.validationErrors = error.response.data.errors[0];
        }
      });
    },

    createAssessment(){
      axios
      .post(route("api.assessment.create"), this.form)
      .then((response) => {
        this.submit = false;
        this.getGISData();
        alert(response.data.message);
      })
      .catch((error) => {
        this.submit = false;
        if (error.response && error.response.status == 422) {
          alert("Kumpletohin ang form. \nPlease complete the form.");
          this.validationErrors = error.response.data.errors[0];
        }
      });
    },

    verifyGis(){
      axios.patch(route("assistances.update", { "assistance": this.gis_data.uuid }), { "uuid": this.gis_data.uuid, status: "Verified" }).then(response => {

      alert(response.data.message);
      this.dialog_reject = false;
      this.$router.push({ path: '/' })

      }).catch(error => console.log(error));
    },

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
    },
    RejectGIS() {


      axios.patch(route("assistances.update", { "assistance": this.gis_data.uuid }), { "uuid": this.gis_data.uuid, status: "Rejected" }).then(response => {

        alert(response.data.message);
        this.dialog_reject = false;
        this.$router.push({ path: '/' })

      }).catch(error => console.log(error));
    },

    getGISData() {
      // console.log({ "assistance": this.$route.params.uuid });
      axios.get(route("assistances.show", { "assistance": this.$route.params.uuid }),)
        .then(response => {
          // console.log(response.data);
          this.gis_data = response.data;
          if (this.gis_data.assessment) { this.form = this.gis_data.assessment; }
        })
    },
    getFundSrc() {

      axios.get(route("api.fund_src")).then(response => {
        this.fund_sources = response.data;


      }).catch(error => console.log(error))
    },
    PrintGIS() {
      window.open("/gis/print/" + this.gis_data.uuid)
    }


  },
  mounted() {

    this.getGISData();
    this.getAssistanceTypes();
    this.getCategories();
    this.getAssessmentOpts();
    this.getFundSrc();


  },
};
</script>

<style></style>


