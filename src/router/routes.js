import MainLayout from "@/layout/MainLayout.vue";

// Pages
import Users from "@/pages/Users.vue";
import CustomerLayout from "@/pages/CustomerLayout.vue";
import CustomerOverview from "@/pages/Customer/Overview.vue";
import CustomerEdit from "@/pages/Customer/Edit.vue";
import ProjectLayout from "@/pages/ProjectLayout.vue";
import ProjectOverview from "@/pages/Project/Overview.vue";
import ProjectEdit from "@/pages/Project/Edit.vue";
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
        component: CustomerLayout,
        children: [
          {
            path: "",
            name: "Kunden-Übersicht",
            component: CustomerOverview
          },
          {
            path: "create",
            name: "Kunde erstellen",
            component: CustomerEdit
          },
          {
            path: "edit/:id",
            name: "Kunde bearbeiten",
            component: CustomerEdit
          },
        ]
      },
      {
        path: "projects",
        component: ProjectLayout,
        children: [
          {
            path: "",
            name: "Projekte-Übersicht",
            component: ProjectOverview
          },
          {
            path: "create",
            name: "Projekt erstellen",
            component: ProjectEdit
          },
          {
            path: "edit/:id",
            name: "Projekt bearbeiten",
            component: ProjectEdit
          },
        ]
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
