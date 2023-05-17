<template>
  <v-app>

    <v-navigation-drawer v-model="drawer" app>
      <v-list-item>
        <v-list-item-content centered>
          <v-list-item-title>

            <v-img max-height="64" max-width="250px" src="/images/DSWD-DVO-LOGO.png" contain alt="DSWD"></v-img>

          </v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <v-list dense>
        <div v-for="(link, i) in links">
          <v-list-item v-if="!link.subLinks" :key="i" :to="link.to" class="v-list-item">
            <v-list-item-icon >
              <v-icon>{{ link.icon }}</v-icon>
            </v-list-item-icon>

            <v-list-item-content>
              <v-list-item-title>
                {{ link.text }}
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>

          <v-list-group v-else :key="link.text" no-action>
            <template v-slot:activator>
              <v-list-item-icon>
                <v-icon>{{ link.icon }}</v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <v-list-item-title>{{ link.text }}</v-list-item-title>
              </v-list-item-content>
            </template>

            <v-list-item v-for="sublink in link.subLinks" :to="sublink.to" :key="sublink.text">
              <v-list-item-title v-text="sublink.text"></v-list-item-title>
            </v-list-item>
          </v-list-group>
        </div>
      </v-list>

      <v-list dense>
        <v-list-item-group v-model="selectedItem" v-if="hasRoles(['super-admin', 'admin'])" color="primary"
          class="d-print-none">
          <v-subheader inset>Libraries</v-subheader>

          <v-list-item v-for="(link, i) in lib_menu" :key="i" :to="link.to">
            <v-list-item-icon >
              <v-icon v-text="link.icon"></v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title v-text="link.text"></v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-item-group>
      </v-list>
    </v-navigation-drawer>


    <v-app-bar app color="indigo darken-3" flat dark>
      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>

      <v-toolbar-title>Assistance to Individuals in Crisis (AICS)
      </v-toolbar-title>

      <v-spacer></v-spacer>

      {{ user.username }}

      <v-menu bottom left>
        <template v-slot:activator="{ on, attrs }">
          <v-btn dark icon v-bind="attrs" v-on="on">
            <v-icon> mdi-dots-vertical</v-icon>
          </v-btn>
        </template>

        <v-list>
          <v-list-item>
            <v-list-item-title>
              <a href="#" @click.prevent="logout()">Logout</a>
            </v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-app-bar>

    <v-main grey>
      <div class="container-fluid">

        <router-view :user="user">

        </router-view>
      </div>
    </v-main>

    <!-- <div class="container-fluid">
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
    </div>-->
  </v-app>
</template>

<script>
import userMixin from '../Mixin/userMixin';

export default {
  props: ['user'],
  mixins: [userMixin],
  data() {
    return {
      drawer: null,
      logo:
        location.protocol +
        "//" +
        location.host +
        "/" +
        process.env.MIX_BASE_NAME +
        "/images/DSWD-DVO-LOGO.png",
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
          {
            to: "/reports",
            text: "Reports",
          },
        ]
    };
  },

  methods: {
    logout() {
      axios.post(route("logout")).then(response => {
        console.log(response);
        location.reload();
      })
        .catch(error => {
          console.log(error);
        });
    }
  },
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