<template>
  <div>
    <div class="row mb-3">
      <div class="col-md-6">
        <h3 slot="header" class="title my-0">{{$route.name}}</h3>
      </div>
      <div class="col-md-6 text-md-right">
        <router-link to="/projects/create" class="btn btn-primary btn-sm">Projekt erstellen</router-link>
      </div>
    </div>

    <base-table :data="tableData" v-if="hasTableData">
      <template slot="columns">
        <th width="16%">Name</th>
        <th width="17%">Kunde</th>
        <th width="12%">Startdatum</th>
        <th width="11%">Status</th>
        <th width="14%">Volumen</th>
        <th width="18%">Projektmanager</th>
        <th width="12%"></th>
      </template>
      <template v-slot:default="{row}">
        <td>{{ row.name }}</td>
        <td>{{ formatHelpers.getObjectProperty(row.customer, 'name') }}</td>
        <td>{{ formatHelpers.formatDate(row.startDate) }}</td>
        <td>{{ formatHelpers.projectStatus(row.status) }}</td>
        <td>{{ formatHelpers.formatCurrency(row.volume) }}</td>
        <td>{{ formatHelpers.getObjectProperty(row.projectManager, 'name') }}</td>
        <td class="text-right">
          <router-link class="btn btn-primary btn-link" :to="'/projects/edit/'+row.pid">
            <i class="fas fa-pen fa-lg"></i>
          </router-link>
          <base-button @click="deletion(row.pid)" type="primary" link>
            <i class="fas fa-trash fa-lg"></i>
          </base-button>
        </td>
      </template>
    </base-table>
    <div class="loader text-white text-center py-5" v-else-if="loading">
      <i class="fas fa-spinner fa-spin fa-3x"></i>
    </div>
    <base-alert type="info" v-else>Keine Projekte vorhanden</base-alert>

  </div>
</template>

<script>
import BaseTable from "@/components/BaseTable";
import BaseAlert from "@/components/BaseAlert";

export default {
  components: {BaseAlert, BaseTable},
  data() {
    return {
      tableData: [],
      loading: true,
    }
  },
  mounted() {
    this.apiHelpers.projectRequest(
      'GET'
    ).then(response => {
      this.tableData = response.data;
      this.loading = false;
    }).catch(error => {
      console.log(error);
    });
  },
  methods: {
    deletion(pid) {
      this.apiHelpers.projectRequest(
        'DELETE',
        {
          pid: pid
        }
      ).then(() => {
        this.$notify({
          message: 'Projekt wurde erfolgreich gelöscht!',
          icon: 'fas fa-trash',
          type: 'success'
        });
        this.tableData = this.tableData.filter(temp => temp.pid !== pid);
      }).catch(error => {
        console.log(error);
      });
    }
  },
  computed: {
    hasTableData() {
      return (this.tableData.length > 0);
    }
  }
};
</script>
<style>
</style>
