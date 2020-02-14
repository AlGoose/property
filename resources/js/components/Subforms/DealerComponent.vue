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

  props: ["isEdit", "dealerData"],

  data: () => ({
    search: "",
    isLoading: false,
    company: { inn: "" },
    companies: [],
    dealer: ""
  }),

  mounted() {
    if (Object.keys(this.dealerData).length > 0) {
      this.company = this.dealerData;
      this.companies.push(this.dealerData);
    }
  },

  watch: {
    company(val) {
      if (!val) {
        this.dealer = "";
        this.dealerData.dealer_id = null;
        return;
      }

      axios
        .post("/dealer/findDealer", { inn: val.inn, kpp: val.kpp })
        .then(response => {
          if (response.data === "") {
            this.dealer = {};
          } else {
            this.dealer = response.data;
          }
          if (
            this.dealerData !== undefined &&
            this.dealerData.current_staff !== undefined
          ) {
            this.$set(
              this.dealer,
              "current_staff",
              this.dealerData.current_staff
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
        .catch(function(error) {
          this.isLoading = false;
        });
    }
  },

  methods: {
    saveStaff(staff) {
      if (!staff) {
        this.dealerData.staff_id = null;
        return;
      }

      let dealer = {
        name: this.company.name,
        address: this.company.address,
        inn: this.company.inn,
        kpp: this.company.kpp
      };

      axios
        .post("/dealer", { dealer: dealer, staff_id: staff })
        .then(response => {
          this.dealerData.dealer_id = response.data.id;
          this.dealerData.staff_id = staff;
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