<template>
  <div>
    <div class="row mb-3">
      <div class="col-md-6">
        <h3 slot="header" class="title my-0">{{ $route.name }}</h3>
      </div>
    </div>

    <form @submit.prevent="save">
      <div class="d-none">
        <input type="hidden" v-model="user.uid"/>
      </div>
      <base-input label="Name" placeholder="Name" v-model="user.name" :has-error="errors.name"></base-input>
      <base-input label="Initialen" placeholder="Initialen" v-model="user.initials" :has-error="errors.initials"></base-input>
      <base-input label="Passwort" placeholder="Passwort" v-model="user.password" :has-error="errors.password"></base-input>
      <div class="row">
        <div class="col-md-6">
          <base-input label="Ist aktiv">
            <select class="form-control" :class="{'is-invalid': errors.active}" v-model="user.active">
              <option :value="true">Aktiv</option>
              <option :value="false">Deaktiviert</option>
            </select>
          </base-input>
        </div>
        <div class="col-md-6">
          <base-input label="Status">
            <select class="form-control" :class="{'is-invalid': errors.status}" v-model="user.status">
              <option value="user">Benutzer</option>
              <option value="admin">Administrator</option>
            </select>
          </base-input>
        </div>
      </div>
      <div class="text-right">
        <router-link to="/users" class="btn btn-secondary btn-sm mr-2">Abbrechen</router-link>
        <base-button type="primary" nativeType="submit">Speichern</base-button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: {
        uid: '',
        name: '',
        initials: '',
        password: '',
        active: true,
        status: 'user'
      },
      errors: {
        name: false,
        initials: false,
        password: false,
        active: false,
        status: false
      },
    }
  },
  beforeMount() {
    const uid = this.$route.params.id;
    if (uid) {
      this.axios({
        method: 'GET',
        url: '/api/user/' + uid + '/',
        headers: {
          'Authorization': sessionStorage.getItem('user')
        }
      }).then((response) => {
        this.user = {
          uid: response.data.uid,
          name: response.data.name,
          initials: response.data.initials,
          password: response.data.password,
          active: response.data.active,
          status: response.data.status
        };
      }).catch(error => {
        console.log(error);
      });
    }
  },
  methods: {
    save() {
      this.axios({
        method: 'POST',
        url: '/api/user/',
        headers: {
          'Authorization': sessionStorage.getItem('user')
        },
        data: {
          user: this.user
        }
      }).then(() => {
        this.$notify({
          message: 'Benutzer wurde erfolgreich gespeichert!',
          icon: 'fas fa-save',
          type: 'success'
        });
        this.$router.push('/users');
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
