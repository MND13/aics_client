<template>
  <v-card>COE
    {{ uuid }}
  </v-card>
</template>

<script>
import userMixin from '../Mixin/userMixin';

export default {
    props: ["user", "uuid"],
    mounted()
    {
        console.log(this.$route.query.uuid);
    }

}
</script>

<style>

</style>