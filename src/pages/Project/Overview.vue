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

    <base-table :data="tableData">
      <template slot="columns">
        <th width="16%">Name</th>
        <th width="18%">Kunde</th>
        <th width="12%">Startdatum</th>
        <th width="12%">Status</th>
        <th width="12%">Volumen</th>
        <th width="18%">Projektmanager</th>
        <th width="12%"></th>
      </template>
      <template v-slot:default="{row}">
        <td>{{ row.name }}</td>
        <td>{{ getObjectProperty(row.customer, 'name') }}</td>
        <td>{{ formatDate(row.startDate) }}</td>
        <td>{{ row.status }}</td>
        <td>{{ row.volume }}</td>
        <td>{{ getObjectProperty(row.projectManager, 'name') }}</td>
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
      url: '/api/project/'
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
    getObjectProperty(object, property) {
      if (object) return object[property];
    },
    deletion(pid) {
      this.axios({
        method: 'DELETE',
        url: '/api/project/'+pid+'/',
      }).then(() => {
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
  }
};
</script>
<style>
</style>
