<template>
  <v-app> 
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-2">
        <!--<ul>
          <li v-for="(link, k) in links" :key="k">
            <router-link :to="link.to">{{ link.text }}</router-link>
          </li>
        </ul>-->


        <v-list dense>
     
      <v-list-item-group 
        v-model="selectedItem"
        color="primary"
        class="d-print-none"
      >
        <v-list-item
          v-for="(link, i) in links"
          :key="i"
          :to="link.to"
        >
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
      <div class="col-md-9"> 
       <router-view :user="user">
       
        </router-view>
      </div>
    </div>
  </div>
</v-app>
</template>

<script>
export default {
  props: ['user'],
  data() {
    return {
      selectedItem: 1,
      links: [
       
        {
          to: "/",
          text: "Home",
        },
        {
          to: "/client",
          text: "Client Information",
          
        },
        {
          to: "/assistance",
          text: "Request Assistance",
          
        },
        {
          to: "/contact",
          text: "Contact Us",
        },
       
      ],
    };
  },
  methods: {},
  mounted() {
   
  },
};
</script>

<style>
</style>