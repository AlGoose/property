<template>
  <v-card class="mx-auto">
    <v-card-title>Дилер</v-card-title>
    <v-card-text>
      <v-autocomplete
        v-model="company"
        :items="entries"
        :search-input.sync="inn"
        color="grey"
        label="ИНН"
        outlined
        item-text="inn"
        item-value="name"
        return-object
      ></v-autocomplete>

      <p class="title font-weight-bold">ИНН: {{company ? company.inn : ''}}</p>
      <p class="title font-weight-bold">КПП: {{company ? company.kpp : ''}}</p>
      <p class="title font-weight-bold">Название: {{company ? company.name : ''}}</p>
      <p class="title font-weight-bold">Адрес: {{company ? company.name : ''}}</p>
      <StaffComponent></StaffComponent>
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
    kpp: "",
    name: "",
    address: "",
    company: null,
    isLoading: false,
    entries: [
      { inn: "5", kpp: "5", name: "5" },
      { inn: "1", kpp: "1", name: "1" }
    ]
  }),

  computed: {
    companies() {}
  },

  watch: {
    inn(val) {
      if (this.isLoading) return;
      //   this.isLoading = true;

      if (val === null || val.length < 8) return;

      console.log(val);
    }
  },

  methods: {
    someHandler() {}
  }
};
</script>