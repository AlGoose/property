<template>
  <v-card class="mx-auto">
    <v-card-title>Дилер</v-card-title>
    <v-card-text>
      <v-text-field v-model="inn" label="ИНН" outlined clearable></v-text-field>
      <p class="title font-weight-bold">КПП:</p>
      <p class="title font-weight-bold">Название:</p>
      <p class="title font-weight-bold">Адрес:</p>
      <v-autocomplete v-model="model" :items="items" color="grey" label="Name" outlined></v-autocomplete>
      <p class="title font-weight-bold">Представитель: {{model}}</p>
    </v-card-text>
    <v-card-actions>
      <v-btn text color="deep-purple accent-4">Learn More</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
export default {
  data: {
    inn: "",
    isLoading: false,
    model: null,
    search: null,
    names: [
      '1,2,3', '2'
    ]
  },

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
      console.log(val);
      //   if (this.isLoading) return;
      //   this.isLoading = true;
      //   fetch("/addresses?address=" + val)
      //     .then(res => res.json())
      //     .then(res => {
      //       console.log(res);
      //       this.entries = res;
      //     })
      //     .catch(err => {
      //       console.log(err);
      //     })
      //     .finally(() => (this.isLoading = false));
    },

    model(val) {
      console.log(val);
    }
  }
};
</script>