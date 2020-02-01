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
    opponents: [],
    ids: [],
    model: 1
  }),

  watch: {
    opponentsData(val) {
      console.log('FISH', val);
      val.forEach(item => {
        this.opponents.push(item.name);
        this.ids.push(item.id);
      });
    },

    ids(val) {
      this.$emit("opponents", val);
    }
  },

  methods: {
    input(value) {
      if (value === "") return;

      if (this.opponents.includes(value)) {
        this.opponent = "";
        return;
      }

      let opponent = {
        name: value
      };

      axios
        .post("/opponent", opponent)
        .then(response => {
          this.ids.push(response.data.id);
          // this.$set(this.ids, this.ids.length, response.data.id);
        })
        .catch(error => {
          console.log(error);
        });

      this.opponents.push(value);
      this.opponent = "";
    },

    remove(item, i) {
      this.opponents.splice(i, 1);
      this.ids.splice(i, 1);
    }
  }
};
</script>