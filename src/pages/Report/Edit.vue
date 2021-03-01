<template>
  <div>
    <div class="row mb-3">
      <div class="col-md-6">
        <h3 slot="header" class="title my-0">{{ $route.name }}</h3>
      </div>
    </div>

    <form @submit.prevent="save">
      <div class="d-none">
        <input type="hidden" v-model="report.rid"/>
      </div>
      <base-input label="Datum" type="date" v-model="report.date" :has-error="errors.date"></base-input>
      <base-input label="Projekt">
        <select class="form-control" :class="{'is-invalid': errors.project}" v-model="report.project">
          <option value="0"></option>
          <option v-for="project in projectData" :key="project.pid" :value="project.pid" v-if="project.isOpen">{{ project.name }}</option>
        </select>
      </base-input>
      <base-input label="Aufgewendete Zeit" type="time" v-model="report.time" :has-error="errors.time"></base-input>
      <base-input label="Beschreibung">
        <textarea rows="4" class="form-control" :class="{'is-invalid': errors.description}" placeholder="Beschreibung der Tätigkeit..."
                  v-model="report.description"></textarea>
      </base-input>
      <div class="text-right">
        <router-link to="/reports" class="btn btn-secondary btn-sm mr-2">Abbrechen</router-link>
        <base-button type="primary" nativeType="submit">Speichern</base-button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      report: {
        rid: '',
        date: '',
        project: '',
        time: '',
        description: '',
        creator: ''
      },
      projectData: [],
      errors: {
        date: false,
        project: false,
        time: false,
        description: false,
        creator: false
      },
    }
  },
  beforeMount() {
    const rid = this.$route.params.id;
    if (rid) {
      this.apiHelpers.reportRequest(
        'GET',
        {
          rid: rid
        }
      ).then((response) => {
        this.report = {
          rid: response.data.rid,
          date: response.data.date,
          project: (response.data.project) ? response.data.project.pid : 0,
          time: response.data.time,
          description: response.data.description,
          creator: response.data.creator
        };
      }).catch(error => {
        console.log(error);
      });
    }

    this.apiHelpers.projectRequest(
      'GET',
      {
        getAll: true
      }
    ).then((response) => {
      this.projectData = response.data;
    }).catch(error => {
      console.log(error);
    });
  },
  methods: {
    save() {
      this.apiHelpers.reportRequest(
        'POST',
        {
          report: this.report
        }
      ).then(() => {
        this.$notify({
          message: 'Report wurde erfolgreich gespeichert!',
          icon: 'fas fa-save',
          type: 'success'
        });
        this.$router.push('/reports');
      }).catch(error => {
        this.$notify({
          message: 'Report konnte nicht gespeichert werden!',
          icon: 'fas fa-save',
          type: 'danger'
        });
        switch (error.response.status) {
          case 420:
            this.errors = error.response.data;
            break;
          default:
            console.log(error);
        }
      });
    },
  },
};
</script>
<style>
</style>
