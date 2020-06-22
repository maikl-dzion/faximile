<template><div>

    <!---- ПРОСМОТР ПРОФИЛЯ ПОЛЬЗОВАТЕЛЯ -->
    <template>
        <v-dialog v-model="detailDialog" fullscreen hide-overlay transition="dialog-bottom-transition">
            <v-card style="border-radius: 0px" >

                <v-toolbar dark color="green">
                    <v-btn icon dark @click="detailDialog = false">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>User Info : {{user.username}} ({{user.login}})</v-toolbar-title>
                    <v-spacer></v-spacer>
                </v-toolbar>

                <div style="padding:4px;" >

                    <template><v-card>

                        <v-tabs v-model="tab"  >
                            <v-tab >Профиль пользователя</v-tab>
                            <v-tab >Реквизиты пользователя</v-tab>
                            <v-tab >Дополнительно</v-tab>
                        </v-tabs>

                        <!--- РЕКВИЗИТЫ ПОЛЬЗОВАТЕЛЯ --->
                        <v-tabs-items v-model="tab" >

                            <v-tab-item ><div class="tabPanelContainer">

                                <table class="table_col">

                                    <colgroup>
                                        <col style="background:#C7DAF0;">
                                    </colgroup>

                                    <tr>
                                        <th>Наменование</th>
                                        <th>Значение</th>
                                    </tr>

                                    <tr><td>ФИО</td>
                                        <td>{{user.username}}</td></tr>

                                    <tr><td>Логин</td>
                                        <td>{{user.login}}</td></tr>

                                    <tr><td>Почта</td>
                                        <td>{{user.p_email}}</td></tr>

                                    <tr><td>Телефон</td>
                                        <td>{{user.p_phone}}</td></tr>

                                    <tr><td>Должность</td>
                                        <td>{{user.post}}</td></tr>

                                    <tr><td>Компания</td>
                                        <td>{{user.company_name}}</td></tr>

                                    <tr><td>Согласие на использование ЭЦП</td>
                                        <td >
                                            <v-icon v-if="user.sign_true" color="green" >
                                                mdi-check-underline
                                            </v-icon>
                                            <v-icon v-else color="red" >mdi-close</v-icon>
                                        </td></tr>

                                    <tr><td>Согласие на обработку данных</td>
                                        <td>
                                            <v-icon v-if="user.user_info_true" color="green" >
                                                mdi-check-underline
                                            </v-icon>
                                            <v-icon v-else color="red" >mdi-close</v-icon>
                                        </td></tr>

                                </table>

                            </div></v-tab-item>

                            <!--- РЕКВИЗИТЫ ПОЛЬЗОВАТЕЛЯ --->
                            <v-tab-item ><div class="tabPanelContainer">
                                <v-card>
                                    <template><v-expansion-panels>
                                        <v-expansion-panel v-for="(list, fname) in infoList" :key="fname" >

                                            <template v-if="list.status" >
                                                <v-expansion-panel-header style="display: block" >

                                                    <v-icon color="green" style="width:20px; margin-right: 20px">
                                                        mdi-check-underline
                                                    </v-icon>

                                                    {{list.type.user_docname}}

                                                    <template v-if="list.item.sign_user" >
                                                        <v-chip
                                                                color="success" class="ma-2" outlined pill>
                                                            <v-icon left>mdi-checkbox-marked-circle</v-icon>
                                                            документ подписан
                                                        </v-chip>
                                                    </template>
                                                    <template v-else >
                                                        <v-chip  class="ma-2"
                                                                 color="red" outlined pill >
                                                            <v-icon left>mdi-close-circle</v-icon>
                                                            документ не подписан
                                                        </v-chip>
                                                    </template>

                                                </v-expansion-panel-header>
                                                <v-expansion-panel-content>
                                                    <iframe :src="rootUrl + list.item.path"
                                                            style="width: 100%; height: 600px;" frameborder="0">
                                                        Ваш браузер не поддерживает фреймы</iframe>
                                                </v-expansion-panel-content>
                                            </template>
                                            <template v-else >
                                                <v-expansion-panel-header style="display: block" >
                                                    <v-icon style="width:20px;" color="red" >mdi-close</v-icon>
                                                    {{list.type.user_docname}} (документ не загружен)
                                                </v-expansion-panel-header>
                                            </template>
                                        </v-expansion-panel>
                                    </v-expansion-panels></template>
                                </v-card>
                            </div></v-tab-item>

                            <v-tab-item ><div class="tabPanelContainer">
                                Дополнительные данные
                            </div></v-tab-item>

                        </v-tabs-items>

                    </v-card></template>

                </div>

            </v-card>
        </v-dialog>
    </template>
    <!--- / END PROFILE USER   --->

    <!----------------------------->
    <!----------------------------->
    <!----------------------------->
    <!----------------------------->

    <!---- ТАБЛИЦА РАБОТНИКОВ ---->
    <v-card style="margin:0px !important;" >

        <v-toolbar dense >
            <v-toolbar-title style="font-size: 14px" >Список работников</v-toolbar-title>
            <v-spacer></v-spacer>

            <slot name="top-panel"></slot>

            <v-btn icon><v-icon>mdi-magnify</v-icon></v-btn>
            <v-btn icon><v-icon>mdi-dots-vertical</v-icon> </v-btn>
        </v-toolbar>

        <!--<pre>{{personList}}</pre>-->

        <div class="data-list-wrapper" ><table class="data-list" style="width:100%" >

            <tr >
                <th v-for="(value, index) in headers"
                    v-html="value.title">
                </th>

                <th>Подробнее</th>
                <th>Действия</th>
            </tr>

            <tr v-for="(value, index) in personList" :key="value.user_id" >
                    <td>{{index + 1}}</td>
                    <td>{{value.user_id}}</td>
                    <td>{{value.username}}</td>
                    <td>{{value.user_phone}} <br>{{value.user_email}}</td>
                    <td>{{value.company_name}}</td>
                    <td>{{value.post}}</td>

                    <td>
                        <span style="color:blue" >{{value.doc_count}}</span>/
                        <span style="color:red" >{{value.doc_count-value.sign_count}}</span>/
                        <span style="color:green" >{{value.sign_count}}</span>
                    </td>

                    <td><template v-if="value.user_state" >
                            <v-chip v-if="value.user_state == 1" class="ma-2" color="orange" outlined pill>
                                <v-icon left>mdi-checkbox-marked-circle</v-icon>
                                отправлено приглашение
                            </v-chip>
                            <v-chip v-if="value.user_state == 2" class="ma-2" color="success"outlined pill>
                                <v-icon left>mdi-checkbox-marked-circle</v-icon>
                                активен
                            </v-chip>
                        </template>
                        <template v-else >
                            <v-chip @click="activationUserProfile(value)"
                                    title="Активировать работника"
                                    class="ma-2" color="red" outlined pill >
                                <v-icon left>mdi-close-circle</v-icon> не активен
                            </v-chip>
                        </template>
                    </td>

                    <td>{{value.user_created_date}}</td>

                    <td><v-btn icon color="blue" @click="viewDetailInfo(value)" >
                           <v-icon>mdi-view-grid</v-icon>
                        </v-btn>
                    </td>

                    <td><v-btn icon color="red" @click="deletePerson(value)" >
                            <v-icon>mdi-progress-close</v-icon>
                        </v-btn>
                    </td>
            </tr>

        </table></div>

    </v-card>
    <!---- / END ТАБЛИЦА РАБОТНИКОВ ---->

</div></template>

<script>
    export default {
        data () {
            return {
                tab : null,
                search     : '',
                personList : [],
                infoList : {},
                detailDialog : false,
                user     : {},
                userId   : 0,
                userType : 0,
                employeeRole : 3,

                headers: [
                    { title: '№', field: 'num', },
                    { title: 'User Id', field: 'user_id', },
                    { title: 'ФИО сотрудника', field: 'username', },
                    { title: 'Тел / Email'  , field: 'post' },
                    { title: 'Компания'      , field: 'company_name' },
                    { title: 'Должность'     , field: 'post' },
                    { title: 'Документы'     , field: 'doc_count' },
                    { title: 'Статус / Активация'        , field: 'user_state' },
                    { title: 'Created'       , field: 'user_created_date' },
                ],
            }},

        created() {
            this.getUploadsFilesUrl();
            this.getPersonList(this.employeeRole, 'personList');
        },

        methods: {

            deletePerson(item) {
                alert(item.user_id);
            },

            getInfoType(docList) {
                const apiUrl = 'documents/user/info/doc-type-list/' + this.userType;
                this.send(apiUrl).then(typeList => {
                    this.infoListRender(typeList, docList);
                });
            },

            viewDetailInfo(item) {
                this.user     = item;
                this.userId   = item['user_id'];
                this.userType = item['u_type'];
                this.detailDialog = true;
                this.getUserInfoDocList(this.userId, this.getInfoType);
            },

            activationUserProfile(userItem) {

                const activationUrl = 'faximile/front/app/person/register/';
                const host = window.location.host + activationUrl;
                userItem['register_page'] = host;

                const apiUrl = 'post/person/activation';
                this.send(apiUrl, "post", userItem).then(resp => {
                    this.getPersonList(this.employeeRole, 'personList');
                    let errorMessage = 'Не удалось отправить приглашение, попробуйте еще раз';
                    if(resp['save_result']) {
                        let message = 'Приглашение отправлено :';
                        let r = resp['save_result'];
                        let sms, mail = '';
                        if(r['phone']) sms  = ' на телефон;';
                        if(r['mail'])  mail = ' на почту;';
                        if(!sms && !mail) {
                            this.alertWindow(errorMessage, 2);
                        } else {
                            this.alertWindow(message + sms + mail);
                        }
                    } else {
                        this.alertWindow(errorMessage, 2);
                    }
                });
            },
        }
    }
</script>

<style>

    .table-user-info {
        width:100%;
    }

    .table-user-info td,
    .table-user-info th {
        padding:3px;
        margin:1px;
        border: 1px gainsboro solid;
    }

    .v-expansion-panel-header__icon {
        margin: 0px 0px 0px 98% !important;
    }

    .table_col {
        font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
        font-size: 14px;
        width: 660px;
        background: white;
        text-align: left;
        border-collapse: collapse;
        color: #3E4347;
    }
    .table_col th:first-child, .table_col td:first-child {
        /*color: #F5F6F6;*/
        color:black;
        border-bottom:1px black solid;
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
        border-bottom:1px gainsboro solid;
    }
</style>