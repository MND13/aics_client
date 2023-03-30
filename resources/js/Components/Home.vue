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
        Document Upload
      </v-stepper-step>
    </v-stepper-header>

    <v-stepper-items>
      <v-stepper-content step="1">
        <v-card flat class="mb-12">



          NAIS HINGIIN NA TULONG (Assistance Requested)

          <select name="assistance_type" v-model="form.aics_type_id" class="form-control" @change="GetRequirements()">
            <option :value="e.id" v-for="e in assistance_types" :key="e.id">
              {{ e.name }}
            </option>
          </select>

          <br>

          <v-alert v-if="requirements.length > 0" icon="mdi mdi-alert-outline" prominent text type="info">

            <h6>PLEASE PREPARE THE FOLLOWING REQUIREMENTS: </h6>
            <ul>
              <li v-for="r in requirements[0].requirements" :key="r.id" style="list-style: number;">
                {{ r.name }}
              </li>
            </ul>


          </v-alert>




        </v-card>

        <v-btn color="primary" :disabled="form.aics_type_id && form.aics_type_id > 0 ? false : true" @click="e1 = 2">
          Continue
        </v-btn>

        <v-btn text>
          Cancel
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
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>{{ user.birth_date }}</td>
                <td>{{ user.gender }}</td>
                <td><input type="text" name="" v-model="form.mobile_number" id="" class="form-control"></td>
                <td><input type="text" name="" v-model="form.occupation" id="" class="form-control"></td>
                <td><input type="text" name="" v-model="form.monthly_salary" id="" class="form-control"></td>

              </tr>
            </tbody>
          </table>


        </v-card>

        <v-btn color="primary" @click="e1 = 3">
          Continue
        </v-btn>

        <v-btn text>
          Cancel
        </v-btn>
      </v-stepper-content>

      <v-stepper-content step="3">
        <v-card class="mb-12" flat>
          Upload Files

          <ul v-if="requirements.length > 0">
            <li v-for="r in requirements[0].requirements" :key="r.id" style="list-style: number;">
              <label for="" class="form-label">{{ r.name }}</label> <br>
              <input type="file" :name="r.name" id="" class="form-conrol"><br>
              <hr>
            </li>
          </ul>


        </v-card>

        <v-btn color="primary" @click="submit">
          Continue
        </v-btn>

        <v-btn text>
          Cancel
        </v-btn>
      </v-stepper-content>
    </v-stepper-items>
  </v-stepper>
</template>
<script>
export default {
  props: ["user"],
  data() {
    return {
      e1: 1,
      assistance_types: {

      },
      form: {
        documents: {},
        mobile_number: this.user.mobile_number,
      },
      requirements: {},
      validationErrors: {
        client: {},
        beneficiary: {},
        assistance: {},
      },
      psgc_data: {}
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
      console.log("here psgc" + this.user.psgc_id);
      axios.get(route("api.psgc.show", { type: "id", _query: { "id": this.user.psgc_id } }),).then(response => {
        console.log("here psgc 2");
        this.psgc_data = response.data;
      }).catch(error => console.log(error));
    },

    submit() {
      this.form;
      axios.post(route("assistances.store"), this.form).then(response => {
         console.log(response.data) 
      }).catch(error => console.log(error));
    }

  },
  mounted() {
    this.getAssistanceTypes();
    this.getPsgc();
  }
}
</script>

<style>
table {
  table-layout: fixed;
}
</style>