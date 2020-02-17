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
        :rules="[v => v && v.kpp !== undefined || 'Введите номер ИНН']"
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

  mounted() {
    if (Object.keys(this.customerData).length > 0) {
      this.company = this.customerData;
      this.companies.push(this.customerData);
    }
  },

  watch: {
    company(val) {
      if (!val) {
        this.customer = "";
        this.customerData.customer_id = null;
        return;
      }

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
        .catch(error => {
          this.isLoading = false;
        });
    }
  },

  methods: {
    saveStaff(staff) {
      if (!staff) {
        this.customerData.staff_id = null;
        return;
      }

      let customer = {
        name: this.company.name,
        address: this.company.address,
        inn: this.company.inn,
        kpp: this.company.kpp
      };

      axios
        .post("/customer", { customer: customer, staff_id: staff })
        .then(response => {
          this.customerData.customer_id = response.data.id;
          this.customerData.staff_id = staff;
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