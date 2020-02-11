<template>
  <v-container>
    <v-data-table :headers="headers" :items="managers" class="elevation-1">
      <template v-slot:top>
        <v-toolbar flat color="white">
          <v-toolbar-title>Админка</v-toolbar-title>
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-spacer></v-spacer>
          <v-dialog v-model="editDialog" max-width="500px">
            <template v-slot:activator="{ on }">
              <v-btn color="primary" outlined dark class="mb-2" v-on="on">
                <v-icon>mdi-account-plus-outline</v-icon>
              </v-btn>
            </template>
            <v-card>
              <v-card-title>
                <span class="headline">{{ formTitle }}</span>
              </v-card-title>

              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12">
                      <v-text-field v-model="editedItem.name" label="Имя" outlined></v-text-field>
                    </v-col>
                    <v-col cols="12">
                      <v-text-field v-model="editedItem.email" label="Почта" outlined></v-text-field>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>

              <v-card-actions>
                <v-btn
                  color="black"
                  @click="sendPassword"
                  outlined
                  v-if="editedIndex != -1"
                >Отправить пароль</v-btn>
                <v-spacer></v-spacer>
                <v-btn color="red" @click="close" outlined>Отмена</v-btn>
                <v-btn color="indigo" @click="save" outlined>Сохранить</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>

          <v-dialog v-model="removeDialog" max-width="250px">
            <v-card>
              <v-card-title>Удалить?</v-card-title>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="indigo" @click="removeDialog = false" outlined>Отмена</v-btn>
                <v-btn color="red" @click="remove" outlined>Удалить</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>

          <v-dialog v-model="alert" width="500">
            <v-card>
              <v-card-title class="headline red lighten-2" primary-title>Ошибка</v-card-title>
              <v-card-text>
                <ul>
                  <li v-for="(item, i) in errors" :key="i">{{item[0]}}</li>
                </ul>
              </v-card-text>
              <v-divider></v-divider>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" text @click="alert = false">I accept</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-toolbar>
      </template>
      <template v-slot:item.action="{ item }">
        <v-icon @click="editItem(item)">mdi-account-edit-outline</v-icon>
        <v-icon @click="deleteItem(item)">mdi-trash-can-outline</v-icon>
      </template>
    </v-data-table>
  </v-container>
</template>

<script>
export default {
  data: () => ({
    editDialog: false,
    removeDialog: false,
    alert: false,
    errors: [],
    headers: [
      {
        text: "Имя",
        align: "left",
        value: "name"
      },
      { text: "Почта", value: "email" },
      { text: "Действия", value: "action", sortable: false }
    ],
    managers: [],
    editedIndex: -1,
    editedItem: {}
  }),

  mounted() {
    if (window.managers == undefined) {
      axios
        .get("/managers")
        .then(response => {
          this.managers = response.data;
        })
        .catch(error => {
          console.log(error);
        });
    } else {
      this.managers = window.managers;
      window.managers = undefined;
    }
  },

  computed: {
    formTitle() {
      return this.editedIndex === -1
        ? "Добавить пользователя"
        : "Изменить пользователя";
    }
  },

  watch: {
    editDialog(val) {
      val || this.close();
    }
  },

  methods: {
    editItem(item) {
      this.editedIndex = this.managers.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.editDialog = true;
    },

    deleteItem(item) {
      const index = this.managers.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.editedItem.index = index;
      this.removeDialog = true;
    },

    close() {
      this.editDialog = false;
      setTimeout(() => {
        this.editedItem = {};
        this.editedIndex = -1;
      }, 300);
    },

    save() {
      if (this.editedIndex > -1) {
        axios
          .put("/managers/" + this.editedItem.id, this.editedItem)
          .then(response => {
            Object.assign(this.managers[this.editedIndex], response.data);
          })
          .catch(error => {
            console.log("ERROOOOOOOR", error.response);
            this.errors = error.response.data.errors;
            this.alert = true;
          })
          .then(() => {
            this.close();
          });
      } else {
        axios
          .post("/managers", this.editedItem)
          .then(response => {
            this.managers.push(response.data);
          })
          .catch(error => {
            console.log("ERROOOOOOOR", error.response);
            this.errors = error.response.data.errors;
            this.alert = true;
          })
          .then(() => {
            this.close();
          });
      }
    },

    remove() {
      axios
        .delete("/managers/" + this.editedItem.id)
        .then(response => {
          this.managers.splice(this.editedItem.index, 1);
          this.removeDialog = false;
          setTimeout(() => {
            this.editedItem = {};
          }, 300);
        })
        .catch(error => {
          console.log("ERROOOOOOOR", error.response);
          this.errors.push(["Нельзя просто так взять и удалить админа!"]);
          this.removeDialog = false;
          this.alert = true;
        });
    },

    sendPassword() {
      axios
        .post(`/managers/${this.editedItem.id}/sendPassword`)
        .then(response => {})
        .catch(error => {
          console.log(error);
        });
    }
  }
};
</script>