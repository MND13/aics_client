<template>
  <v-row no-gutters>
    <v-col cols="12" sm="4">
      <v-card flat>
        <v-card-title>Office Form

        </v-card-title>
        <v-card-text>
          <v-form ref="form">
            <v-text-field v-model="formData.name" label="Office Name" :error-messages="formErrors.name"></v-text-field>

            <v-text-field v-model="formData.address" label="Address" :error-messages="formErrors.address"></v-text-field>

            <v-text-field v-model="formData.contact_person" label="Contact Person"
              :error-messages="formErrors.contact_person"></v-text-field>


            <v-text-field v-model="formData.contact_no" label="Contact No."
              :error-messages="formErrors.contact_no"></v-text-field>



            <v-btn color="primary" class="mr-4" @click="submitForm" :disabled="submit"
              v-if="hasRoles(['super-admin', 'admin'])">
              <span>{{ formType }} Office</span>
            </v-btn>

            <v-btn color="error" class="mr-4" @click="resetForm">
              <span>Cancel</span>
            </v-btn>
          </v-form>
        </v-card-text>
      </v-card>
    </v-col>


    <v-col cols="12" sm="8">
      <v-card flat>
        <v-card-title>Office Table
          <v-spacer></v-spacer>
          <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>


        </v-card-title>
        <v-card-text>
          <v-data-table :search="search" :headers="headers" :items="offices" :items-per-page="5" :loading="loading"
            class="elevation-1">

            <template v-slot:item.actions="{ item }">
              <v-icon small class="mr-2" @click="editOffice(item)" v-if="hasRoles(['super-admin', 'admin'])">
                mdi-pencil
              </v-icon>
              <v-icon small class="mr-2" @click="deleteOffice(item)" v-if="hasRoles(['super-admin', 'admin'])">
                mdi-delete
              </v-icon>
            </template>
          </v-data-table>
        </v-card-text>
      </v-card>
    </v-col>


  </v-row>
</template>

<script>

import axios from 'axios';
import userMixin from './../Mixin/userMixin.js'
import authMixin from './../Mixin/authMixin.js'
import { debounce, cloneDeep, isEmpty } from 'lodash'

export default {
  mixins: [userMixin, authMixin],
  props: ['user'],
  data() {
    return {
      formType: "Create",
      formData: {},
      formErrors: {},

      headers: [
        { text: 'Name', value: 'name' },
        { text: 'address', value: 'address' },
        { text: 'Contact Person', value: 'contact_person' },
        { text: 'Contact No.', value: 'contact_no' },
        { text: 'Actions', value: 'actions' },
      ],
      offices: [],
      loading: true,
      submit: false,
      offices: [],
      search: ''
    };
  },
  methods: {
    submitForm: debounce(function () {
      if (this.formType == "Update") {
        this.updateOffice()
      } else {
        this.createOffice();
      }
    }, 250),
    createOffice() {
      this.submit = true;
      this.formData.psgc_id = 368;

      axios.post(route('offices.store'), this.formData)
        .then(res => {
          this.submit = false;
          this.getOffices();
          this.resetForm();
          alert('Office has been created');
        })
        .catch(err => {
          this.submit = false;
          this.formErrors = err.response.data.errors
        })
    },
    updateOffice() {
      this.submit = true;
      this.formErrors = {};
      axios.post(route('offices.update', this.formData.id), this.formData)
        .then(res => {
          this.submit = false;
          this.getOffices();
          this.resetForm();
          alert('Office has been updated');
        })
        .catch(err => {
          this.submit = false;
          this.formErrors = err.response.data.errors
        })
    },
    resetForm() {
      this.formData = {};
      this.formErrors = {};
      this.formType = "Create";
    },

    editOffice(office) {
      this.formType = "Update";
      this.formData = cloneDeep(office);

    },
    deleteOffice(office) {
      if (confirm(`Are you sure you want to delete ${office.name}`)) {

        axios.delete(route('offices.destroy', office.id))
          .then(res => {
            this.getOffices();
          })
          .catch(err => { console.log(err) })
          ;
      }
    },
    getOffices() {
      this.loading = true;
      axios.get(route("api.offices.index")).then(response => {
        this.offices = response.data;
        if (this.userData.role == "admin") {
          this.offices = this.offices.filter(i => i.id == this.userData.office_id);
        }
        this.loading = false;
      }).catch(error => console.log(error));
    },
    getText(item) { return `${item.name}` },
  },
  mounted() {
    this.getOffices()

  },
}
</script>

<style></style>