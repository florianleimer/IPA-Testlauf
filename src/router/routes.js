import MainLayout from "@/layout/MainLayout.vue";

// Pages
import Login from "@/pages/LoginPage.vue";
import UserLayout from "@/pages/UserLayout.vue";
import UserOverview from "@/pages/User/Overview.vue";
import UserEdit from "@/pages/User/Edit.vue";
import CustomerLayout from "@/pages/CustomerLayout.vue";
import CustomerOverview from "@/pages/Customer/Overview.vue";
import CustomerEdit from "@/pages/Customer/Edit.vue";
import ProjectLayout from "@/pages/ProjectLayout.vue";
import ProjectOverview from "@/pages/Project/Overview.vue";
import ProjectEdit from "@/pages/Project/Edit.vue";
import ReportLayout from "@/pages/ReportLayout.vue";
import ReportOverview from "@/pages/Report/Overview.vue";
import ReportEdit from "@/pages/Report/Edit.vue";
import Profile from "@/pages/ProfilePage.vue";
import NotFound from "@/pages/NotFoundPage.vue";

const routes = [
  {
    path: "/login",
    name: "Login",
    component: Login
  },
  {
    path: "/",
    component: MainLayout,
    redirect: "/customers",
    children: [

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
        component: ReportLayout,
        children: [
          {
            path: "",
            name: "Reports-Übersicht",
            component: ReportOverview
          },
          {
            path: "create",
            name: "Report erstellen",
            component: ReportEdit
          },
          {
            path: "edit/:id",
            name: "Report bearbeiten",
            component: ReportEdit
          },
        ]
      },
      {
        path: "users",
        component: UserLayout,
        children: [
          {
            path: "",
            name: "Benutzer-Übersicht",
            component: UserOverview
          },
          {
            path: "create",
            name: "Benutzer erstellen",
            component: UserEdit
          },
          {
            path: "edit/:id",
            name: "Benutzer bearbeiten",
            component: UserEdit
          },
        ]
      },
      {
        path: "profile",
        name: "Profil",
        component: Profile
      },
      {
        path: "404",
        name: "Fehler",
        component: NotFound
      },
    ]
  }
];

export default routes;
