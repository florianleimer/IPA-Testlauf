<template>
  <div>
    <div class="row mb-3">
      <div class="col-md-6">
        <h3 slot="header" class="title my-0">{{$route.name}}</h3>
      </div>
    </div>

    <form @submit.prevent="save">
      <div class="d-none">
        <input type="hidden" v-model="project.pid"/>
      </div>
      <base-input label="Name" placeholder="Name" v-model="project.name"></base-input>
      <base-input label="Kunde">
        <select class="form-control" v-model="project.customer">
          <option value="0"></option>
          <option v-for="customer in customersData" :value="customer.cid">{{ customer.name }}</option>
        </select>
      </base-input>
      <base-input label="Startdatum" type="date" v-model="project.startDate"></base-input>
      <base-input label="Projektstatus">
        <select class="form-control" v-model="project.status">
          <option value=""></option>
          <option value="open">Offen</option>
          <option value="completed">Erledigt</option>
          <option value="support">Support</option>
        </select>
      </base-input>
      <base-input label="Projektvolumen" placeholder="z.B. CHF 1'000.-" type="number" v-model="project.volume"></base-input>
      <base-input label="Projektmanager">
        <select class="form-control" v-model="project.projectManager">
          <option value="0"></option>
          <option v-for="user in usersData" :value="user.uid">{{ user.name }}</option>
        </select>
      </base-input>
      <base-input label="Kommentare">
        <textarea rows="4" class="form-control" placeholder="Kommentare zum Projekt..."
                  v-model="project.comments"></textarea>
      </base-input>
      <div class="text-right">
        <router-link to="/projects" class="btn btn-secondary btn-sm mr-2">Abbrechen</router-link>
        <base-button type="primary" nativeType="submit">Speichern</base-button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      project: {
        pid: '',
        name: '',
        customer: 0,
        startDate: '',
        status: '',
        volume: '',
        projectManager: 0,
        comments: ''
      },
      customersData: [],
      usersData: [],
      errors: {
        name: false,
        customer: false,
        startDate: false,
        status: false,
        volume: false,
        projectManager: false,
        comments: false
      },
    }
  },
  beforeMount() {
    const pid = this.$route.params.id;
    if (pid) {
      this.axios({
        method: 'GET',
        url: '/api/project/'+pid+'/',
      }).then((response) => {
        this.project = {
          pid: response.data.pid,
          name: response.data.name,
          customer: (response.data.customer) ? response.data.customer.cid : 0,
          startDate: response.data.startDate,
          status: response.data.status,
          volume: response.data.volume,
          projectManager: (response.data.projectManager) ? response.data.projectManager.uid : 0,
          comments: response.data.comments
        };
      }).catch(error => {
        console.log(error);
      });
    }

    this.axios({
      method: 'GET',
      url: '/api/customer/',
    }).then((response) => {
      this.customersData = response.data;
    }).catch(error => {
      console.log(error);
    });
    this.axios({
      method: 'GET',
      url: '/api/user/',
    }).then((response) => {
      this.usersData = response.data;
    }).catch(error => {
      console.log(error);
    });
  },
  methods: {
    save() {
      this.axios({
        method: 'POST',
        url: '/api/project/',
        data: {
          project: this.project
        }
      }).then(() => {
        this.$notify({
          message: 'Projekt wurde erfolgreich gespeichert!',
          icon: 'fas fa-save',
          type: 'success'
        });
        this.$router.push('/projects');
      }).catch(error => {
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
