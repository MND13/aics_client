<template >
  <v-card flat class="text-center">
    <h1>Page not Found</h1>
    <v-btn color="primary" @click="back()">Go Back</v-btn>
  </v-card>
 
</template>

<script>
export default {

  methods:
  {
    back()
    {
      this.$router.go(-1);
    }
  }

}
</script>

<style>

</style>