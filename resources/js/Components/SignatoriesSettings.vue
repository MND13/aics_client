<template>
  <div>

    <v-row no-gutters>

      <v-col cols="12" sm="4">
      
        <v-form ref="form_settings">
          <v-text-field v-model="formData.min_range" label="Min Range" :error-messages="formErrors.name"></v-text-field>

          <v-text-field v-model="formData.max_range" label="Max Range" :error-messages="formErrors.name"></v-text-field>


          <v-autocomplete multiple class="my-2 rounded-0" chips outlined clearable dense v-model="formData.signatories"
            :loading="!signatories" label="For Signatory" :items="signatories" deletable-chips
            :error-messages="validationErrors.gl_for_signatory_id" item-value="id" item-text="name" hide-details="auto">
            <template v-slot:item="data">
              <v-list-item-content>
                <v-list-item-title>{{ data.item.name }}</v-list-item-title>
                <v-list-item-subtitle>{{ data.item.position }}</v-list-item-subtitle>
              </v-list-item-content>
            </template>
          </v-autocomplete>


          <v-btn color="primary" class="mr-4" @click="submitForm" :disabled="submit"
            v-if="hasRoles(['super-admin', 'admin'])">
            <span>{{ formType }} Signatory</span>
          </v-btn>

          <v-btn color="error" class="mr-4" @click="resetForm">
            <span>Cancel</span>
          </v-btn>
        </v-form>

      </v-col>
      <v-col cols="12" sm="8">
        <v-card flat>
          <v-card-title>Signatory Table
            <v-spacer></v-spacer>
            <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line
              hide-details></v-text-field>


          </v-card-title>
          <v-card-text>
            <v-data-table :search="search" :headers="headers" :items="signatories_settings" :items-per-page="5"
              :loading="loading" class="elevation-1">

              <template v-slot:item.signatories="{ item }">


                <ol>
                  <li v-for="(e, i ) in item.names" :key="i"> {{ e.name }} ({{ e.initials }})</li>
                </ol>

              </template>

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
  </div>
</template>

<script>

import axios from 'axios';
import userMixin from '../Mixin/userMixin.js'
import authMixin from '../Mixin/authMixin.js'
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
        { text: 'Min', value: 'min_range' },
        { text: 'Max', value: 'max_range' },
        { text: 'Signatories', value: 'signatories' },
        { text: 'Actions', value: 'actions' },

      ],
      signatories: [],
      loading: true,
      submit: false,
      offices: [],
      search: '',
      formsettings: {},
      validationErrors: {},
      signatories_settings: []
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

      axios.post(route('signatories_settings.store'), this.formData)
        .then(res => {
          this.submit = false;
          this.getSignatoriesSettings();
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
      
      axios.put(route('signatories_settings.update', this.formData.id), this.formData)
        .then(res => {
          this.submit = false;
          this.getSignatoriesSettings();
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
    getSignatoriesSettings() {
      this.loading = true;
      axios.get(route('signatories_settings.index'))
        .then(res => {
          this.loading = false;
          this.signatories_settings = res.data;

        })
        .catch(err => {
          this.loading = false;
        })
        ;
    },
    getSignatories() {
      this.loading = true;
      axios.get(route('api.signatories'))
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
      this.formData.signatories = this.formData.names;

    },
    deleteSignatory(signatory) {
      if (confirm(`Are you sure you want to delete ?`)) {
        axios.delete(route('signatories_settings.destroy', signatory.id))
          .then(res => {
            this.getSignatories();
            alert('Signatory has been deleted');
          })
          .catch(err => { })
          ;
      }
    },

    getText(item) { return `${item.name}` },

  },
  mounted() {

    this.getSignatories();
    this.getSignatoriesSettings();
  },
}
</script>

<style></style>