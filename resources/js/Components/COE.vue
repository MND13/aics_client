<template>
  <v-card flat>COE
    {{ uuid }}

    <v-form ref="form">
      <v-text-field v-model="formData.first_name" label="First Name"
        :error-messages="formErrors.first_name"></v-text-field>

      <v-btn color="primary" class="mr-4" @click="submitForm" :disabled="submit"
        v-if="hasRoles(['super-admin', 'admin'])">
        <span>{{ formType }} User</span>
      </v-btn>

      <v-btn color="error" class="mr-4" @click="resetForm">
        <span>Cancel</span>
      </v-btn>
    </v-form>


  </v-card>
</template>

<script>
import userMixin from './../Mixin/userMixin.js'
import authMixin from './../Mixin/authMixin.js'

export default {
  props: ["uuid"],
  mixins: [userMixin, authMixin],
  data()
  {
    return {
      formData: {},
      formErrors: []
    }
  },
  methods: {
    resetForm()
    {
      this.formData = {};
    }
  },
  mounted() {
    console.log("this.$route.query");
    console.log(this.$route.query);
    console.log(this.$route.query.uuid);
  }

}
</script>

<style></style>