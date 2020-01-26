<template>
  <div>
    <v-card class="mx-auto card" height="100%">
      <v-card-title>Конкуренты</v-card-title>
      <v-card-text>
        <v-text-field
          v-model="opponent"
          label="Конкуренты"
          outlined
          clearable
          v-on:keyup.enter="input(opponent)"
          append-icon="mdi-plus"
          @click:append="input(opponent)"
        ></v-text-field>

          <v-list flat v-if="opponents.length">
            <v-list-item-group v-model="model" mandatory color="indigo">
              <v-list-item v-for="(item, i) in opponents" :key="i">
                  <v-list-item-title v-text="item"></v-list-item-title>
                <v-icon @click="remove(item)">mdi-minus-circle-outline</v-icon>
              </v-list-item>
            </v-list-item-group>
          </v-list>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
export default {
  data: () => ({
    opponent: null,
    opponents: []
  }),

  methods: {
    input(value) {
      if (value === "") return;

      if (this.opponents.includes(value)) {
        this.opponent = "";
        return;
      }

      let test = {
        name: value
      };

      //   axios
      //     .post("http://property.test/addOpponent", test)
      //     .then(function(response) {
      //       console.log(response);
      //     })
      //     .catch(function(error) {
      //       console.log(error);
      //     });

      this.opponents.push(value);
      this.opponent = "";
    },

    remove(item) {
      this.opponents.splice(this.opponents.indexOf(item), 1);
    }
  }
};
</script>

<style scoped>
/* .card {
    height: 100px;
} */
</style>