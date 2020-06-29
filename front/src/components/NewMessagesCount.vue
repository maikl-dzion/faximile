<template>
<div>
    <div v-if="newMessagesCount" class="user__new_messages_count">
        {{newMessagesCount}}
    </div>
</div>
</template>

<script>
export default {
  name: 'NewMessagesCount',
  props: ['current_user_id'],

  data: () => ({
    userId: 0,
    newMessagesCount: 0
  }),

  created () {
    this.userId = this.store('user_id')
  },

  methods: {
    getNoReadMessages () {
      const currentUserId = this.current_user_id
      const apiUrl = 'chat/user/get-count-new-messages/' + currentUserId + '/' + this.userId
      this.send(apiUrl).then(response => {
        this.newMessagesCount = response['count']
      })
    }
  },

  mounted: function () {
    this.getNoReadMessages()

    setInterval(() => {
      this.getNoReadMessages()
    }, 3000)
  }
}
</script>

<style scoped>

</style>
