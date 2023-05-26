<template>
  <div class="container">
    <div class="col-md-12 ">

      <v-card>

{{ uuid }} <br>
{{ coe_id }}

        <v-card-title>Certificate Of Eligibility (COE)</v-card-title>
        <v-card-text>

          <v-form ref="form">
            <v-text-field v-model="formData.sdo" label="Special Disbursing Officer"
              :error-messages="formErrors.sdo"></v-text-field>


            <div class="row">
              <div class="col-md-12">
                RECORDS OF THE CASE SUCH AS THE FOLLOWING ARE CONFIDENTIALLY FILED AT THE CRISIS INTERVENTION SECTION
              </div>
              <div class="col-md-12" style="column-count: 3;">

                <template v-for="(e, i) in records_opts">
                  <v-checkbox v-model="formData.records" :label="e" :value="e" class="shrink mr-0 mt-0"></v-checkbox>
                </template>
              </div>

            </div>

            <div class="text-center">

              <v-btn color="primary" class="mr-4" @click="submitForm" :disabled="submit"
                v-if="hasRoles(['super-admin', 'admin','social-worker'])"> <span>{{ formType }} COE </span>
              </v-btn>

              <v-btn color="error" class="mr-4" @click="resetForm">
                <span>Cancel</span>
              </v-btn>



            </div>

          </v-form>
        </v-card-text>

      </v-card>

    </div>
  </div>
</template>

<script>
import userMixin from './../Mixin/userMixin.js'
import { debounce, cloneDeep, isEmpty } from 'lodash'
import authMixin from './../Mixin/authMixin.js'

export default {
  props: ["uuid","coe_id"],
  mixins: [userMixin, authMixin],
  data() {
    return {
      formData: {
        sdo: "",
        records: [],
      },
      formErrors: [],
      records_opts: [
        "Referral Letter",
        "Social Case Study Report",
        "Justification",
        "Valid ID Presented",
        "4PS DSWD ID",
        "Medical Certificate/Abstract",
        "Prescriptions",
        "Statement of Account",
        "Treatment Protocol",
        "Quotation",
        "Discharge Summary",
        "Laboratory Request",
        "Charge Slip",
        "Funeral Contract",
        "Death Certificate",
        "Death Summary",      
        " Others",
      ],
      formType: "Create",
      selected: [],
      submit: false,
      id_options: ["School-ID",
        "Voter's ID",
        "PhilHealth ID",
        "Postal ID",
        "Driver's License",
        "Senior Citizen ID",
        "OFW ID",
        "Philippine Passport",
        "SSS UMID Card",
        "TIN Card",
        "PRC ID"],

    }


  },
  methods: {
    resetForm() {
      this.formData = {};
    },
    submitForm: debounce(function () {
      if (this.formType == "Update") {
        this.updateCOE()
      } else {
        this.createCOE();
      }
    }, 250),
    createCOE() {
      this.submit = true;
      this.formData.uuid = this.uuid;
      console.log(this.formData);
      axios.post(route('api.coe.store'), this.formData)
        .then(res => {
          this.submit = false;
          this.resetForm();
          alert('COE has been created');
        })
        .catch(err => {
          this.submit = false;
          this.formErrors = err.response.data.errors
        })


    },
    getCOE()
    {
      axios.get(route("api.coe",this.uuid)).then(response=>{
        console.log(response.data);
      }).catch(err=>console.log(err));
    }

  },
  mounted() {
   
  }

}
</script>

<style></style>