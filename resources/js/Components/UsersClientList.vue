<template>
  <v-row no-gutters>
    <v-col cols="12" sm="4">
      <v-card flat>
        <v-card-title>User Form

        </v-card-title>
        <v-card-text>
          <v-form ref="form">
            <v-text-field v-model="formData.first_name" label="First Name"
              :error-messages="formErrors.first_name"></v-text-field>

            <v-text-field v-model="formData.middle_name" label="Middle Name"
              :error-messages="formErrors.middle_name"></v-text-field>

            <v-text-field v-model="formData.last_name" label="Last Name"
              :error-messages="formErrors.last_name"></v-text-field>







            <v-text-field v-model="formData.birth_date" label="Birth Day" :error-messages="formErrors.birth_date"
              type="date"></v-text-field>


            <v-text-field v-model="formData.mobile_number" label="Mobile Number"
              :error-messages="formErrors.mobile_number"></v-text-field>

            <v-select v-model="formData.gender" label="Select" :items="['Babae', 'Lalake']"></v-select>



            <v-text-field v-model="formData.password" :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
              :type="showPassword ? 'text' : 'password'" name="input-10-1" label="Password" hint="At least 8 characters"
              counter @click:append="showPassword = !showPassword" :error-messages="formErrors.password"></v-text-field>

            <v-text-field v-model="formData.password_confirmation"
              :append-icon="showPasswordConfirmation ? 'mdi-eye' : 'mdi-eye-off'"
              :type="showPasswordConfirmation ? 'text' : 'password'" name="input-10-1" label="Confirm Password"
              hint="At least 8 characters" counter
              @click:append="showPasswordConfirmation = !showPasswordConfirmation"></v-text-field>

            <v-btn color="primary" class="mr-4" @click="submitForm" :disabled="submit"
              v-if="hasRoles(['super-admin', 'admin'])">
              <span>{{ formType }} User</span>
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
        <v-card-title>

          User Table
          <v-spacer></v-spacer>
          <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>

        </v-card-title>
        <v-card-text>

          <v-data-table items-per-page="10" :search="search" :headers="headers" :items="users" :items-per-page="5"
            :loading="loading" class="elevation-1">

            <template v-slot:item.psgc="{ item }">

              <span v-if="item.psgc">
                {{ item.psgc.brgy_name }}
                {{ item.psgc.city_name }}
                {{ item.psgc.province_name }}
              </span>
            </template>

            <template v-slot:item.actions="{ item }">
              <v-icon small class="mr-2" @click="editUser(item)" v-if="hasRoles(['super-admin', 'admin'])">
                mdi-pencil
              </v-icon>
              <v-icon small class="mr-2" @click="deleteUser(item)" v-if="hasRoles(['super-admin', 'admin'])">
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
      formType: "Edit",
      formData: {
        mobile_number: "",
        gender: "",
        birth_date: "",
      },
      formErrors: {},
     
      showPassword: false,
      showPasswordConfirmation: false,
      headers: [
        { text: 'Username', value: 'username' },
        { text: 'Full Name', value: 'full_name' },
        { text: 'Birth Date', value: 'birth_date' },
        { text: 'Address', value: 'psgc' },
        { text: 'Actions', value: 'actions' },
      ],
      users: [],
      loading: true,
      submit: false,
      offices: [],
      search: ''
    };
  },
  methods: {
    submitForm: debounce(function () {
      if (this.formType == "Update") {
        this.updateUser()
      }
    }, 250),

    updateUser() {
      this.submit = true;
      this.formErrors = {};
      console.log(this.formData);
      axios.patch(route('users.update', this.formData.id), this.formData)
        .then(res => {
          this.submit = false;
          this.getUsers();
          this.resetForm();
          alert('User has been updated');
        })
        .catch(err => {
          this.submit = false;
          this.formErrors = err.response.data.errors
        })
    },
    resetForm() {
      this.formData = {};
      this.formErrors = {};
      this.formType = "Update";
    },
    getUsers() {
      this.loading = true;
      axios.get(route('users.clients'))
        .then(res => {
          console.log(res)
          this.loading = false;
          this.users = res.data.users;
       
        })
        .catch(err => {
          this.loading = false;
        })
        ;
    },
    editUser(user) {
      this.formType = "Update";
      this.formData = cloneDeep(user);
      this.formData.role = this.userRole(user);
    },
    deleteUser(user) {
      if (confirm(`Are you sure you want to delete ${user.email}`)) {
        axios.delete(route('users.destroy', user.id))
          .then(res => {
            this.getUsers();
          })
          .catch(err => { })
          ;
      }
    },
    getOffices() {
      axios.get(route("api.offices.index")).then(response => {
        this.offices = response.data;
        if (this.userData.role == "admin") {
          this.offices = this.offices.filter(i => i.id == this.userData.office_id);
        }
      }).catch(error => console.log(error));
    },
    getText(item) { return `${item.name}` },
  },
  mounted() {
    this.getOffices()
    this.getUsers();
  },
}
</script>

<style></style>