<template><v-container>

    <!--<top-header ></top-header>-->

    <v-card style="width: 70%; margin: 0 auto 0 auto;" >

        <!--<v-card-title >-->
            <!--<span class="headline" style="font-size:16px !important;" >Регистрация</span>-->
        <!--</v-card-title>-->

        <v-toolbar color="deep-purple accent-4" cards dark flat style="margin:0px; padding:0px;">
            <v-card-title class="title font-weight-regular">Регистрация</v-card-title>
            <v-spacer></v-spacer>
        </v-toolbar><p></p>

        <!--<pre>{{userInfoDocType}}</pre>-->

        <v-card-text><v-container><v-row>

            <v-col cols="6" sm="6" md="6">
                <v-text-field v-model="item.phone" label="Телефон" ></v-text-field>
                <v-btn color="blue darken-1" text @click="checkPhone('phone', item.phone)">Подтвердите номер телефона</v-btn>
            </v-col>

            <v-col  cols="6" sm="6" md="6">
                <v-text-field v-model="item.code" label="Код" ></v-text-field>
                <v-btn v-if="checkFlag" color="blue darken-1" text @click="checkPhone('code', item.code)">Отправить</v-btn>
            </v-col>

            <v-col  cols="12" sm="12" md="12"> <hr style="border:3px grey solid"></v-col>
            <!------------------------------------------->

            <template v-if="checkFlag == 2">

                <!----- ДАННЫЕ ------>
                <v-col cols="12" sm="12" md="6">
                    <v-text-field v-model="item.username" label="ФИО"></v-text-field>
                </v-col>

                <v-col cols="12" sm="12" md="6" >
                    <v-text-field v-model="item.company_name" label="Компания" disabled ></v-text-field>
                </v-col>

                <v-col cols="12" sm="12" md="6">
                    <v-text-field v-model="item.email" label="Email" disabled ></v-text-field>
                </v-col>

                <v-col cols="12" sm="12" md="6">
                    <v-text-field v-model="item.post" label="Email" disabled ></v-text-field>
                </v-col>

                <v-col cols="12" sm="12" md="6">
                    <v-text-field v-model="item.login" label="Логин" disabled ></v-text-field>
                </v-col>

                <v-col cols="12" sm="12" md="6">
                    <v-text-field v-model="item.phone" label="Телефон" disabled ></v-text-field>
                </v-col>

                <v-col cols="12" sm="12" md="12">
                    <hr style="border:3px grey solid">
                </v-col>

                <!----- ДОКУМЕНТЫ ------>
                <template v-if="userType == 1" >

                    <!--<v-col cols="12" sm="12" md="6" >-->
                        <!--<v-file-input type="file" id="passport" v-on:change="handleFileUpload('passport')"-->
                                      <!--show-size counter multiple label="Паспорт"></v-file-input>-->
                    <!--</v-col>-->

                    <!--<v-col cols="12" sm="12" md="6" >-->
                        <!--<v-file-input type="file" id="register" @change="handleFileUpload('register')"-->
                                      <!--show-size counter multiple label="Регистрация"></v-file-input>-->
                    <!--</v-col>-->

                    <!--<v-col cols="12" sm="12" md="6" >-->
                        <!--<v-file-input type="file" id="snils" @change="handleFileUpload('snils')"-->
                                      <!--show-size counter multiple label="СНИЛС"></v-file-input>-->
                    <!--</v-col>-->

                    <!--<v-col cols="12" sm="12" md="6" >-->
                        <!--<v-file-input type="file" id="inn" @change="handleFileUpload('inn')"-->
                                      <!--show-size counter multiple label="ИНН"></v-file-input>-->
                    <!--</v-col>-->

                    <v-col cols="12" sm="12" md="6"
                           v-for="(value, index) in userInfoDocType" :key="value.id" >
                        <v-file-input type="file" :id="value.alias" @change="handleFileUpload(value.alias)"
                                      show-size counter multiple :label="value.user_docname"></v-file-input>
                    </v-col>

                </template>
                <template v-if="userType == 2" >

                     <v-col cols="12" sm="12" md="6"
                            v-for="(value, index) in userInfoDocType" :key="value.id" >
                            <v-file-input type="file" :id="value.alias" @change="handleFileUpload(value.alias)"
                                       show-size counter multiple :label="value.user_docname"></v-file-input>
                     </v-col>

                     <!--<v-col >-->
                         <!--<pre>{{userInfoDocType}}</pre>-->
                     <!--</v-col>-->

                </template>
                <template v-if="userType == 3" >

                </template>

                <!---- СОГЛАСИЕ ------->
                <v-col cols="12" sm="12" md="12">
                    <hr style="border:3px grey solid">
                </v-col>

                <v-col cols="12" sm="12" md="6" >
                    <v-checkbox
                            v-model="item.sign_true"
                            label="Согласие на использование ЭЦП"
                    ></v-checkbox>
                </v-col>

                <v-col cols="12" sm="12" md="6" >
                    <v-checkbox
                            v-model="item.user_info_true"
                            label="Согласие на обработку данных"
                    ></v-checkbox>
                </v-col>

            </template>

        </v-row></v-container></v-card-text>

        <v-card-actions>
            <v-spacer></v-spacer>
            <template v-if="checkFlag == 2">
                <!--<v-btn color="blue darken-1" text @click="close">Отменить</v-btn>-->
                <v-btn color="blue darken-1" text @click="save">Сохранить</v-btn>
            </template>
        </v-card-actions>

    </v-card>

</v-container></template>

<script>
    export default {
        props: ['hash'],
        data: () => ({
            checkFlag : 2,
            param     : {},
            item      : {},
            userHash  : '',
            userInfoDocType : [],

            doc : {
                passport : {},
                register : {},
                snils    : {},
                inn      : {},
            },

            userType : 1,
        }),

        created() {
            this.userHash =  this.hash;
            this.getPerson();
        },

        methods: {

            getPerson() {
                const apiUrl = 'faximile/get-person/link_hash/' + this.userHash;
                this.send(apiUrl).then(response => {
                    this.item = response;
                    this.item['code'] = '';
                    this.userType = this.item['u_type'];
                    this.getUserInfoDocType();
                });
            },

            handleFileUpload(name){
                var file = document.querySelector('#' + name);
                this.doc[name] = file.files[0];
            },

            checkPhone(type, prop) {
                const args = type + '/' + this.item.id + '/' + prop;
                const apiUrl = 'faximile/phone/check/' + args;
                this.send(apiUrl).then(response => {
                    if('check' in response) {
                        if(response['check']) {
                            this.checkFlag = 2;
                            this.alertWindow('Номер подтвержден');
                        } else {
                            this.checkFlag = 0;
                            this.alertWindow('Не удалось подтвердить', 2);
                        }
                    } else {
                        if('send' in response) {
                            if(response['send']) {
                                this.checkFlag = 1;
                                this.alertWindow('Код отравлен на почту');
                            } else {
                                this.checkFlag = 0;
                                this.alertWindow('Не удалось отправить', 2);
                            }
                        }
                        // this.param = response;
                    }
                });
            },

            save() {

                var postData = new FormData();
                for(var fname in this.doc) {
                    let value = this.doc[fname];
                    postData.append(fname, value);
                }

                for(var fname in this.item) {
                    let value = this.item[fname];
                    postData.append(fname, value);
                }

                const apiUrl = 'post/person/register';
                this.send(apiUrl, "post", postData).then(response => {
                    if(response['save_result']) {
                        this.$router.push('/auth-page');
                    }
                });
            }

        }
    };
</script>

<style>

</style>
