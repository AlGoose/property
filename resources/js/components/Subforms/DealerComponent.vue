<template>
  <v-card class="mx-auto">
    <v-card-title>Дилер</v-card-title>
    <v-card-text>
      <v-autocomplete
        v-model="company"
        :items="companies"
        :search-input.sync="inn"
        color="grey"
        label="ИНН"
        outlined
        item-text="name"
        item-value="name"
        return-object
      ></v-autocomplete>

      <p class="subtitle">ИНН: {{company ? company.inn : ''}}</p>
      <p class="subtitle">КПП: {{company ? company.kpp : ''}}</p>
      <p class="subtitle">Название: {{company ? company.name : ''}}</p>
      <p class="subtitle">Адрес: {{company ? company.address : ''}}</p>
      <StaffComponent v-bind:test="company ? company.kpp : ''"></StaffComponent>
    </v-card-text>
  </v-card>
</template>

<script>
import StaffComponent from "./StaffComponent";

export default {
  components: {
    StaffComponent
  },
  data: () => ({
    inn: null,
    kpp: null,
    name: null,
    address: null,
    company: null,
    isLoading: false,
    companies: []
  }),

  watch: {
    inn(val) {
      if (this.isLoading) return;
      if (val === null || val.length != 10) return;

      let newThis = this;
      this.isLoading = true;
      this.companies = [];

      axios
        .get("/dealer/findByInn/" + val)
        .then(function(response) {
          console.log(response);
          response.data.suggestions.forEach(item => {
            let company = {
              inn: item.data.inn,
              kpp: item.data.kpp,
              name: item.value,
              address: item.data.address.value
            };
            newThis.companies.push(company);
          });
            newThis.isLoading = false;
        })
        .catch(function(error) {
          console.log(error);
          newThis.isLoading = false;
        });
    }
  }
};
</script>

<style scoped>
p {
  color: black;
}
</style>