<template>
  <div>
    <div class="row mb-3">
      <div class="col-md-6">
        <h3 slot="header" class="title my-0">{{ $route.name }}</h3>
      </div>
      <div class="col-md-6 text-md-right">
        <router-link to="/reports/create" class="btn btn-primary btn-sm">Report erstellen</router-link>
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
          <th width="15%">Datum</th>
          <th width="20%">Projekt</th>
          <th width="18%">Aufgewendete Zeit</th>
          <th width="35%">Beschreibung</th>
          <th width="12%"></th>
        </template>
        <template v-slot:default="{row}">
          <td>{{ formatHelpers.formatDate(row.date) }}</td>
          <td>{{ formatHelpers.getObjectProperty(row.project, 'name') }}</td>
          <td>{{ row.time }}</td>
          <td>{{ formatHelpers.trimString(row.description, 30) }}</td>
          <td class="text-right">
            <div v-if="row.isEditable">
              <router-link class="btn btn-primary btn-link" :to="'/reports/edit/'+row.rid">
                <i class="fas fa-pen fa-lg"></i>
              </router-link>
              <base-button @click="deletion(row.rid)" type="primary" link>
                <i class="fas fa-trash fa-lg"></i>
              </base-button>
            </div>
          </td>
        </template>
      </base-table>
      <div class="loader text-white text-center py-5" v-else-if="loading">
        <i class="fas fa-spinner fa-spin fa-3x"></i>
      </div>
      <base-alert type="info" v-else>Keine Reports vorhanden</base-alert>
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
    this.apiHelpers.reportRequest(
      'GET'
    ).then(response => {
      this.data = response.data;
      this.loading = false;
    }).catch(error => {
      console.log(error);
    });
  },
  methods: {
    deletion(rid) {
      this.apiHelpers.reportRequest(
        'DELETE',
        {
          rid: rid
        }
      ).then(() => {
        this.$notify({
          message: 'Report wurde erfolgreich gelöscht!',
          icon: 'fas fa-trash',
          type: 'success'
        });
        this.data = this.data.filter(temp => temp.rid !== rid);
      }).catch(error => {
        console.log(error);
      });
    }
  },
  computed: {
    tableData() {
      if (this.search.length > 1) {
        return this.data.filter((row) => {
          if (this.formatHelpers.formatDate(row.date).toLowerCase().includes(this.search.toLowerCase())) return true;
          if (this.formatHelpers.getObjectProperty(row.project, 'name').toLowerCase().includes(this.search.toLowerCase())) return true;
          //if (row.time.toLowerCase().includes(this.search.toLowerCase())) return true;
          if (row.description.toLowerCase().includes(this.search.toLowerCase())) return true;
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
