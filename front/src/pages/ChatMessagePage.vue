<template>
    <div id="inspire" style="height: 100%">
        <top-header page_role>
            <template v-slot:top-menu>
                <li>
                    <router-link to="/page/performer">Документы</router-link>
                </li>
            </template>
        </top-header>

        <!--<pre>{{personRender}}</pre>-->
        <!--<pre>{{personalMessages}}</pre>-->

        <div class="chat-container">
            <div style="display: flex; height: 100%">
                <div class="left-users-panel">
                    <div class="header-panel">Пользователи</div>

                    <div v-for="(user, i) in personRender"
                         @click="getUserPersonalMessages(user, $event)"
                         :id="user.login + user.id"
                         class="user-container">
                         <div class="user__avatar">
                            <!--<img src="../assets/avatar-default.jpg"/>-->
                            <img src="../assets/default-avatar.png"/>
                         </div>
                         <div class="user__name">
                            <div style="font-weight: bold;font-size: 15px;">{{user.username}}</div>
                            <div style="font-style:italic; font-size: 11px;">текст последнего сообщение</div>
                         </div>
                         <div class="user__new_count">
                             <NewMessagesCount v-if="user.id != selectUserId"
                                 :current_user_id="user.id"
                             ></NewMessagesCount>
                         </div>
                    </div>
                </div>
                <!--- left-users-panel -->

                <div class="center-messages-panel" >
                    <div class="header-panel">Сообщения</div>
                    <div class="messages__send_message">
                        <div class="file__load_btn">
                            <v-file-input style="margin:0px;"
                                          label="File load"></v-file-input>
                        </div>
                        <div class="send__message_content">
                            <textarea v-model="newMessage.text" class="form-control"
                              style="margin:0px;" rows="2"></textarea>
                        </div>
                        <div class="send__message_btn">
                            <v-btn @click="sendMesssage()" class="mx-0"
                                   color="green" style="height:93%; width:100%;color:white;">
                                <v-icon dark>mdi-send</v-icon>
                            </v-btn>
                        </div>
                    </div>

                    <div v-if="selectUserName" class="selected_user_box">
                        Сообщения от : {{selectUserName}}
                    </div>

                    <div class="message-container">
                        <div v-for="(message, i) in personalMessages"
                             style="margin-top: 5px;" :key="message.id">
                             <div v-if="dateFormatLine(message.create_at)" >
                                 {{dateLine}}
                             </div>
                             <template v-if="message.text">
                                <div v-if="message.send_user_id == userId" class="my__message-box">
                                    <div class="message__username">{{message.username}}</div>
                                    <div class="message__text" v-html="message.text"></div>
                                    <div class="message__date">{{message.create_at}}</div>
                                </div>
                                <div v-else class="user__message-box">
                                    <div class="message__username">{{message.username}}</div>
                                    <div class="message__text" v-html="message.text"></div>
                                    <div class="message__date">{{message.create_at}}</div>
                                </div>
                             </template>
                        </div>
                    </div>

                </div>

                <div class="right-tasks-panel" style>
                    <div class="header-panel">Задачи</div>
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
    source: String
  },

  data: () => ({
    pageTitle: 'Чат сообщений',
    // drawer: null,
    userId: 0,
    selectUserId: 0,
    selectUserName: '',
    dateLine : '',
    selectUser: {},

    personList: [],
    messageList: [],
    messages: [],
    personalMessages: [],
    message: {},

    newMessage: {
      send_user_id: 0,
      client_user_id: 0,
      text: ''
    }
  }),

  computed: {
    personRender () {
      return this.personList
    }
  },

  created () {
    this.checkUserRole()
    this.userId = this.store('user_id')
    this.fetchInitData()
    this.$vuetify.theme.dark = false
  },

  methods: {

    async fetchInitData () {
      let apiUrl = 'chat/user/get-chat-users/' + this.userId
      this.personList = await this.send(apiUrl)
      this.getNoReadMessages()
      this.getUserPersonalMessages(this.personList[0])
    },

    dateFormatLine (date) {
        const d = date.split(' ');
        // debugger;
        if(this.dateLine != d[0]) {
          this.dateLine = d[0];
          return true;
        }
        return false;
    },

    getNoReadMessages () {
      const selectUserId = this.selectUserId
      const apiUrl = 'chat/user/get-no-read-messages/' + selectUserId + '/' + this.userId
      this.send(apiUrl).then(this.setCountMessages)
    },

    setCountMessages (messagesCount) {
      for (let i in this.personList) {
        const user = this.personList[i]
        const id = user.id
        let count = 0
        let oldCount = 0
        if (messagesCount[id]) {
          count = messagesCount[id]
          oldCount = this.personList[i]['m_count']
          if (oldCount === count) continue
          this.personList[i]['m_count'] = count
        }
      }
    },

    getUserPersonalMessages (user = null, event = null) {
      if (!user) return false

      this.selectUser = user
      this.selectUserId = user.id
      this.selectUserName = user.username

      let elemId = user.login + user.id
      let activeClass = 'user__active'
      let listClass = 'user-container'
      if (event) this.setActiveElement(event, activeClass)
      // this.jqSetActiveElement(elemId, activeClass, listClass);

      for (let i in this.personList) {
        if (this.personList[i].id === user.id) {
          this.personList[i]['m_count'] = ''
          break
        }
      }

      const apiUrl = 'chat/user/get-personal-messages/' +
                      this.userId + '/' + this.selectUserId
      this.send(apiUrl).then(resp => {
        this.personalMessages = resp
      })
    },

    sendMesssage () {
      this.newMessage['send_user_id'] = this.userId
      this.newMessage['client_user_id'] = this.selectUserId
      let apiUrl = 'post/chat/user/send-message'
      this.send(apiUrl, 'post', this.newMessage).then(resp => {
        this.newMessage.text = ''
        this.saveResponse(resp)
        this.getUserPersonalMessages(this.selectUser)
      })
    },

    checkUserRole () {
      const role = this.store('role')
      if (role === 3) {
        this.$router.push('/')
      }
    }
  },

  mounted: function () {
    setInterval(() => {
      if (this.selectUserId) {
        this.getUserPersonalMessages(this.selectUser)
      }
    }, 4000)
  }
}
</script>

<style>
    .chat-container {
        display: block;
        border: 2px gainsboro solid;
        padding: 2px;
        height: 100%;
    }

    .left-users-panel,
    .center-messages-panel,
    .right-tasks-panel {
        border: 1px gainsboro solid;
        padding: 2px;
        margin: 2px;
    }

    .left-users-panel {
        background: #f5f2eb;
        width: 30%;
    }

    .center-messages-panel {
        background: cornflowerblue;
        width: 50%;
    }

    .right-tasks-panel {
        background: #e5f0ea;
        width: 20%;
    }

    .header-panel {
        border-bottom: 2px gainsboro solid;
        font-style: italic;
        margin-bottom: 4px;
        height: 55px;
    }

    /* контейнер пользователя */
    .user-container {
        display: flex;
        height: 44px;
        border: 1px gainsboro solid;
        margin-bottom: 8px;
        padding: 2px;
        background: #00acc1;
        cursor: pointer;
    }

    .user__active {
        background: green;
        color: white;
    }

    .user__avatar {
        width: 20%;
    }

    .user__name {
        width: 80%;
    }

    .user__new_count {
        width: 25px;
    }

    .user__avatar img {
        height: 100%;
    }

    .user__new_messages_count {
        width: 100%;
        color: white;
        text-align: center;
        background: limegreen;
        margin-top: -6px;
        margin-left: 8px;
    }

    /*  </ контейнер пользователя */

    /*  start > контейнер сообщений */

    .message-container {
        background: cornflowerblue;
        border: 0px red solid;
        margin-top: 5px;
        padding: 2px;
        /*height: 100%;*/
    }

    .my__message-box,
    .user__message-box {
        padding: 4px;
        border-radius: 5px;
        border: 1px gainsboro solid;
        box-shadow: 0 0 0 1px #ccc,
        inset 0 0 0 1px #ccc;
    }

    .my__message-box {
        background: royalblue;
        text-align: left;
        margin-left: 20%;
    }

    .user__message-box {
        background: darkslateblue;
        text-align: left;
        margin-right: 20%;

    }

    .message__username {
        text-align: left;
        color: #b0bed9;
        font-style: italic;
        font-size: 10px;
    }

    .message__text {
        text-align: justify;
        font-size: 17px;
        padding: 3px 3px 3px 14px;
        background: white;
        color: black;
        border-radius: 6px 0px 6px 0px;
        font-size: 12px;
    }

    .message__date {
        text-align: right;
        font-style: italic;
        color: burlywood;
        font-size: 9px;
    }

    /*  </ контейнер сообщений */

    .messages__send_message {
        display: flex;
        padding: 4px;
        border: 1px gainsboro solid;
    }

    .file__load_btn {
        border: 0px red solid;
        width: 6%;
    }

    .send__message_content {
        margin: 0px 2px 0px 2px;
        border: 0px red solid;
        width: 73%;
    }

    .send__message_btn {
        border: 0px red solid;
        width: 20%;
    }

    .selected_user_box {
        font-style: italic;
        padding: 3px;
        color: orangered;
        font-weight: bold;
        background: #b0bec5;
    }

</style>
