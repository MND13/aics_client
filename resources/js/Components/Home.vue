<template>
  <v-card flat>

    <v-card-title v-if="hasRoles(['user'])">

      <v-card theme="dark" outlined>
        <div class="d-flex flex-no-wrap justify-space-between">

          <v-avatar class="ma-3" size="125" rounded="0">
            <v-img :src="user.profile_pic.file_directory"></v-img>
          </v-avatar>
          <div>
            <v-card-title class="text-h5">
              {{ user.first_name }} {{ user.middle_name }} {{ user.last_name }} {{ user.ext_name }} 
            </v-card-title>

            <v-card-subtitle>FO11-DSWD-AICS-{{ user.username }} <br> {{ user.birth_date | formatDate }} <br/>
              {{ user.mobile_number }}
             </v-card-subtitle>

            <v-card-actions>

            </v-card-actions>
          </div>


        </div>
      </v-card>

      <v-spacer></v-spacer>
      <v-btn elevation="0" color="primary" :to="{ name: 'assistance' }">Request Assistance </v-btn>

    </v-card-title>

    <v-card-text>
      <div class="row" v-if="!hasRoles(['user'])">
        <div class="col-md-3">
          <v-select :items="status_list" v-model="StatusFilterValue" label="Status"></v-select>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-3"></div>
        <div class="col-md-3"><v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line
            hide-details></v-text-field></div>
      </div>



      <v-data-table :loading="isLoading" dense :headers="headers" :items="assistances" :search="search">

        <template v-slot:item.aics_client="{ item }">
          <span v-if="item.aics_client">{{
            item.aics_client.last_name }}, {{ item.aics_client.first_name }} {{ item.aics_client.middle_name }} {{
    item.aics_client.ext_name }}
          </span>
        </template>

        <template v-slot:item.status="{ item }">
          <v-chip :color="status_color(item.status)" dark small label>
            {{ item.status }} </v-chip>
        </template>

        <template v-slot:item.created_at="{ item }">
          {{ item.created_at | formatDate }}
        </template>

        <template v-slot:item.schedule="{ item }">
          {{ item.schedule | formatDate }}
        </template>

        <template v-slot:item.aics_type="{ item }">
          {{ item.aics_type.name }}
        </template>

        <template v-slot:item.office="{ item }">
          {{ item.office.name }}
        </template>

        <template v-slot:item.actions="{ item }">
          <v-btn dark small @click="openDetails(item)" v-if="hasRoles(['user'])">
            Details
          </v-btn>

          <v-btn small dark :to="{ name: 'assessment', params: { 'uuid': item.uuid } }" v-if="userData.role != 'user'">
            Review
          </v-btn>
        </template>
      </v-data-table>


      <v-dialog v-model="dialog_create" large>
        <v-card>
          <v-card-title>
            <v-spacer></v-spacer>
            <v-btn icon @click="dialog_create = false">
              <v-icon>mdi-close</v-icon>
            </v-btn>
          </v-card-title>
          <v-card-text v-if="dialog_data">

            <h5 v-if="dialog_data.aics_type">{{ dialog_data.aics_type.name }} </h5>

            <span> Status: {{ dialog_data.status }} <br></span>
            <span> Remarks: {{ dialog_data.remarks }} <br></span>
            <span> Date Submitted: {{ dialog_data.created_at | formatDate }} </span><br>
            <span v-if="dialog_data.office"> Office: {{ dialog_data.office.name }} <br>
              {{ dialog_data.office.address }}
            </span><br>
            <table class="table table-bordered mt-2" v-if="dialog_data.aics_documents">
              <thead>
                <tr>
                  <td>Attachments:</td>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(e, i) in dialog_data.aics_documents" :key="i">
                  <td> <a :href="e.file_directory" target="_blank">
                      {{ e.requirement.name }}</a>
                  </td>
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
import userMixin from '../Mixin/userMixin';

export default {

  mixins: [userMixin],
  props: ["status"],
  data() {
    return {
      headers: [
        { text: 'Date Submitted', value: 'created_at', width: "150px" },
        { text: 'Schedule', value: 'schedule', width: "150px" },
       // { text: 'Client', value: 'aics_client', },
        { text: 'Client', value: 'aics_client.full_name', },
        { text: 'Mobile No.', value: 'aics_client.mobile_number', },
        { text: 'Assistance', value: 'aics_type', },
        { text: 'Office', value: 'office' },
        { text: 'Status', value: 'status', width: "100px", filter: this.StatusFilter },
        { text: 'Actions', value: 'actions', width: "150px" },
      ],
      assistances: [],
      dialog_create: false,
      dialog_data: {},
      isLoading: false,
      status_list: [
        "",
        "Pending",
        "Verified",
        "Served",
        "Rejected",
      ],
      StatusFilterValue: "",
      search: "",
    }

  }, watch:
  {
    status(s) {
      console.log(s);
      this.StatusFilterValue = s;
    }
  },

  methods: {
    status_color(c) {


      switch (c) {
        case "Rejected":
          return "red";
          break;
        case "Served":
          return "gray";
          break;
        case "Serving":
          return "green";
          break;
        case "Verified":
          return "blue";
          break;
        case "Pending":
          return "orange"
          break;
        default:
          return "gray";
          break;
      }
    },
    getAssistances() {
      this.isLoading = true;
      axios.get(route("assistances.index"))
        .then(response => {
          // console.log(response.data);
          this.assistances = response.data;
          this.isLoading = false;
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
    },
    StatusFilter(value) {

      if (!this.StatusFilterValue) {
        return true;
      }

      return value === this.StatusFilterValue;
    }
  },
  mounted() {
    this.getAssistances()


    this.StatusFilterValue = this.status
  }

}
</script>

