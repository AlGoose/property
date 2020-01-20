<template>
  <v-container fluid>
    <v-row>
      <v-col cols="12" sm="12">
        <v-form ref="form" v-model="valid" lazy-validation>
          <div v-for="item in form" :key="item.id">
            <v-text-field
              v-if="item.type === 'v-text-field'"
              v-model="item.data"
              :label="item.label"
              outlined
              clearable
              required
              :rules="item.rules"
            ></v-text-field>

            <v-text-field
              v-else-if="item.type === 'phoneNumber'"
              v-model="item.data"
              :label="item.label"
              outlined
              clearable
              required
              :rules="item.rules"
              v-mask="item.mask"
            ></v-text-field>

            <template v-else-if="item.type === 'opponentsField'">
              <v-text-field
                v-model="item.opponent"
                :label="item.label"
                outlined
                clearable
                v-on:keyup.enter="input"
                append-icon="mdi-plus"
                @click:append="input"
              ></v-text-field>
              <v-card v-if="item.data.length" outlined class="card">
                <v-list flat>
                  <v-list-item-group v-model="model" mandatory color="indigo">
                    <v-list-item v-for="(item, i) in item.data" :key="i">
                      <v-list-item-content>
                        <v-list-item-title v-text="item"></v-list-item-title>
                      </v-list-item-content>
                      <v-icon @click="remove(item)">mdi-minus-circle-outline</v-icon>
                    </v-list-item>
                  </v-list-item-group>
                </v-list>
              </v-card>
            </template>

            <template v-else>
              <v-menu
                ref="menu"
                v-model="menu"
                :close-on-content-click="false"
                :return-value.sync="item.data"
                transition="scale-transition"
                offset-y
                min-width="290px"
              >
                <template v-slot:activator="{ on }">
                  <v-text-field v-model="item.data" :label="item.label" readonly outlined v-on="on"></v-text-field>
                </template>
                <v-date-picker v-model="item.data" no-title scrollable locale="Rus">
                  <v-spacer></v-spacer>
                  <v-btn text color="primary" @click="menu = false">Отмена</v-btn>
                  <v-btn text color="primary" @click="$refs.menu[0].save(item.data)">Выбрать</v-btn>
                </v-date-picker>
              </v-menu>
            </template>
          </div>

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
import { mask } from "vue-the-mask";

export default {
  directives: {
    mask
  },
  mounted() {
    if (this.$route.params.address) {
      this.form.address.data = this.$route.params.address;
    }
  },
  data: () => ({
    dialog: false,
    menu: false,
    model: 1,
    valid: true,
    form: {
      name: {
        type: "v-text-field",
        data: "",
        label: "Название объекта",
        rules: [v => !!v || "Name is required"]
      },
      address: {
        type: "v-text-field",
        data: "",
        label: "Адрес объекта",
        rules: [v => !!v || "address is required"]
      },
      customer: {
        type: "v-text-field",
        data: "",
        label: "Заказчик",
        rules: [v => !!v || "customer is required"]
      },
      contacts: {
        type: "v-text-field",
        data: "",
        label: "Контактные данные",
        rules: [v => !!v || "contacts is required"]
      },
      dealer_name: {
        type: "v-text-field",
        data: "",
        label: "ФИО Дилера",
        rules: [v => !!v || "Name is required"]
      },
      dealer_phone: {
        type: "phoneNumber",
        data: "",
        label: "Мобильный телефон",
        rules: [v => !!v || "Phone is required"],
        mask: "# (###) ### ####"
      },
      opponents: {
        type: "opponentsField",
        opponent: "",
        data: [],
        label: "Конкуренты"
      },
      date: {
        type: "datePicker",
        data: new Date().toISOString().substr(0, 10),
        label: "Срок реализации проекта"
      }
    }
  }),

  methods: {
    addForm() {
      let newThis = this;

      let res = {};
      for (let prop in this.form) {
        res[prop] = this.form[prop].data;
      }

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

    input() {
      if (this.form.opponents.opponent === "") return;

      if (this.form.opponents.data.includes(this.form.opponents.opponent)) {
        this.form.opponents.opponent = "";
        return;
      }

      this.form.opponents.data.push(this.form.opponents.opponent);
      this.form.opponents.opponent = "";
    },

    remove(item) {
      this.form.opponents.data.splice(
        this.form.opponents.data.indexOf(item),
        1
      );
      this.form.opponents.data = [...this.form.opponents.data];
    }
  }
};
</script>

<style scoped>
.card {
  margin-bottom: 30px;
}
</style>