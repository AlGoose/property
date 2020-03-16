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
                        <p class="title">Срок реализации</p>
                        <p class="body-1">{{project.project.date}}</p>
                        <p class="title">Дата заключения</p>
                        <p class="body-1">{{project.project.time}}</p>
                        <p class="title">Дата тендера</p>
                        <p class="body-1">{{project.project.tender_date}}</p>
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
                <v-btn
                  class="importButton"
                  color="indigo"
                  dark
                  outlined
                  @click="$refs.inputUpload.click()"
                >Импортировать</v-btn>
                <input
                  v-show="false"
                  ref="inputUpload"
                  type="file"
                  accept=".xlsx, .xls"
                  @change="test"
                />
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

    <v-overlay :value="overlay">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>

    <div class="text-center">
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
            <v-btn color="primary" text @click="$router.go()">I accept</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>
  </div>
</template>


<script>
export default {
  data() {
    return {
      overlay: false,
      alert: false,
      isReady: false,
      dialog: false,
      errors: [],
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

    test(event) {
      this.overlay = true;
      let formData = new FormData();
      formData.append("excel", event.target.files[0]);
      formData.append("id", this.project.project.id);

      axios
        .post("/file/import", formData, {
          headers: {
            "Content-Type": "multipart/form-data"
          }
        })
        .then(response => {
          this.$router.go();
        })
        .catch(error => {
          this.errors = error.response.data.errors;
          this.overlay = false;
          this.alert = true;
        });
    }
  }
};
</script>

<style scoped>
p {
  color: black;
  margin-bottom: 0;
}

.importButton {
  margin: 10px;
}
</style>
