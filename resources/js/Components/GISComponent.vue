<template>
  <div class="container-fluid">

    <div class="row">
      <div class="col-md-12"
        v-if="hasRoles(['social-worker', 'admin', 'super-admin']) && (gis_data.status == 'Serving' || gis_data.status == 'Served')">
        <v-spacer></v-spacer>
        <v-btn dark @click="PrintGIS()">Print GIS</v-btn>
        <v-btn dark @click="PrintCOE()">Print COE</v-btn>
        <v-btn dark @click="PrintCAV()"
          v-if="gis_data.assessment && gis_data.assessment.mode_of_assistance == 'CAV'">Print CAV</v-btn>
        <v-btn dark @click="PrintGL()" v-else>Print GL</v-btn>
      </div>
    </div>
    <div class="row g-2">
      <div class="col-md-3">

        <v-card flat outlined tile>
          <v-card-text>
            <v-row v-if="gis_data.aics_client">
              <v-col cols="6" class="d-flex child-flex" md="6" v-for="(images, i) in gis_data.aics_client.profile_docs"
                :key="i">
                <a :href="images.file_directory" target="_blank">
                  <v-img :src="images.file_directory" max-height="100px" height="auto" width="100%"></v-img>
                </a>
              </v-col>
            </v-row>
            <v-skeleton-loader v-else type="list-item-avatar-three-line"></v-skeleton-loader>
          </v-card-text>
        </v-card>

        <v-card tile flat outlined class="mt-2">
          <v-card-subtitle class="indigo--text">Submission Info</v-card-subtitle>
          <v-list dense v-if="gis_data.status">
            <v-list-item two-line v-for="(e, i) in submission_info" :key="i">
              <v-list-item-content>
                <v-list-item-title> {{ e.title }} </v-list-item-title>
                <v-list-item-subtitle v-if="e.title == 'Status'">

                  <v-chip class="white--text" dark small :color="status_color(e.value)"> {{
                    e.value
                  }} </v-chip> as of {{ gis_data.status_date | formatDateOnly }}</v-list-item-subtitle>

                <v-list-item-subtitle v-else>{{ e.value }}</v-list-item-subtitle>

                <v-divider></v-divider>
              </v-list-item-content>
            </v-list-item>
          </v-list>
          <v-skeleton-loader v-else type="article"></v-skeleton-loader>
        </v-card>


        <v-card flat tile outlined class="mt-2">
          <v-list dense>
            <v-list-item two-line>
              <v-list-item-content>
                <v-list-item-title>Schedule for Interview: </v-list-item-title>
                <v-list-item-subtitle>
                  <span v-if="gis_data.status == 'Pending'">
                    <v-text-field type="datetime-local" v-model="schedule"
                      :error-messages="schedule_error"></v-text-field>
                  </span>
                  <span v-else>{{ gis_data.schedule | formatDate }}</span>

                </v-list-item-subtitle>
                <v-divider></v-divider>
              </v-list-item-content>
            </v-list-item>

            <v-list-item two-line>
              <v-list-item-content>
                <v-list-item-title>Remarks:</v-list-item-title>
                <v-list-item-subtitle>
                  <span v-if="gis_data.status == 'Pending'">
                    <v-text-field v-model="remarks"></v-text-field>
                  </span>
                  <span v-else> {{ gis_data.remarks }} </span>
                </v-list-item-subtitle>

              </v-list-item-content>
            </v-list-item>
          </v-list>
        </v-card>

        <v-card tile flat outlined class="mt-2">
          <v-card-subtitle class="indigo--text">Attachments</v-card-subtitle>

          <v-list dense v-if="gis_data.aics_documents">
            <!--<v-list-item dense v-for="(e, i) in gis_data.aics_documents" :key="i" link :href="e.file_directory"
              target="_blank" color="primary">-->
            <v-list-item dense v-for="(e, i) in gis_data.aics_documents" :key="i" color="primary">
              <v-list-item-content>
                <v-list-item-subtitle @click="viewFile(e.file_directory)"> {{ e.requirement.name }}</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
          </v-list>

          <v-skeleton-loader v-else type="article"></v-skeleton-loader>

        </v-card>

      </div>
      <div class="col-md-9">

        <v-form @submit.prevent="submitForm" enctype="multipart/form-data" id="GIS">



          <v-card flat outlined tile>
            <v-card-title>NAIS HINGIIN NA TULONG</v-card-title>

            <v-card-subtitle> (Assistance Requested) </v-card-subtitle>
            <v-card-text>
              <span v-if="gis_data.aics_type"> {{ gis_data.aics_type.name }}</span>
              <v-skeleton-loader v-else type="text"></v-skeleton-loader>
            </v-card-text>
          </v-card>

          <v-card class="mt-2 client-details" v-if="gis_data.aics_beneficiary" outlined>
            <v-card-title>
              IMPORMASYON NG BENEPISYARYO
            </v-card-title>
            <v-card-subtitle>
              (Beneficiary's Identifying Information)
            </v-card-subtitle>
            <v-card-text>

              <v-row>
                <v-col cols="12" md="3">
                  <label>
                    Apelyido <small>(Last name) </small>
                  </label>
                  {{ gis_data.aics_beneficiary.last_name }}
                </v-col>
                <v-col cols="12" md="3">
                  <label>
                    Unang Pangalan <small>(First name) </small>
                  </label>
                  {{ gis_data.aics_beneficiary.first_name }}
                </v-col>
                <v-col cols="12" md="3">
                  <label>
                    Gitnang Pangalan <small>(Middle name) </small>
                  </label>
                  {{ gis_data.aics_beneficiary.middle_name }}
                </v-col>
                <v-col cols="12" md="3">
                  <label>
                    Ext <small>(Sr.,Jr., II, III) </small>
                  </label>
                  {{ gis_data.aics_beneficiary.ext_name }}
                </v-col>
              </v-row>
              <hr>
              <v-row>
                <v-col cols="12" md="12">
                  <label>House No./Street/Purok <small>(Ex. 123 Sun St.) </small> </label>
                  {{ gis_data.aics_beneficiary.street_number }}
                </v-col>
              </v-row>
              <hr>
              <v-row v-if="gis_data.aics_beneficiary.psgc">
                <v-col cols="12" md="3">
                  <label for="">Region <small>(Ex. NCR)</small></label>
                  {{ gis_data.aics_beneficiary.psgc.region_name }}
                </v-col>
                <v-col cols="12" md="3">
                  <label for="">Province/District <small>(Ex. Dis. III)</small></label>
                  {{ gis_data.aics_beneficiary.psgc.province_name }}
                </v-col>
                <v-col cols="12" md="3">
                  <label for="">City/Municipality <small>(Ex. Quezon City)</small></label>
                  {{ gis_data.aics_beneficiary.psgc.city_name }}
                </v-col>
                <v-col cols="12" md="3">
                  <label for="">Barangay <small>(Ex. Batasan Hills)</small></label>
                  {{ gis_data.aics_beneficiary.psgc.brgy_name }}
                </v-col>
              </v-row>
              <hr>
              <v-row>
                <v-col cols="12" md="3">
                  <label for="">Telepono <small>(Mobile Number) </small></label>
                </v-col>
                <v-col cols="12" md="3">
                  <label for="">Kapanganakan <small>(Birthdate)</small></label>
                  {{ gis_data.aics_beneficiary.birth_date }}
                </v-col>
                <v-col cols="12" md="3">
                  <label for="">Edad <small>(Age)</small></label>
                  {{ gis_data.aics_beneficiary.age }}
                </v-col>
                <v-col cols="12" md="3">
                  <label for="">Kasarian <small>(gender)</small></label>
                  {{ gis_data.aics_beneficiary.gender }}
                </v-col>
              </v-row>
              <hr>
              <v-row>
                <v-col cols="12" md="3">
                  <label for="">Trabaho <small>(Occupation) </small></label>
                  {{ gis_data.aics_beneficiary.occupation }}
                </v-col>
                <v-col cols="12" md="3">
                  <label for="">Buwanang Kita <small>(Monthly Salary)</small></label>
                  {{ gis_data.aics_beneficiary.monthly_salary }}
                </v-col>
                <v-col cols="12" md="3">
                  <label for="">Civil Status</label>
                  {{ gis_data.aics_beneficiary.civil_status }}
                </v-col>
              </v-row>
            </v-card-text>
          </v-card>

          <v-card v-if="gis_data.aics_client" flat outlined tile class="mt-2 client-details">
            <v-card-title>
              <span v-if="gis_data.aics_beneficiary"> IMPORMASYON NG KINATAWAN </span>
              <span v-else> IMPORMASYON NG BENEPISYARYO</span>
            </v-card-title>

            <v-card-subtitle>
              <span v-if="gis_data.aics_beneficiary"> (Representative's Identifying Information) </span>
              <span v-else> (Beneficiary's Identifying Information)</span>
            </v-card-subtitle>

            <v-card-text>

              <v-row>
                <v-col cols="12" md="3">
                  <label>
                    Apelyido <small>(Last name) </small>
                  </label>
                  {{ gis_data.aics_client.last_name }}
                </v-col>
                <v-col cols="12" md="3">
                  <label>
                    Unang Pangalan <small>(First name) </small>
                  </label>
                  {{ gis_data.aics_client.first_name }}
                </v-col>
                <v-col cols="12" md="3">
                  <label>
                    Gitnang Pangalan <small>(Middle name) </small>
                  </label>
                  {{ gis_data.aics_client.middle_name }}
                </v-col>
                <v-col cols="12" md="3">
                  <label>
                    Ext <small>(Sr.,Jr., II, III) </small>
                  </label>
                  {{ gis_data.aics_client.ext_name }}
                </v-col>
              </v-row>
              <hr>
              <v-row>
                <v-col cols="12" md="12">
                  <label>House No./Street/Purok <small>(Ex. 123 Sun St.) </small> </label>
                  {{ gis_data.aics_client.street_number }}
                </v-col>
              </v-row>
              <hr>




              <v-row v-if="gis_data.aics_client.psgc">
                <v-col cols="12" md="3">
                  <label for="">Region <small>(Ex. NCR)</small></label>
                  {{ gis_data.aics_client.psgc.region_name }}
                </v-col>
                <v-col cols="12" md="3">
                  <label for="">Province/District <small>(Ex. Dis. III)</small></label>
                  {{ gis_data.aics_client.psgc.province_name }}
                </v-col>
                <v-col cols="12" md="3">
                  <label for="">City/Municipality <small>(Ex. Quezon City)</small></label>
                  {{ gis_data.aics_client.psgc.city_name }}
                </v-col>
                <v-col cols="12" md="3">
                  <label for="">Barangay <small>(Ex. Batasan Hills)</small></label>
                  {{ gis_data.aics_client.psgc.brgy_name }}
                </v-col>
              </v-row>
              <hr>
              <v-row>
                <v-col cols="12" md="3">
                  <label for="">Telepono <small>(Mobile Number) </small></label>
                  {{ gis_data.aics_client.mobile_number }}
                </v-col>
                <v-col cols="12" md="3">
                  <label for="">Kapanganakan <small>(Birthdate)</small></label>
                  {{ gis_data.aics_client.birth_date }}
                </v-col>
                <v-col cols="12" md="3">
                  <label for="">Edad <small>(Age)</small></label>
                  {{ gis_data.age }}
                </v-col>
                <v-col cols="12" md="3">
                  <label for="">Kasarian <small>(gender)</small></label>
                  {{ gis_data.aics_client.gender }}
                </v-col>
              </v-row>
              <hr>
              <v-row>
                <v-col cols="12" md="3">
                  <label for="">Trabaho <small>(Occupation) </small></label>
                  {{ gis_data.occupation }}
                </v-col>
                <v-col cols="12" md="3">
                  <label for="">Buwanang Kita <small>(Monthly Salary)</small></label>
                  {{ gis_data.monthly_salary }}
                </v-col>
                <v-col cols="12" md="3">
                  <label for="">Civil Status</label>
                  {{ gis_data.civil_status }}
                </v-col>
                <v-col cols="12" md="3">
                  <label for="">Relasyon</label>
                  {{ gis_data.rel_beneficiary }}
                </v-col>
              </v-row>
            </v-card-text>
          </v-card>

          <v-card tile flat outlined id="docs_preview" v-if="view_url" class="my-2" :loading="loading_view_url">
            <v-app-bar flat dense short dark color="indigo darken-3">View Attachment</v-app-bar>
            <iframe :src="view_url" frameborder="0" style="width:100%; min-height: 500px;"></iframe>
          </v-card>


          <v-row v-if="hasRoles(['social-worker', 'admin', 'super-admin']) && gis_data.status != 'Pending'" class="g-2">
            <v-col cols="12" md="4">
              <v-card flat outlined tile>
                <v-app-bar flat dense short color="indigo darken-3 " dark>
                  Beneficiary Category
                </v-app-bar>
                <v-card-text>


                  <v-autocomplete class="rounded-0" outlined clearable dense v-model="form.category_id"
                    label="Target Sector" :items="categories" item-value="id" item-text="category"
                    :error-messages="validationErrors.category_id">
                  </v-autocomplete>

                  <v-autocomplete outlined class="rounded-0" clearable dense v-model="form.subcategory_id"
                    label="Specific Subcategory" :items="subcategories" item-value="id" item-text="subcategory"
                    :error-messages="validationErrors.subcategory_id">
                  </v-autocomplete>

                  <v-text-field outlined dense class="rounded-0" v-if="form.subcategory_id == 8"
                    v-model="form.subcategory_others" label="Others"></v-text-field>

                </v-card-text>
              </v-card>
            </v-col>

            <v-col cols="12" md="8">
              <v-card flat outlined tile>
                <v-app-bar flat dense short dark color="indigo darken-3">Social Worker's Assessment</v-app-bar>
                <v-card-text>

                  <v-autocomplete class="rounded-0" outlined dense label="Assessment Option"
                    v-model="selected_assessment_option" :items="assessment_options" item-text="option" return-object>
                  </v-autocomplete>

                  <v-textarea class="rounded-0" outlined dense label="Social Worker's Assessment"
                    v-model="form.assessment" :error-messages="validationErrors.assessment" cols="30"
                    rows="10"></v-textarea>

                </v-card-text>
              </v-card>
            </v-col>
          </v-row>


          <v-card flat outlined tile class="mt-2"
            v-if="hasRoles(['social-worker', 'admin', 'super-admin']) && gis_data.status != 'Pending'">
            <v-app-bar flat dense short color="indigo darken-3 " dark>
              Assessment Information
            </v-app-bar>
            <v-card-text>
              <v-row class="g-2">
                <v-col>
                  <v-autocomplete class="rounded-0" outlined clearable dense v-model="form.mode_of_admission" cols="12"
                    md="4" label="Mode of Admission" :items="['Walk-in', 'Referral']"
                    :error-messages="validationErrors.mode_of_admission" hide-details="auto">
                  </v-autocomplete>
                </v-col>
                <v-col cols="12" md="8">
                  <v-text-field class="rounded-0" outlined dense v-model="form.purpose" label="Purpose"
                    :error-messages="validationErrors.purpose" hide-details="auto"></v-text-field>
                </v-col>
                <v-col cols="12" md="4">
                  <v-autocomplete class="rounded-0" outlined clearable dense v-model="form.mode_of_assistance"
                    label="Mode of Assistance" :items="['CAV', 'GL']"
                    :error-messages="validationErrors.mode_of_assistance" hide-details="auto">
                  </v-autocomplete>
                </v-col>

                <v-col cols="12" md="8">
                  <v-autocomplete class="rounded-0" v-model="selected_fund_sources" :items="fund_sources" small-chips
                    outlined label="Fund Source" dense return-object item-text="name" multiple density="compact"
                    hide-details="auto">
                  </v-autocomplete>
                  <v-simple-table>
                    <thead>
                      <tr>
                        <th style="width:30%">Fund Source</th>
                        <th style="width:65%">Amount</th>
                        <th style="width:5%"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(e, i) in selected_fund_sources" :key="i">
                        <td>
                          {{ e.name }}

                        </td>
                        <td>
                          <span v-if="e.txn_id">
                            {{ e.amount }}
                          </span>
                          <span v-else> <br>
                            <v-text-field class="rounded-0" v-model="e.amount" outlined dense step="0.25"
                              hide-details="auto"></v-text-field>
                          </span>
                        </td>
                        <td>

                          <v-icon small @click="deleteFundSrc(i)">
                            mdi-delete
                          </v-icon>
                        </td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td>TOTAL</td>
                        <td>{{ sumValue }}</td>
                        <td>

                        </td>
                      </tr>
                    </tfoot>
                  </v-simple-table>
                </v-col>
                <v-col cols="12" md="4">
                  <v-text-field class="rounded-0" readonly outlined dense v-model="form.interviewed_by"
                    label="Interviewed By" :error-messages="validationErrors.interviewed_by"
                    hide-details="auto"></v-text-field>
                </v-col>
                <v-col cols="12" md="4">

                  <v-autocomplete class="rounded-0" outlined clearable dense v-model="form.signatory_id"
                    :loading="!signatories" label="Approved by" :items="signatories"
                    :error-messages="validationErrors.signatory_id" item-value="id" item-text="name" hide-details="auto">
                    <template v-slot:item="data">
                      <v-list-item-content>
                        <v-list-item-title>{{ data.item.name }}</v-list-item-title>
                        <v-list-item-subtitle>{{ data.item.position }}</v-list-item-subtitle>
                      </v-list-item-content>
                    </template>
                  </v-autocomplete>
                </v-col>
                <v-col cols="12" md="4">
                  <v-text-field outlined dense class="rounded-0" v-model="form.sdo"
                    v-if="form.mode_of_assistance == 'CAV'" label="Special Disbursing Officer (SDO)" hide-details="auto"
                    :error-messages="validationErrors.sdo"></v-text-field>
                </v-col>

                <v-col cols="12" md="12">
                  <v-text-field outlined dense class="rounded-0" v-model="form.referral"
                    v-if="form.mode_of_admission == 'Referral'" label="Referral" hide-details="auto"
                    :error-messages="validationErrors.referral"></v-text-field>
                </v-col>

              </v-row>

            </v-card-text>

          </v-card>


          <div class="card mt-2"
            v-if="hasRoles(['social-worker', 'admin', 'super-admin']) && gis_data.status != 'Pending'">
            <div class="card-title">
              RECORDS IN FILE
            </div>
            <div class="card-body">
              <v-row align="start" no-gutters>
                <template v-for="e in records_opts">
                  <v-col xs="12" sm="5" md="3" lg="3">
                    <v-checkbox v-model="form.records" :label="e" :value="e" class="shrink mr-0 mt-0"></v-checkbox>
                  </v-col>
                </template>

              </v-row>

            </div>
          </div>

          <div class="card mt-2"
            v-if="hasRoles(['social-worker', 'admin', 'super-admin']) && gis_data.status != 'Pending' && form.mode_of_assistance == 'GL'">
            <div class="card-title">
              GL
            </div>
            <div class="card-body">
              <v-autocomplete v-model="selected_provider" :loading="!providers" :items="providers" label="Provider"
                return-object outlined item-text="company_name" dense class="rounded-0">
                <template v-slot:item="data">
                  <v-list-item-content>
                    <v-list-item-title>{{ data.item.company_name }}</v-list-item-title>
                    <v-list-item-subtitle>{{ data.item.company_address }}</v-list-item-subtitle>
                  </v-list-item-content>
                </template>

              </v-autocomplete>


              <v-autocomplete class="rounded-0" outlined clearable dense v-model="form.gl_signatory_id"
                :loading="!signatories" label="Signatory" :items="signatories"
                :error-messages="validationErrors.gl_signatory_id" item-value="id" item-text="name" hide-details="auto">
                <template v-slot:item="data">
                  <v-list-item-content>
                    <v-list-item-title>{{ data.item.name }}</v-list-item-title>
                    <v-list-item-subtitle>{{ data.item.position }}</v-list-item-subtitle>
                  </v-list-item-content>
                </template>
              </v-autocomplete>



              <hr>

              <div class="mt-2" v-if="selected_provider">

                {{ selected_provider.addressee_name }} <br />
                {{ selected_provider.addressee_position }}<br />
                {{ selected_provider.company_name }}<br />
                {{ selected_provider.company_address }}<br />

              </div>
            </div>
          </div>

          <div class="text-center col-md-12" style="padding: 10px 0px">
            <v-btn type="submit" large class="--white-text" color="primary" :disabled="submit"
              v-if="hasRoles(['encoder']) && gis_data.status == 'Pending'">
              VERIFY
            </v-btn>

            <v-btn type="submit" large class="--white-text" color="primary" :disabled="submit"
              v-if="hasRoles(['social-worker']) && (gis_data.status == 'Verified' || gis_data.status == 'Serving' || gis_data.status == 'Served')">

              <span v-if="form.id">UPDATE</span>
              <span v-else>SUBMIT</span>

            </v-btn>

            <v-btn @click="MarkServed" dark large class="--white-text" color="red" :disabled="submit"
              v-if="hasRoles(['social-worker']) && gis_data.status == 'Serving'">
              Mark as Served
            </v-btn>

            <v-btn v-if="gis_data.status == 'Pending' && hasRoles(['encoder'])" large class="--white-text" color="error"
              @click="dialog_reject = true" :disabled="submit">
              REJECT
            </v-btn>


            <v-menu v-if="hasRoles(['social-worker']) && (gis_data.status == 'Serving' || gis_data.status == 'Served')">
              <template v-slot:activator="{ on, attrs }">
                <v-btn large dark v-bind="attrs" v-on="on">
                  <v-icon> mdi-printer </v-icon> Print
                </v-btn>
              </template>
              <v-list>
                <v-list-item @click="PrintGIS()">
                  <v-list-item-title>
                    <v-icon> mdi-printer </v-icon>Print GIS <v-chip label small>CTLR + P</v-chip>
                  </v-list-item-title>
                </v-list-item>
                <v-list-item @click="PrintCOE()">
                  <v-list-item-title>
                    <v-icon> mdi-printer </v-icon> Print COE <v-chip label small>CTLR + SHIFT + P</v-chip>
                  </v-list-item-title>
                </v-list-item>
                <v-list-item @click="PrintCAV()"
                  v-if="gis_data.assessment && gis_data.assessment.mode_of_assistance == 'CAV'">
                  <v-list-item-title>
                    <v-icon> mdi-printer </v-icon> Print CAV <v-chip label small>CTLR + ALT + P</v-chip>
                  </v-list-item-title>
                </v-list-item>
                <v-list-item @click="PrintGL()" v-else>
                  <v-list-item-title>
                    <v-icon> mdi-printer </v-icon> Print GL <v-chip label small>CTLR + SPACE</v-chip>
                  </v-list-item-title>
                </v-list-item>

              </v-list>
            </v-menu>




          </div>

          <v-dialog v-model="dialog_reject" width="50%">
            <v-card>
              <v-card-title>Reject GIS</v-card-title>
              <v-card-text>
                Reason
                <select class="form-control" v-model="rejectform.reason">
                  <option :value="e" v-for="(e, i) in ['Incomplete Documents']" :key="i">{{ e }}</option>
                </select>
                Message
                <textarea v-model="rejectform.remarks" id="" cols="10" rows="5" class="form-control"></textarea>

                <v-btn @click="RejectGIS" dark color="red">
                  Reject
                </v-btn>
              </v-card-text>

            </v-card>
          </v-dialog>

          <!--</form>-->
        </v-form>



      </div>

    </div>
    <span v-shortkey="['ctrl', 'p']" @shortkey="PrintGIS()"></span>
    <span v-shortkey="['ctrl', 'shift', 'p']" @shortkey="PrintCOE()"></span>
    <span v-if="gis_data.assessment && gis_data.assessment.mode_of_assistance == 'CAV'" v-shortkey="['ctrl', 'alt', 'p']"
      @shortkey="PrintCAV()"></span>
    <span v-else v-shortkey="['ctrl', 'space']" @shortkey="PrintGL()"></span>


  </div>
</template>

<style>
.card-title {
  color: #ffffff;
  padding: 1pc;
  background: #283593;
  border-bottom: solid 1px #d2d2d2;
  font-size: 10pt;
}
</style>

<script>
import userMixin from './../Mixin/userMixin.js'
import authMixin from './../Mixin/authMixin.js'
import CurrencyInput from './CurrencyInput'
import { debounce, cloneDeep } from 'lodash'
import moment from 'moment';
export default {
  mixins: [userMixin, authMixin],
  components: { CurrencyInput },
  // props: ["gis_data", "getList", "userData", "setDialogCreate"],
  data() {
    return {
      gis_data: {},
      form: {
        mode_of_admission: "Walk-in",
        interviewed_by: this.user.first_name + " " + this.user.middle_name + " " + this.user.last_name,
        records: [],
      },
      assistance_types: {},
      psgc: {},
      regions: {},
      errors: {},
      validationErrors: {},
      max_date: new Date().toISOString().split("T")[0],
      categories: [],
      subcategories: [],
      submit: false,
      assessment_options: [],
      selected_assessment_option: {},
      dialog_reject: false,
      rejectform: {},
      fund_sources: [],
      formType: "Create",
      records_opts: [
        "Referral Letter",
        "Social Case Study Report",
        "Justification",
        "Valid ID Presented",
        "4PS DSWD ID",
        "Medical Certificate/Abstract",
        "Prescriptions",
        "Statement of Account",
        "Treatment Protocol",
        "Quotation",
        "Discharge Summary",
        "Laboratory Request",
        "Charge Slip",
        "Funeral Contract",
        "Death Certificate",
        "Death Summary",
        "Others",
      ],
      id_options: ["School-ID",
        "Voter's ID",
        "PhilHealth ID",
        "Postal ID",
        "Driver's License",
        "Senior Citizen ID",
        "OFW ID",
        "Philippine Passport",
        "SSS UMID Card",
        "TIN Card",
        "PRC ID"],
      providers: [],
      selected_provider: {},
      signatories: [],
      selected_gl_signatory: "",
      schedule: '',
      schedule_error: '',
      fundsrc_dialog: false,
      fsd: [],
      selected_fund_sources: [],
      remarks: "",
      total: 0,
      for_reveresal: [],
      view_url: null,
      loading_view_url: false,

    };
  },
  watch: {
    "form.subcategory_id": function (newVal, oldVal) {
      if (newVal != 8) {
        this.form.subcategory_others = "";
      }
    },
    selected_assessment_option(val) {

      this.form.assessment = val.template;

    },
    selected_fund_sources(newVal, oldVal) {

      let x = oldVal.filter(object1 => {
        return !newVal.some(object2 => {
          return object1.id === object2.id;
        });
      });

      if (x.length > 0 && x[0].txn_id) {
        this.for_reveresal.push(cloneDeep(x[0]));
      }

    }

  },
  computed: {
    sumValue() {
      if (this.selected_fund_sources.length > 0) {
        return this.selected_fund_sources.reduce((acc, item) => {
          return acc + parseFloat(item.amount);
        }, 0);
      }
      return 0;
    },
    submission_info() {
      return [
        { title: "Status", value: this.gis_data.status },
        { title: "Date Submitted:", value: moment(this.gis_data.created_at).format("MMM. DD, YYYY LT") },
        { title: "Office:", value: this.gis_data.office ? this.gis_data.office.name : "" },
        { title: "Verified by:", value: this.gis_data.verified_by ? this.gis_data.verified_by.full_name : "---" },
        { title: "Interviewed by:", value: this.gis_data.assessment ? this.gis_data.assessment.interviewed_by : "---" },
      ]
    },

  },

  methods: {
    submitForm: debounce(function () {
      this.submit = true;
      this.form.gis_id = this.gis_data.id; // FOREIGN KEY
      this.form.fund_sources = this.selected_fund_sources;
      this.form.amount = this.sumValue;
      if (this.form.mode_of_assistance == "GL") { this.form.provider_id = this.selected_provider.id; }

      if (this.form.id) { //UPDATE
        this.updateAssessment();
      } else { //CREATE 
        if (this.gis_data.status == "Pending") {
          this.verifyGis();
        } else {

          this.createAssessment();
        }
      }

    }, 250),

    updateAssessment() {

      if (this.for_reveresal) {
        this.form.fs_reversal = this.for_reveresal;
      }

      axios
        .post(route("api.assessment.update", this.form.id), this.form)
        .then((response) => {
          this.submit = false;
          alert(response.data.message);
          location.reload();
        })
        .catch((error) => {
          this.submit = false;
          if (error.response && error.response.status == 422) {
            alert("Kumpletohin ang form. \nPlease complete the form.");
            this.validationErrors = error.response.data.errors[0];
          }
        });
    },

    createAssessment() {
      axios
        .post(route("api.assessment.create"), this.form)
        .then((response) => {
          this.submit = false;
          this.getGISData();
          alert(response.data.message);
        })
        .catch((error) => {
          this.submit = false;
          if (error.response && error.response.status == 422) {
            alert("Kumpletohin ang form. \nPlease complete the form.");
            this.validationErrors = error.response.data.errors[0];
          }
        });
    },

    verifyGis() {

      if (this.schedule) {

        axios.patch(route("assistances.update", { "assistance": this.gis_data.uuid }), { "uuid": this.gis_data.uuid, status: "Verified", "schedule": this.schedule, "remarks": this.remarks, "task": "update_status" }).then(response => {

          alert(response.data.message);
          this.dialog_reject = false;
          // this.$router.push({ path: '/' })
          this.$router.go(-1)

        }).catch(error => console.log(error));
      }
      else {

        this.schedule_error = "Enter Schedule";
        this.submit = false;


      }

    },

    resetForm() {
      this.form = {
        mode_of_admission: "Walk-in",
        records: [],


      };
    },

    isEmpty(value) {
      return _.isEmpty(value);
    },

    getAssistanceTypes() {
      axios.get(route("api.aics.assistance-types")).then((response) => {
        this.assistance_types = response.data;
      });
    },

    getCategories() {
      axios.get(route("api.categories")).then((response) => {
        this.categories = response.data.categories;
        this.subcategories = response.data.subcategory;
      });
    },
    getAssessmentOpts() {
      axios.get(route("api.assessment_opts")).then((response) => {
        this.assessment_options = response.data;
      });
    },
    RejectGIS() {

      this.rejectform.uuid = this.gis_data.uuid;
      this.rejectform.status = "Rejected";
      this.rejectform.remarks = this.rejectform.reason + " - " + this.rejectform.remarks;
      this.rejectform.task = "verify";


      axios.patch(route("assistances.update", { "assistance": this.gis_data.uuid }), this.rejectform).then(response => {

        console.log(response);
        alert(response.data.message);
        this.dialog_reject = false;
        this.$router.push({ path: '/' })

      }).catch(error => console.log(error));
    },

    getGISData() {
      // console.log({ "assistance": this.$route.params.uuid });
      axios.get(route("assistances.show", { "assistance": this.$route.params.uuid }),)
        .then(response => {

          this.gis_data = response.data;

          if (this.gis_data.assessment) {
            this.form = this.gis_data.assessment;

            if (!this.form.records) {
              this.form.records = [];
            };

            this.form.records = JSON.parse(this.gis_data.assessment.records);

            if (this.form.provider_id) {

              this.selected_provider = this.providers.find(el => el.id === this.form.provider_id);

            }

            if (this.gis_data.assessment.fund_sources) {
              //this.selected_fund_sources = this.gis_data.assessment.fund_sources;
              //this.selected_fund_sources = [{ "id": 1, "name": "DC1"}]
              this.selected_fund_sources = this.gis_data.selected_fs;

            }




          }
        }).catch(error => {
          console.log("error");
          console.log(error);

          if (error.response.status === 404) {

            this.$router.push({ name: 'NotFound' });
          }
        })
    },
    getFundSrc() {

      axios.get(route("api.fund_src")).then(response => {
        this.fund_sources = response.data;
      }).catch(error => console.log(error))
    },
    PrintGIS() {
      window.open("/api/gis/" + this.gis_data.uuid)
    },
    PrintCOE() {
      window.open("/api/coe/" + this.gis_data.uuid)
    },
    PrintCAV() {
      window.open("/api/cav/" + this.gis_data.uuid)
    },
    PrintGL() {
      window.open("/api/gl/" + this.gis_data.uuid)
    },
    getProviders() {
      axios.get(route("api.providers")).then(response => {
        this.providers = response.data.sort();
      }).catch(error => console.log(error))
    },
    getSignatories() {

      axios.get(route("api.signatories")).then(response => {
        this.signatories = response.data.sort();
      }).catch(error => console.log(error))
    },

    deleteFundSrc(i) {

      if (this.selected_fund_sources[i].txn_id) {
        this.for_reveresal.push(cloneDeep(this.selected_fund_sources[i]));
      }

      this.selected_fund_sources[i].amount = 0;
      this.selected_fund_sources.splice(i, 1);

    },

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
    MarkServed() {

      let x = confirm("Are you sure to close this transaction?");

      if (x) {
        let tempform = {};
        tempform.uuid = this.gis_data.uuid;
        tempform.status = "Served";
        tempform.task = "update_status";

        axios.patch(route("assistances.update", { "assistance": this.gis_data.uuid }), tempform).then(response => {

          console.log(response);
          alert(response.data.message);
          //this.dialog_reject = false;
          //this.$router.push({ path: '/' })
          location.reload();

        }).catch(error => console.log(error));
      }
    },

    viewFile(file_directory) {
      this.loading_view_url = true;
      axios.post(route("api.view_attachment"), { "file_directory": file_directory }).then(response => {
        this.view_url = response.data;
        this.loading_view_url = false;
      }).catch(err => console.log(err));
    }


  },
  mounted() {


    this.getAssistanceTypes();
    this.getCategories();
    this.getAssessmentOpts();
    this.getFundSrc();
    this.getProviders();
    this.getSignatories();
    this.getGISData();

  },
};
</script>

<style></style>