<template>
  <div class="row">
    <div class="col-12">
      <card>
        <div class="row mb-3">
          <div class="col-md-6">
            <h3 slot="header" class="title my-0">{{ $route.name }}</h3>
          </div>
        </div>

        <form @submit.prevent="save">
          <div class="row">
            <div class="col-md-8">
              <base-input label="Name" placeholder="Name" v-model="user.name" :has-error="errors.name"></base-input>
            </div>
            <div class="col-md-4">
              <base-input label="Initialen" placeholder="Initialen" v-model="user.initials"
                          :has-error="errors.initials"></base-input>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <base-input label="Passwort" placeholder="Passwort" type="password" v-model="user.password"
                          :has-error="errors.password"></base-input>
            </div>
            <div class="col-md-6">
              <base-input label="Passwort wiederholen" placeholder="Passwort wiederholen" type="password"
                          v-model="user.passwordRepeat" :has-error="errors.passwordRepeat"></base-input>
            </div>
          </div>

          <div class="text-right">
            <router-link to="/" class="btn btn-secondary btn-sm mr-2">Abbrechen</router-link>
            <base-button type="primary" nativeType="submit">Speichern</base-button>
          </div>
        </form>
      </card>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: {
        name: '',
        initials: '',
        password: '',
        passwordRepeat: '',
      },
      errors: {
        name: false,
        initials: false,
        password: false,
        passwordRepeat: false
      },
    }
  },
  beforeMount() {
    this.apiHelpers.profileRequest(
      'GET'
    ).then((response) => {
      this.user = {
        name: response.data.name,
        initials: response.data.initials,
        password: '',
        passwordRepeat: '',
      };
    }).catch(error => {
      console.log(error);
    });
  },
  methods: {
    save() {
      this.apiHelpers.profileRequest(
        'POST',
        {
          user: this.user
        }
      ).then(() => {
        this.$notify({
          message: 'Dein Profil wurde erfolgreich gespeichert!',
          icon: 'fas fa-save',
          type: 'success'
        });

        // Reset errors
        this.errors = {
          name: false,
          initials: false,
          password: false,
          passwordRepeat: false
        };
      }).catch(error => {
        this.$notify({
          message: 'Dein Profil konnte nicht gespeichert werden!',
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
