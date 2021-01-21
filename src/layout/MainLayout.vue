<template>
  <div class="wrapper">
    <side-bar>
      <template slot="links">
        <sidebar-link to="/customers" name="Kunden" icon="fas fa-id-card" v-show="userHelpers.hasAccess('/customers')"/>
        <sidebar-link to="/projects" name="Projekte" icon="fas fa-folder-open" v-show="userHelpers.hasAccess('/projects')"/>
        <sidebar-link to="/reports" name="Zeiterfassung" icon="fas fa-business-time" v-show="userHelpers.hasAccess('/reports')"/>
        <sidebar-link to="/users" name="Benutzer" icon="fas fa-users" v-show="userHelpers.hasAccess('/users')"/>
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

export default {
  components: {
    TopNavbar,
    ContentFooter,
    DashboardContent
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
