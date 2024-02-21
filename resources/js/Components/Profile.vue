<template>
    <v-row dense>
        <v-col cols="12" md="2" sm="2" dense no-gutters>

            <v-list dense>
                <v-list-item v-for="(item, index) in menuItems" :key="index" @click="changeContent(index)"
                    :class="{ 'selected': selectedItem === index }">

                    <v-list-item-title>{{ item.text }}</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-col>

        <v-col cols="12" md="10" sm="10" dense>

            <v-card dense flat>

                <v-card-text>
                    <v-container v-if="selectedItem !== null">
                        <!-- Render different content based on selected item -->
                        <div v-if="selectedItem === 0"> <v-card flat>
                                <div class="d-flex flex-no-wrap ">
                                    <v-avatar class="ma-3" size="125" rounded="0"
                                        v-if="user.profile_pic && user.profile_pic.file_directory">
                                        <v-img :src="user.profile_pic.file_directory"></v-img>
                                    </v-avatar>
                                    <v-skeleton-loader v-else type="list-item-avatar"></v-skeleton-loader>
                                    <div>
                                        <v-card-title class="text-h5">
                                            {{ user.first_name }} {{ user.middle_name }} {{ user.last_name }} {{
                                                user.ext_name }}
                                        </v-card-title>

                                        <v-card-subtitle> USERNAME: {{ user.username }}

                                        </v-card-subtitle>

                                        <v-card-actions></v-card-actions>
                                    </div>
                                </div>
                            </v-card>



                            <v-card flat>

                                <v-card-text>


                                    <v-row>
                                        <v-col cols="12" md="3">
                                            <label>
                                                Apelyido <small>(Last name) </small>
                                            </label>
                                            {{ profile.last_name }}
                                        </v-col>
                                        <v-col cols="12" md="3">
                                            <label>
                                                Unang Pangalan <small>(First name) </small>
                                            </label>
                                            {{ profile.first_name }}
                                        </v-col>
                                        <v-col cols="12" md="3">
                                            <label>
                                                Gitnang Pangalan <small>(Middle name) </small>
                                            </label>
                                            {{ profile.middle_name }}
                                        </v-col>
                                        <v-col cols="12" md="3">
                                            <label>
                                                Ext <small>(Sr.,Jr., II, III) </small>
                                            </label>
                                            {{ profile.ext_name }}
                                        </v-col>
                                    </v-row>


                                    <hr>
                                    <v-row>
                                        <v-col cols="12" md="12">
                                            <label>House No./Street/Purok <small>(Ex. 123 Sun St.) </small> </label>
                                            {{ profile.street_number }}
                                        </v-col>
                                    </v-row>
                                    <hr>
                                    <v-row v-if="profile.psgc" class="d-flex">
                                        <v-col cols="12" md="3">
                                            <label for="">Region <small>(Ex. NCR)</small></label>
                                            {{ profile.psgc.region_name }}
                                        </v-col>
                                        <v-col cols="12" md="3">
                                            <label for="">Province/District <small>(Ex. Dis. III)</small></label>
                                            {{ profile.psgc.province_name }}
                                        </v-col>
                                        <v-col cols="12" md="3">
                                            <label for="">City/Municipality <small>(Ex. Quezon City)</small></label>
                                            {{ profile.psgc.city_name }}
                                        </v-col>
                                        <v-col cols="12" md="3">
                                            <label for="">Barangay <small>(Ex. Batasan Hills)</small></label>
                                            {{ profile.psgc.brgy_name }}
                                        </v-col>
                                    </v-row>
                                    <hr>
                                    <v-row class="d-flex">
                                        <v-col cols="12" md="3">
                                            <label for="">Telepono <small>(Mobile Number) </small></label>
                                            {{ profile.mobile_number }}
                                        </v-col>
                                        <v-col cols="12" md="3">
                                            <label for="">Kapanganakan <small>(Birthdate)</small></label>
                                            {{ profile.birth_date }}
                                        </v-col>
                                        <v-col cols="12" md="3">
                                            <label for="">Edad <small>(Age)</small></label>
                                            {{ profile.age }}
                                        </v-col>
                                        <v-col cols="12" md="3">
                                            <label for="">Kasarian <small>(gender)</small></label>
                                            {{ profile.gender }}
                                        </v-col>
                                    </v-row>
                                    <hr>
                                    <v-row class="d-flex">
                                        <v-col cols="12" md="3">
                                            <label for="">Trabaho <small>(Occupation) </small></label>
                                            {{ profile.occupation }}
                                        </v-col>
                                        <v-col cols="12" md="3">
                                            <label for="">Buwanang Kita <small>(Monthly Salary)</small></label>
                                            {{ profile.monthly_salary }}
                                        </v-col>
                                        <v-col cols="12" md="3">
                                            <label for="">Civil Status</label>
                                            {{ profile.civil_status }}
                                        </v-col>
                                        <v-col cols="12" md="3">
                                            <label for="">Mobile Verified</label>

                                            <div v-if="profile.mobile_verified > 0">Yes</div>
                                            <div v-else>No</div>

                                        </v-col>


                                    </v-row>

                                </v-card-text>
                            </v-card>

                            <v-card v-if="hasRoles(['social-worker', 'admin', 'super-admin'])" class="mt-2" flat>
                                <v-card-text>
                                    <v-row>
                                        <v-col cols="12" md="6">
                                            <label for="">Office</label> {{ profile.office.name }} <br>
                                            {{ profile.office.address }}
                                        </v-col>

                                        <v-col cols="12" md="6">
                                            <label for="">Role</label>
                                            <ul>
                                                <li v-for="role in  profile.roles" :key="role.id">{{ role.name }}</li>
                                            </ul>

                                        </v-col>
                                    </v-row>
                                </v-card-text>
                            </v-card>
                        </div>
                        <div v-if="selectedItem === 1">

                            <v-card flat>
                                <v-card-text>

                                    <v-form ref="form">

                                        <v-text-field v-model="formData.password"
                                            :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                                            :type="showPassword ? 'text' : 'password'" name="input-10-1" label="Password"
                                            hint="At least 8 characters" counter
                                            @click:append="showPassword = !showPassword"
                                            :error-messages="formErrors.password"></v-text-field>

                                        <v-text-field v-model="formData.password_confirmation"
                                            :append-icon="showPasswordConfirmation ? 'mdi-eye' : 'mdi-eye-off'"
                                            :type="showPasswordConfirmation ? 'text' : 'password'" name="input-10-1"
                                            label="Confirm Password" hint="At least 8 characters" counter
                                            @click:append="showPasswordConfirmation = !showPasswordConfirmation"></v-text-field>

                                        <v-btn color="primary" class="mr-4" @click="submitForm" :disabled="submit">
                                            Update
                                        </v-btn>
                                    </v-form>
                                </v-card-text>
                            </v-card>
                        </div>



                    </v-container>
                </v-card-text>
            </v-card>





        </v-col>




    </v-row>
</template>

<style>
label {
    display: block;
    font-weight: bold;

}


.selected {
    background-color: #f0f0f0;
}
</style>

<script>
import userMixin from './../Mixin/userMixin.js'
import { debounce, cloneDeep, isEmpty } from 'lodash'

export default {
    mixins: [userMixin],
    props: ['user'],
    data() {
        return {
            formData: [],
            profile: [],
            formErrors: [],
            showPassword: false,
            showPasswordConfirmation: false,
            submit: false,
            selectedItem: 0,
            menuItems: [
                { text: 'Profile', },
                { text: 'Change Password', }
                // Add more menu items as needed
            ]

        }
    },
    methods: {
        getProfile() {
            axios.get(route("users.show", { user: this.user.id })).then(res => {
                this.profile = cloneDeep(res.data);
                this.formData = cloneDeep(res.data);
            })
        },
        submitForm() {
            axios.patch(route('users.update', this.formData.id), this.formData)
                .then(res => {
                    console.log(res);
                })
                .catch(err => {
                    this.submit = false;
                    this.formErrors = err.response.data.errors
                })
        },

        changeContent(index) {
            this.selectedItem = index;
        }

    },
    mounted() {
        this.getProfile();
    }

}
</script>

<style></style>