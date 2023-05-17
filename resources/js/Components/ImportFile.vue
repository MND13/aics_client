<template>
  <v-card flat>
    <v-card-title>Import </v-card-title>
    <v-card-text>
      <form @submit.prevent="submit" enctype="multipart/form-data">
        <input type="file" name="" id="" @input="onFileChange($event)" required accept=".xlsx,.xls"/>

        

        <v-btn
          :loading="isBusy"
          color="black"
          class="ma-2 white--text"
          :disabled="isBusy"
          type="submit"
          v-if="hasRoles(['super-admin', 'admin'])"
        >
          SUBMIT
        </v-btn>

        feedback: {{ feedback }}
        <span v-if="errorFile">
          Please download the file to view the errors. 
          <a :href="errorFile">{{ errorFile  }}</a>
        </span>
        
      </form>
    </v-card-text>


    <v-card-title>Imported Files</v-card-title>
    <v-card-text>
      <v-data-table
        :headers="headers"
        :items="uploadedFiles"
        :items-per-page="5"
        class="elevation-1"
      >
        <template v-slot:item.file_directory="{ item }">
          <a :href="item.file_directory">{{ item.file_directory }}</a>
        </template>
        <template v-slot:item.actions="{ item }">
          <v-icon small class="mr-2" @click="deleteFile(item)" v-if="hasRoles(['super-admin', 'admin'])">
            mdi-delete
          </v-icon>
        </template>
      </v-data-table>
    </v-card-text>
  </v-card>
</template>

<script>
import userMixin from './../Mixin/userMixin.js'

export default {
  name: "import",
  mixins: [userMixin],
  props: ['user'],
  data() {
    return {
      file: [],
      isBusy: false,
      feedback: "",
      errorFile: "",
      headers: [
        {
          text: 'File Name',
          align: 'start',
          value: 'file_name',
        },
        {
          text: 'File Directory',
          align: 'start',
          value: 'file_directory',
        },
        {
          text: 'Uploaded on',
          value: 'created_at',
        },
        {
          text: 'Actions',
          value: 'actions',
        },
      ],
      uploadedFiles: [],
    };
  },
  methods: {
    submit() {
      this.errorFile = "";
      // console.log("here");
      this.isBusy = true;
      let formData = new FormData();
      formData.append("file", this.file);

      const headers = {
        "Content-Type": "multipart/form-data;",
      };

      axios
        .post(route("api.client.upload"), formData, headers)
        .then((response) => {
         // console.log("ajdlkasd");
          this.isBusy = false;
         // console.log(response.data);
          this.feedback = response.data.success;
          this.getUploadedFiles();
        })
        .catch((error) => {
          this.isBusy = false;
          this.feedback = error.response.data.errors.file[0];
          this.errorFile = error.response.data.errors.errors_file_path;
          // window.location.href = error.response.data.errors.errors_file_path;
        });
    },
    onFileChange(e) {
     // console.log(e);
      this.file = e.target.files[0];
     // console.log(this.formData);
    },
    getUploadedFiles(){
      axios.get(route('api.dirty-list.index'))
      .then(res => {
        this.uploadedFiles = res.data;
      })
      .catch(err => {});
    },
    deleteFile(item){
      if(confirm(`Are you sure you want to delete ${item.file_name}? This will remove unserved beneficiaries from this file.`)){
        axios.delete(route('api.dirty-list.delete', item.id))
        .then(res => {
          this.getUploadedFiles();
        })
        .catch(err => {})
        ;
      }
    }
  },
  mounted(){
    this.getUploadedFiles();
  }
};
</script>

<style>
</style>