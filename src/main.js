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
import VueRouter from "vue-router";
import RouterPrefetch from 'vue-router-prefetch'
import App from "./App";
import router from "./router/index";

import axios from 'axios'
import VueAxios from 'vue-axios'
import BlackDashboard from "./plugins/blackDashboard";

Vue.use(VueAxios, axios)

Vue.use(BlackDashboard);

Vue.use(VueRouter);
Vue.use(RouterPrefetch);

// Redirect if not logged in
router.beforeEach((to, from, next) => {
  const publicPages = ['/login'];
  const authRequired = !publicPages.includes(to.path);

  if (authRequired) {
    if (!sessionStorage.getItem('user'))
      return next('/login');

    axios({
      method: "GET",
      url: '/api/login/',
      params: {
        token: sessionStorage.getItem('user')
      }
    }).then(response => {
      if (response.data !== true) {
        sessionStorage.setItem('user', '');
        return next('/login');
      }
    }).catch(error => {
      console.log(error);
    });
  }

  next();
});

/* eslint-disable no-new */
new Vue({
  router,
  render: h => h(App)
}).$mount("#app");
