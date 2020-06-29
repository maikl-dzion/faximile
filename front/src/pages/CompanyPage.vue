<template><div>

    <top-header page_role="4" >
        <template v-slot:top-menu >
            <li><router-link to="/chat-page"  >
                Чат сообщений
            </router-link></li>
        </template>
    </top-header>

    <v-container fluid >

        <v-toolbar dense >
            <v-toolbar-title style="font-size: 14px" >Список документов работодателя</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon><v-icon>mdi-magnify</v-icon></v-btn>
            <v-btn icon><v-icon>mdi-dots-vertical</v-icon></v-btn>
        </v-toolbar>

        <!--<pre>{{documentsCompany}}</pre>-->

        <div class="data-list-wrapper" ><table class="data-list" style="width:100%" >

            <tr >
                <th style="max-width:10px;" >№</th>
                <th style="max-width:20px;" >ID док.</th>
                <th>***</th>
                <th>Название документа</th>
                <th>Работник</th>
                <th>Дата создания</th>
                <th>Статус документа</th>
            </tr>

            <tr v-for="(value, index) in documentsCompany" >
                <td>{{index + 1}}</td>
                <td>{{value.doc_id}}</td>
                <td><a :href="getRootUrl + value.path" target="_blank">
                    <v-icon style="cursor:pointer;" color="green" >mdi-file-document</v-icon>
                </a></td>
                <td style="" >
                    {{value.document_name}}
                </td>
                <td style="" >
                    {{value.username}}
                </td>
                <td>{{value.doc_create_at}}</td>
                <td style="width:20%;" >
                    <template v-if="value.sign_user" >
                        <v-chip class="ma-2" color="green" outlined pill >
                            <v-icon left>mdi-checkbox-marked-circle</v-icon>
                            подписан
                        </v-chip>
                    </template>
                    <template v-else >
                        <v-chip class="ma-2" color="red" outlined pill >
                            <v-icon left>mdi-close-circle</v-icon>
                            не подписан
                        </v-chip>
                    </template>
                </td>
            </tr>

        </table></div>

    </v-container>

</div></template>

<script>
export default {
  data: () => ({
    pageTitle: 'Список документов работодателя',
    items: [],
    dialog: false,
    headers: [],
    documentsCompany: []

  }),

  computed: {},

  created () {
    this.getFaximileData('company', null, 'documentsCompany')
  },

  methods: {
    editItem (item) {},
    deleteItem (item) {},
    close () {
      this.dialog = false
    },
    save () {}
  }
}
</script>

<style>
    .v-data-table-header {
        background: green;
    }

    .v-data-table-header tr th span{
        color:white
    }
</style>
