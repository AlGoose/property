<template>
  <div>
    <v-container>
      <v-card outlined>
        <v-card-title>{{project.name.data}}</v-card-title>
        <v-card-text>
          <v-expansion-panels multiple focusable>
            <v-expansion-panel>
              <v-expansion-panel-header class="title">Проект</v-expansion-panel-header>
              <v-expansion-panel-content>
                <v-row>
                  <v-col cols="12" md="4">
                    <v-card flat>
                      <v-card-text>
                        <p class="title">Название</p>
                        <p class="body-1">{{project.name.data}}</p>
                        <p class="title">Адрес</p>
                        <p class="body-1">{{project.address.data}}</p>
                        <p class="title">Дата</p>
                        <p class="body-1">{{project.date.data}}</p>
                        <p class="title">Время</p>
                        <p class="body-1">{{project.time.data}}</p>
                      </v-card-text>
                    </v-card>
                  </v-col>

                  <v-col cols="12" md="8">
                    <v-card flat>
                      <v-card-title>Проделанная работа</v-card-title>
                      <v-card-text>
                        <v-textarea
                          :value="project.work.data"
                          no-resize
                          height="200px"
                          solo
                          flat
                          readonly
                        ></v-textarea>
                      </v-card-text>
                    </v-card>
                  </v-col>
                </v-row>
              </v-expansion-panel-content>
            </v-expansion-panel>

            <v-expansion-panel>
              <v-expansion-panel-header class="title">Дилер</v-expansion-panel-header>
              <v-expansion-panel-content>
                <v-row>
                  <v-col cols="12" md="6">
                    <v-card flat>
                      <v-card-text>
                        <p class="title">ИНН</p>
                        <p class="body-1">{{dealer.inn.data}}</p>
                        <p class="title">КПП</p>
                        <p class="body-1">{{dealer.kpp.data}}</p>
                        <p class="title">Имя</p>
                        <p class="body-1">{{dealer.name.data}}</p>
                        <p class="title">Адрес</p>
                        <p class="body-1">{{dealer.address.data}}</p>
                      </v-card-text>
                    </v-card>
                  </v-col>

                  <v-col cols="12" md="6">
                    <v-card flat>
                      <v-card-text>
                        <p class="title">Представитель</p>
                        <p class="body-1">{{dealer_staff.name.data}}</p>
                        <p class="title">Телефон</p>
                        <p class="body-1">{{dealer_staff.phone.data}}</p>
                        <p class="title">Почта</p>
                        <p class="body-1">{{dealer_staff.email.data}}</p>
                      </v-card-text>
                    </v-card>
                  </v-col>
                </v-row>
              </v-expansion-panel-content>
            </v-expansion-panel>

            <v-expansion-panel>
              <v-expansion-panel-header class="title">Заказчик</v-expansion-panel-header>
              <v-expansion-panel-content>
                <v-row>
                  <v-col cols="12" md="6">
                    <v-card flat>
                      <v-card-text>
                        <p class="title">ИНН</p>
                        <p class="body-1">{{customer.inn.data}}</p>
                        <p class="title">КПП</p>
                        <p class="body-1">{{customer.kpp.data}}</p>
                        <p class="title">Имя</p>
                        <p class="body-1">{{customer.name.data}}</p>
                        <p class="title">Адрес</p>
                        <p class="body-1">{{customer.address.data}}</p>
                      </v-card-text>
                    </v-card>
                  </v-col>

                  <v-col cols="12" md="6">
                    <v-card flat>
                      <v-card-text>
                        <p class="title">Представитель</p>
                        <p class="body-1">{{customer_staff.name.data}}</p>
                        <p class="title">Телефон</p>
                        <p class="body-1">{{customer_staff.phone.data}}</p>
                        <p class="title">Почта</p>
                        <p class="body-1">{{customer_staff.email.data}}</p>
                      </v-card-text>
                    </v-card>
                  </v-col>
                </v-row>
              </v-expansion-panel-content>
            </v-expansion-panel>

            <v-expansion-panel>
              <v-expansion-panel-header class="title">Конкуренты</v-expansion-panel-header>
              <v-expansion-panel-content>
                <v-list>
                  <v-list-item v-for="(item, i) in opponents" :key="'C'+ i">
                    <v-list-item-content>
                      <v-list-item-title>{{item.name}}</v-list-item-title>
                    </v-list-item-content>
                  </v-list-item>
                </v-list>
              </v-expansion-panel-content>
            </v-expansion-panel>

            <v-expansion-panel>
              <v-expansion-panel-header class="title">Детали</v-expansion-panel-header>
              <v-expansion-panel-content>
                <v-data-table
                  v-if="products.length"
                  :headers="headers"
                  :items="products"
                  item-key="name"
                  class="elevation-1"
                >
                  <template v-slot:item="{ item }">
                    <tr>
                      <td>{{ item.name }}</td>
                      <td>{{ item.pivot.count }}</td>
                      <td>{{ item.pivot.price }}</td>
                      <td>{{ item.pivot.total }}</td>
                    </tr>
                  </template>
                </v-data-table>
              </v-expansion-panel-content>
            </v-expansion-panel>
          </v-expansion-panels>
        </v-card-text>

        <v-card-actions class="row justify-content-center">
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
    </v-container>

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
    this.project_id = this.$route.params.id;
    this.auth_id = window.current_user.id;

    let form;

    if (window.project == undefined) {
      axios
        .get("/project/" + this.project_id)
        .then(response => {
          this.initial(response.data);
        })
        .catch(error => {
          console.log(error);
        });
    } else {
      this.initial(window.project);
    }
  },

  data() {
    return {
      headers: [
        {
          text: "Название продукта",
          align: "left",
          value: "name"
        },
        { text: "Количество", value: "pivot.count" },
        { text: "Цена за единицу (₽)", value: "pivot.price" },
        { text: "Общая стоимость (₽)", value: "pivot.total" }
      ],
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
        address: {
          label: "Адрес",
          data: ""
        }
      },
      dealer_staff: {
        name: {
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
      customer: {
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
        address: {
          label: "Адрес",
          data: ""
        }
      },
      customer_staff: {
        name: {
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
      project: {
        name: {
          label: "Название",
          data: ""
        },
        address: {
          label: "Адрес",
          data: ""
        },
        work: {
          label: "Работа",
          data: ""
        },
        date: {
          label: "Дата",
          data: ""
        },
        time: {
          label: "Время",
          data: ""
        }
      },
      opponents: [],
      products: []
    };
  },

  methods: {
    initial(form) {
      for (let prop in this.dealer) {
        this.dealer[prop].data = form.dealer[prop];
      }
      for (let prop in this.dealer_staff) {
        this.dealer_staff[prop].data = form.dealer_staff[prop];
      }

      for (let prop in this.customer) {
        this.customer[prop].data = form.customer[prop];
      }
      for (let prop in this.customer_staff) {
        this.customer_staff[prop].data = form.customer_staff[prop];
      }

      for (let prop in this.project) {
        this.project[prop].data = form[prop];
      }

      this.opponents = form.opponents;
      this.products = form.products;
      this.products.forEach(item => {
        item.pivot.total = item.pivot.count * item.pivot.price;
      });

      this.user_id = form.user_id;
      window.project = undefined;
    },

    remove() {
      axios
        .delete("/project/" + this.project_id)
        .then(response => {
          this.$router.push("/project");
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    edit() {
      this.$router.push({
        name: "edit"
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
p {
  color: black;
  margin-bottom: 0;
}
</style>
