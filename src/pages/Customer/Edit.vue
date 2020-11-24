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
      <base-input label="Name" placeholder="Name" v-model="customer.name"></base-input>
      <base-input label="Kundennummer" placeholder="Kundennummer" v-model="customer.clientNumber"></base-input>
      <base-input label="Adresse" placeholder="Adresse" v-model="customer.address"></base-input>
      <base-input>
        <label>Kommentare</label>
        <textarea rows="4" class="form-control" placeholder="Kommentare zum Kunden..."
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
      cssStyle: {
        name: 'form-control',
        clientNumber: 'form-control',
        address: 'form-control',
        comments: 'form-control'
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
          clientNumber: response.data.client_number,
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
            // input validation
            for (let el in error.response.data) {
              if (error.response.data[el]) {
                this.cssStyle[el] = 'form-control';
              } else {
                this.cssStyle[el] = 'form-control is-invalid';
              }
            }
            break;
          default:
            // eslint-disable-next-line no-console
            console.log(error);
        }
      });
    },
  },
};
</script>
<style>
</style>
