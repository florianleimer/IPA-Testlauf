<template>
  <div>
    <div class="row mb-3">
      <div class="col-md-6">
        <h3 slot="header" class="title my-0">{{$route.name}}</h3>
      </div>
      <div class="col-md-6 text-md-right">
        <router-link to="/customers/create" class="btn btn-primary btn-sm">Kunde erstellen</router-link>
      </div>
    </div>

    <base-table :data="tableData">
      <template slot="columns">
        <th width="22%">Name</th>
        <th width="22%">Kundennummer</th>
        <th width="22%">Adresse</th>
        <th width="22%">Kommentare</th>
        <th width="12%"></th>
      </template>
      <template v-slot:default="{row}">
        <td>{{ row.name }}</td>
        <td>{{ row.clientNumber }}</td>
        <td>{{ row.address }}</td>
        <td>{{ row.comments }}</td>
        <td class="text-right">
          <router-link class="btn btn-primary btn-link" :to="'/customers/edit/'+row.cid">
            <i class="fas fa-pen fa-lg"></i>
          </router-link>
          <base-button @click="deletion(row.cid)" type="primary" link>
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
      url: '/api/customer/'
    }).then(response => {
      this.tableData = response.data;
    }).catch(error => {
      console.log(error);
    });
  },
  methods: {
    deletion(cid) {
      this.axios({
        method: 'DELETE',
        url: '/api/customer/'+cid+'/',
      }).then(() => {
        this.$notify({
          message: 'Kunde wurde erfolgreich gelöscht!',
          icon: 'fas fa-trash',
          type: 'success'
        });
        this.tableData = this.tableData.filter(temp => temp.cid !== cid);
      }).catch(error => {
        console.log(error);
      });
    }
  }
};
</script>
<style>
</style>
