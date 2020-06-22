<template><v-app>

  <AlertWindow></AlertWindow>

  <!-- <v-app-bar app color="white" >

      <div class="d-flex align-center">
        <v-img alt="Vuetify Logo" class="shrink mr-2"  width="40" transition="scale-transition"
                src="https://cdn.vuetifyjs.com/images/logos/vuetify-logo-dark.png"/>
        <router-link to="/" style="text-decoration: none; font-weight:bold;">ФАКСИМИЛЕ</router-link>
      </div>

      <v-spacer></v-spacer>
      <v-toolbar-title style="font-size:13px; margin:0px 10px 0px 0px">{{userName}}</v-toolbar-title>
      <v-divider inset vertical ></v-divider>
      <v-btn icon @click="storeClear()" >
        <v-icon>mdi-home-export-outline</v-icon>
      </v-btn>

  </v-app-bar> -->

  <!-- <top-header></top-header>  -->

  <v-content>
      <router-view />
  </v-content>

</v-app></template>

<script>
export default {
  name: "App",
  data: () => ({
      showFlag : true,
  }),

  created() {
      // this.getUploadsFilesUrl();
      // this.setUserName();
  },

  methods : {},

};
</script>

<style scoped>
   .menu-list a {
      color:white;
      margin-right:15px; 
   }
</style>
