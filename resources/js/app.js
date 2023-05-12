/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;
import '@mdi/font/css/materialdesignicons.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css';

import Vuetify from 'vuetify';
import VueRouter from "vue-router";
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import App from "./components/App.vue";
import Home from "./components/Home.vue";
import moment from 'moment';
import 'viewerjs/dist/viewer.css'
import VueViewer from 'v-viewer'
//import ImportFile from './Components/ImportFile.vue'
import GISComponent from "./Components/GISComponent.vue"
import Users from "./Components/Users"
import Assistance from "./Components/Assistance.vue"
import Contact from "./Components/Contact.vue"
import Assessment from "./Components/Assessment.vue"
import COEComponent from "./Components/COE.vue"
import Providers from "./Components/Providers"
import FundSource from "./Components/FundSource"
Vue.use(Vuetify);
Vue.use(VueRouter);
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(VueViewer)

const router = new VueRouter({
    mode: "history",
    base: process.env.MIX_BASE_NAME ,
    routes: [
        // { path: "/404", component: NotFound },
        {
            path: "/",
            redirect: { name: "home" },
        },
        {
            path: "/home",
            name: "home",
            component: Home,
            props: true,
        },
        {
            path: "/assistance",
            name: "assistance",
            component: Assistance,

        },
        {
            path: "/contact",
            name: "contact",
            component: Contact,

        },
        {
            path: "/assessment/:uuid",
            name: "assessment",
            component: GISComponent,

        },
        {
            path: "/assessment/:uuid/coe",
            name: "coe",
            component: COEComponent,

        },
        {
            path: "/users",
            name: "users",
            component: Users,

        },
        {
            path: "/providers",
            name: "Providers",
            component: Providers,
        },
        {
            path: "/fund_source",
            name: "FundSource",
            component: FundSource,
        },
    ],
});

Vue.filter("formatDate", function (value) {
    if (value) {
        return moment(String(value)).format("MMMM DD, YYYY h:mm a");
    }
});

Vue.component('register-component', require('./components/Register.vue').default);


export default new Vuetify({
    icons: {
      iconfont: 'mdi', // default - only for display purposes
    },
  })

  
const app = new Vue({
    el: '#app',
    components: { App },
    router,
    vuetify: new Vuetify(),

});

//console.log(process.env.MIX_BASE_NAME);