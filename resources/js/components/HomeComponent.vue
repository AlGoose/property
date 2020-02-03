<template>
  <v-container fluid>
    <v-card class="mx-auto">
      <v-card-title>Поиск объекта</v-card-title>
      <v-card-text>
        <v-text-field v-model="address" label="Адрес" outlined></v-text-field>

        <v-expansion-panels multiple focusable>
          <v-expansion-panel v-for="(item, i) in entries" :key="i">
            <v-expansion-panel-header class="title">{{item.address}}</v-expansion-panel-header>
            <v-expansion-panel-content>
              <p class="title">Название</p>
              <p class="body-1">{{item.name}}</p>
              <p class="title">Срок</p>
              <p class="body-1">{{item.date}}</p>
              <p class="title">Дата создания</p>
              <p class="body-1">{{item.time}}</p>
              <v-checkbox v-model="selected" label="Проверено?" :value="i"></v-checkbox>
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-expansion-panels>

        <v-btn
          class="button"
          :disabled="addressLength"
          block
          color="indigo"
          outlined
          @click="createForm"
        >Добавить форму</v-btn>
      </v-card-text>
    </v-card>
    <!-- <v-row>
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
        
      </v-col>
    </v-row>-->
  </v-container>
</template>

<script>
export default {
  data: () => ({
    selected: [],
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
    },
    addressLength() {
      // return this.address.length == 0;
      return !(this.entries.length === this.selected.length && this.entries.length != 0);
    }
  },

  created() {
    this.inputAddress = window.debounce(this.searchAddress, 500);
  },

  watch: {
    address(value) {
      this.inputAddress();
    }
  },

  methods: {
    createForm() {
      this.$router.push({
        name: "form",
        params: { address: this.model, mode: "create" }
      });
    },

    searchAddress() {
      console.log("search:", this.address);
      axios
        .post("/addresses", { address: this.address })
        .then(response => {
          console.log(response);
          this.entries = response.data;
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
};
</script>

<style scoped>
.button {
  margin-top: 30px;
}
</style>