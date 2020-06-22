<template>
    <div id="inspire">

        <top-header page_role="">
            <template v-slot:top-menu>
                <li>
                    <router-link to="/page/performer">Документы</router-link>
                </li>
            </template>
        </top-header>

        <!--<pre>{{personList}}</pre>-->

        <div style="display: block; border:0px red solid; padding:4px;">

            <div style="display: flex;">

                <div class="single-widget-area mb-30" style="width: 25%">
                    <!--<pre>{{personRender}}</pre>-->
                    <!--<div class="widget-title" style="background: gainsboro" ><h6>Пользователи</h6></div>-->
                    <ol class="nikki-archives">
                        <template v-for="person in personRender">
                            <li style="display: flex;">
                                <v-icon style="margin-right:10px;">mdi-account-box-outline</v-icon>
                                <a @click="getUserPersonalMessages(person)">
                                    {{person.username}}
                                </a>
                                <div style="color:green; font-weight: bold">{{person.m_count}}</div>
                            </li>
                        </template>
                    </ol>
                </div>

                <div style="width: 75%">

                    <div class="leave-comment-area clearfix">
                        <div class="comment-form">
                            <div class="row">
                                <div class="col-12">
                                    <button @click="sendMesssage()" class="btn nikki-btn">Отправить сообщение</button>
                                </div>
                                <form class="col-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                        <textarea v-model="newMessage.text" class="form-control" name="message"
                                                  id="message" cols="30" rows="10"
                                                  placeholder="Сообщение ..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="comment_area clearfix">

                        <ol>
                            <template v-for="mess in personalMessages">

                                <template v-if="mess.send_user_id != userId">
                                    <li class="single_comment_area">
                                        <ol class="children">
                                            <li class="single_comment_area"
                                                style="border-left: 1px gainsboro solid; padding-left: 10px; margin-bottom:4px;">
                                                <div class="comment-wrapper d-flex">
                                                    <div class="comment-content">
                                                        <div style="display: flex;">
                                                            <h6 style="margin-right: 20px; font-style: italic">
                                                                {{mess.client_name}}</h6>
                                                            <span style="padding-left: 20px; border-left: 1px gainsboro solid;"
                                                                  class="comment-date">{{mess.create_at}}</span>
                                                        </div>
                                                        <p v-html="mess.text"></p>
                                                    </div>
                                                </div>
                                            </li>
                                        </ol>
                                    </li>
                                </template>
                                <template v-else>
                                    <li class="single_comment_area"
                                        style="border-left: 1px gainsboro solid; padding-left: 10px;">
                                        <div class="comment-wrapper d-flex">
                                            <div class="comment-content">
                                                <div style="display: flex;">
                                                    <h6 style="margin-right: 20px; font-style: italic">
                                                        {{mess.client_name}}</h6>
                                                    <span style="padding-left: 20px; border-left: 1px gainsboro solid;"
                                                          class="comment-date">{{mess.create_at}}</span>
                                                </div>
                                                <p v-html="mess.text"></p>
                                                <!--<a href="#">Like</a>-->
                                                <!--<a class="active" href="#">Reply</a>-->
                                            </div>
                                        </div>
                                    </li>
                                </template>

                            </template>
                        </ol>
                    </div>

                </div>

            </div>
        </div>

        <v-footer app>
            <span>&copy; 2020</span>
        </v-footer>

    </div>
</template>

<script>
    export default {

        props: {
            source: String,
        },

        data: () => ({
            pageTitle: 'Чат сообщений',
            // drawer: null,
            userId: 0,
            selectUserId: 0,
            selectUserName: '',
            selectUser: {},

            personList: [],
            messageList: [],
            messages: [],
            personalMessages: [],
            message: {},

            newMessage: {
                send_user_id: 0,
                client_user_id: 0,
                text: '',
            },

        }),

        computed: {
            personRender() {
                return this.personList;
            }
        },

        watch: {
            // personList() {
            //     return this.personList;
            // }
        },

        created() {
            this.checkUserRole();
            this.userId = this.store('user_id');
            this.fetchInitData();
            this.$vuetify.theme.dark = false;
        },

        methods: {

            async fetchInitData() {
                let apiUrl = 'chat/user/get-chat-users/' + this.userId;
                this.personList = await this.send(apiUrl);
                this.getNoReadMessages();
                this.getUserPersonalMessages(this.personList[0]);
            },

            getNoReadMessages() {
                const selectUserId = this.selectUserId;
                const apiUrl = 'chat/user/get-no-read-messages/' + selectUserId + '/' + this.userId;
                this.send(apiUrl).then(this.setCountMessages);
            },

            setCountMessages(messagesCount) {
                // let len = '10';
                // for(let id in messagesCount) {
                //     const count = messagesCount[id];
                //     let oldCount = $('#' +id+ '-badge-item').html();
                //     if(oldCount == count) continue;
                //
                //     $('#' +id+ '-badge-box').show();
                //     $('#' +id+ '-badge-item').html(count);
                // }

                for (let i in this.personList) {
                    const user = this.personList[i];
                    const id = user.id;
                    let count = 0;
                    let oldCount = 0;
                    if (messagesCount[id]) {
                        count = messagesCount[id];
                        oldCount = this.personList[i]['m_count'];
                        if (oldCount == count) continue;
                        this.personList[i]['m_count'] = count;
                    }
                }
            },

            getUserPersonalMessages(user) {
                this.selectUser = user;
                this.selectUserId = user.id;
                this.selectUserName = user.username;
                //$('#' + this.selectUserId + '-badge-box').hide();
                //$('#' + this.selectUserId + '-badge-item').html('');
                for (let i in this.personList) {
                    if(this.personList[i].id == user.id) {
                        this.personList[i]['m_count'] = '';
                        break;
                    }
                }
                // this.personalMessages = [];
                const apiUrl = 'chat/user/get-personal-messages/' + this.userId + '/' + this.selectUserId;
                this.send(apiUrl).then(resp => {
                    this.personalMessages = resp;
                });
            },

            sendMesssage() {
                this.newMessage['send_user_id'] = this.userId;
                this.newMessage['client_user_id'] = this.selectUserId;
                let apiUrl = 'post/chat/user/send-message';
                this.send(apiUrl, "post", this.newMessage).then(resp => {
                    this.newMessage.text = '';
                    this.saveResponse(resp);
                    this.getUserPersonalMessages(this.selectUser);
                });
            },

            checkUserRole() {
                const role = this.store('role');
                if (role == 3) {
                    this.$router.push('/');
                }
            },

        },

        mounted: function () {

            setInterval(() => {
                this.getNoReadMessages();
                if (this.selectUserId)
                    this.getUserPersonalMessages(this.selectUser);
            }, 3000);

            // setInterval (() => {
            //     this.getNoReadMessages();
            // }, 6000);
        },
    }
</script>

<style>
    .message-container {
        width: 100%;
    }

    .message-left {
        border-left: 2px red dotted;
        border-bottom: 2px gainsboro solid;
        text-align: left;
        width: 100%;
        margin: 3px;
        padding: 6px;
    }

    .message-right {
        border-right: 2px blue dotted;
        border-bottom: 2px gainsboro solid;
        text-align: right;
        width: 100%;
        margin: 3px;
        padding: 6px;
    }

    .comment_area .single_comment_area::after {
        background: none;
    }
</style>

