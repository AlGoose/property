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
          v-on:keyup.enter="addOpponent(opponent)"
          append-icon="mdi-plus"
          @click:append="addOpponent(opponent)"
        ></v-text-field>

        <v-list flat v-if="opponentsData.length">
          <v-list-item-group v-model="model" mandatory color="indigo">
            <v-list-item v-for="(item, i) in opponentsData" :key="i">
              <v-list-item-title v-text="item.name"></v-list-item-title>
              <v-icon @click="remove(item,i)">mdi-minus-circle-outline</v-icon>
            </v-list-item>
          </v-list-item-group>
        </v-list>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
export default {
  props: ["opponentsData"],
  data: () => ({
    opponent: null,
    model: 1
  }),

  methods: {
    addOpponent(value) {
      if (value == null || /^\s*$/.test(value)) return;

      for (let i = 0; i < this.opponentsData.length; i++) {
        if(this.opponentsData[i].name === value) {
          return;
        }
      }

      let opponent = {
        name: value
      };

      axios
        .post("/opponent", opponent)
        .then(response => {
          this.opponentsData.push(response.data);
          this.opponent = "";
        })
        .catch(error => {
          console.log(error);
        });
    },

    remove(i) {
      this.opponentsData.splice(i, 1);
    }
  }
};
</script>