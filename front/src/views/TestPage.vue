<template><div style="text-align: center">

    <top-header page_role="2"></top-header>

    <!--<div v-if="alertToggle" >-->
        <!--<AlertWindow/>-->
    <!--</div>-->

    <!--<v-snackbar v-model="snackbar"-->
        <!--:multi-line="multiLine" style="" color="green" >-->
        <!--{{ snackbarText }}-->
        <!--<v-btn color="red" text-->
           <!--@click="snackbar = false"> Close-->
        <!--</v-btn>-->
    <!--</v-snackbar>-->

    <v-card style="padding:10px;" >

        <v-btn @click="snackbar=true">
            snackbar
        </v-btn>

        <v-btn @click="signVerify(31)">
            Проверить подпись
        </v-btn>

        <v-btn @click="checkEventBus">
            проверить шину событий
        </v-btn>

    </v-card>


    <div style="display: flex; width: 100%; margin: 10px auto 0px auto" >

        <div style="border:1px grey solid; padding:8px; margin:5px; width:33%; " >
            <v-btn style="border-radius: 0px; color:white; width: 100%" color="green darken-1"
                   @click="addUser(newUser)">
                Добавить пользователя
            </v-btn><p></p>

            <table style="width: 100%">
                <tr >
                    <th>Имя поля</th>
                    <th>Значение поля</th>
                </tr>
                <tr v-for="(value, field) in newUser" >
                    <td style="border: 1px gainsboro solid; text-align:left" >{{field}}</td>
                    <td style="border: 1px gainsboro solid" ><input type="text" v-model="newUser[field]" ></td>
                </tr>
            </table>
            <pre>{{newUser}}</pre>
        </div>

        <div style="border:1px grey solid; padding:8px; margin:5px; width:33%; " >
            <v-btn style="border-radius: 0px; color:white; width: 100%" color="green darken-1"
                   @click="addCompany(newCompany)">
                   Добавить компанию
            </v-btn> <p></p>

            <table style="width: 100%">
                <tr >
                    <th>Имя поля</th>
                    <th>Значение поля</th>
                </tr>
                <tr v-for="(value, field) in newCompany" >
                    <td style="border: 1px gainsboro solid; text-align:left" >{{field}}</td>
                    <td style="border: 1px gainsboro solid" >
                        <input type="text" v-model="newCompany[field]" ></td>
                </tr>
            </table>
            <pre>{{newCompany}}</pre>
        </div>

        <div style="border:1px grey solid; padding:8px; margin:5px; width:33%; " >

            <v-btn style="border-radius: 0px; color:white; width: 100%" color="green darken-1"
                   @click="addDocument">
                   Добавить документ
            </v-btn><p></p>

            <input type="file" @change="handleFileUpload()" id="file" ref="file" />
            <p></p>

            <table style="width: 100%">
                <tr >
                    <th>Имя поля</th>
                    <th>Значение поля</th>
                </tr>
                <tr v-for="(value, field) in newDocument" >
                    <td style="border: 1px gainsboro solid; text-align:left" >{{field}}</td>
                    <td style="border: 1px gainsboro solid" ><input type="text" v-model="newDocument[field]" ></td>
                </tr>
            </table>
            <pre>{{newDocument}}</pre>
        </div>

    </div>


    <!--<div style="border:1px grey solid; width:20%; margin:10px; padding:3px; " >-->

        <!--<p></p>-->
        <!--<v-btn color="blue darken-1" @click="addCompany(newCompany)">-->
            <!--Добавить компанию-->
        <!--</v-btn>-->
        <!--<p></p>-->
        <!--<div style="border:1px grey solid; padding:10px;">-->
            <!--<input type="file" @change="handleFileUpload()" id="file" ref="file" />-->
            <!--<p></p>-->
            <!--<v-btn color="blue darken-1" @click="addDocument">-->
                <!--Добавить документ-->
            <!--</v-btn>-->
        <!--</div>-->
    <!--</div>-->

    <!--<template>-->
        <!--<div class="container">-->
            <!--<div class="large-12 medium-12 small-12 cell">-->
                <!--<label>File-->
                    <!--<input type="file" id="file" ref="file" v-on:change="handleFileUpload()"/>-->
                <!--</label>-->
                <!--<button v-on:click="submitFile()" style="border:1px green solid" >Загрузить на сервер</button>-->
            <!--</div>-->
        <!--</div>-->
    <!--</template>-->

    <!--<img :src="getRootUrl + 'documents/mH6AOY1zEyFNzd76SspvLmGc6VLcYuOfUtrYXFrv.jpeg'" />-->

</div></template>

<script>

import axios from 'axios';

export default {
    data: () => ({
        test : {},
        file: '',
        newUser : {
            "id": 0,
            "username" : "Исмаилов Фарух",
            "login"    : "fa",
            "password" : "1234",
            "role"      : 3,
            "company_id": 15,
            "phone": "89062486209",
            "email": "test@mail.ru",
            "post": "Сварщик",
            "u_type": 2,
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

        newDocument : {
            "id": 0,
            "doctype_id": 2,
            "person_id" : 27,
            "doc_state" : 1
        },
    }),

    computed: {
        getFlag() {
            return this.flag === -1 ? "Добавить документ" : "Редкатировать документ";
        }
    },

    watch: {
        //dialog(val) { val || this.close(); }
    },

    created() {
        this.testFoo();
    },

    methods: {

        async testFoo() {
            const apiUrl = 'faximile/get-person/id/25';
            const resp   = await this.send(apiUrl);
            // lg(resp);
        },

        addUser(newItem) {
            const apiUrl = 'documents/add/person';
            this.send(apiUrl, "post", newItem).then(response => {
                this.saveResponse(response);
                this.close();
            });
        },

        addCompany(newItem) {
            const apiUrl = 'documents/add/company';
            this.send(apiUrl, "post", newItem).then(response => {
                this.saveResponse(response);
                this.close();
            });
        },

        addDocument() {

            let postData = new FormData();
            postData.append('file'      , this.file);
            postData.append('doctype_id', this.newDocument.doctype_id);
            postData.append('person_id' , this.newDocument.person_id);
            postData.append('doc_state' , this.newDocument.doc_state);

            const apiUrl = 'documents/add/document';
            this.send(apiUrl, "post", postData).then(response => {
                this.saveResponse(response);
                this.close();
            });
        },

        submitFile(){

            let formData = new FormData();
            formData.append('file', this.file);
            formData.append('user_name', 'Maikl');
            let headers = {
                headers: { 'Content-Type': 'multipart/form-data'}
            }

            this.send('documents/add/upload_files', 'post', formData)
                .then(response => {
                    console.log('SUCCESS!!');
                })

        },

        handleFileUpload(){
            this.file = this.$refs.file.files[0];
        },

        checkEventBus() {

            this.alertWindow('Успешное сохранение', 3);

            //const messageData = { 'message_type' : 1, 'message': 'Успешное сохранение'};
            //this.sendEventBus('alert_window_message', messageData)
        }

    },
};
</script>

<style>
  table td {
      padding:2px;
      border: 1px gainsboro solid;
  }

  input[type="text"] {
      border: 1px grey solid;
      padding:1px;
      margin: 0px;
      width: 100%;
  }
</style>
