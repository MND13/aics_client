<template>
  <KeepAlive>

    <v-card flat outlined tile class="m-2 p-5">
      <v-card-title class="text-center my-0 py-0">
        <div class="row justify-content-center my-0">
          <div class="col-md-12 my-0 py-0">

            <img style="max-height:64px; max-width:300px" src="/images/DSWD-DVO-LOGO.png" contain alt="DSWD" ref="logo" />
            <br>
            <b>Welcome to Assistance to Individuals in Crisis (AICS)! </b>
          </div>
        </div>
      </v-card-title>
      <v-card-text>

        <div class="row mb-2 text-center">
          <p> Register to create your account & immediately request for Medical, Educational, Funeral, Food,
            Transportation or Other Cash Assistance.</p>
          <br>
        </div>

        <div class="row justify-content-center">
          <div class="col-md-12  " v-if="formErrors && formErrors.full_name">
            <v-alert v-for="(e, i) in formErrors.full_name " :key="i" type="error">
              {{ e }}
            </v-alert>
          </div>
        </div>


        <v-form ref="form" enctype="multipart/form-data">
          <input type="hidden" name="_token" :value="csrf">
          <div class="row justify-content-center">
            <div class="col-md-12 "> <b> FULL NAME (ACCORDING TO PRESENTED VALID ID) </b></div>
            <hr>
          </div>
          <div class="row justify-content-center g-2">

            <div class="col-md-3">
              <v-text-field v-model="form.first_name" label="First Name*" outlined dense
                :error-messages="formErrors.first_name" required></v-text-field>
            </div>

            <div class="col-md-3">
              <v-text-field class="mb-0" v-model="form.middle_name" label="Middle Name" outlined dense
                :error-messages="formErrors.middle_name"></v-text-field>
              <v-checkbox class="mt-0" label="I have No Middle Name (NMN)" v-model="nmn"></v-checkbox>
            </div>

            <div class="col-md-3">
              <v-text-field v-model="form.last_name" label="Last Name*" outlined dense
                :error-messages="formErrors.last_name" required></v-text-field>
            </div>

            <div class="col-md-3">
              <v-select v-model="form.ext_name" label="Ext Name" outlined dense :items="suffixes" item-value="id"
                item-text="name" :error-messages="formErrors.ext_name" clearable>
              </v-select>
            </div>
          </div>


          <div class="row justify-content-center">
            <div class="col-md-12 "> <b> ADDRESS </b> </div>
            <hr>
          </div>

          <div class="row justify-content-center g-2">
            <div class="col-md-12">

              <v-text-field v-model="form.street_number" label="House No./Street/Purok*" outlined dense
                :error-messages="formErrors.street_number" required></v-text-field>

            </div>
          </div>

          <div class="row justify-content-center g-2">

            <div class="col-md-3">
              <v-select label="Region" v-model="region" outlined dense :items="['XI']">
              </v-select>
            </div>
            <div class="col-md-3">
              <v-autocomplete v-model="province_name" :loading="loading" :items="provinces" @change="getCities()"
                cache-items hide-no-data hide-details label="Province" outlined item-text="province_name" item-value="id"
                dense></v-autocomplete>
            </div>
            <div class="col-md-3">
              <v-autocomplete v-model="city_name" :disabled="!cities" :loading="loading" :items="cities"
                @change="getBrgys()" hide-no-data hide-details label="City/Municipality" outlined item-text="city_name"
                item-value="id" dense></v-autocomplete>
            </div>
            <div class="col-md-3">
              <v-autocomplete v-model="form.psgc_id" :disabled="!brgys" :loading="loading" :items="brgys" hide-no-data
                hide-details label="Barangay" outlined item-text="brgy_name" item-value="id" dense
                :error-messages="formErrors.psgc_id" required></v-autocomplete>
            </div>
          </div>

          <div class="row justify-content-center">
            <div class="col-md-12 "> <b> OTHER INFORMATION </b> </div>
          </div>
          <hr>

          <div class="row justify-content-center g-2">

            <div class="  col-md-3">
              <v-text-field type="date" v-model="form.birth_date" label="Birthday*" outlined dense
                :error-messages="formErrors.birth_date" @input="calculateAge" required></v-text-field>
            </div>
            <div class="col-md-3">
              <v-text-field v-model="form.age" label="Age" outlined dense :error-messages="formErrors.age"
                readonly></v-text-field>
            </div>

            <div class="col-md-3">
              <v-select v-model="form.gender" label="Sex*" outlined dense :items="['Lalake', 'Babae']" item-value="id"
                item-text="name" :error-messages="formErrors.gender">
              </v-select>

            </div>
            <div class=" col-md-3">


              <v-text-field v-model="form.mobile_number" label="Mobile Number*" outlined dense
                :error-messages="formErrors.mobile_number" counter></v-text-field>

            </div>
          </div>


          <!-- <div class="row justify-content-center g-2">
          <div class=" col-md-3">


           <v-text-field  type="email" v-model="form.email" label="E-mail" outlined dense
              :error-messages="formErrors.email"></v-text-field>

          </div>

          <div class=" col-md-3">

           <v-text-field  type="password" v-model="form.password" label="Password" outlined dense
              :error-messages="formErrors.password"></v-text-field>


          </div>

          <div class=" col-md-3">


           <v-text-field  type="password" v-model="form.password_confirmation" label="Confirm Password" outlined dense
              :error-messages="formErrors.password_confirmation"></v-text-field>



          </div>
        </div>-->


          <div class="row justify-content-center">
            <div class="col-md-12 ">
              <b>PROOF OF IDENTIFICATION </b> <br>
              <hr>
            </div>
          </div>
          <div class="row justify-content-center">
            <div class="col-md-6  ">
              <p>Upload a clear copy of your valid ID</p>

              <p> Accepted valid IDS:</p>
              <ul style=" column-count: 2;">
                <li>National ID</li>
                <li>Driver's License</li>
                <li>Senior Citizen ID</li>
                <li>Voter's ID/Certificate</li>
                <li>Person’s With Disability (PWD) ID</li>
                <li>Phil-health ID</li>
                <li>NBI Clearance</li>
                <li>BIR (TIN)</li>
                <li>Pag-ibig ID</li>
                <li>Any Government Issued IDs</li>
              </ul>

            </div>
            <div class="col-md-6">
              <v-file-input ref="valid_id" label="Upload a clear copy of your valid ID"
                accept="image/png, image/jpeg, image/jpg" capture="camera" :error-messages="formErrors.valid_id"
                v-model="valid_id"></v-file-input>

              <div class="preview">
                <v-img v-if="url" :src="url" tyle="max-width: 300px;" />
              </div>
            </div>

          </div>

          <div class="row justify-content-center">
            <div class="col-md-6  ">
              <p>Upload/Take a picture of yourself</p>
            </div>
            <div class="col-md-6">
              <v-file-input ref="file_selfie" label="Upload/Take a picture of yourself" accept="image/*" capture="camera"
                :error-messages="formErrors.client_photo" v-model="client_photo"></v-file-input>

              <div class="preview">
                <v-img v-if="url_selfie" :src="url_selfie" tyle="max-width: 300px;" />
              </div>
            </div>

          </div>




          <div class="row justify-content-center ">
            <div v-if="showcpatcha" class="col-auto">
              <vue-recaptcha :sitekey="nocaptchakey" :loadRecaptchaScript="true" @verify="onCaptchaVerified"
                @expired="onCaptchaExpired">
              </vue-recaptcha>
              <div class="error--text"> {{
                $data["formErrors"]["g-recaptcha-response"] }}</div>
            </div>
            <div class="  col-md-12">

              <v-btn color="primary" @click="submitForm" :disabled="submit" :loading="submit" block>
                REGISTER
              </v-btn>


              <div class="m-5 text-center">
                Already have an account? Please <a href="/login">Login</a>
              </div>

            </div>
          </div>
        </v-form>



      </v-card-text>
    </v-card>
  </KeepAlive>
</template>

<script>
import { debounce, cloneDeep, isEmpty } from 'lodash';
import VueRecaptcha from 'vue-recaptcha';

export default
  {
    components: { VueRecaptcha },
    props: ["provinces",],
    data() {
      return {
        form: {},
        formErrors: {},
        max_date: new Date().toISOString().split("T")[0],
        province_name: "",
        city_name: "",
        cities: [],
        brgys: [],
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        errors: {},
        valid_id: null,
        client_photo: null,
        nmn: false,
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
        loading: false,
        submit: false,
        region: "XI",
        captcha_verified: false,
        nocaptchakey: process.env.MIX_NOCAPTCHA_SITEKEY,
        showcpatcha: process.env.MIX_APP_ENV == "production" ? true : false,
        recaptcha: '',

      }
    },
    watch: {
      nmn(e) {
        if (e) {
          this.form.middle_name = "NMN";

        } else {
          this.form.middle_name = "";

        }

      },
      /*form(val, nw) {

        console.log("nnnnnn");
        console.log(nw);
        console.log("this.form");
        console.log(val);
        // console.log(issessionStorage());

      },*/
    },
    computed: {
      url() {
        if (!this.valid_id) return;
        return URL.createObjectURL(this.valid_id);
      },
      url_selfie() {
        if (!this.client_photo) return;
        return URL.createObjectURL(this.client_photo);
      },

    },
    methods:
    {
      /*storeSession() {

        sessionStorage.setItem('form_stored', JSON.stringify(val));

      },*/
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

        axios.get(route("api.psgc.show", { type: "brgy", fields })).then(response => {
          this.brgys = response.data;
          this.loading = false;
        }).catch(error => { console.log(error); this.loading = false; });

      },

      submitForm: debounce(function () {


        let formData = new FormData();
        formData.append('valid_id', this.valid_id);
        formData.append('client_photo', this.client_photo);
        formData.append('g-recaptcha-response', this.recaptcha);

        if (this.form.ext_name == "") { delete this.form["ext_name"]; }

        const entries = Object.entries(this.form);

        entries.forEach(element => {
          formData.append(element[0], element[1]);
        });
        this.submit = true;

        sessionStorage.setItem('form_stored', JSON.stringify(this.form));


        axios.post("register", formData).then(response => {
          this.submit = false;
          location.reload();
          
          
        }).catch(error => {
          this.submit = false;
          if (error.response.data.errors) {
            this.formErrors = error.response.data.errors;
            // this.$refs.logo.scrollIntoView({ behavior: 'smooth' });
            this.$nextTick(() => {
              const el = this.$el.querySelector(".error--text:first-of-type");
              this.$vuetify.goTo(el);
              return;
            });
          }

        });

      }, 250),


      onCaptchaVerified: function (recaptchaToken) {
        this.recaptcha = recaptchaToken
        this.validateCaptcha = true


      },
      onCaptchaExpired: function () {
        this.$refs.recaptcha.reset();
      },



    },
    mounted() {


      if (sessionStorage.getItem('form_stored')) {
        try {
          let x = JSON.parse(sessionStorage.getItem('form_stored'));
          this.form = cloneDeep(x);
        } catch (error) {
          sessionStorage.removeItem('form_stored');
        }

      }
    }
  }
</script>

<style></style>