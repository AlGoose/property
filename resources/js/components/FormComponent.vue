<template>
  <v-container fluid>
    <v-row>
      <v-col cols="12" sm="12">
        <v-form ref="form" v-model="valid" lazy-validation>
          <div v-for="item in form" :key="item.id">
            <v-text-field
              v-model="item.data"
              :label="item.label"
              outlined
              clearable
              required
              :rules="item.rules"
            ></v-text-field>
          </div>

          <v-text-field v-model="opponent" label="Конкуренты" outlined clearable v-on:keyup.enter="test"></v-text-field>
          <v-card v-if="opponents.length" outlined>
            <v-list flat>
              <v-list-item-group v-model="model" mandatory color="indigo">
                <v-list-item v-for="(item, i) in opponents" :key="i">
                  <v-list-item-content>
                    <v-list-item-title v-text="item"></v-list-item-title>
                  </v-list-item-content>
                </v-list-item>
              </v-list-item-group>
            </v-list>
          </v-card>

          <v-btn block color="indigo" outlined @click="validate">Добавить форму</v-btn>
        </v-form>
      </v-col>
    </v-row>

    <v-row justify="center">
      <v-dialog v-model="dialog" persistent scrollable width="600px">
        <v-card>
          <v-card-title>Все верно?</v-card-title>
          <v-divider></v-divider>

          <v-card-text style="height: 300px;">
            <div v-for="item in form" :key="item.id">
              <p>{{item.label}}: {{item.data}}</p>
            </div>
          </v-card-text>

          <v-divider></v-divider>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn depressed color="error" @click="dialog = false">Отмена</v-btn>
            <v-btn depressed color="success" @click="addForm">Добавить</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>
  </v-container>
</template>

<script>
export default {
  data: () => ({
    dialog: false,
    model: 1,
    valid: true,
    form: {
      name: {
        data: "",
        label: "Название объекта",
        rules: [v => !!v || "Name is required"]
      },
      address: {
        data: "",
        label: "Адрес объекта",
        rules: [v => !!v || "address is required"]
      },
      customer: {
        data: "",
        label: "Заказчик",
        rules: [v => !!v || "customer is required"]
      },
      contacts: {
        data: "",
        label: "Контактные данные",
        rules: [v => !!v || "contacts is required"]
      },
      date: {
        data: "",
        label: "Срок реализации проекта",
        rules: [v => !!v || "date is required"]
      },
      dealer_name: {
        data: "",
        label: "ФИО Дилера",
        rules: [v => !!v || "Name is required"]
      },
      dealer_phone: {
        data: "",
        label: "Мобильный телефон",
        rules: [v => !!v || "Phone is required"]
      }
    },
    opponent: "",
    opponents: []
  }),

  methods: {
    addForm() {
      let newThis = this;

      let res = {};
      for (let prop in this.form) {
        res[prop] = this.form[prop].data;
      }
      res['opponents'] = this.opponents;

      axios
        .post("http://property.test/project", res)
        .then(function(response) {
          newThis.$refs.form.reset();
          newThis.opponent = "";
          newThis.opponents = [];
          newThis.dialog = false;
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    validate() {
      if (this.$refs.form.validate()) {
        this.dialog = true;
      }
    },

    test() {
      if (this.opponent === "") return;

      if (this.opponents.includes(this.opponent)) {
        this.opponent = "";
        return;
      }

      this.opponents.push(this.opponent);
      this.opponent = "";
    }
  }
};
</script>