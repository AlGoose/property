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
                <v-btn color="red" @click="removeManager" outlined>Удалить</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>

          <v-dialog v-model="alert" width="500">
            <v-card>
              <v-card-title class="headline red lighten-2" primary-title>Ошибка</v-card-title>
              <v-card-text>
                <ul>
                  <li v-for="(item, i) in errors" :key="i">{{item}}</li>
                </ul>
              </v-card-text>
              <v-divider></v-divider>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" text @click="alert = false; errors = []">I accept</v-btn>
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
    console.log("MANAGERS");

    if (window.managers == undefined) {
      console.log("AXIOS");

      axios
        .get("/managers")
        .then(response => {
          this.managers = response.data;
        })
        .catch(error => {
          console.log(error);
        });
    } else {
      console.log("BLADE");

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
      this.removeDialog = false;
      this.editDialog = false;
      setTimeout(() => {
        this.editedItem = {};
        this.editedIndex = -1;
      }, 300);
    },

    save() {
      if (this.editedIndex > -1) {
        this.editManager();
      } else {
        this.addManager();
      }
    },

    addManager() {
      this.sendRequest("post", "/managers", this.editedItem, response => {
        this.managers.push(response.data);
      });
    },

    editManager() {
      this.sendRequest(
        "put",
        `/managers/${this.editedItem.id}`,
        this.editedItem,
        response => {
          Object.assign(this.managers[this.editedIndex], response.data);
        }
      );
    },

    removeManager() {
      this.sendRequest(
        "delete",
        `/managers/${this.editedItem.id}`,
        null,
        response => {
          this.managers.splice(this.editedItem.index, 1);
        }
      );
    },

    sendPassword() {
      this.sendRequest(
        "post",
        `/managers/${this.editedItem.id}/sendPassword`,
        null,
        response => {}
      );
    },

    sendRequest(type, path, request, callback) {
      axios[type](path, request)
        .then(response => {
          callback(response);
        })
        .catch(error => {
          this.errors.push(error.response.data.message);
          this.alert = true;
        })
        .then(() => {
          this.close();
        });
    }
  }
};
</script>