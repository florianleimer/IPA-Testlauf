import MainLayout from "@/layout/MainLayout.vue";

// Pages
import Users from "@/pages/Users.vue";
import Customers from "@/pages/Customers.vue";
import Projects from "@/pages/Projects.vue";
import Reports from "@/pages/Reports.vue";
import NotFound from "@/pages/NotFoundPage.vue";

const routes = [
  {
    path: "/",
    component: MainLayout,
    redirect: "/users",
    children: [
      {
        path: "users",
        name: "Benutzer",
        component: Users
      },
      {
        path: "customers",
        name: "Kunden",
        component: Customers
      },
      {
        path: "projects",
        name: "Projekte",
        component: Projects
      },
      {
        path: "reports",
        name: "Zeiterfassung",
        component: Reports
      },
      /*
      {
        path: "*",
        name: "Fehler",
        component: NotFound
      },
      */
    ]
  }
];

export default routes;
