<template>
  <v-row>
    <v-col cols="12" sm="4">

      <v-card flat>
        <v-card-title>Providers</v-card-title>
        <v-card-text>
          <v-form ref="form">

              

            <v-text-field v-model="formData.addressee_name" label="Addressee"
              :error-messages="formErrors.addressee_name"></v-text-field>

            <v-text-field v-model="formData.addressee_position" label="Position"
              :error-messages="formErrors.addressee_position"></v-text-field>

            <v-text-field v-model="formData.company_name" label="Company"
              :error-messages="formErrors.company_name"></v-text-field>

              <v-text-field v-model="formData.company_address" label="Address"
              :error-messages="formErrors.company_address"></v-text-field>

              <v-text-field v-model="formData.action_executed_by" label="Executed By"
              :error-messages="formErrors.action_executed_by"></v-text-field>

              <v-text-field v-model="formData.to_mention" label="To Mention"
              :error-messages="formErrors.to_mention"></v-text-field>

          
            <v-btn color="primary" class="mr-4" @click="submitForm" :disabled="submit"
              v-if="userData.role == 'super-admin'">
              <span>{{ formType }}</span>
            </v-btn>

            <v-btn color="error" class="mr-4" @click="resetForm">
              <span>Cancel</span>
            </v-btn>
          </v-form>
        </v-card-text>
      </v-card>




    </v-col>
    <v-col cols="12" sm="8">

      <v-card>
        <v-card-title>Providers Table
          <v-spacer></v-spacer>
          <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>

        </v-card-title>

        <v-data-table dense :items-per-page="20" :loading="isLoading" :headers="headers" :items="data" :search="search">

          <template v-slot:item.actions="{ item }">
            <v-icon small class="mr-2" @click="editProviders(item)" v-if="userData.role == 'super-admin'">
              mdi-pencil
            </v-icon>
            <v-icon small class="mr-2" @click="deleteProviders(item)" v-if="userData.role == 'super-admin'">
              mdi-delete
            </v-icon>
          </template>

        </v-data-table>


      </v-card>


    </v-col>

  </v-row>
</template>

<script>
import userMixin from './../Mixin/userMixin.js'
import { debounce, cloneDeep, isEmpty } from 'lodash'

export default {
  mixins: [userMixin],
  props: ['user'],
  data() {
    return {
      formType: "Create",
      form: {},
      data: [],
      isLoading: false,
      search: '',
      headers: [
        { text: 'Addressee', value: 'addressee_name' },
        { text: 'Position', value: 'addressee_position' },
        { text: 'Company', value: 'company_name' },
        { text: 'Address', value: 'company_address' },
        { text: 'Executed By', value: 'action_executed_by' },
        { text: 'To Mention', value: 'to_mention' },
        { text: 'Actions', value: 'actions' },




      ],
      types: [
        '',
        'Congressional',
        'Regular Funds and Other Referrals',
        'Senator/Governor/Mayor',
        'Party-List'
      ], formData: {},
      formErrors: {},
      submit: false,

    }
  },
  methods:
  {
    getProviders() {
      this.isLoading = true;
      axios.get(route("api.providers")).then(response => {
        this.data = response.data;
        this.isLoading = false;

      }).catch(error => console.log(error))
    },
    submitForm: debounce(function () {
      if (this.formType == "Update") {
        this.updateProviders()
      } else {
        this.createProviders();
      }
    }, 250),
    createProviders() {
      this.submit = true;

      axios.post(route('api.providers.store'), this.formData)
        .then(res => {
          this.submit = false;
          this.getProviders();
          this.resetForm();
          alert('Providers has been created');
        })
        .catch(err => {


          this.submit = false;
          this.formErrors = err.response.data.errors
        })
    },

    editProviders(providers) {
      this.formType = "Update";
      this.formData = cloneDeep(providers);

    },

    resetForm() {
      this.formData = {};
      this.formErrors = {};
      this.formType = "Create";
    },
    updateProviders() {
      this.submit = true;
      this.formErrors = {};
      axios.put(route('api.providers.update', this.formData.id), this.formData)
        .then(res => {
          this.submit = false;
          this.getProviders();
          this.resetForm();
          alert('Providers has been updated');
        })
        .catch(err => {
          this.submit = false;
          this.formErrors = err.response.data.errors
        })
    },

    deleteProviders(providers) {
      if (confirm(`Are you sure you want to delete ${providers.name}`)) {
        axios.delete(route('api.providers.delete', providers.id))
          .then(res => {
            this.getProviders();
          })
          .catch(err => { })
          ;
      }
    },


  },

  mounted() {
    this.getProviders();
  }

}
</script>

<style></style>