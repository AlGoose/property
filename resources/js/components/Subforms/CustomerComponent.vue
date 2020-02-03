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
        :disabled="isEdit"
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
  props: ["isEdit", "customerData"],

  data: () => ({
    search: "",
    company: { inn: "" },
    isLoading: false,
    companies: [],
    customer: ""
  }),

  watch: {
    customerData(val) {
      if (this.isEdit) {
        this.company = val;
        this.companies.push(val);
      }
    },

    company(val) {
      axios
        .post("/customer/findCustomer", { inn: val.inn, kpp: val.kpp })
        .then(response => {
          if (response.data === "") {
            this.customer = {};
          } else {
            this.customer = response.data;
          }
          if (
            this.customerData !== undefined &&
            this.customerData.current_staff !== undefined
          ) {
            this.$set(
              this.customer,
              "current_staff",
              this.customerData.current_staff
            );
          }
        })
        .catch(error => {
          console.log(error);
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
        .catch((error) => {
          console.log(error);
          this.isLoading = false;
        });
    }
  },

  methods: {
    saveStaff(staff) {
      if (!this.customer.id) {
        axios
          .post("/customer", { customer: this.company, staff_id: staff })
          .then(response => {
            this.$emit("customer", {
              customer_id: response.data.id,
              customer_staff_id: staff
            });
          });
      } else {
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
            this.$emit("customer", {
              customer_id: response.data.id,
              customer_staff_id: staff
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