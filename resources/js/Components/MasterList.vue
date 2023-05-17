<template>
  <div>
    <v-dialog v-model="dialog_create" width="80%" fullscreen>
      <v-card>
        <v-card-title>
          <v-spacer></v-spacer>
          <v-btn icon @click="dialog_create = false">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-card-title>
        <v-card-text>
          <GISComponent :dialog_data="dialogData_edit" :getList="getList" :user-data="userData" :set-dialog-create="setDialogCreate"></GISComponent>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-card flat>
      <v-card-title>
        Master List
        <v-spacer></v-spacer>
        <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>
      </v-card-title>
      <v-card-text>
        <v-data-table dense :headers="headers" :items="data" :items-per-page="10" :loading="isBusy"
          loading-text="Loading... Please wait" :search="search">
          <template v-slot:item.status="{ item }">

            <v-chip v-if="item.payroll_client">
              In Payroll {{ item.payroll_client.payroll.amount }}
              | Client No: {{ item.payroll_client.sequence }}
              <span v-if="item.payroll_client.status"> | {{ item.payroll_client.status }}</span>
            </v-chip>
          </template>

          <template v-slot:item.barangay="{ item }">
            <span v-if="item.psgc"> {{ item.psgc.brgy_name }}</span>
          </template>

          <template v-slot:item.city_muni="{ item }">
            <span v-if="item.psgc"> {{ item.psgc.city_name }}</span>
          </template>

          <template v-slot:item.province="{ item }">
            <span v-if="item.psgc"> {{ item.psgc.province_name }}</span>
          </template>

          <template v-slot:item.region="{ item }">
            <span v-if="item.psgc"> {{ item.psgc.region_name }}</span>
          </template>

          <template v-slot:item.actions="{ item }">
            <v-icon small class="mr-2" @click="EditItem(item)">
              mdi-pencil
            </v-icon>


            <v-icon small class="mr-2" @click="PrintGIS(item)" v-if="item.payroll_client && item.payroll_client.payroll_id">
              mdi-printer
            </v-icon>

          </template>
        </v-data-table>
      </v-card-text>
    </v-card>
  </div>
</template>
 
<script>
import GISComponent from "./GISComponent.vue";
import userMixin from './../Mixin/userMixin.js'

export default {
  mixins: [userMixin],
  components: { GISComponent },
  data() {
    return {
      search: "",
      data: [],
      isBusy: true,

      perPage: 100,
      currentPage: 1,
      dialog_create: false,
      dialogData_edit: {},
      headers: [
        { value: "first_name", text: "First Name", sortable: true },
        { value: "middle_name", text: "Middle Name", sortable: true },
        { value: "last_name", text: "Last Name", sortable: true },
        { value: "ext_name", text: "Ext", sortable: true },
        { value: "birth_date", text: "DOB", sortable: true },
        { value: "barangay", text: "Barangay", sortable: true },
        { value: "city_muni", text: "City/Muni", sortable: true },
        { value: "province", text: "Province", sortable: true },
        //{ value: "region", text: "Region", sortable: true },

        { value: "status", text: "Status", sortable: true },
        { value: "actions", text: "Actions" },
      ],
    };
  },

  methods: {
    getList() {
      this.isBusy = true;
      axios
        .get(route("api.clients"))
        .then((response) => {
          this.isBusy = false;
          this.data = response.data;
        })
        .catch((error) => console.log(error));
    },
    EditItem(item) {
      this.dialog_create = true;
      this.dialogData_edit = {};
      this.dialogData_edit = item;
    },
    PrintGIS(item) {
      window.open(
        route("api.pdf.gis2", { id: item.id }),
        "_blank"
      );
    },
    setDialogCreate(value){
      this.dialog_create = value;
    }
  },
  mounted() {
    this.getList();
  },
};
</script>