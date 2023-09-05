<template>
    <div>
        <v-col cols="12"> <v-text-field v-model="bene.street_number" label="House No./Street/Purok*" outlined dense
                :error-messages="formErrors.street_number"></v-text-field>
        </v-col>

        <v-col cols="12" md="2">

            <v-select label="Region" outlined dense :items="['XI']">
            </v-select>
        </v-col>
        <v-col cols="12" md="3">
            <v-autocomplete v-model="province_name" :loading="loading" :items="provinces" @change="getCities()" cache-items
                hide-no-data hide-details label="Province" outlined item-text="province_name" item-value="id"
                dense></v-autocomplete>
        </v-col>
        <v-col cols="12" md="3">
            <v-autocomplete v-model="city_name" :disabled="!cities" :loading="loading" :items="cities" @change="getBrgys()"
                hide-no-data hide-details label="City/Municipality" outlined item-text="city_name" item-value="id"
                dense></v-autocomplete>
        </v-col>
        <v-col cols="12" md="3">
            <v-autocomplete v-model="bene.psgc_id" :disabled="!brgys" :loading="loading" :items="brgys" hide-no-data
                hide-details label="Barangay" outlined item-text="brgy_name" item-value="id" dense
                :error-messages="formErrors.psgc_id"></v-autocomplete>

        </v-col>
    </div>
</template>

<script>
export default {
    
    data() {
        return {
            province_name: "",
            city_name: "",
            cities: [],
            brgys: [],
        }
    },
    methods: {
        getCities() {
            this.cities = [];
            this.brgys = [];
            this.loading = true;
            axios.get(route("api.psgc.show", { type: "cities", field: "province_name", value: this.province_name })).then(response => {
                this.cities = response.data;
                this.loading = false;
            }).catch(error => { console.log(error); this.loading = false; });
        },

        getBrgys() {
            this.loading = true;
            axios.get(route("api.psgc.show", { type: "brgy", field: "city_name", value: this.city_name })).then(response => {
                this.brgys = response.data;
                this.loading = false;
            }).catch(error => { console.log(error); this.loading = false; });
        },
    }

}
</script>

<style></style>