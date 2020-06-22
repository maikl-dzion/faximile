<template><div>

  <!--<v-card style="margin:4px !important;" >-->

    <v-toolbar dense>
      <!-- <v-app-bar-nav-icon></v-app-bar-nav-icon> -->
      <v-toolbar-title style="font-size: 14px" >Список компаний</v-toolbar-title>
      <v-spacer></v-spacer>

      <slot name="top-panel"></slot> &nbsp; &nbsp;&nbsp;&nbsp;

      <v-btn icon><v-icon>mdi-magnify</v-icon></v-btn>
      <v-btn icon><v-icon>mdi-dots-vertical</v-icon> </v-btn>
    </v-toolbar>

    <!--<v-data-table-->
      <!--:headers="headers"-->
      <!--:items="items"-->
      <!--:search="search"-->
    <!--&gt;</v-data-table>-->

  <!--</v-card>-->


  <!--<pre>{{items}}</pre>-->
  <div class="data-list-wrapper" ><table class="data-list" style="width:100%" >

    <tr >
      <th v-for="(value, index) in headers">
        {{value.title}}
      </th>
      <th>Действия</th>
    </tr>

    <template v-for="(value, index) in items" >
      <tr >
        <td>{{index + 1}}</td>
        <td>{{value.company_uid}}</td>
        <td>{{value.company_name}}</td>
        <td>{{value.c_phone}}</td>
        <td>{{value.c_email}}</td>
        <td>{{value.inn}}</td>
        <td>
          <span style="color:blue" >{{value.doc_count}}</span>/
          <span style="color:red" >{{value.doc_count-value.sign_count}}</span>/
          <span style="color:green" >{{value.sign_count}}</span>
        </td>

        <td>
          <v-btn  @click="deleteCompany(value)" class="ma-2"
                  style="border-radius: 0px; max-width:20px; padding:0px !important; color:white;" color="red" >
            <v-icon >mdi-progress-close</v-icon>
          </v-btn>
        </td>
      </tr>

    </template>

  </table></div>

</div></template>

<script>
  export default {
    data () {
      return {
        search: '',
        items : [],
        headers: [
          { title: '№'        , field: 'num', },
          { title: 'Id'       , field: 'company_id', },
          { title: 'Компания' , field: 'company_name', },
          { title: 'Телефон'  , field: 'c_phone' },
          { title: 'Почта'    , field: 'c_email' },
          { title: 'ИНН'      , field: 'inn' },
          { title: 'Документы', field: 'doc_count' },

        ],
    }},

    created() {
        // this.getTableData('company', 'items');
        //this.getTableData('person' , 'personsList');
        this.getCompanyDocuments('items');
    },

    methods: {

        deleteCompany(item) {
           const companyId = item.company_uid;
        },

    }
  }
</script>