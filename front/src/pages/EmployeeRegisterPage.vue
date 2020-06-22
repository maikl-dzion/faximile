<template><v-container>

    <v-card style="width: 70%; margin: 0 auto 0 auto;" >

        <v-toolbar color="green" cards dark flat style="margin:0px; padding:0px; border-radius: 0px">
            <v-card-title class="title font-weight-regular">Страница регистрации работника </v-card-title>
            <v-spacer></v-spacer>
            <v-card-title class="title font-weight-regular"> Приветствуем Вас , {{userName}}</v-card-title>
        </v-toolbar><p></p>

        <!--<pre>{{user}}</pre>-->

        <v-card-text><v-container><v-row>

            <v-col cols="6" sm="6" md="6">
                <v-text-field v-model="user.phone" label="Телефон" disabled ></v-text-field>
                <v-btn color="blue darken-1" text
                       @click="sendCodeToPhone(user)">
                       Подтвердите номер телефона
                </v-btn>
            </v-col>

            <v-col  cols="6" sm="6" md="6">
                <template v-if="stepFlag" >
                    <v-text-field v-model="sendCode" label="Код" ></v-text-field>
                    <v-btn  color="blue darken-1" text
                           @click="checkCodeToPhone(user, sendCode)"> Проверить </v-btn>
                </template>
            </v-col>

            <v-col  cols="12" sm="12" md="12"> <hr style="border:3px grey solid"></v-col>
            <!------------------------------------------->

            <template v-if="stepFlag > 1" >

                <!----- ДАННЫЕ ------>
                <v-col cols="12" sm="12" md="6">
                    <v-text-field v-model="user.username" label="ФИО"></v-text-field>
                </v-col>

                <v-col cols="12" sm="12" md="6" >
                    <v-text-field v-model="user.company_name" label="Компания" disabled ></v-text-field>
                </v-col>

                <v-col cols="12" sm="12" md="6">
                    <v-text-field v-model="user.email" label="Email" disabled ></v-text-field>
                </v-col>

                <v-col cols="12" sm="12" md="6">
                    <v-text-field v-model="user.post" label="Email" disabled ></v-text-field>
                </v-col>

                <v-col cols="12" sm="12" md="6">
                    <v-text-field v-model="user.login" label="Логин" disabled ></v-text-field>
                </v-col>

                <v-col cols="12" sm="12" md="6">
                    <v-text-field v-model="user.phone" label="Телефон" disabled ></v-text-field>
                </v-col>

                <v-col cols="12" sm="12" md="12">
                    <hr style="border:3px grey solid">
                </v-col>

                <!----- ДОКУМЕНТЫ ------>
                <template v-if="userType == 1" >

                    <v-col cols="12" sm="12" md="6"
                           v-for="(value, index) in personDocTypeList" :key="value.id" >
                        <v-file-input type="file" :id="value.alias" @change="handleFileUpload(value.alias)"
                                      show-size counter multiple :label="value.user_docname"></v-file-input>
                    </v-col>

                </template>
                <template v-if="userType == 2" >

                    <v-col cols="12" sm="12" md="6"
                           v-for="(value, index) in personDocTypeList" :key="value.id" >
                        <v-file-input type="file" :id="value.alias" @change="handleFileUpload(value.alias)"
                                      show-size counter multiple :label="value.user_docname"></v-file-input>
                    </v-col>

                </template>
                <template v-if="userType == 3" >

                </template>

                <!---- СОГЛАСИЕ ------->
                <v-col cols="12" sm="12" md="12">
                    <hr style="border:3px grey solid">
                </v-col>

                <v-col cols="12" sm="12" md="6" >
                    <v-checkbox
                            v-model="user.sign_true"
                            label="Согласие на использование ЭЦП"
                    ></v-checkbox>
                </v-col>

                <v-col cols="12" sm="12" md="6" >
                    <v-checkbox
                            v-model="user.user_info_true"
                            label="Согласие на обработку данных"
                    ></v-checkbox>
                </v-col>

            </template>

        </v-row></v-container></v-card-text>

        <v-card-actions>
            <v-spacer></v-spacer>
            <template v-if="stepFlag > 1" >
                <v-btn color="blue darken-1" text @click="save()" >Сохранить</v-btn>
            </template>
        </v-card-actions>

    </v-card>

</v-container></template>

<script>

export default {
        props: ['hash'],
        data: () => ({
            userName  : '',
            checkFlag : 2,
            stepFlag  : 2,
            param     : {},
            item      : {},
            user      : {},
            userHash  : '',
            userType : 1,
            sendCode : '',

            personDocTypeList : [],

            doc : {
                passport : {},
                register : {},
                snils    : {},
                inn      : {},
            },

        }),

        created() {
            this.userHash =  this.hash;
            this.getPerson('link_hash', this.userHash).then(resp => {
                this.personResponse(resp);
            });
        },

        methods: {

            personResponse(resp) {
                this.item = resp;
                this.item['code'] = '';

                this.user = resp;
                this.user['code'] = '';
                this.userType     = this.user['u_type'];
                this.userName     = this.user['username'];
                this.getPersonInfoDocTypeList(this.userType, 'personDocTypeList');
            },

            handleFileUpload(name){
                var file = document.querySelector('#' + name);
                this.doc[name] = file.files[0];
            },

            sendCodeToPhone(user) {
                const userId = user.user_id;
                const apiUrl = 'persons/send/phone/code/' + userId;
                this.get(apiUrl).then(resp => {
                    if(resp['send_state']) {
                        this.stepFlag = 1;
                    } else {
                        lg({'error_message' : 'Не удалось отправить кода на телефон', 'data' : resp});
                    }
                });
            },

            checkCodeToPhone(user, sendCode) {
                const userId = user.user_id;
                const apiUrl = 'persons/check/phone/code/' + userId + '/' + sendCode;
                this.get(apiUrl).then(resp => {
                    let alertMessage = 'Не удалось подтвердить, код не совпал';
                    if(resp['check_code']) {
                        this.stepFlag = 2;
                        this.alertWindow('Телефон успешно подтвержден');
                    } else {
                        this.alertWindow('Не удалось подтвердить, код не совпал', 2);
                    }
                });
            },

            save() {

                if(!this.user.user_info_true) {
                    alert('Вы не установили галочку в поле "Согласие на обработку данных", это обязательное поле');
                    return false;
                }

                if(!this.user.sign_true) {
                    alert('Вы не установили галочку в поле "Согласие на использование ЭЦП", это обязательное поле');
                    return false;
                }

                var postData = new FormData();

                for(var fname in this.doc) {
                    let value = this.doc[fname];
                    postData.append(fname, value);
                }

                for(var fname in this.user) {
                    let value = this.user[fname];
                    postData.append(fname, value);
                }

                const apiUrl = 'post/person/register';
                this.send(apiUrl, "post", postData).then(response => {
                    if(response['save_result']) {
                        this.$router.push('/page/auth');
                    } else {
                        alert("Не удалось сохранить данные,попробуйте еще раз");
                    }
                });
            }
        }
};
</script>

<style>

</style>
