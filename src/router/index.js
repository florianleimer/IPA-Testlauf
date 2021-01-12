import VueRouter from "vue-router";
import routes from "./routes";

// configure router
const router = new VueRouter({
  routes, // short for routes: routes
  mode: 'history',
  linkActiveClass: "active",
});

export default router;
