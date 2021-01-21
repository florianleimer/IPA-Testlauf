/*
 =========================================================
 * Vue Black Dashboard - v1.1.0
 =========================================================

 * Product Page: https://www.creative-tim.com/product/black-dashboard
 * Copyright 2018 Creative Tim (http://www.creative-tim.com)

 =========================================================

 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

 */
import Vue from "vue";
import App from "./App";

import VueRouter from "vue-router";
import RouterPrefetch from 'vue-router-prefetch'
import router from "./router/index";
Vue.use(VueRouter);
Vue.use(RouterPrefetch);

import axios from 'axios'
import VueAxios from 'vue-axios'
Vue.use(VueAxios, axios)

import BlackDashboard from "./plugins/blackDashboard";
Vue.use(BlackDashboard);

import helpers from "./helpers";
Vue.use(helpers);

import config from "./config";

// Redirect if not logged in
router.beforeEach((to, from, next) => {
  if (!config.publicPages.some(el => to.path.startsWith(el))) {
    if (!Vue.userHelpers.getUser())
      return next('/login');

    Vue.apiHelpers.loginRequest(
      'GET',
      {
        token: Vue.userHelpers.getUserToken()
      }
    ).then(response => {
      if (response.data.validated !== true) {
        Vue.userHelpers.setUser({});
        return next('/login');
      }

      const allowedPages = config.rolePages[response.data.role];
      if (!allowedPages.some(el => to.path.startsWith(el))) {
        return next('/404');
      }
    }).catch(error => {
      console.log(error);

      Vue.userHelpers.setUser({});
      return next('/login');
    });
  }

  next();
});

/* eslint-disable no-new */
new Vue({
  router,
  render: h => h(App)
}).$mount("#app");
