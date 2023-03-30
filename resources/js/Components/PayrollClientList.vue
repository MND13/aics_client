<template>
  <v-card flat outlined>


    <v-card-title v-if="userData.role == 'Admin' || userData.role == 'Super-Admin'">

      <!--<v-btn @click="print_options=!print_options" color="black" dark class="m-1">Print</v-btn>-->

      <v-btn @click="print()" color="black" dark class="m-1">
        <v-icon> mdi-printer </v-icon> Print Current Page:{{ page }}</v-btn>

      <v-btn @click="print_w_gt()" color="black" dark class="m-1">
        <v-icon> mdi-printer </v-icon> Print last Page w/ Footer & Grand Total
      </v-btn>




      <v-icon @click="print_footer()"> mdi mdi-foot-print</v-icon>
      <!--<v-icon>mdi-download</v-icon>-->

    </v-card-title>
    <v-card-text>
      <div class="row">
        <div class="col-md-12 text-center d-print-block">
          <h6>{{ data.title }}</h6>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6" v-if="data.psgc">
          CITY/MUNICIPALITY : {{ data.psgc.city_name }}<br />
          BARANGAY : {{ data.psgc.brgy_name }} <br />
          SCHEDULE : {{ data.schedule }}
        </div>
        <div class="col-md-6 text-end">
          <v-spacer></v-spacer>
          <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details
            class="d-print-none"></v-text-field>
        </div>
      </div>
      <div class="col-md-12 text-end" v-if="userData.role == 'Admin' || userData.role == 'Super-Admin'">

        <v-btn v-if="selected.length > 0" color="black" class="white--text" @click="MarkAsClaimed()">Mark as
          Claimed</v-btn>

        <v-btn v-if="selected.length > 0" color="black" class="white--text" @click="MarkAsUnClaimed()">Mark as
          Unclaimed</v-btn>

        <v-btn v-if="selected.length > 0" @click="PrintGISMany()" color="black" dark class="m-1">
          <v-icon> mdi-printer </v-icon> Print GIS
        </v-btn>

      </div>
      <v-data-table show-select v-model="selected" v-if="data.clients" :headers="headers" :items="data.clients.map((i, index) => {
        i.key = index + 1
        return i;
      })" :items-per-page="10" :loading="isBusy" loading-text="Loading... Please wait" :search="search"
        @update:page="currentPage" :footer-props="{
          'items-per-page-options': [10],
          'disable-items-per-page': true,
          'showFirstLastPage': true,

        }" :page="page">

        <template v-slot:footer.page-text="{ pageStart, pageStop, itemsLength }">
          <v-row align="center" no-gutters>
            <v-col>
              <label for="">Page No.</label>
              <input type="text" name="" id="" class="form-control" v-model="page">
            </v-col>
            <v-col>
              {{ pageStart }} of {{ itemsLength }}
            </v-col>
          </v-row>

        </template>

        <template v-slot:item.key="{ item }">
          {{ item.key }}
        </template>


        <template v-slot:item.last_name="{ item }">
          {{ item.aics_client.last_name }}
        </template>
        <template v-slot:item.ext_name="{ item }">
          {{ item.aics_client.ext_name }}
        </template>
        <template v-slot:item.first_name="{ item }">
          {{ item.aics_client.first_name }}
        </template>
        <template v-slot:item.middle_name="{ item }">
          {{ item.aics_client.middle_name }}
        </template>
        <template v-slot:item.amount="{ item }">
          {{ data.amount }}
        </template>

        <!--template v-slot:item.actions="{ item }">
          <v-icon small class="mr-2" @click="EditItem(item)">
            mdi-pencil
          </v-icon>
        </template>-->

        <template v-slot:item.status="{ item }">
          <v-edit-dialog :return-value.sync="item.status" large persistent @save="save(item)" @cancel="cancel(item)"
            v-if="userData.role == 'Admin' || userData.role == 'Super-Admin'">
            <div>{{ item.status }}</div>
            <template v-slot:input>
              <div class="mt-4 text-h6">Claim Status</div>
              <select class="form-control" v-model="item.status">

                <option value="claimed">Claimed</option>
                <option value="">Unclaimed</option>
                <option value="cancelled-revalidate">Cancelled (Revalidate)</option>
              </select>
            </template>
          </v-edit-dialog>
        </template>

        <template v-slot:item.actions="{ item }">
        <v-icon small class="mr-2" @click="PrintGIS(item)" >
          mdi-printer
        </v-icon>
      </template>

      </v-data-table>
    </v-card-text>

    <!--<v-dialog v-model="print_options" width="300px" height="300px">
      <v-card>
        <v-card-title></v-card-title>
        <v-card-text>

         Select Page: 
         <input type="text" name="" id="" v-model="page" class="form-control">
          <label>
          With Grand Total &
          Footer? </label> <input type="checkbox" name="" id="" v-model="gt"  >

        </v-card-text>
      </v-card>
    </v-dialog>-->






  </v-card>
</template>

<script>
import { isEmpty } from 'lodash';
import { debounce } from "lodash";
import userMixin from './../Mixin/userMixin.js'


export default {
  mixins: [userMixin],
  props: ["id", "user"],
  data() {
    return {
      data: [],
      isBusy: false,
      headers: [
        { value: "sequence", text: "No.", sortable: false, width: "20px;" },
        { value: "last_name", text: "Last Name", sortable: false, width: "100px;" },
        { value: "ext_name", text: "Ext", sortable: false, width: "20px;" },
        { value: "first_name", text: "First Name", sortable: false },
        { value: "middle_name", text: "Middle Name", sortable: false },

        { value: "amount", text: "Amount", sortable: false },

        { value: "status", text: "Status", sortable: false, width: "50px;" },
        { value: "actions", text: "Actions", sortable: false, width: "50px;" },
      ],
      search: "",
      page: 1,
      print_options: false,
      selected: [],
      url: "",
    };
  },
  computed: {
    lastPage() { return Math.ceil(this.data.clients.length / 10); }
  },
  watch() {
    id();
    {
      this.getClients();
    }

  },
  methods: {
    print() {
      window.open(
        route("api.payroll.printv2", { id: this.id, page: this.page }),
        "_blank"
      );
    },
    print_w_gt() {
      window.open(
        route("api.payroll.printv2", { id: this.id, page: this.lastPage, gt: 1 }),
        "_blank"
      );
    },
    print_footer() {
      window.open(route("api.payroll.print_footer", this.id), "_blank");
    },
    getClients() {
      axios
        .get(route("api.payroll.show", this.id))
        .then((res) => {
          this.data = res.data;
          this.isBusy = false;
        })
        .catch((error) => console.log(error));
    },
    async save(e) {
      // console.log("in save");
      //console.log(e);
      return axios.put(route("api.payroll-clients.update", e.id), e);
    },
    cancel(e) {
      e;
    },
    currentPage(e) {
      this.page = e;
    },
    isEmpty(value) {
      return isEmpty(value);
    },
    MarkAsClaimed: debounce(async function () {
      //let ids = this.selected.map(item => item.id);
      let promises = [];
      for (let index = 0; index < this.selected.length; index++) {
        const item = this.selected[index];
        item.status = "claimed";
        promises.push(this.save(item));
      }
      await Promise.all(promises);
      this.getClients();
    }, 250),
    MarkAsUnClaimed: debounce(async function () {
      //let ids = this.selected.map(item => item.id);
      let promises = [];
      for (let index = 0; index < this.selected.length; index++) {
        const item = this.selected[index];
        item.status = "";
        promises.push(this.save(item));
      }
      await Promise.all(promises);
      this.getClients();
    }, 250),
    PrintGISMany() {
      console.log(this.selected);
      let ids = this.selected.map(item => item.aics_client_id);

      ids.forEach(id => {

        window.open(
          route("api.pdf.gis2", { id: id }),
          "_blank"
        );

      });

      /*window.open(
        route("api.pdf.gis2", { id: item.id }),
        "_blank"
      );*/


    },

    PrintGIS(item) {
      window.open(
        route("api.pdf.gis2", { id: item.aics_client_id }),
        "_blank"
      );
    },



  },
  mounted() {
    //console.log("list");
    // console.log(this.id);
    this.isBusy = true;
    this.getClients();

    this._keyListener = function (e) {
      if (e.key === "g" && (e.ctrlKey || e.metaKey)) {
        e.preventDefault(); // present "Save Page" from getting triggered.

        this.PrintGISMany()
      }
    };

    document.addEventListener('keydown', this._keyListener.bind(this));

  },

};
</script>

<style></style>