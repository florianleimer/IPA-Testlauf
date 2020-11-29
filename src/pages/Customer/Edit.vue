<template>
  <div>
    <div class="row mb-3">
      <div class="col-md-6">
        <h3 slot="header" class="title my-0">{{$route.name}}</h3>
      </div>
    </div>

    <form @submit.prevent="save">
      <div class="d-none">
        <input type="hidden" v-model="customer.cid"/>
      </div>
      <base-input label="Name" placeholder="Name" v-model="customer.name" :has-error="errors.name"></base-input>
      <base-input label="Kundennummer" placeholder="Kundennummer" v-model="customer.clientNumber" :has-error="errors.name"></base-input>
      <base-input label="Adresse" placeholder="Adresse" v-model="customer.address" :has-error="errors.address"></base-input>
      <base-input label="Kommentare">
        <textarea rows="4" class="form-control" :class="{'is-invalid': errors.comments }" placeholder="Kommentare zum Kunden..."
                  v-model="customer.comments"></textarea>
      </base-input>
      <div class="text-right">
        <router-link to="/customers" class="btn btn-secondary btn-sm mr-2">Abbrechen</router-link>
        <base-button type="primary" nativeType="submit">Speichern</base-button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      customer: {
        cid: '',
        name: '',
        clientNumber: '',
        address: '',
        comments: ''
      },
      errors: {
        name: false,
        clientNumber: false,
        address: false,
        comments: false
      },
    }
  },
  beforeMount() {
    const cid = this.$route.params.id;
    if (cid) {
      this.axios({
        method: 'GET',
        url: '/api/customer/'+cid+'/',
      }).then((response) => {
        this.customer = {
          cid: response.data.cid,
          name: response.data.name,
          clientNumber: response.data.clientNumber,
          address: response.data.address,
          comments: response.data.comments,
        }
      }).catch(error => {
        console.log(error);
      });
    }
  },
  methods: {
    save() {
      this.axios({
        method: 'POST',
        url: '/api/customer/',
        data: {
          customer: this.customer
        }
      }).then(() => {
        this.$notify({
          message: 'Kunde wurde erfolgreich gespeichert!',
          icon: 'fas fa-save',
          type: 'success'
        });
        this.$router.push('/customers');
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
