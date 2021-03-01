<template>
  <div>
    <div class="row mb-3">
      <div class="col-md-6">
        <h3 slot="header" class="title my-0">{{ $route.name }}</h3>
      </div>
      <div class="col-md-6 text-md-right">
        <router-link to="/users/create" class="btn btn-primary btn-sm">Benutzer erstellen</router-link>
      </div>
    </div>

    <div class="base-table-wrapper">
      <div class="table-search row">
        <div class="offset-md-8 col-md-4">
          <base-input placeholder="Suche..." v-model="search"></base-input>
        </div>
      </div>
      <base-table :data="tableData" v-if="hasTableData">
        <template slot="columns">
          <th width="30%">Name</th>
          <th width="20%">Initialen</th>
          <th width="17%">Aktiv</th>
          <th width="20%">Status</th>
          <th width="12%"></th>
        </template>
        <template v-slot:default="{row}">
          <td>{{ row.name }}</td>
          <td>{{ row.initials }}</td>
          <td>{{ formatHelpers.formatBool(row.active) }}</td>
          <td>{{ formatHelpers.userStatus(row.status) }}</td>
          <td class="text-right">
            <router-link class="btn btn-primary btn-link" :to="'/users/edit/'+row.uid">
              <i class="fas fa-pen fa-lg"></i>
            </router-link>
            <base-button @click="deletion(row.uid)" type="primary" link>
              <i class="fas fa-trash fa-lg"></i>
            </base-button>
          </td>
        </template>
      </base-table>
      <div class="loader text-white text-center py-5" v-else-if="loading">
        <i class="fas fa-spinner fa-spin fa-3x"></i>
      </div>
      <base-alert type="info" v-else>Keine Benutzer vorhanden</base-alert>
    </div>

  </div>
</template>

<script>
import BaseTable from "@/components/BaseTable";
import BaseAlert from "@/components/BaseAlert";

export default {
  components: {BaseAlert, BaseTable},
  data() {
    return {
      data: [],
      search: '',
      loading: true,
    }
  },
  mounted() {
    this.apiHelpers.userRequest(
      'GET'
    ).then(response => {
      this.data = response.data;
      this.loading = false;
    }).catch(error => {
      console.log(error);
    });
  },
  methods: {
    deletion(uid) {
      this.apiHelpers.userRequest(
        'DELETE',
        {
          uid: uid
        }
      ).then(() => {
        this.$notify({
          message: 'Benutzer wurde erfolgreich gelöscht!',
          icon: 'fas fa-trash',
          type: 'success'
        });
        this.data = this.data.filter(temp => temp.uid !== uid);
      }).catch(error => {
        console.log(error);
      });
    }
  },
  computed: {
    tableData() {
      if (this.search.length > 1) {
        return this.data.filter((row) => {
          if (row.name .toLowerCase().includes(this.search.toLowerCase())) return true;
          if (row.initials.toLowerCase().includes(this.search.toLowerCase())) return true;
          if (this.formatHelpers.formatBool(row.active).toLowerCase().includes(this.search.toLowerCase())) return true;
          if (this.formatHelpers.userStatus(row.status).toLowerCase().includes(this.search.toLowerCase())) return true;
          return false;
        });
      } else {
        return this.data;
      }
    },
    hasTableData() {
      return (this.tableData.length > 0);
    }
  }
};
</script>
<style></style>
