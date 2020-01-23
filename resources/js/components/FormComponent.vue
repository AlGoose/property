<template>
  <v-container fluid>
    <h3 align="center" v-if="$route.name === 'edit'">Изменение проекта</h3>
    <h3 align="center" v-else>Создание проекта</h3>

    <v-form ref="form" v-model="valid" lazy-validation>
      <v-card class="card" v-for="(group, key) in form" :key="key">
        <v-card-title>
          <h3>{{group.title}}</h3>
        </v-card-title>

        <v-card-text>
          <v-row>
            <v-col
              v-for="item in group.items"
              :key="item.id"
              :cols="item.sizes !== undefined ? item.sizes.cols : 12"
              :md="item.sizes !== undefined ? item.sizes.md : 12"
            >
              <v-text-field
                v-if="item.type === 'v-text-field'"
                v-model="item.data"
                :label="item.label"
                outlined
                clearable
                required
                :rules="item.rules"
                v-mask="item.mask"
                :readonly="item.readonly"
              ></v-text-field>

              <template v-else-if="item.type === 'opponentsField'">
                <v-text-field
                  v-model="item.opponent"
                  :label="item.label"
                  outlined
                  clearable
                  v-on:keyup.enter="input(item.opponent)"
                  append-icon="mdi-plus"
                  @click:append="input(item.opponent)"
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

              <template v-else-if="item.type === 'datePicker'">
                <v-menu
                  v-model="item.menu"
                  :close-on-content-click="false"
                  :nudge-right="40"
                  transition="scale-transition"
                  offset-y
                  min-width="290px"
                >
                  <template v-slot:activator="{ on }">
                    <v-text-field
                      v-model="item.data"
                      :label="item.label"
                      readonly
                      outlined
                      v-on="on"
                    ></v-text-field>
                  </template>
                  <v-date-picker
                    no-title
                    scrollable
                    locale="Rus"
                    v-model="item.data"
                    @input="item.menu = false"
                  ></v-date-picker>
                </v-menu>
              </template>

              <template v-else-if="item.type === 'products'">
                <v-form ref="form_prod" v-model="valid_prod" lazy-validation>
                  <v-row>
                    <v-col
                      v-for="(item, key) in item.items"
                      :key="key"
                      :cols="item.sizes !== undefined ? item.sizes.cols : 12"
                      :md="item.sizes !== undefined ? item.sizes.md : 12"
                    >
                      <v-text-field
                        v-if="item.type === 'v-text-field'"
                        v-model="item.data"
                        :label="item.label"
                        outlined
                        clearable
                        required
                        :rules="item.rules"
                        v-mask="item.mask"
                        :readonly="item.readonly"
                      ></v-text-field>

                      <v-btn
                        v-else-if="item.type === 'button'"
                        class="button"
                        block
                        color="red"
                        outlined
                        @click="addProduct"
                      >Добавить товар</v-btn>

                      <v-simple-table
                        class="table"
                        v-else-if="item.type === 'table' && item.data.length"
                      >
                        <template v-slot:default>
                          <thead>
                            <tr>
                              <th class="text-left">Артикул</th>
                              <th class="text-left">Наименование</th>
                              <th class="text-left">Количество</th>
                              <th class="text-left">Цена</th>
                              <th class="text-left">Итог</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr valign="middle" v-for="(item, i) in item.data" :key="i">
                              <td>{{ item.code }}</td>
                              <td>{{ item.name }}</td>
                              <td>{{ item.count }}</td>
                              <td>{{ item.price }}</td>
                              <td>{{ item.total }}</td>
                              <v-icon @click="removeProduct(item,i)">mdi-minus-circle-outline</v-icon>
                            </tr>
                          </tbody>
                        </template>
                      </v-simple-table>
                    </v-col>
                  </v-row>
                </v-form>
              </template>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>

      <v-row>
        <v-col cols="12" md="4">
          <v-text-field v-model="count" label="count" outlined></v-text-field>
        </v-col>

        <v-col cols="12" md="4">
          <v-text-field v-model="price" label="price" outlined></v-text-field>
        </v-col>

        <v-col cols="12" md="4">
          <v-text-field v-model="total" label="total" outlined></v-text-field>
        </v-col>
      </v-row>
      <v-btn
        v-if="$route.name === 'edit'"
        block
        color="indigo"
        outlined
        @click="validate"
      >Изменить форму</v-btn>
      <v-btn v-else block color="indigo" outlined @click="validate">Добавить форму</v-btn>
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
  directives: { mask },
  mounted() {
    let newThis = this;
    if (this.$route.name === "edit") {
      if (window.project == undefined) {
        axios
          .get("/project/" + this.$route.params.id + "/edit")
          .then(function(response) {
            console.log("AXIOS");
            console.log(response);
            for (let prop in newThis.dealer) {
              newThis.dealer[prop].data = response.data.dealer[prop];
            }
            for (let prop in newThis.form) {
              if (prop === "opponents") {
                response.data.opponents.forEach(item => {
                  newThis.form.opponents.data.push(item.name);
                });
              } else {
                newThis.form[prop].data = response.data[prop];
              }
            }
          })
          .catch(function(error) {
            console.log(error);
          });
      } else {
        console.log("BLADE");
        console.log(window.project);
        for (let prop in newThis.dealer) {
          newThis.dealer[prop].data = window.project.dealer[prop];
        }
        for (let prop in newThis.form) {
          if (prop === "opponents") {
            window.project.opponents.forEach(item => {
              newThis.form.opponents.data.push(item.name);
            });
          } else {
            newThis.form[prop].data = window.project[prop];
          }
        }
        window.project = undefined;
      }
    } else {
      if (this.$route.params.address) {
        this.form.address.data = this.$route.params.address;
      }
    }
  },

  data: () => ({
    mode: "create",
    dialog: false,
    valid: true,
    valid_prod: true,
    form: {
      dealer: {
        title: "Диллер",
        items: {
          inn: {
            sizes: {
              cols: 12,
              md: 6
            },
            type: "v-text-field",
            data: "",
            label: "ИНН",
            rules: [],
            mask: "##########"
          },
          kpp: {
            sizes: {
              cols: 12,
              md: 6
            },
            type: "v-text-field",
            data: "",
            label: "КПП",
            rules: [],
            mask: "#########",
            readonly: true
          },
          name: {
            sizes: {
              cols: 12,
              md: 12
            },
            type: "v-text-field",
            data: "",
            label: "Название",
            rules: [],
            readonly: true
          },
          agent: {
            sizes: {
              cols: 12,
              md: 12
            },
            type: "v-text-field",
            data: "",
            label: "Имя представителя",
            rules: [v => !!v || "Name is required"]
          },
          phone: {
            sizes: {
              cols: 12,
              md: 6
            },
            type: "v-text-field",
            data: "",
            label: "Телефон представителя",
            rules: [],
            mask: "(###) ### ## ##"
          },
          email: {
            sizes: {
              cols: 12,
              md: 6
            },
            type: "v-text-field",
            data: "",
            label: "Почта представителя",
            rules: []
          }
        }
      },
      customer: {
        title: "Заказчик",
        items: {
          name: {
            sizes: {
              cols: 12,
              md: 12
            },
            type: "v-text-field",
            data: "",
            label: "Название объекта",
            rules: [v => !!v || "Name is required"]
          },
          address: {
            sizes: {
              cols: 12,
              md: 12
            },
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
            rules: [],
            readonly: true
          },
          phone: {
            sizes: {
              cols: 12,
              md: 6
            },
            type: "v-text-field",
            data: "",
            label: "Телефон представителя",
            rules: [],
            mask: "(###) ### ## ##"
          },
          email: {
            sizes: {
              cols: 12,
              md: 6
            },
            type: "v-text-field",
            data: "",
            label: "Почта представителя",
            rules: []
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
            label: "Срок реализации проекта",
            menu: false
          },
          work: {
            type: "work",
            data: [],
            label: "Конкуренты"
          },
          time: {
            type: "datePicker",
            data: new Date().toISOString().substr(0, 10),
            label: "Дата заполнения бланка",
            menu: false
          },
          product: {
            type: "products",
            items: {
              product_code: {
                sizes: {
                  cols: 12,
                  md: 4
                },
                type: "v-text-field",
                data: "",
                label: "Артикул товара",
                rules: [v => !!v || "Артикул is required"]
              },
              product_name: {
                sizes: {
                  cols: 12,
                  md: 8
                },
                type: "v-text-field",
                data: "",
                label: "Наименование товара",
                readonly: true
              },
              product_count: {
                sizes: {
                  cols: 12,
                  md: 4
                },
                type: "v-text-field",
                data: "",
                label: "Количество",
                rules: [v => !!v || "Количество is required"],
                mask: "#####"
              },
              product_price: {
                sizes: {
                  cols: 12,
                  md: 4
                },
                type: "v-text-field",
                data: "",
                label: "Цена за единицу",
                rules: [v => !!v || "Цена is required"],
                mask: "#####"
              },
              product_total: {
                sizes: {
                  cols: 12,
                  md: 4
                },
                type: "v-text-field",
                data: "",
                label: "Общая сумма",
                rules: [v => !!v || "Cумма is required"],
                mask: "#####"
              },
              product_button: {
                type: "button"
              },
              product_table: {
                type: "table",
                data: [
                  {
                    code: "123",
                    name: "Деталь 1",
                    count: 10,
                    price: 100,
                    total: 1000
                  },
                  {
                    code: "456",
                    name: "Деталь 2",
                    count: 2,
                    price: 4,
                    total: 8
                  }
                ]
              }
            }
          }
        }
      }
    },
    count: "",
    price: "",
    total: ""
  }),

  watch: {
    count: function(val) {
      if (parseFloat(val) && parseFloat(this.price)) {
        this.total = parseFloat(this.price) * parseFloat(val);
      }
    },
    price: function(val) {
      if (parseFloat(val) && parseFloat(this.count)) {
        this.total = parseFloat(this.count) * parseFloat(val);
      }
    },
    total: function(val) {
      if (parseFloat(val) && parseFloat(this.count)) {
        this.price = (parseFloat(val) / parseFloat(this.count)).toFixed(2);
      }
    }
  },

  methods: {
    addForm() {
      let newThis = this;

      let res = {
        dealer: 1,
        project: { name: "", address: "" },
        products: {
          1: { count: 5, price: 100 },
          25: { count: 5, price: 100 }
        },
        oponents: [1, 2, 3]
      };
      for (let prop in this.dealer) {
        res.dealer[prop] = this.dealer[prop].data;
      }
      for (let prop in this.form) {
        res.project[prop] = this.form[prop].data;
      }

      axios
        .post("http://property.test/project", res)
        .then(function(response) {
          newThis.$refs.form.reset();
          newThis.form.opponents.opponent = "";
          newThis.form.opponents.data = [];
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

    input(value) {
      if (value === "") return;

      if (this.form.customer.items.opponents.data.includes(value)) {
        this.form.customer.items.opponents.opponent = "";
        return;
      }

      this.form.customer.items.opponents.data.push(value);
      this.form.customer.items.opponents.opponent = "";
    },

    remove(item) {
      this.form.customer.items.opponents.data.splice(
        this.form.customer.items.opponents.data.indexOf(item),
        1
      );
      this.form.customer.items.opponents.data = [
        ...this.form.customer.items.opponents.data
      ];
    },

    addProduct() {
      if (this.$refs.form_prod[0].validate()) {
        console.log("FINE");

        let res = {
          code: this.form.customer.items.product.items.product_code.data,
          name: "Деталь 1 Деталь 1 Деталь 1 Деталь 1 Деталь 1 Деталь 1",
          count: this.form.customer.items.product.items.product_count.data,
          price: this.form.customer.items.product.items.product_price.data,
          total: this.form.customer.items.product.items.product_total.data
        };
        this.form.customer.items.product.items.product_table.data.push(res);
        this.form.customer.items.product.items.product_code.data = "";
        this.form.customer.items.product.items.product_count.data = "";
        this.form.customer.items.product.items.product_price.data = "";
        this.form.customer.items.product.items.product_total.data = "";
        this.$refs.form_prod[0].reset();
      } else {
        console.log("NOT FINE");
      }
    },

    removeProduct(item) {
      console.log(item);
      this.form.customer.items.product.items.product_table.data.splice(
        this.form.customer.items.product.items.product_table.data.indexOf(item),
        1
      );
      // this.form.customer.items.opponents.data = [
      //   ...this.form.customer.items.opponents.data
      // ];
    }
  }
};
</script>

<style scoped>
.card {
  margin-bottom: 30px;
}

.table {
  margin-top: 20px;
}
</style>