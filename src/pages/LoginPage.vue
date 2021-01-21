<template>
  <div class="wrapper">
    <div class="container h-100">
      <div class="row align-items-center h-100">
        <div class="col-md-6 offset-md-3">
          <card>
            <h3 slot="header" class="title my-0">Reporting-System</h3>
            <form @submit.prevent="login">
              <base-input label="Initialen" placeholder="Initialen" v-model="user.username"
                          :has-error="errors.username"></base-input>
              <base-input label="Passwort" placeholder="Passwort" type="password" v-model="user.password"
                          :has-error="errors.password"></base-input>
              <div class="text-right">
                <base-button type="primary" nativeType="submit">Login</base-button>
              </div>
            </form>
          </card>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: {
        username: '',
        password: ''
      },
      errors: {
        username: false,
        password: false
      }
    }
  },
  methods: {
    login() {
      this.apiHelpers.loginRequest(
        'POST',
        {
          user: this.user
        }
      ).then(response => {
        this.$notify({
          message: 'Erfolgreiches Login!',
          icon: 'fas fa-user',
          type: 'success'
        });

        this.userHelpers.setUser(response.data);
        this.$router.push('/');
      }).catch(error => {
        this.$notify({
          message: 'Das Login hat nicht geklappt!',
          icon: 'fas fa-user',
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
    }
  }
};
</script>
<style>
</style>
