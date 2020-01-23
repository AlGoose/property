<template>
  <div>
    <div class="row justify-content-center">
      <v-card max-width="800" outlined>
        <v-list-item three-line>
          <v-list-item-content>
            <h4>Дилер</h4>
            <div class="list" v-for="(item, i) in dealer" :key="'A'+ i">
              <p>{{item.label}} : {{item.data}}</p>
            </div>

            <h4>Проект</h4>
            <div class="list" v-for="(item, i) in form" :key="'B'+ i">
              <p>{{item.label}} : {{item.data}}</p>
            </div>
          </v-list-item-content>
        </v-list-item>

        <v-card-actions>
          <v-btn class="ma-2" outlined color="indigo" @click="back">Назад</v-btn>
          <v-btn
            class="ma-2"
            outlined
            color="teal"
            v-if="user_id === auth_id"
            @click="edit"
          >Редактировать</v-btn>
          <v-btn
            class="ma-2"
            outlined
            color="error"
            v-if="user_id === auth_id"
            @click="validate"
          >Удалить</v-btn>
        </v-card-actions>
      </v-card>
    </div>

    <v-row justify="center">
      <v-dialog v-model="dialog" width="200px">
        <v-card>
          <v-card-title>Точно удалить?</v-card-title>
          <v-card-actions>
            <v-btn depressed color="success" @click="dialog = false">Отмена</v-btn>
            <v-btn depressed color="error" @click="remove">Удалить</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>
    
  </div>
</template>


<script>
export default {
  mounted: function() {
    let newThis = this;
    this.project_id = this.$route.params.id;
    this.auth_id = window.current_user.id;

    if (window.project == undefined) {
      axios
        .get("/project/" + this.project_id)
        .then(function(response) {
          console.log(response);
          for (let prop in newThis.dealer) {
            newThis.dealer[prop].data = response.data.dealer[prop];
          }
          for (let prop in newThis.form) {
            if (prop === "opponents") {
              response.data.opponents.forEach(item => {
                console.log(item.name);
                newThis.form.opponents.data.push(item.name);
              });
            } else {
              newThis.form[prop].data = response.data[prop];
            }
          }
          newThis.user_id = response.data.user_id;
        })
        .catch(function(error) {
          console.log(error);
        });
    } else {
      console.log(window.project);
      for (let prop in newThis.dealer) {
        newThis.dealer[prop].data = window.project.dealer[prop];
      }
      for (let prop in newThis.form) {
        if (prop === "opponents") {
          window.project.opponents.forEach(item => {
            console.log(item.name);
            newThis.form.opponents.data.push(item.name);
          });
        } else {
          newThis.form[prop].data = window.project[prop];
        }
      }
      this.user_id = window.project.user_id;
      window.project = undefined;
    }
  },

  data() {
    return {
      dialog: false,
      auth_id: "",
      user_id: "",
      project_id: "",
      dealer: {
        inn: {
          label: "ИНН",
          data: ""
        },
        kpp: {
          label: "КПП",
          data: ""
        },
        name: {
          label: "Имя",
          data: ""
        },
        agent: {
          label: "Представитель",
          data: ""
        },
        phone: {
          label: "Телефон",
          data: ""
        },
        email: {
          label: "Почта",
          data: ""
        }
      },
      form: {
        name: {
          label: "Название",
          data: ""
        },
        address: {
          label: "Адрес",
          data: ""
        },
        inn: {
          label: "ИНН",
          data: ""
        },
        customer: {
          label: "Заказчик",
          data: ""
        },
        contacts: {
          label: "Контакты",
          data: ""
        },
        opponents: {
          label: "Конкуренты",
          data: []
        },
        date: {
          label: "Дата",
          data: ""
        },
        work: {
          label: "Работа",
          data: ""
        }
      }
    };
  },

  methods: {
    remove() {
      let newThis = this;
      axios
        .delete("/project/" + this.project_id)
        .then(function(response) {
          newThis.$router.push("/project");
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    edit() {
      console.log(window.project);
      this.$router.push({
        name: "edit",
        params: { mode: "edit" }
      });
    },

    back() {
      this.$router.push("/project");
    },

    validate() {
      this.dialog = true;
    }
  }
};
</script>

<style scoped>
* {
  padding-bottom: 14px;
}

.list {
  height: 30px;
}
</style>
