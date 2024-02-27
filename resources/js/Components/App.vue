<template>
  <div>

    <v-navigation-drawer v-model="drawer" app>
      <v-list-item>
        <v-list-item-content centered>
          <v-list-item-title>

            <v-img max-height="64" max-width="250px" src="images/DSWD-DVO-LOGO.png" contain alt="DSWD"></v-img>

          </v-list-item-title>
        </v-list-item-content>
      </v-list-item>


      <v-list dense>
        <div v-for="(link, i) in links">
          <v-list-item v-if="!link.subLinks" :key="i" :to="link.to" class="v-list-item">


            <v-list-item-icon>
              <v-icon>{{ link.icon }}</v-icon>
            </v-list-item-icon>

            <v-list-item-content>
              <v-list-item-title>
                {{ link.text }}
              </v-list-item-title>
              <v-list-item-subtitle v-if="link.subtitle">
                <small> ({{ link.subtitle }})</small>
              </v-list-item-subtitle>

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
            <v-list-item-icon>
              <v-icon v-text="link.icon"></v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title v-text="link.text"></v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-item-group>
      </v-list>
    </v-navigation-drawer>

    <div>
        <v-idle @idle="onidle" @remind="onremind" :reminders="[10]" :loop="true" :wait="0" :duration="900"
          :events="['mousemove', 'click', 'keypress', 'touchstart', 'touchmove',]" ref="foo"
          style="visibility: hidden;" />
      </div>

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


    <v-dialog v-model="snackbar" class="text-center" max-width="600">
      <v-app>
        <v-card>
          <v-card-title></v-card-title>
          <v-card-text class="text-center">

            <h1> <span class="mdi mdi-alert-octagon-outline"></span></h1>
            You will be logged out in <strong color="red"> {{ mytimer }} </strong> due to inactivity.

          </v-card-text>
          
        </v-card>
      </v-app>
    </v-dialog>


  </div>
</template>
<style scoped lang="scss">
::v-deep .v-application--wrap {
  min-height: fit-content;
}
</style>
<script>
import userMixin from '../Mixin/userMixin';

export default {
  props: ['user'],
  mixins: [userMixin],
  data() {
    return {
      snackbar: false,
      drawer: null,
      mytimer: 0,
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
          to: { name: 'home', },
          text: "Home"
        },
        {
          to: "/home/Pending",
          text: "Pending",
          subtitle: "New Clients"
        },
        {
          to: "/home/Verified",
          text: "Verified",
          subtitle: "For Interview"
        },
        {
          to: "/home/Serving",
          text: "Serving"
        },
        {
          to: "/home/Served",
          text: "Served"
        },
        {
          to: "/home/Rejected",
          text: "Rejected"
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
        {
          to: "/profile",
          text: "Profile",
        }

      ],
      lib_menu:
        [
          {
            to: "/users",
            text: "Users",
          },
          {
            to: "/clients",
            text: "Clients",
          },
          {
            to: "/offices",
            text: "Offices",
          },
          {
            to: "/charging",
            text: "Charging",
          },
          {
            to: "/signatories",
            text: "Signatories",
          },
          {
            to: "/signatories-settings",
            text: "Signatories Settings",
          },


        ]
    };
  },
  watch: {

    mytimer: {
      handler(value) {
        setTimeout(() => {
          this.mytimer = this.$refs.foo.$el.innerHTML;
        }, 1000);

      },
      immediate: true, // This ensures the watcher is triggered upon creation
      deep: true
    }

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
    },
    onidle() {
      this.logout();
    },
    onremind(time) {
      this.snackbar = true;
      this.mytimer = this.$refs.foo.$el.innerHTML;
    },
    resetTimer()
    {
      this.snackbar = false;
    }

  },

  mounted() {

    //this.mytimer = this.$refs.foo.$el.innerHTML;
    // console.log(this.mytimer );

    setTimeout(() => {
      this.mytimer = this.$refs.foo.$el.innerHTML;
    }, 1000);

    window.addEventListener('mousemove', this.resetTimer);
    window.addEventListener('keydown', this.resetTimer);
    window.addEventListener('keypress', this.resetTimer);
    


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