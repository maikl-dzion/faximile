<template><div>

    <!--- МЕНЮ ЗАГОЛОВКА    --->
    <top-header page_role="3" >
        <template v-slot:top-menu >
            <li><a  @click="tabPanel = 'my_documents'"  >Мои документы</a></li>
            <li><a  @click="tabPanel = 'my_info_docs'"  >Мой реквизиты</a></li>
            <li><a  @click="tabPanel = 'my_profile'"    >Мой профиль</a></li>
        </template>
    </top-header>
    <!--- / МЕНЮ ЗАГОЛОВКА  --->

    <!--- МОДАЛЬНОЕ ОКНО    --->
    <template>
        <div class="text-center" >
        <v-dialog v-model="signDialog" width="300"  >

                <v-card>
                    <v-card-title
                            class="headline green lighten-2"
                            primary-title style="color:white" >
                            Электронная подпись
                    </v-card-title>

                    <v-card-text>
                        <div style="text-align:justify" >
                            Чтобы подписать документ,
                            введите код отправленный Вам на телефон
                        </div>
                        <div style="text-align:center">
                            <v-col cols="12" sm="12" md="12">
                                <v-text-field v-model="signCode" label="код" solo ></v-text-field>
                            </v-col>
                        </div>

                        <div style="font-weight: bold; font-size: 16px;text-align:center">
                            {{docItem.document_name}}
                        </div>
                    </v-card-text>

                    <v-divider></v-divider>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn @click="sign" color="success" text >
                            Подписать документ
                        </v-btn>
                    </v-card-actions>
                </v-card>

         </v-dialog></div>
    </template>
    <!--- / МОДАЛЬНОЕ ОКНО --->

    <v-container style="margin:0px !important;" fluid>

        <template v-if="tabPanel == 'my_documents'" >

            <v-toolbar dense >
                <v-toolbar-title style="font-size: 14px" >Список документов работника</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn icon><v-icon>mdi-magnify</v-icon></v-btn>
                <v-btn icon><v-icon>mdi-dots-vertical</v-icon></v-btn>
            </v-toolbar>

            <!--<pre>{{documentsUser}}</pre>-->
            <div class="data-list-wrapper" ><table class="data-list" style="width:100%" >

                <tr >
                    <th style="max-width:10px;" >№</th>
                    <th style="max-width:20px;" >ID док.</th>
                    <th>***</th>
                    <th>Название документа</th>
                    <th>Дата создания</th>
                    <th>Статус документа</th>
                </tr>

                <tr v-for="(value, index) in documentsUser" >
                    <td>{{index + 1}}</td>
                    <td>{{value.doc_id}}</td>
                    <td><a :href="getRootUrl + value.path" target="_blank">
                        <v-icon style="cursor:pointer;" color="green" >mdi-file-document</v-icon>
                    </a></td>
                    <td style="text-align: left" >
                        {{value.document_name}}
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
                            <v-btn  @click="signOpen(value)" class="ma-2"
                                    style="border-radius: 0px;" color="success" >
                                    <v-icon left>mdi-signature-freehand</v-icon>
                                    ПОДПИСАТЬ
                            </v-btn>
                        </template>

                    </td>

                </tr>

            </table></div>
        </template>
        <template v-else-if="tabPanel == 'my_profile'" >

            <v-toolbar dense >
                <v-toolbar-title style="font-size: 14px" >User Profile</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn icon><v-icon>mdi-magnify</v-icon></v-btn>
                <v-btn icon><v-icon>mdi-dots-vertical</v-icon></v-btn>
            </v-toolbar>

            <v-row >
               <v-col cols="12" sm="12" md="4" >
                   <v-card>
                        <!--- ИЗМЕНЕНИЕ ДАННЫХ ПОЛЬЗОВАТЕЛЯ ----->
                        <v-card-text><v-container><v-row>
                                <v-col cols="12">
                                    <v-text-field v-model="user.username"
                                                  label="ФИО пользователя" ></v-text-field></v-col>

                                <v-col cols="12">
                                    <v-text-field v-model="user.phone"
                                                  label="Телефон" ></v-text-field></v-col>

                                <v-col cols="12">
                                    <v-text-field v-model="user.email"
                                                  label="Email" ></v-text-field></v-col>
                        </v-row></v-container></v-card-text>

                        <v-card-actions><v-spacer></v-spacer>
                            <v-btn color="blue darken-1" text @click="editPerson()">Сохранить</v-btn>
                        </v-card-actions>
                        <!--- / ИЗМЕНЕНИЕ ДАННЫХ ПОЛЬЗОВАТЕЛЯ ----->

                        <!--- ИЗМЕНЕНИЕ ПАРОЛЯ ----->
                        <v-card-text><v-container><v-row>
                            <v-col cols="12">
                                <v-text-field v-model="changePassword.passwd_1"
                                              label="Новый пароль" ></v-text-field></v-col>

                            <v-col cols="12">
                                <v-text-field v-model="changePassword.passwd_2"
                                              label="Повторить пароль" ></v-text-field></v-col>
                        </v-row></v-container></v-card-text>

                        <v-card-actions><v-spacer></v-spacer>
                            <v-btn color="blue darken-1" text @click="changeUserPassword()">Изменить пароль</v-btn>
                        </v-card-actions>
                        <!--- / ИЗМЕНЕНИЕ ПАРОЛЯ ---->
                   </v-card>
               </v-col>
               <v-col cols="12" sm="12" md="8" >
                   <!--<pre>{{infoList}}</pre>-->
                   <v-card v-for="(list, fname) in infoList" :key="fname">

                       <div style="width:100%; display: flex; padding:5px;">

                           <div style="width:30px; margin-right:10px; border:0px red solid" >
                               <v-icon v-if="list.item.id"  style="width:100%;" color="green" >mdi-check-underline</v-icon>
                               <v-icon v-else color="red" style="width:100%;">mdi-close</v-icon>
                           </div>

                           <div style="width:30px; margin-right:10px; border:0px red solid" >
                               <v-icon @click="showDocument('iframe-doc-' + fname)" color="grey" style="width:100%; cursor: pointer">
                                   mdi-chevron-down
                               </v-icon>
                           </div>

                           <div style="width:30%; border:0px red solid" >
                               {{list.type.user_docname}}
                           </div>

                           <div style="width:40%; margin-right:10px; border:0px red solid" >
                               <v-file-input type="file" :id="list.type.alias"
                                              @change="handleFileUpload(list.type.alias)"
                                              show-size counter multiple :label="list.type.user_docname"></v-file-input>
                           </div>

                           <div style="width:20%; border:0px red solid">
                               <v-btn color="green" @click="loadDocFile(list.type)"
                                      style="color:white; border-radius: 0px; width:100%;">
                                      Сохранить
                               </v-btn>
                           </div>

                       </div>

                       <div :id="'iframe-doc-' + fname" style="width:100%; padding:5px; display: none" class="user-info-docs" >
                           <iframe v-if="list.item.id" :src="getRootUrl + list.item.path"
                                   style="width: 100%; height: 600px;" frameborder="0">
                                   Ваш браузер не поддерживает фреймы</iframe>
                       </div>

                   </v-card>

                   <!--<v-card>-->
                       <!--<pre>{{infoList}}</pre>-->
                       <!--<v-row>-->
                           <!--<v-col cols="12" sm="12" md="6"-->
                                  <!--v-for="(value, index) in userInfoDocType" :key="value.id" >-->
                                  <!--<v-file-input type="file" :id="value.alias" @change="handleFileUpload(value.alias)"-->
                                             <!--show-size counter multiple :label="value.user_docname"></v-file-input>-->
                           <!--</v-col>-->
                       <!--</v-row>-->
                   <!--</v-card>-->

               </v-col>

            </v-row>
        </template >
        <template v-else-if="tabPanel == 'my_info_docs'">

            <div class="data-list-wrapper" ><table class="data-list" style="width:100%" >

                <tr >
                    <th style="width:10px;" >№</th>
                    <th style="width:30px;" >ID док.</th>
                    <th>***</th>
                    <th>Название документа</th>
                    <th>Дата создания</th>
                    <th>Статус документа</th>
                </tr>

                <tr v-for="(value, index) in userInfoDocList" >
                    <td>{{index + 1}}</td>
                    <td>{{value.doc_id}}</td>
                    <td><a :href="getRootUrl + value.path" target="_blank">
                        <v-icon style="cursor:pointer;" color="green" >mdi-file-document</v-icon>
                    </a></td>
                    <td style="text-align: left" >
                        {{value.user_docname}}
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
                            <v-btn  @click="signOpen(value)" class="ma-2"
                                    style="border-radius: 0px;" color="success" >
                                    <v-icon left>mdi-signature-freehand</v-icon>
                                    ПОДПИСАТЬ
                            </v-btn>
                        </template>

                    </td>
                </tr>

            </table></div>

        </template>

    </v-container>

</div></template>

<script>
export default {
  data: () => ({
        pageTitle  : 'Список документов работника',
        tabPanel   : 'my_profile',
        signDialog : false,
        signCode   : '',
        documentsUser   : [],
        userInfoDocList : [],
        userInfoDocType : [],
        docFiles : {},
        docItem  : {},
        user     : {},
        userId   : 0,
        userType : 0,
        infoList : [],

        changePassword : {
            passwd_1 : '',
            passwd_2 : '',
        },
  }),

  computed: {},

  created() {
      this.userId = this.store('user_id');
      this.getFaximileData('person', null, 'documentsUser');
      this.getPerson();
  },

  methods: {

    getPerson() {
          const apiUrl = 'faximile/get-person/id/' + this.userId;
          this.send(apiUrl).then(response => {
              this.user = response;
              this.userType = this.user['u_type'];
              this.getUserInfoDocList();
              this.getUserInfoDocType();
              this.userInfoDocuments({ type : this.userType, id : this.userId});
          });
    },

    handleFileUpload(name){
      var file = document.querySelector('#' + name);
      this.docFiles[name] = file.files[0];
    },

    showDocument(id)  {
        // const frame = document.querySelector('#' + id);
        $('#' + id).toggle();
    },

    getUserInfoDocList() {
          const apiUrl = 'documents/user/info/docs/' + this.userId;
          this.send(apiUrl).then(response => {
              this.userInfoDocList = response;
          });
    },

    signOpen(item) {
        this.docItem    = item;
        const apiUrl = 'faximile/phone/check/phone/' +this.userId+ '/0';
        this.send(apiUrl, "get").then(response => {
            if('send' in response && response['send']) {
                this.signDialog = true;
            } else {
                const message = 'Не удалось отправить сообщение на телефон';
                this.alertWindow(message);
            }
        });
    },

    checkCode() {
          const apiUrl = 'faximile/phone/check/code/' + this.userId + '/' + this.signCode;
          this.send(apiUrl, "get").then(response => {
              if('check' in response && response['check']) {
                  this.sign();
              } else {
                  const message = 'Не удалось проверить код,попробуйте еще раз';
                  this.alertWindow(message);
              }
          });
    },

    sign() {
        // debugger;
        const userId = this.userId;
        const docId  = this.docItem.doc_id;

        let apiUrl = 'documents/sign/user/' + userId+ '/' + docId;
        if(this.tabPanel == 'my_info_docs')
            apiUrl += '/user_info';

        this.send(apiUrl, "get").then(resp => {
            if('sign_result' in resp &&
                resp['sign_result']) {
                this.signDialog = false;
                this.getFaximileData('person', null, 'documentsUser');
                this.getUserInfoDocList();
            }
            this.alertWindow(resp['message']);
        });
    },

    editPerson() {
          const user = this.user;
          user['user_id'] = this.userId;
          const apiUrl = 'post/person/profile/edit';
          this.send(apiUrl, "post", user).then(response => {
              this.saveResponse(response);
          });
    },

    changeUserPassword() {
        const pwd1 = this.changePassword.passwd_1;
        const pwd2 = this.changePassword.passwd_2;
        if(!pwd1) {
            alert('Пароль не задан');
            return false;
        }

        if(pwd1 && pwd1 == pwd2) {
            const userId = this.userId;
            // const apiUrl = 'documents/user/change/password/' + userId + '/' + pwd1;
            const postData = { id : userId, password : pwd1};
            const apiUrl = 'post/person/change/password';
            this.send(apiUrl, "post", postData).then(response => {
                this.saveResponse(response);
            });
        } else {
            alert('Пароли не совпадает');
            return false
        }
    },

    loadDocFile(item) {
          const typeId = item.id;
          const userId = this.userId;
          let postData = new FormData();
          let fileNames = {};
          for(var fname in this.docFiles) {
              let file = this.docFiles[fname];
              postData.append(fname, file);
              postData.append('field_name', fname);
              fileNames[fname] = fname;
          }

          postData.append('names', fileNames);
          postData.append('login'    , this.user.login);
          postData.append('user_id'  , userId);
          postData.append('user_infotype_id' , typeId);

          const apiUrl = 'post/person/load/file/doc-info';
          this.send(apiUrl, "post", postData).then(response => {
              let message = 'Не удалось сохранить файл';
              if(response['save_result'])
                  message = 'Файл успешно сохранен';
              this.userInfoDocuments({ type : this.userType, id : this.userId});
              this.alertWindow(message);
          });
    }


  },

};
</script>

<style>
  .v-data-table-header {
     background: green;
  }
  .v-data-table-header tr th span{
     color:white
  }
</style>
