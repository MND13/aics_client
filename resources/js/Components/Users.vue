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


            <v-text-field v-model="formData.username" label="User Name"
              :error-messages="formErrors.username"></v-text-field>


            <v-select v-model="formData.office_id" label="Office" :items="offices" item-value="id" item-text="name"
              :error-messages="formErrors.office_id">

              <template v-slot:selection="{ item }">
                {{ getText(item) }}
              </template>

            </v-select>


           <v-text-field v-model="formData.birth_date" label="Birth Day" :error-messages="formErrors.birth_date"
              type="date"></v-text-field>


            <v-text-field v-model="formData.mobile_number" label="Mobile Number"
              :error-messages="formErrors.mobile_number"></v-text-field>

            <v-select v-model="formData.gender" label="Select" :items="['Babae', 'Lalake']"></v-select>


            <v-text-field v-model="formData.email" label="E-mail" :error-messages="formErrors.email"></v-text-field>

            <v-select v-model="formData.role" :items="roles" label="Role" item-text="role" item-value="value"
              :error-messages="formErrors.role"></v-select>

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
            <template v-slot:item.office="{ item }">
              <span>
                {{ item.office ? item.office.name : "" }}
              </span>
            </template>
            <template v-slot:item.role="{ item }">
              <span>
                {{ userRole(item) }}
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
      formType: "Create",
      formData: {
        mobile_number : "00000000000",
        gender : "Lalake",
        birth_date : "1900-01-01",
        mobile_verified : "1",
      },
      formErrors: {},
      roles: [
        {
          role: "Admin",
          value: "admin",
        },
        {
          role: "Encoder",
          value: "encoder",
        },
        {
          role: "Social Worker",
          value: "social-worker",
        },
        {
          role: "User",
          value: "user",
        },
      ],
      showPassword: false,
      showPasswordConfirmation: false,
      headers: [
        { text: 'Name', value: 'username' },
        { text: 'Office', value: 'office' },
        { text: 'Email Address', value: 'email' },
        { text: 'Role', value: 'role' },
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
      } else {
        this.createUser();
      }
    }, 250),
    createUser() {
      this.submit = true;
      this.formData.psgc_id = 368;
      this.formData.mobile_number = "00000000000";
      this.formData.gender = "Lalake";
      this.formData.birth_date = "1900-01-01";
      this.formData.mobile_verified = "1";

      axios.post(route('users.store'), this.formData)
        .then(res => {
          this.submit = false;
          this.getUsers();
          this.resetForm();
          alert('User has been created');
        })
        .catch(err => {
          this.submit = false;
          this.formErrors = err.response.data.errors
        })
    },
    updateUser() {
      this.submit = true;
      this.formErrors = {};
      //console.log(this.formData);
      this.formData.mobile_verified = "1";
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
      this.formType = "Create";
    },
    getUsers() {
      this.loading = true;
      axios.get(route('users.index'))
        .then(res => {
          this.loading = false;
          this.users = res.data.users;
          if (this.userData.role == "admin") {
            this.users = this.users.filter(i => i.office_id == this.userData.office_id);
          }
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