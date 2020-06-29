import Vue from 'vue'
import Router from 'vue-router'
import Home from './pages/Home.vue'

Vue.use(Router)

const pageFolder = './views'
const pagesUrl = './pages'

export default new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: [

    { path: '/', component: Home },

    {
      path: '/auth-page',
      component: () => import(pageFolder + '/AuthPage.vue')
    },

    {
      path: '/documents-page',
      component: () => import(pageFolder + '/DocumentsPage.vue')
    },

    {
      path: '/profile-page',
      component: () => import(pageFolder + '/ProfilePage.vue')
    },

    {
      path: '/person-page',
      component: () => import(pageFolder + '/PersonPage.vue')
    },

    {
      path: '/company-page',
      component: () => import(pageFolder + '/CompanyPage.vue')
    },

    {
      path: '/create-object-page',
      component: () => import(pageFolder + '/CreateObjectPage.vue')
    },

    {
      path: '/chat-page',
      component: () => import(pageFolder + '/ChatMessagePage.vue')
    },

    {
      path: '/person-register-page/:hash',
      props: true,
      component: () => import(pageFolder + '/PersonRegisterPage.vue')
    },

    {
      path: '/test-page',
      component: () => import(pageFolder + '/TestPage.vue')
    },

    // ----------------  NEW ROUTES ---------------------
    { path: '/', component: Home },

    {
      path: '/page/performer',
      component: () => import(pagesUrl + '/PerformerPage')
    },

    {
      path: '/page/company',
      component: () => import(pagesUrl + '/CompanyPage')
    },

    {
      path: '/page/employee',
      component: () => import(pagesUrl + '/EmployeePage')
    },

    {
      path: '/page/profile',
      component: () => import(pagesUrl + '/ProfilePage')
    },

    {
      path: '/page/auth',
      component: () => import(pagesUrl + '/AuthPage')
    },

    {
      path: '/person/register/:hash',
      props: true,
      component: () => import(pagesUrl + '/EmployeeRegisterPage'),
    },

    {
      path: '/page/chat',
      component: () => import(pagesUrl + '/ChatMessagePage')
    },

    {
      path: '/page/create/object',
      component: () => import(pagesUrl + '/CreateObjectPage')
    },

  ]
})
