<template>
  <v-container fluid>
    <v-row>
      <v-col cols="12" sm="12">
        <v-autocomplete
          v-model="model"
          :items="items"
          :loading="isLoading"
          :search-input.sync="search"
          color="grey"
          hide-no-data
          hide-selected
          outlined
          item-text="Address"
          item-value="API"
          label="Адрес объекта"
          placeholder="Вводите адрес для поиска"
          return-object
        ></v-autocomplete>
        <v-btn block color="indigo" outlined @click="createForm">Добавить форму</v-btn>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  data: () => ({
    address: "",
    textLimit: 60,
    entries: [],
    isLoading: false,
    model: null,
    search: null
  }),

  computed: {
    items() {
      return ["1", "123", "111", "121"];
      // return this.entries.map(entry => {
      //   const Address =
      //     entry.address.length > this.textLimit
      //       ? entry.address.slice(0, this.textLimit) + "..."
      //       : entry.address;

      //   return Object.assign({}, entry, { Address });
      // });
    }
  },

  watch: {
    search(val) {
      if (this.isLoading) return;

      this.isLoading = true;

      fetch("/addresses?address=" + val)
        .then(res => res.json())
        .then(res => {
          console.log(res);
          this.entries = res;
        })
        .catch(err => {
          console.log(err);
        })
        .finally(() => (this.isLoading = false));
    }
  },

  methods: {
    createForm() {
      this.$router.push({
        name: "form",
        params: { address: this.model }
      });
    }
  }
};
</script>