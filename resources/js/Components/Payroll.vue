<template>
  <div>
    <v-dialog v-model="openModal">
      <v-card>
        <v-card-title>New Payroll</v-card-title>
        <v-card-text>
          <form @submit.prevent="submit" enctype="multipart/form-data" action="">
            <label for=""> Title</label>
            <input type="text" name="" v-model="formData.title" id="" class="form-control" />

            <!--<label for="">Region</label>
            <input
              type="text"
              name=""
              v-model="formData.region"
              id=""
              class="form-control"
            />

            <label for="">Province</label>
            <input
              type="text"
              name=""
              v-model="formData.province"
              id=""
              class="form-control"
            />

            <label for="">Municipality/City</label>
            <input
              type="text"
              name=""
              v-model="formData.muni_city"
              id=""
              class="form-control"
            />

            <label for="">Barangay</label>
            <input
              type="text"
              name=""
              v-model="formData.barangay"
              id=""
              class="form-control"
            />-->

            <div class="row mt-2">
              <div class="col-md-3">
                <label>Region <small>(Ex. NCR)</small>
                  <span color="red">*</span>
                </label>
                <select id="psgc_id" name="" v-model="region_selector" v-if="regions" class="form-control"
                  @change="getPsgc">
                  <option value=""></option>
                  <option :value="e" v-for="(e, i) in regions" :key="i">
                    {{ i }}
                  </option>
                </select>

                <div v-if="validationErrors && validationErrors.psgc_id" style="color: red">
                  {{ validationErrors.psgc_id[0] }}
                </div>
              </div>

              <div class="col-md-3">
                <label>Province/District <small>(Ex. Dis. III)</small>
                  <span color="red">*</span>
                </label>
                <select id="psgc_id" name="" v-model="province_selector" v-if="provinces" class="form-control">
                  <option :value="e" v-for="(e, i) in provinces" :key="i">
                    {{ i }}
                  </option>
                </select>

                <div v-if="validationErrors && validationErrors.psgc_id" style="color: red">
                  {{ validationErrors.psgc_id[0] }}
                </div>
              </div>

              <div class="col-md-3">
                <label>
                  City/Municipality <small>(Ex. Quezon City)</small>
                  <span color="red">*</span>
                </label>

                <select name="" v-model="city_selector" v-if="cities" class="form-control">
                  <option :value="e" v-for="(e, i) in cities" :key="i">
                    {{ i }}
                  </option>
                </select>

                <div v-if="validationErrors && validationErrors.psgc_id" style="color: red">
                  {{ validationErrors.psgc_id[0] }}
                </div>
              </div>

              <div class="col-md-3">
                <label>Barangay
                  <small>(Ex. Batasan Hills)</small>
                  <span color="red">*</span>
                </label>
                <select id="psgc_id" name="" v-model="formData.psgc_id" v-if="cities" class="form-control">
                  <option :value="e[0].id" v-for="(e, i) in barangays" :key="i">
                    {{ i }}
                  </option>
                </select>

                <div v-if="validationErrors && validationErrors.psgc_id" style="color: red">
                  {{ validationErrors.psgc_id[0] }}
                </div>
              </div>
            </div>

            <label for="">Schedule</label>
            <input type="date" name="" v-model="formData.schedule" id="" class="form-control" />

            <label for="">Amount</label>
            <input type="number" name="" v-model="formData.amount" id="" class="form-control" />

            <label for="">Certified By (1)</label>
            <input type="text" name="" v-model="formData.certified_by1" id="" class="form-control" />

            <label for="">Certified By (2)</label>
            <input type="text" name="" v-model="formData.certified_by2" id="" class="form-control" />

            <label for="">Approved By</label>
            <input type="text" name="" v-model="formData.approved_by" id="" class="form-control" />

            <label for="">SDO</label>
            <input type="text" name="" v-model="formData.sdo" id="" class="form-control" />

            <label for="">Fund Source</label>
            <input type="text" name="" v-model="formData.source_of_fund" id="" class="form-control" />

            <label for="">Charging</label>
            <input type="text" name="" v-model="formData.charging" id="" class="form-control" />

            <label for="">Status</label>
            <select name="" id="" v-model="formData.status" class="form-control">
              <option :value="e" v-for="(e, i) in ['active', 'closed']" :key="i">{{ e }}</option>
            </select>

            <button type="submit" class="btn btn-primary btn-block">
              SUBMIT
            </button>
          </form>
        </v-card-text>
      </v-card>
    </v-dialog>

    <v-card flat>

      <v-card-title>Payroll <v-spacer />
        <v-progress-circular :width="3" indeterminate color="primary" v-if="isExporting"></v-progress-circular>

        <v-btn @click="NewPayroll()" dark class="m-1"
          v-if="hasRoles(['super-admin', 'admin'])">New Payroll</v-btn>
      </v-card-title>
      <v-card-text>
        <v-data-table :headers="headers" :items="payrolls" :items-per-page="50" :loading="isBusy"
          loading-text="Loading... Please wait" :search="search">
          <template v-slot:item.province="{ item }">
            {{ item.psgc.province_name }}
          </template>

          <template v-slot:item.muni_city="{ item }">
            {{ item.psgc.city_name }}
          </template>

          <template v-slot:item.barangay="{ item }">
            {{ item.psgc.brgy_name }}
          </template>

          <template v-slot:item.actions="{ item }">
            <!--<v-icon small class="mr-5" @click="DownloadPayroll(item)"  :disabled="isExporting">
              mdi-download
            </v-icon>-->

            <v-icon small class="mr-5" @click="ViewList(item.id)">
              mdi-eye
            </v-icon>

            <v-icon small class="mr-5" @click="EditItem(item)"
              v-if="hasRoles(['super-admin', 'admin'])">
              mdi-pencil
            </v-icon>

            <v-menu bottom left>
              <template v-slot:activator="{ on, attrs }">

                <v-icon small v-bind="attrs" v-on="on"
                  v-if="hasRoles(['super-admin', 'admin'])">mdi-download</v-icon>

              </template>

              <v-list>
                <v-list-item link>
                  <v-list-item-title @click="DownloadPayroll(item, 'unclaimed')" :disabled="isExporting">
                    Export (Unclaimed)
                  </v-list-item-title>
                </v-list-item>
                <v-list-item link>
                  <v-list-item-title @click="DownloadPayroll(item)" :disabled="isExporting">
                    Export (CRIMS)
                  </v-list-item-title>
                </v-list-item>
                <v-list-item link>
                  <v-list-item-title @click="exportCoe(item.id)">
                    Export (COE)
                  </v-list-item-title>
                </v-list-item>
              </v-list>
            </v-menu>

           <!-- <v-icon small class="mr-5" @click="DeleteItem(item)"  v-if="userData.role == 'Super-Admin'">
              mdi-delete
            </v-icon>-->
          </template>
        </v-data-table>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
import { debounce } from "lodash";
import userMixin from './../Mixin/userMixin.js'

export default {
  mixins: [userMixin],
  props: ['user'],
  data() {
    return {
      openModal: false,
      formData: {
        title: "",
      },
      search: "",
      isBusy: false,
      headers: [
        { value: "schedule", text: "Schedule", sortable: true },

        { value: "title", text: "Title", sortable: true },
        { value: "sdo", text: "SDO", sortable: true },

        { value: "province", text: "Province", sortable: true },
        { value: "muni_city", text: "City/Municipality", sortable: true },
        { value: "barangay", text: "Barangay", sortable: true },

        { value: "amount", text: "AMOUNT", sortable: true },
        { value: "status", text: "STATUS", sortable: true },
        { value: "actions", text: "Actions", sortable: true },
      ],
      payrolls: [],
      provinces: {},
      cities: {},
      regions: {},
      barangays: {},
      region_selector: {},
      province_selector: {},
      city_selector: {},
      validationErrors: {},
      search: "",
      isExporting: false,
    };
  },
  watch: {
    region_selector(newVal, oldVal) {
      ((this.provinces = {}), (this.cities = {}), (this.barangays = {})),
        (this.provinces = this.groupByKey(newVal, "province_name"));
    },

    province_selector(newVal, oldVal) {
      ((this.cities = {}), (this.barangays = {})),
        (this.cities = this.groupByKey(newVal, "city_name"));
    },

    city_selector(newVal, oldVal) {
      (this.barangays = {}),
        (this.barangays = this.groupByKey(newVal, "brgy_name"));
    },
  },
  methods: {
    submit() {
      this.isBusy = true;
      if (this.formData.id > 0) {
        axios
          .post(route("api.payroll.update", this.formData.id), this.formData)
          .then((response) => {
            //console.log(response.data);
            this.isBusy = false;
            this.getPayrolls();
            this.openModal = false;
          })
          .catch((error) => console.log(error));
      } else {
        axios
          .post(route("api.payroll.create"), this.formData)
          .then((response) => {
            this.isBusy = false;
            this.data = response.data;

            if (response.data.Message == "Saved") {
              this.getPayrolls();
              this.openModal = false;
            } else {
              if (response.data.Message.errorInfo) {
                alert(response.data.Message.errorInfo[2]);
              } else {
                alert(response.data.Message);
              }
            }
          })
          .catch((error) => console.log(error));
      }
    },
    getPayrolls() {
      axios.get(route("api.payroll.index")).then((response) => {
        this.payrolls = response.data;
      });
    },
    NewPayroll() {
      this.formData = {};
      this.openModal = true;
    },
    EditItem(e) {
      this.formData = {};
      this.formData = e;
      this.openModal = true;
    },
    DeleteItem(e) {
      let x = confirm("Delete this Payroll?");
      if (x) {
        axios
          .post(route("api.payrolls.delete", e.id))
          .then((response) => {
            // console.log(response.data);
            if (response.data.Message == "Deleted") {
              alert(response.data.Message);
              this.getPayrolls();
            } else {
              alert(response.data.Message);
            }
          })
          .catch((error) => console.log(error));
      } else {
        alert("Cancelled");
      }
    },
    DownloadPayroll: debounce(function (payroll, status = "claimed") {
      this.isExporting = true;
      axios.post(route('api.payroll.export', payroll.id), {status})
        .then((res) => {
          this.isExporting = false;
          window.location.href = res.data.file;
        })
        .catch(err => {
          console.warn(err);
          this.isExporting = false;
        });
    }, 250),
    ViewList(id) {
      //this.$router.push("payroll/" + id);
      this.$router.push({ name: "payroll_list", params: { id: id } });
    },
    getPsgc() {
      axios
        .get(route("api.psgc.show", "brgy"), {
          params: {
            field: "region_psgc",
            value: this.region_selector[0].region_psgc,
          },
        })
        .then((response) => {
          this.psgc = response.data;
          this.provinces = this.groupByKey(this.psgc, "province_name");
        });
    },
    groupByKey(array, key) {
      return array.reduce((hash, obj) => {
        if (obj[key] === undefined) return hash;
        return Object.assign(hash, {
          [obj[key]]: (hash[obj[key]] || []).concat(obj),
        });
      }, {});
    },
    exportCoe(id) {
      window.open(route("api.payroll.print_coe", id));
    },
  },
  mounted() {
    this.getPayrolls();

    axios.get(route("api.psgc.show", "region")).then((response) => {
      this.regions = this.groupByKey(response.data, "region_name");
    });
  },
};
</script>

<style></style>