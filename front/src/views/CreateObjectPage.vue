<template><div style="">

    <top-header page_role="2">
        <template v-slot:top-menu >
            <li><router-link to="/page/performer"  >
                Документы
            </router-link></li>
        </template>
    </top-header>

    <div class="tab-panel" style="border: 0px grey solid;
                                  border-bottom: 1px gainsboro solid;
                                  margin-bottom:5px;">
        <div class="classynav" style="border:0px green solid;" >
            <ul style="display: contents;">
                <li><a  @click="tabPanel = 'create_document'" >Создание документа</a></li>
                <li><a  @click="tabPanel = 'create_person'"   >Создание работника</a></li>
                <li><a  @click="tabPanel = 'create_company'"  >Создание компании</a></li>
            </ul>
        </div>
    </div>

    <div class="tab-panel" >

       <template v-if="tabPanel == 'create_person'" > <!---- СОЗДАНИЕ ПОЛЬЗОВАТЕЛЯ -->

           <v-btn @click="addUser(newUser)" dark
                  class="ma-2" tile color="indigo" style="width:300px; margin-left: 0px !important;">
                  <v-icon left>mdi-account</v-icon> Создать пользователя
           </v-btn>

           <table class="table_col">
               <colgroup>
                   <col style="background:#C7DAF0;">
               </colgroup>

               <tr><th>Наименование</th>
                   <th>Значение</th>
               </tr>

               <tr><td>ФИО работника</td>
                   <td><input v-model="newUser.username"
                              type="text" class="input-text"></td>
               </tr>

               <tr><td>Login</td>
                   <td><input v-model="newUser.login"
                              type="text" class="input-text"></td>
               </tr>

               <tr><td>Пароль</td>
                   <td><input v-model="newUser.password"
                              type="text" class="input-text"></td>
               </tr>

               <tr><td>Телефон</td>
                   <td><input v-model="newUser.phone"
                              type="text" class="input-text"></td>
               </tr>

               <tr><td>Email</td>
                   <td><input v-model="newUser.email"
                              type="text" class="input-text"></td>
               </tr>

               <tr><td>Должность</td>
                   <td><input v-model="newUser.post"
                              type="text" class="input-text"></td>
               </tr>

               <tr><td>Роль</td>
                   <td><select v-model="newUser.role">
                           <option v-for="option in personRoles" :value="option.id">
                               {{option.title}}
                           </option>
                       </select>
                   </td>
               </tr>

               <tr><td>Тип пользователя</td>
                   <td><select v-model="newUser.u_type">
                           <option v-for="option in personTypes" :value="option.id">
                               {{option.title}}
                           </option>
                       </select>
                   </td>
               </tr>

               <tr><td>Компания</td>
                   <td><select v-model="newUser.company_id">
                           <option v-for="option in companyList" :value="option.id">
                               {{option.company_name}}
                           </option>
                       </select>
                   </td>
               </tr>

           </table>

       </template>
       <template v-if="tabPanel == 'create_document'"> <!---- СОЗДАНИЕ ДОКУМЕНТА    -->


           <v-btn @click="addDocument()" dark
                  class="ma-2" tile color="indigo" style="width:300px; margin-left: 0px !important;">
                  <v-icon left>mdi-file</v-icon> Создать документ
           </v-btn>

           <table class="table_col">
               <colgroup>
                   <col style="background:#C7DAF0;">
               </colgroup>

               <tr><th>Наименование</th>
                   <th>Значение</th>
               </tr>

               <tr><td>Выбрать файл</td>
                   <td><input type="file" @change="handleFileUpload('doc-file')"
                              id="doc-file" ref="file" style="cursor:pointer;" /></td>
               </tr>

               <tr><td>Выбрать работника</td>
                   <td><select v-model="newDocument.person_id">
                           <option v-for="option in personList" :value="option.id">
                               {{option.username}}
                           </option>
                       </select>
                   </td>
               </tr>

               <tr><td>Выбрать тип документа</td>
                   <td><select v-model="newDocument.doctype_id">
                           <option v-for="option in doctypeList" :value="option.id">
                               {{option.docname}}
                           </option>
                       </select>
                   </td>
               </tr>

           </table>

       </template>
       <template v-if="tabPanel == 'create_company'" > <!---- СОЗДАНИЕ КОМПАНИИ     -->

           <v-btn @click="addCompany(newCompany)" dark
                  class="ma-2" tile color="indigo" style="width:300px; margin-left: 0px !important;">
                  <v-icon left>mdi-account</v-icon> Добавить компанию
           </v-btn>

           <table class="table_col">
               <colgroup>
                   <col style="background:#C7DAF0;">
               </colgroup>

               <tr><th>Наименование</th>
                   <th>Значение</th>
               </tr>

               <tr><td>Наименование компании</td>
                   <td><input v-model="newCompany.company_name"
                              type="text" class="input-text"></td>
               </tr>

               <tr><td>ИНН</td>
                   <td><input v-model="newCompany.inn"
                              type="text" class="input-text"></td>
               </tr>

               <tr><td>Телефон</td>
                   <td><input v-model="newCompany.phone"
                              type="text" class="input-text"></td>
               </tr>

               <tr><td>Email</td>
                   <td><input v-model="newCompany.email"
                              type="text" class="input-text"></td>
               </tr>

               <tr><td>Тип компании</td>
                   <td><select v-model="newCompany.com_type">
                           <option v-for="option in comTypeList" :value="option.id">
                               {{option.title}}
                           </option>
                       </select>
                   </td>
               </tr>

           </table>

       </template>

    </div>

    <!--<pre>{{companyList}}</pre>-->
    <!--<pre>{{personList}}</pre>-->

</div></template>

<script>

    import axios from 'axios';

    export default {
        data: () => ({
            tabPanel : 'create_document',
            docFile : {},

            newUser : {
                "id": 0,
                "username"  : "Исмаилов Фарид",
                "login"     : "far",
                "password"  : "1234",
                "role"      : 3,
                "company_id": 15,
                "phone": "89062486209",
                "email": "test@mail.ru",
                "post": "Разнорабочий",
                "u_type": 2,
            },

            newDocument : {
                "id": 0,
                "doctype_id": 2,
                "person_id" : 27,
                "doc_state" : 1
            },

            newCompany : {
                "id": 0,
                "company_name": 'ООО "Тестовая компания"',
                "inn"  : "456-5469u-y78844-9085775",
                "phone": "8-909-344-34-47",
                "email": "t1@mail.ru",
                "com_type"  : 2,
                "com_state" : 1
            },

            companyList : [],
            personList  : [],
            doctypeList : [],

            personTypes : [
                { id : 1 , title: 'Тип 1' },
                { id : 2 , title: 'Тип 2' },
                { id : 3 , title: 'Тип 3' },
            ],

            personRoles : [
                // { id : 1 , title: 'Admin' },
                { id : 2 , title: 'Менеджер системы' },
                { id : 3 , title: 'Работник' },
                { id : 4 , title: 'Менеджер компании' },
            ],

            comTypeList : [
                { id : 1 , title: 'Тип компании 1' },
                { id : 2 , title: 'Тип компании 2' },
                { id : 3 , title: 'Тип компании 3' },
            ],
        }),

        computed: {},

        created() {
            this.fetchData();
        },

        methods: {

            async fetchData() {
                this.personList = await this.getTableData('person');
                this.companyList = await this.getTableData('company');
                this.doctypeList = await this.getTableData('doctype');
            },

            addUser(newItem) {
                const apiUrl = 'documents/add/person';
                this.send(apiUrl, "post", newItem).then(response => {
                    this.saveResponse(response);
                });
            },

            addCompany(newItem) {
                const apiUrl = 'documents/add/company';
                this.send(apiUrl, "post", newItem).then(response => {
                    this.saveResponse(response);
                });
            },

            handleFileUpload(id){
                const file = document.querySelector('#' + id)
                this.docFile = file.files[0];
            },

            addDocument() {

                let postData = new FormData();
                postData.append('file'      , this.docFile);
                postData.append('doctype_id', this.newDocument.doctype_id);
                postData.append('person_id' , this.newDocument.person_id);
                postData.append('doc_state' , this.newDocument.doc_state);

                const apiUrl = 'documents/add/document';
                this.send(apiUrl, "post", postData).then(response => {
                    this.saveResponse(response);
                });
            },

        },
    };
</script>

<style>

    table td {
        padding:2px;
        border: 1px gainsboro solid;
    }

    input[type="text"] {
        border: 0px grey solid;
        padding:2px;
        margin: 0px;
        width: 100%;
    }

    select {
        border: 0px grey solid;
        padding:3px;
        margin: 0px;
        width: 100%;
        cursor:pointer;
    }

    .tab-panel {
        border:1px gainsboro solid;
        padding:10px;
        width: 70%;
        margin:5px auto 0px auto;
    }

    .table_col {
        font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
        font-size: 14px;
        width: 600px;
        background: white;
        text-align: left;
        border-collapse: collapse;
        color: #3E4347;
    }
    .table_col th:first-child, .table_col td:first-child {
        color: #F5F6F6;
        color: green;
        border-left: none;
    }
    .table_col th {
        font-weight: normal;
        border-bottom: 2px solid #F5E1A6;
        border-right: 20px solid white;
        border-left: 20px solid white;
        padding: 8px 10px;
    }
    .table_col td {
        border-right: 20px solid white;
        border-left: 20px solid white;
        padding: 12px 10px;
        color: #8b8e91;
    }

    .table_col .input-text {
        border: 0px gainsboro solid;
        border-left: 1px gainsboro solid;
        padding-left:10px;
    }
</style>
