<template>
  <v-card class="mx-auto">
    <v-card-title>Заказчик</v-card-title>
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
      <StaffComponent v-if="customer" :entity="customer" mode="customer" @staff="saveStaff"></StaffComponent>
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
    search: "",
    company: { inn: "" },
    isLoading: false,
    companies: [],
    customer: ""
  }),

  watch: {
    company(val) {
      //console.log(val)
      axios //TODO:
        .post("/customer/findCustomer", { inn: val.inn, kpp: val.kpp })
        .then(response => {
          // console.log(response);
          if (response.data === "") {
            this.customer = {};
          } else {
            this.customer = response.data;
          }
        })
        .catch(e => {
          console.log(e);
        });
    },

    search(val) {
      if (this.isLoading) return;
      if (val === null || val.length != 10) return;

      let newThis = this;
      this.isLoading = true;
      //   this.companies = [];

      axios
        // .get("/customer/findByInn/" + val)
        .get("/data/findByInn/" + val)
        .then(response => {
          this.companies = response.data;
          newThis.isLoading = false;
        })
        .catch(function(error) {
          //   console.log(error);
          newThis.isLoading = false;
        });
    }
  },

  methods: {
    saveStaff(staff) {
      // console.log(staff);
      if (!this.customer.id) {
        // console.log("NEW CUSTOMER");
        axios
          .post("/customer", { customer: this.company, staff_id: staff })
          .then(response => {
            // console.log(response.data);
            this.$emit("customer", {
              customer_id: response.data.id,
              customer_staff_id: staff
            });
          });
      } else {
        // console.log("OLD CUSTOMER"); //МБ костыль на добавление нового стафа к имеющемуся в БД диллеру.
        axios
          .post("/customer", {
            customer: {
              inn: this.company.inn,
              kpp: this.company.kpp,
              address: this.company.address,
              name: this.company.name
            },
            staff_id: staff
          })
          .then(response => {
            // console.log(response.data);
            this.$emit("customer", {
              customer_id: response.data.id,
              customer_staff_id: staff
            });
          });
      }

      // console.log("CustomerComponent", staff);
    }
  }
};
</script>

<style scoped>
p {
  color: black;
}
</style>