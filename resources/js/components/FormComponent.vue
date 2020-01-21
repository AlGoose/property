<template>
  <v-container fluid>
    <v-form ref="form" v-model="valid" lazy-validation>
      <h3>Дилер</h3>

      <v-row>
        <v-col cols="12" md="6">
          <v-text-field v-model="dealer.inn.data" v-mask="dealer.inn.mask" label="ИНН" outlined></v-text-field>
        </v-col>

        <v-col cols="12" md="6">
          <v-text-field v-model="dealer.kpp.data" v-mask="dealer.kpp.mask" label="КПП" outlined></v-text-field>
        </v-col>

        <v-col cols="12" md="12">
          <v-text-field v-model="dealer.name.data" label="Название" outlined></v-text-field>
        </v-col>

        <v-col cols="12" md="7">
          <v-text-field
            v-model="dealer.agent.data"
            :rules="dealer.agent.rules"
            label="Имя представителя"
            outlined
          ></v-text-field>
        </v-col>

        <v-col cols="12" md="5">
          <v-text-field
            v-model="dealer.phone.data"
            :rules="dealer.phone.rules"
            v-mask="dealer.phone.mask"
            label="Телефон"
            outlined
          ></v-text-field>
        </v-col>
      </v-row>

      <h3>Проект</h3>

      <v-row>
        <v-col cols="12" sm="12">
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

            <template v-else-if="item.type === 'work'">
              <v-textarea
                v-model="item.data"
                height="200"
                no-resize
                outlined
                name="input-7-4"
                label="Проделанная работа"
              ></v-textarea>
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
        </v-col>
      </v-row>
    </v-form>

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
    dealer: {
      inn: {
        type: "withMask",
        data: "",
        label: "ИНН",
        rules: [],
        mask: "##########"
      },
      kpp: {
        type: "withMask",
        data: "",
        label: "КПП",
        rules: [],
        mask: "#########"
      },
      name: {
        type: "v-text-field",
        data: "",
        label: "Название",
        rules: []
      },
      agent: {
        type: "v-text-field",
        data: "",
        label: "Имя представителя",
        rules: [v => !!v || "Name is required"]
      },
      phone: {
        type: "withMask",
        data: "",
        label: "Мобильный телефон",
        rules: [v => !!v || "Phone is required"],
        mask: "(###) ### ## ##"
      }
    },
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
      inn: {
        type: "v-text-field",
        data: "",
        label: "ИНН",
        rules: [v => !!v || "ИНН is required"]
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
      },
      work: {
        type: "work",
        data: [],
        label: "Конкуренты"
      }
      // time: {
      //   type: "datePicker",
      //   data: new Date().toISOString().substr(0, 10),
      //   label: "Дата"
      // }
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