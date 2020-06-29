import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store/index'
import vuetify from './plugins/vuetify'
import GlobalPlugin from './plugins/GlobalPlugin'
import HttpService from './plugins/HttpService'

Vue.use(GlobalPlugin)
Vue.use(HttpService)

Vue.config.productionTip = false
Vue.prototype.$eventBus = new Vue()

new Vue({
  router,
  store,
  vuetify,
  render: h => h(App)
}).$mount('#app')
