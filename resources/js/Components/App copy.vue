<template>
  <v-app>
    <div class="container-fluid">
      <div class="row">

        <div class="col-md-2">
          <v-list dense>

            <v-list-item-group v-model="selectedItem" color="primary" class="d-print-none">
              <v-list-item v-for="(link, i) in links" :key="i" :to="link.to">
                <v-list-item-icon>
                  <v-icon v-text="link.icon"></v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title v-text="link.text"></v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-list-item-group>
          </v-list>

          <v-divider inset></v-divider>


          <v-list dense>
            <v-list-item-group v-model="selectedItem" v-if="hasRoles(['super-admin'])" color="primary"
              class="d-print-none">
              <v-subheader inset>Libraries</v-subheader>

              <v-list-item v-for="(link, i) in lib_menu" :key="i" :to="link.to">
                <v-list-item-icon>
                  <v-icon v-text="link.icon"></v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title v-text="link.text"></v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-list-item-group>
          </v-list>



        </div>
        <div class="col-md-10">
          <router-view :user="user">

          </router-view>
        </div>
      </div>
    </div>
  </v-app>
</template>

<script>
import userMixin from '../Mixin/userMixin';

export default {
  props: ['user'],
  mixins: [userMixin],
  data() {
    return {
      selectedItem: 1,
      links: [],
      default_menu: [
        {
          to: "/",
          text: "Home",
        },
        {
          to: "/assistance",
          text: "Request Assistance",

        },
        {
          to: "/profile",
          text: "Profile",

        },
        {
          to: "/contact",
          text: "Contact Us",
        },
      ],
      admin_menu: [
        {
          to: "/",
          text: "Home"
        },

        {
          to: "/",
          text: "Pending"
        },
        {
          to: "/",
          text: "Serving"
        },

        {
          to: "/",
          text: "Served"
        },
        {
          to: "/",
          text: "Rejected"
        },

      ],
      lib_menu:
        [
          {
            to: "/users",
            text: "Users",
          },
          {
            to: "/providers",
            text: "Providers",
          },
          {
            to: "/fund_source",
            text: "Fund Source",
          },
        ]
    };
  },

  methods: {},
  mounted() {


    switch (this.userData.role) {
      case "user":
        this.links = this.default_menu
        break;
      default:
        this.links = this.admin_menu
        break;
    }

  },
};
</script>

<style></style>