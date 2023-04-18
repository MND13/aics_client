<template>
  <v-card flat>

    <v-card-title>
      WELCOME {{ user.first_name }} {{ user.middle_name }} {{ user.last_name }} {{ user.ext_name }}! <br>
      USERNAME: {{ user.username }} <br>
      <v-spacer></v-spacer><v-btn dark :to="{ name: 'assistance' }">Request Assistance </v-btn>

    </v-card-title>
    <v-card-text>





      <!--<v-data-table dense flat :headers="headers" :items="assistances" @click:row="openAssistance">-->
      <v-data-table dense flat :headers="headers" :items="assistances">


        <template v-slot:item.created_at="{ item }">
          {{ item.created_at | formatDate }}
        </template>

        <template v-slot:item.aics_type="{ item }">
          {{ item.aics_type.name }}
        </template>

        <template v-slot:item.actions="{ item }">
          <v-btn dark @click="openDetails(item)">
            Details
          </v-btn>
        </template>


      </v-data-table>


      <v-dialog v-model="dialog_create" width="80%">
        <v-card>
          <v-card-title>
            <v-spacer></v-spacer>
            <v-btn icon @click="dialog_create = false">
              <v-icon>mdi-close</v-icon>
            </v-btn>
          </v-card-title>
          <v-card-text v-if="dialog_data">



            <h5 v-if="dialog_data.aics_type">{{ dialog_data.aics_type.name }} </h5>
            <p>
              Status: {{ dialog_data.status }} <br>
              Date: {{ dialog_data.created_at | formatDate }} </p>

            <table class="table table-bordered " v-if="dialog_data.aics_documents">
              <thead>
                <tr>
                  <td>Attachments:</td>

                </tr>
              </thead>
              <tbody>

                <tr v-for="(e, i) in dialog_data.aics_documents" :key="i">

                  <td> <a :href="e.file_directory" target="_blank">
                      {{ e.requirement.name }}</a></td>
                </tr>
              </tbody>
            </table>
          </v-card-text>
        </v-card>
      </v-dialog>


    </v-card-text>
  </v-card>
</template>

<script>
export default {
  props: ["user"],
  data() {
    return {
      headers: [
        { text: 'Date', value: 'created_at' },
        { text: 'Assistance', value: 'aics_type' },
        { text: 'Status', value: 'status' },
        { text: 'Actions', value: 'actions', },

      ],
      assistances: [],
      dialog_create: false,
      dialog_data: {},
    }
  },
  methods: {
    getAssistances() {
      axios.get(route("assistances.index"))
        .then(response => {
          console.log(response.data);
          this.assistances = response.data;
        }).catch(error => console.log(error));
    },
    /*openAssistance(item, row) {
      window.open("/api/gis/" + item.uuid);
    },*/
    openDetails(item) {

      this.dialog_data = {};
      this.dialog_data = item;
      this.dialog_create = true;

    }
  },
  mounted() {
    this.getAssistances()

  }

}
</script>

<style></style>