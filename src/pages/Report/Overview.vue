<template>
  <div>
    <div class="row mb-3">
      <div class="col-md-6">
        <h3 slot="header" class="title my-0">{{$route.name}}</h3>
      </div>
      <div class="col-md-6 text-md-right">
        <router-link to="/reports/create" class="btn btn-primary btn-sm">Report erstellen</router-link>
      </div>
    </div>

    <base-table :data="tableData">
      <template slot="columns">
        <th width="15%">Datum</th>
        <th width="20%">Projekt</th>
        <th width="18%">Aufgewendete Zeit</th>
        <th width="35%">Beschreibung</th>
        <th width="12%"></th>
      </template>
      <template v-slot:default="{row}">
        <td>{{ formatDate(row.date) }}</td>
        <td>{{ getObjectProperty(row.project, 'name') }}</td>
        <td>{{ row.time }}</td>
        <td>{{ trimString(row.description, 30) }}</td>
        <td class="text-right">
          <router-link class="btn btn-primary btn-link" :to="'/reports/edit/'+row.rid">
            <i class="fas fa-pen fa-lg"></i>
          </router-link>
          <base-button @click="deletion(row.rid)" type="primary" link>
            <i class="fas fa-trash fa-lg"></i>
          </base-button>
        </td>
      </template>
    </base-table>
  </div>
</template>

<script>
import BaseTable from "@/components/BaseTable";

export default {
  components: {BaseTable},
  data() {
    return {
      tableData: [],
    }
  },
  mounted() {
    this.axios({
      method: 'GET',
      url: '/api/report/'
    }).then(response => {
      this.tableData = response.data;
    }).catch(error => {
      console.log(error);
    });
  },
  methods: {
    formatDate(dateString) {
      if (!dateString) return '';
      const date = new Date(dateString);
      return date.toLocaleDateString();
    },
    trimString(string, length) {
      if (string.length <= length) return string;
      return string.substring(0, length) + '...';
    },
    getObjectProperty(object, property) {
      if (object) return object[property];
    },
    deletion(rid) {
      this.axios({
        method: 'DELETE',
        url: '/api/report/'+rid+'/',
      }).then(() => {
        this.$notify({
          message: 'Report wurde erfolgreich gelöscht!',
          icon: 'fas fa-trash',
          type: 'success'
        });
        this.tableData = this.tableData.filter(temp => temp.rid !== rid);
      }).catch(error => {
        console.log(error);
      });
    }
  }
};
</script>
<style>
</style>
