<template>
  <v-card class="mx-auto">
    <v-card-title>Дилер</v-card-title>
    <v-card-text>
      <v-autocomplete
        v-model="company"
        :items="companies"
        :search-input.sync="search"
        color="grey"
        label="ИНН"
        outlined
        hide-details
        no-filter
        return-object
        item-text="name"
        :loading="isLoading"
      >
        <template v-slot:item="{ item }">{{item.name}}, КПП:{{item.kpp}}</template>
        <template v-slot:selection="{ item }">{{item.inn}}, КПП:{{item.kpp}}</template>
      </v-autocomplete>

      <p class="subtitle">Название: {{company ? company.name : ''}}</p>
      <p class="subtitle">Адрес: {{company ? company.address : ''}}</p>
      <StaffComponent v-if="dealer" :entity="dealer" mode="dealer" @staff="saveStaff"></StaffComponent>
    </v-card-text>
  </v-card>
</template>

<script>
import StaffComponent from "./StaffComponent";
//7728168971
export default {
  components: {
    StaffComponent
  },
  data: () => ({
    search: "",
    company: { inn: "" },
    isLoading: false,
    companies: [],
    dealer: ""
  }),

  watch: {
    company(val) {
      axios
        .post("/dealer/findDealer", { inn: val.inn, kpp: val.kpp })
        .then(response => {
          if (response.data === "") {
            this.dealer = {};
          } else {
            this.dealer = response.data;
          }
        })
        .catch(e => {
          console.log(e);
        });
    },

    search(val) {
      if (this.isLoading) return;
      if (val === null || val.length != 10) return;

      this.isLoading = true;

      axios
        .get("/data/findByInn/" + val)
        .then(response => {
          this.companies = response.data;
          this.isLoading = false;
        })
        .catch(function(error) {
          this.isLoading = false;
        });
    }
  },

  methods: {
    saveStaff(staff) {
      if (!this.dealer.id) {
        axios
          .post("/dealer", { dealer: this.company, staff_id: staff })
          .then(response => {
            this.$emit('dealer', {
              dealer_id: response.data.id,
              dealer_staff_id: staff
            });
          });
      } else {
        axios
          .post("/dealer", {
            dealer: {
              inn: this.company.inn,
              kpp: this.company.kpp,
              address: this.company.address,
              name: this.company.name
            },
            staff_id: staff
          })
          .then(response => {
            this.$emit('dealer', {
              dealer_id: response.data.id,
              dealer_staff_id: staff
            });
          });
      }
    }
  }
};
</script>

<style scoped>
p {
  color: black;
}
</style>