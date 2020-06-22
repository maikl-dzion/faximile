<template><div style="" >

  <top-header page_role="2">
    <template v-slot:top-menu >

        <li><a  @click="tabPanel = 'documents'"    >Документы</a></li>
        <li><a  @click="tabPanel = 'person_list'"  >Сотрудники</a></li>
        <li><a  @click="tabPanel = 'company_list'" >Компании</a></li>

        <li><router-link to="/create-object-page"  >
             Добавление объектов
        </router-link></li>

        <li><router-link to="/chat-page"  >
            Чат сообщений
        </router-link></li>

    </template>
  </top-header>


  <v-container style ="" fluid>

      <template v-if="tabPanel == 'person_list'">

           <PersonsList>
               <template v-slot:top-panel >
                   <div style="display: flex; padding:8px 0px 0px 0px" >
                       <ActivationPerson /> &nbsp; &nbsp; &nbsp;
                       <!--<AddPerson />-->
                   </div>
               </template>
           </PersonsList>

      </template>
      <template v-else-if="tabPanel == 'company_list'">

           <CompanyList>
              <!--<template v-slot:top-panel >-->
                  <!--<div style="display: flex; padding:8px 0px 0px 0px" >-->
                   <!--<AddCompany/>-->
                  <!--</div>-->
              <!--</template>-->
           </CompanyList>

      </template>
      <template v-else >

       <!--<v-card style="margin:4px 0px !important; border:0px red solid;-->
                      <!--width: 100% !important; border-radius:0px" >-->

          <v-toolbar color="white" dense>
              <v-toolbar-title style="font-size: 14px" >{{pageTitle}}</v-toolbar-title>
              <v-divider class="mx-4" inset vertical></v-divider>
              <v-spacer></v-spacer>
              <!--<div style="padding:8px 0px 0px 0px" ><AddDocument/></div>-->
          </v-toolbar>

          <!--<pre>{{items}}</pre>-->

          <div class="data-list-wrapper" ><table class="data-list" style="width:100%" >

                  <tr >
                      <th v-for="(value, index) in headers"
                          v-html="value.title" ></th>
                      <th >Действия</th>
                  </tr>

                  <tr v-for="(value, index) in items" >
                      <td>{{index + 1}}</td>
                      <td>{{value.doc_id}}</td>
                      <td><a :href="getRootUrl + '/' + value.path" target="_blank">
                          <v-icon color="green" >mdi-file</v-icon>
                      </a></td>
                      <td>{{value.document_name}}</td>
                      <td>{{value.company_name}}</td>
                      <td>{{value.username}}</td>
                      <td>{{value.doc_create_at}}</td>

                      <td>
                          <template v-if="value.sign_user" >
                              <v-chip
                                      color="success" class="ma-2" outlined pill>
                                      <v-icon left>mdi-checkbox-marked-circle</v-icon>
                                      подписан
                              </v-chip>
                          </template>
                          <template v-else >
                              <v-chip  class="ma-2"
                                      color="red" outlined pill >
                                      <v-icon left>mdi-close-circle</v-icon>
                                      не подписан
                              </v-chip>
                          </template>
                      </td>

                      <td style="width: 20px;" ><template v-if="value.sign_user" >
                              <v-btn  @click="verifyDocument(value)" class="ma-2"
                                      style="border-radius: 0px;width:80%; padding:0px !important;" color="success" >
                                      <v-icon left>mdi-signature-freehand</v-icon>
                                      <v-icon left>mdi-account-edit</v-icon>
                              </v-btn>
                          </template>
                      </td>

                      <td><v-btn @click="deleteDocument(value)" color="red" icon >
                                  <v-icon >mdi-progress-close</v-icon>
                          </v-btn>
                      </td>
                  </tr>

          </table></div>
        <!--</v-card>-->

      </template>
  </v-container>

</div></template>

<script>
export default {
  data: () => ({

    pageTitle : 'Список документов',
    tabPanel  : 'documents',
    items : [],
    dialog  : false,
    headers : [
      { title: "№"   , field: "num"},
      { title: "ID док."   , field: "doc_id"},
      { title: "***"   , field: "view"},
      { title: "Документ"   , field: "document_name"},
      { title: "Компания"   , field: "company_name" },
      { title: "Сотрудник"  , field: "username" },
      { title: "Дата создания"  , field: "doc_create_at" },
      { title: "Статус"     , field: "verify_state" },
      { title: "Проверить <br> подпись"     , field: "check_verify" },
      //{ text: "ДЕЙСТВИЯ"    , value: "actions", sortable: false }
    ],

  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ?
          "Добавить документ" : "Редактировать документ";
    }
  },

  watch: {
      // dialog(val) {val || this.close();}
  },

  created() {
      this.getFaximileData('executor', null, 'items');

      // const eventName = 'activation_person';
      // this.getEventBus(eventName, resp => {
      //     this.getFaximileData('executor', null, 'items');
      // });
  },

  methods: {

    deleteDocument(item) {
        lg(item.document_name)
    },

    verifyDocument(item) {
        this.signUserVerify(item.doc_id);
    },
  }
};
</script>

<style>
  .v-data-table-header {
     background: gray;
  }

  .v-data-table-header tr th span{
     color:white
  }
</style>
