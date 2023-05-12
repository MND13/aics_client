<template>
  <v-card flat>COE
    
  </v-card>
</template>

<script>
import userMixin from '../Mixin/userMixin';
export default {
  props: ["user"],
  mixins: [userMixin],
  mounted() {
    console.log("this.$route.query");
    console.log(this.$route.query);
    console.log(this.$route.query.uuid);
  }

}
</script>

<style></style>