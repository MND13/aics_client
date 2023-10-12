<template>
    <v-container>
        <v-row>


            <v-col cols="9" class="ma-auto">

                <v-card flat tile outlined class="p-auto text-center">
                    <img src="/images/DSWD-DVO-LOGO.png" class="m-5" style="max-width: 60%; height: auto;" />

                    <v-spacer></v-spacer>
                    <v-card-title class="justify-center">HI, {{ user.full_name }}! <br>
                        Please Verify Your Account <br></v-card-title>
                    <v-card-subtitle>We are sending a OTP to validate your mobile number: {{ user.mobile_number }}
                    </v-card-subtitle>
                    <v-spacer></v-spacer>
                    <v-card-text class="justify-center ">
                        <v-row>
                            <v-col>
                                <v-form>
                                    <div class="ma-auto" style="max-width: 300px">
                                        <v-otp-input v-model="formData.otp" length="6"></v-otp-input>
                                        <v-btn color="primary" class="mb-3" @click="submitForm" :loading="loading"
                                            :disabled="submit">
                                            Submit
                                        </v-btn>
                                    </div>

                                </v-form>




                            </v-col></v-row>


                    </v-card-text>
                    <v-card-actions class="justify-center">
                        Didn't receive a code?
                        <v-btn :loading="loading" class="ma-1" color="error" plain @click="otp_gen">
                            Resend
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>

import userMixin from './../Mixin/userMixin.js'
import authMixin from './../Mixin/authMixin.js'
import { debounce, cloneDeep, isEmpty } from 'lodash'

export default {
    mixins: [userMixin, authMixin],
    data() {
        return {
            submit: false,
            formData : {},
            loading: false,
            submit: false,
            errors: [],
        }
    },
    methods:
    {
        submitForm() {
            this.submit = true;
            axios.post(route("api.otp.verify"), this.formData).then(response => {
                console.log(response.data);
                
            }).catch(error => console.log(error));

            this.submit = false;
            
        },
        otp_gen() {
            this.loading = true;
            axios.post(route("api.otp.generate")).then(repsonse => {
                console.log(response);
                this.loading = false;
            }).catch(err => console.log(err))
        }
    }
}
</script>

<style></style>