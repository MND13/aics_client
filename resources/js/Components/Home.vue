<template>
  <v-card flat>

    <v-card-title v-if="userData.role=='user'"> 
    
      WELCOME {{ user.first_name }} {{ user.middle_name }} {{ user.last_name }} {{ user.ext_name }}! <br>
      USERNAME: {{ user.username }} <br>
      <v-spacer></v-spacer>
      <v-btn  elevation="0" color="primary" :to="{ name: 'assistance' }">Request Assistance </v-btn>

    </v-card-title>
    <v-card-text>


      <!--<v-data-table dense flat :headers="headers" :items="assistances" @click:row="openAssistance">-->
      <v-data-table dense :headers="headers" :items="assistances">


        <template v-slot:item.aics_client="{ item }">

          <span v-if="item.aics_client">{{
            item.aics_client.last_name }}, {{ item.aics_client.first_name }} {{ item.aics_client.middle_name }}  {{ item.aics_client.ext_name }}
          </span>
        </template>

        <template v-slot:item.status="{ item }">
          <v-chip :color="status_color" dark  small  label> {{ item.status }} </v-chip>
        </template>

        <template v-slot:item.created_at="{ item }">
          {{ item.created_at | formatDate }}
        </template>

        <template v-slot:item.aics_type="{ item }">
          {{ item.aics_type.name }}
        </template>

        <template v-slot:item.office="{ item }">
          {{ item.office.name }}
        </template>


        <template v-slot:item.actions="{ item }">
          <v-btn dark small @click="openDetails(item)">
            Details
          </v-btn>
        </template>


      </v-data-table>


      <v-dialog v-model="dialog_create" fullscreen>
        <v-card>
          <v-card-title>
            <v-spacer></v-spacer>
            <v-btn icon @click="dialog_create = false">
              <v-icon>mdi-close</v-icon>
            </v-btn>
          </v-card-title>
          <v-card-text v-if="dialog_data">
            <div class="row">
              <div class="col-md-9">

             
                <GISComponent :dialog_data="dialog_data" :getList="getList" :user-data="userData" :set-dialog-create="setDialogCreate"></GISComponent>
             


                


              </div>
              <div class="col-md-3">





                <h5 v-if="dialog_data.aics_type">{{ dialog_data.aics_type.name }} </h5>

                <span> Status: {{ dialog_data.status }} <br></span>
                <span> Date: {{ dialog_data.created_at | formatDate }} </span><br>

                <span v-if="dialog_data.office"> Office: {{ dialog_data.office.name }} <br> {{ dialog_data.office.address
                }}
                </span>

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
              </div>
            </div>

          </v-card-text>
        </v-card>
      </v-dialog>


    </v-card-text>
  </v-card>
</template>

<script>
import userMixin from '../Mixin/userMixin';
import GISComponent from './GISComponent.vue';

export default {
  components: { GISComponent },
  mixins: [userMixin],
  props: ["user"],
  data() {
    return {
      headers: [
        { text: 'Date', value: 'created_at' },
        { text: 'Client', value: 'aics_client' },
        { text: 'Assistance', value: 'aics_type', width: "30%" },
        { text: 'Office', value: 'office' },
        { text: 'Status', value: 'status' },
        { text: 'Actions', value: 'actions', },
      ],
      assistances: [],
      dialog_create: false,
      dialog_data: {},
      status_color: "blue",
      dialog_create: false,
      getList: [],
    }
  },

  methods: {
    getAssistances() {
      axios.get(route("assistances.index"))
        .then(response => {
          // console.log(response.data);
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

    },
    setDialogCreate(value) {
      this.dialog_create = value;
    }
  },
  mounted() {
    this.getAssistances()

  }

}
</script>

<style></style>