<template>
  <div class="container">

    <div class="alert alert-danger col-md-10 offset-md-1" role="alert" v-if="Object.keys(errors).length > 0">

      {{ errors.message }}

      <ul>
        <li v-for="(e, i) in errors.errors" :key="i">{{ e[0] }}</li>
      </ul>
    </div>

    <form method="POST" @submit.prevent="submit" enctype="multipart/form-data" action="">
      <!-- <form method="POST"  enctype="multipart/form-data" action="">-->

      <input type="hidden" name="_token" :value="csrf">

      <div class="row">
        <div class="col-md-12 offset-md-1"> <b> FULL NAME (ACCORDING TO PRESENTED VALID ID) </b></div>
      </div>
      <div class="row g-2">


        <div class="offset-md-1  col-md-3">
          <label for="">First Name</label>
          <input type="text" v-model="form.first_name" name="first_name" class="form-control" required>


        </div>

        <div class="col-md-3">
          <label for="">Middle Name</label>
          <input type="text" v-model="form.middle_name" name="middle_name" class="form-control ">
          <v-checkbox label="I have No Middle Name (NMN)" v-model="nmn" ></v-checkbox>


        </div>

        <div class="col-md-3">
          <label for="">Last Name</label>
          <input type="text" v-model="form.last_name" name="last_name" class="form-control">


        </div>

        <div class="col-md-1">
          <label for="">Ext Name</label>
          <select v-model="form.ext_name" name="" class="form-control">
            <option value=""></option>
            <option value="JR">JR</option>
            <option value="SR">SR</option>
            <option value="I">I</option>
            <option value="II">II</option>
            <option value="III">III</option>
            <option value="IV">IV</option>
            <option value="V">V</option>
            <option value="VI">VI</option>
            <option value="VII">VII</option>
            <option value="VIII">VIII</option>
            <option value="IX">IX</option>
            <option value="X">X</option>
          </select>
        </div>

      </div>


      <div class="row">
        <div class="col-md-12 offset-md-1"> <b> ADDRESS </b> </div>
      </div>

      <div class="row g-2">
        <div class=" offset-md-1  col-md-10">
          <label for="">House No./Street/Purok</label>
          <input v-model="form.street_number" type="text" name="street_number" class="form-control">



        </div>
      </div>

      <div class="row g-2">

        <div class="offset-md-1  col-md-1">
          <label for="">Region</label>
          <select class="form-control">
            <option selected>XI</option>
          </select>
        </div>
        <div class="col-md-3">
          <label for="">Province</label>
          <select class="form-control" v-model="province_name" @change="getCities()" id="provinces">
            <option selected></option>
            <option v-for="(e, i) in provinces" :key="i">{{ e.province_name }}</option>

            <!-- @foreach ($provinces as $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach-->

          </select>
        </div>
        <div class="col-md-3">
          <label for="">City/Municipality</label>
          <select class="form-control" id="cities" v-model="city_name" @change="getBrgys()">
            <option selected></option>
            <option v-for="(e, i) in cities" :key="i">{{ e.city_name }}</option>


          </select>
        </div>
        <div class="col-md-3">
          <label for="">Barangay</label>
          <select v-model="form.psgc_id" class="form-control" id="barangays">
            <option></option>
            <option v-for="(e, i) in brgys" :key="i" :value="e.id">{{ e.brgy_name }}</option>

          </select>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12 offset-md-1"> <b> OTHER INFORMATION </b> </div>
      </div>

      <div class="row g-2">

        <div class="offset-md-1  col-md-3">
          <label for="">Birthday</label>
          <input id="birth_date" v-model="form.birth_date" type="date" class="form-control" :max="max_date"
            @input="calculateAge" required />

        </div>
        <div class="col-md-1">
          <label for="">Age</label>
          <input id="age" type="text" class="form-control" :value="form.age" readonly />

        </div>



        <div class="col-md-3">
          <label for="">Gender</label>
          <select class="form-control" v-model="form.gender">
            <option value="Lalake">Lalake</option>
            <option value="Babae">Babae</option>
          </select>
        </div>

        <div class=" col-md-3">
          <label for="">Mobile No.</label>
          <input v-model="form.mobile_number" type="text" class="form-control">
        </div>
      </div>




      <div class="row g-2">
        <div class="offset-md-1 col-md-3">
          <label for="">Email</label>
          <input type="email" v-model="form.email" name="email" class="form-control">
        </div>

        <div class=" col-md-3">
          <label for="">Confirm Password</label>
          <input type="password" v-model="form.password" name="password" class="form-control">
        </div>

        <div class=" col-md-3">
          <label for="">Password</label>
          <input type="password" v-model="form.password_confirmation" name="password_confirmation" class="form-control">
        </div>
      </div>


      <div class="row">
        <div class="col-md-12 offset-md-1">
          <b>PROOF OF IDENTIFICATION </b> <br>

        </div>
      </div>
      <div class="row">
        <div class="col-md-4 offset-md-1 ">
          <p>Upload a clear copy of your valid ID</p>

          <p> Accepted valid IDS:</p>
          <ul style=" column-count: 2;">
            <li>National ID</li>
            <li>Driver's License</li>
            <li>Senior Citizen ID</li>
            <li>Voter's ID/Certificate</li>
            <li>Person’s With Disability (PWD) ID</li>
            <li>Phil-health ID</li>
            <li>NBI Clearance</li>
            <li>BIR (TIN)</li>
            <li>Pag-ibig ID</li>
            <li>Any Government Issued IDs</li>

          </ul>


        </div>
        <div class="col-md-6">
          <input name="documents" class="form-control" @change="uploadFile" ref="file" type="file"
            accept="image/png, image/jpeg, application/pdf" required>

          <div class="preview">

            <img v-if="url" :src="url" style="max-width: 300px;" />

          </div>

        </div>

      </div>

      <div class="row">
        <div class="col-md-10 offset-md-1 ">



        </div>




      </div>

      <div class="row ">
        <div class="offset-md-1  col-md-10">

          <button type="submit" class="btn btn-primary col-md-12">
            REGISTER
          </button>
        </div>
      </div>
    </form>

  </div>
</template>

<script>
export default
  {
    props: ["provinces"],
    data() {
      return {
        form: {
        },
        max_date: new Date().toISOString().split("T")[0],
        province_name: "",
        city_name: "",
        cities: [],
        brgys: [],
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        errors: {},
        file: null,
        url: "",
        nmn: false
      }
    },
    watch: {
      nmn(e) {
        if (e) {
          this.form.middle_name = "NMN";

        } else {
          this.form.middle_name = "";

        }

      }
    },
    methods:
    {
      calculateAge: function () {
        if (!this.form.birth_date) {
          this.form.age = 0;
        } else {
          let currentDate = new Date();
          let birthDate = new Date(this.form.birth_date);
          let difference = currentDate - birthDate;
          let age = Math.floor(difference / 31557600000);
          this.form.age = age;
        }
      },

      getCities() {
        this.cities = [];
        this.brgys = [];
        axios.get(route("api.psgc.show", { type: "cities", field: "province_name", value: this.province_name })).then(response => {
          this.cities = response.data;
        }).catch(error => console.log(error));
      },

      getBrgys() {

        axios.get(route("api.psgc.show", { type: "brgy", field: "city_name", value: this.city_name })).then(response => {
          this.brgys = response.data;
        }).catch(error => console.log(error));
      },

      submit() {

        let formData = new FormData();
        formData.append('file', this.file);

        if(this.form.ext_name == "") 
        {
          delete this.form["ext_name"];
        }

        const entries = Object.entries(this.form);

        entries.forEach(element => {
          formData.append(element[0], element[1]);
        });

      

        axios.post("register", formData).then(response => {
          //console.log(response.data);
          alert("Registered");
          location.reload();
        }).catch(error => {
          console.log(error.response.data);
          if (error.response.data) {
            this.errors = error.response.data;
            window.scrollTo(0, 0);

          }
        });


      },
      uploadFile() {
        this.file = this.$refs.file.files[0];
        this.url = URL.createObjectURL(this.file);
      }
    }
  }
</script>

<style></style>