<template>
  <div class="wrapper">
    <side-bar>
      <template slot="links">
        <sidebar-link to="/users" name="Benutzer" icon="fas fa-users"/>
        <sidebar-link to="/customers" name="Kunden" icon="fas fa-id-card"/>
        <sidebar-link to="/projects" name="Projekte" icon="fas fa-folder-open"/>
        <sidebar-link to="/reports" name="Zeiterfassung" icon="fas fa-business-time"/>
      </template>
    </side-bar>
    <div class="main-panel">
      <top-navbar></top-navbar>

      <dashboard-content @click.native="toggleSidebar">

      </dashboard-content>

      <content-footer></content-footer>
    </div>
  </div>
</template>
<style lang="scss">
</style>
<script>
import TopNavbar from "./TopNavbar.vue";
import ContentFooter from "./ContentFooter.vue";
import DashboardContent from "./Content.vue";
import MobileMenu from "./MobileMenu";
export default {
  components: {
    TopNavbar,
    ContentFooter,
    DashboardContent,
    MobileMenu
  },
  methods: {
    toggleSidebar() {
      if (this.$sidebar.showSidebar) {
        this.$sidebar.displaySidebar(false);
      }
    }
  }
};
</script>
