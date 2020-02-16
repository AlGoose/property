<template>
  <div>
    <v-container>
      <v-row justify="center" v-if="!isReady">
        <v-progress-circular :size="70" :width="7" color="indigo" indeterminate></v-progress-circular>
      </v-row>
      <v-card outlined v-else>
        <v-card-title>{{project.project.name}}</v-card-title>
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
                        <p class="body-1">{{project.project.name}}</p>
                        <p class="title">Адрес</p>
                        <p class="body-1">{{project.project.address}}</p>
                        <p class="title">Дата</p>
                        <p class="body-1">{{project.project.date}}</p>
                        <p class="title">Время</p>
                        <p class="body-1">{{project.project.time}}</p>
                      </v-card-text>
                    </v-card>
                  </v-col>

                  <v-col cols="12" md="8">
                    <v-card flat>
                      <v-card-title>Проделанная работа</v-card-title>
                      <v-card-text>
                        <v-textarea
                          :value="project.project.work"
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
                        <p class="body-1">{{project.dealer.inn}}</p>
                        <p class="title">КПП</p>
                        <p class="body-1">{{project.dealer.kpp}}</p>
                        <p class="title">Имя</p>
                        <p class="body-1">{{project.dealer.name}}</p>
                        <p class="title">Адрес</p>
                        <p class="body-1">{{project.dealer.address}}</p>
                      </v-card-text>
                    </v-card>
                  </v-col>

                  <v-col cols="12" md="6">
                    <v-card flat>
                      <v-card-text>
                        <p class="title">Представитель</p>
                        <p class="body-1">{{project.dealer.current_staff.name}}</p>
                        <p class="title">Телефон</p>
                        <p class="body-1">{{project.dealer.current_staff.phone}}</p>
                        <p class="title">Почта</p>
                        <p class="body-1">{{project.dealer.current_staff.email}}</p>
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
                        <p class="body-1">{{project.customer.inn}}</p>
                        <p class="title">КПП</p>
                        <p class="body-1">{{project.customer.kpp}}</p>
                        <p class="title">Имя</p>
                        <p class="body-1">{{project.customer.name}}</p>
                        <p class="title">Адрес</p>
                        <p class="body-1">{{project.customer.address}}</p>
                      </v-card-text>
                    </v-card>
                  </v-col>

                  <v-col cols="12" md="6">
                    <v-card flat>
                      <v-card-text>
                        <p class="title">Представитель</p>
                        <p class="body-1">{{project.customer.current_staff.name}}</p>
                        <p class="title">Телефон</p>
                        <p class="body-1">{{project.customer.current_staff.phone}}</p>
                        <p class="title">Почта</p>
                        <p class="body-1">{{project.customer.current_staff.email}}</p>
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
                  <v-list-item v-for="(item, i) in project.opponents" :key="'C'+ i">
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
                  v-if="project.products.length"
                  :headers="headers"
                  :items="project.products"
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

            <v-expansion-panel>
              <v-expansion-panel-header class="title">Документы</v-expansion-panel-header>
              <v-expansion-panel-content>
                <v-list>
                  <v-list-item v-for="(file, i) in project.documents" :key="'C'+ i">
                    <v-list-item-content>
                      <v-list-item-title>{{file.name}}</v-list-item-title>
                    </v-list-item-content>
                    <a :href="file.src" :download="file.name">
                      <v-btn color="indigo" icon>
                        <v-icon>mdi-download-outline</v-icon>
                      </v-btn>
                    </a>
                  </v-list-item>
                </v-list>
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
            v-if="project.project.user_id === auth_id"
            @click="edit"
          >Редактировать</v-btn>
          <v-btn
            class="ma-2"
            outlined
            color="error"
            v-if="project.project.user_id === auth_id"
            @click="dialog = true"
          >Удалить</v-btn>
        </v-card-actions>
      </v-card>
    </v-container>

    <v-row justify="center">
      <v-dialog v-model="dialog" width="250px">
        <v-card>
          <v-card-title>Точно удалить?</v-card-title>
          <v-card-actions class="justify-center">
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
  data() {
    return {
      isReady: false,
      dialog: false,
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
      auth_id: "",
      project: {
        project: {},
        dealer: {},
        customer: {},
        opponents: [],
        products: [],
        documents: []
      }
    };
  },

  mounted: function() {
    this.auth_id = window.current_user.id;

    if (window.project == undefined) {
      axios
        .get("/project/" + this.$route.params.id)
        .then(response => {
          this.project = response.data;
          this.project.products.forEach(item => {
            item.pivot.total = item.pivot.count * item.pivot.price;
          });
          this.isReady = true;
        })
        .catch(error => {
          console.log(error);
        });
    } else {
      this.project = window.project;
      this.project.products.forEach(item => {
        item.pivot.total = item.pivot.count * item.pivot.price;
      });
      window.project = undefined;
      this.isReady = true;
    }
  },

  methods: {
    remove() {
      axios
        .delete("/project/" + this.$route.params.id)
        .then(response => {
          // this.$router.push("/project");
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
