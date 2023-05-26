<template>
  <v-row no-gutters>
    <v-col cols="12" sm="4">
      <v-card flat>
        {{ hasRoles(['super-admin', 'admin']) }}
        <v-card-title>Signatory Form

        </v-card-title>
        <v-card-text>
          <v-form ref="form">
            <v-text-field v-model="formData.name" label="Name" :error-messages="formErrors.name"></v-text-field>

            <v-text-field v-model="formData.position" label="Position"
              :error-messages="formErrors.position"></v-text-field>



            <v-btn color="primary" class="mr-4" @click="submitForm" :disabled="submit"
              v-if="hasRoles(['super-admin', 'admin'])">
              <span>{{ formType }} Signatory</span>
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
        <v-card-title>Signatory Table
          <v-spacer></v-spacer>
          <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>


        </v-card-title>
        <v-card-text>
          <v-data-table :search="search" :headers="headers" :items="signatories" :items-per-page="5" :loading="loading"
            class="elevation-1">

            <template v-slot:item.actions="{ item }">
              <v-icon small class="mr-2" @click="editSignatory(item)" v-if="hasRoles(['super-admin', 'admin'])">
                mdi-pencil
              </v-icon>
              <v-icon small class="mr-2" @click="deleteSignatory(item)" v-if="hasRoles(['super-admin', 'admin'])">
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
        { text: 'Position', value: 'position' },
        { text: 'Actions', value: 'actions' },

      ],
      signatories: [],
      loading: true,
      submit: false,
      offices: [],
      search: ''
    };
  },
  methods: {
    submitForm: debounce(function () {
      if (this.formType == "Update") {
        this.updateSignatory()
      } else {
        this.createSignatory();
      }
    }, 250),
    createSignatory() {
      this.submit = true;
      this.formData.psgc_id = 368;

      axios.post(route('signatories.store'), this.formData)
        .then(res => {
          this.submit = false;
          this.getSignatories();
          this.resetForm();
          alert('Signatory has been created');
        })
        .catch(err => {
          this.submit = false;
          this.formErrors = err.response.data.errors
        })
    },
    updateSignatory() {
      this.submit = true;
      this.formErrors = {};
      console.log(this.formData);
      axios.post(route('signatories.update', this.formData.id), this.formData)
        .then(res => {
          this.submit = false;
          this.getSignatories();
          this.resetForm();
          alert('Signatory has been updated');
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
    getSignatories() {
      this.loading = true;
      axios.get(route('signatories.index'))
        .then(res => {
          this.loading = false;
          this.signatories = res.data;

        })
        .catch(err => {
          this.loading = false;
        })
        ;
    },
    editSignatory(signatory) {
      this.formType = "Update";
      this.formData = cloneDeep(signatory);

    },
    deleteSignatory(signatory) {
      if (confirm(`Are you sure you want to delete ${signatory.name} - ${signatory.position} ?`)) {
        axios.delete(route('signatories.destroy', signatory.id))
          .then(res => {
            this.getSignatories();
          })
          .catch(err => { })
          ;
      }
    },

    getText(item) { return `${item.name}` },
  },
  mounted() {

    this.getSignatories();
  },
}
</script>

<style></style>