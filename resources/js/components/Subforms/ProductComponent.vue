<template>
  <div>
    <v-card class="mx-auto card" height="100%">
      <v-card-title>Товары</v-card-title>
      <v-card-text>
        <v-row justify="center">
          <v-dialog v-model="dialog" persistent max-width="600px">
            <template v-slot:activator="{ on }">
              <v-btn color="indigo" dark v-on="on" outlined>Добавить товар</v-btn>
            </template>
            <v-card>
              <v-card-title>
                <span class="headline">Добавить товар</span>
              </v-card-title>
              <v-card-text>
                <v-form ref="form" v-model="valid" lazy-validation>
                  <v-row>
                    <v-col cols="12" md="4">
                      <v-autocomplete
                        v-model="product"
                        :items="entires"
                        :search-input.sync="search"
                        color="grey"
                        label="Артикул"
                        outlined
                        hide-details
                        no-filter
                        item-text="name"
                        return-object
                        :loading="isLoading"
                      >
                        <template v-slot:item="{ item }">{{item.name}}</template>
                        <template v-slot:selection="{ item }">{{item.article}}</template>
                      </v-autocomplete>
                    </v-col>
                    <v-col cols="12" md="8">
                      <v-text-field
                        :value="product != undefined ? product.name : ''"
                        label="Название"
                        solo
                        flat
                        readonly
                        :rules="[v => !!v || 'Выберите продукт']"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="4">
                      <v-text-field
                        v-model="count"
                        label="Количество"
                        outlined
                        @change="onChangeCount"
                        :rules="[v => !!v || 'Введите количество', v => /^\d+(\.\d+)?$/.test(v) || 'Неверный формат данных']"
                      ></v-text-field>
                      <!-- /^\d+(\.\d+)?$/ -->
                    </v-col>
                    <v-col cols="12" md="4">
                      <v-text-field
                        v-model="price"
                        label="Цена за единицу"
                        outlined
                        @change="onChangePrice"
                        :rules="[v => !!v || 'Введите цену', v => /^\d+(\.\d+)?$/.test(v) || 'Неверный формат данных']"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="4">
                      <v-text-field
                        v-model="total"
                        label="Итоговая сумма"
                        outlined
                        @change="onChangeTotal"
                        :rules="[v => !!v || 'Введите общую сумму', v => /^\d+(\.\d+)?$/.test(v) || 'Неверный формат данных']"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn outlined color="indigo" @click="closeDialog">Отмена</v-btn>
                <v-btn outlined color="indigo" @click="addProduct">Добавить</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-row>

        <!-- <v-simple-table class="table" v-if="products.length">
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
              <tr valign="middle" v-for="(item, i) in products" :key="i">
                <td>{{ item.code }}</td>
                <td>{{ item.name }}</td>
                <td>{{ item.count }}</td>
                <td>{{ item.price }}</td>
                <td>{{ item.total }}</td>
                <td>
                  <v-icon @click="removeProduct(item,i)">mdi-minus-circle-outline</v-icon>
                </td>
              </tr>
            </tbody>
          </template>
        </v-simple-table>-->
        <v-data-table v-if="products.length" :headers="headers" :items="products" item-key="name" class="elevation-1">
          <template v-slot:item="{ item }">
            <td>{{ item.name }}</td>
            <td>{{ item.count }}</td>
            <td>{{ item.price }}</td>
            <td>{{ item.total }}</td>
            <td>
              <v-icon @click="removeProduct(item,i)">mdi-minus-circle-outline</v-icon>
            </td>
          </template>
        </v-data-table>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
export default {
  props: ["productsData"],

  data: () => ({
    headers: [
      {
        text: "Название продукта",
        align: "left",
        value: "name"
      },
      { text: "Количество", value: "pivot.count" },
      { text: "Цена за единицу (₽)", value: "pivot.price" },
      { text: "Общая стоимость (₽)", value: "pivot.total" },
      {
        text: "",
        sortable: false
      }
    ],
    valid: true,
    dialog: false,
    products: [],
    entires: [],
    search: null,
    product: {},
    count: null,
    price: null,
    total: null,
    isLoading: false,
    ids: {}
  }),

  watch: {
    productsData(val) {
      console.log("CAT", val);
      this.products = val;
      this.products.forEach(item => {
        item.pivot.total = item.pivot.count * item.pivot.price;
      });
    },

    search() {
      this.inputArticle();
    },

    ids(value) {
      this.$emit("products", value);
    }
  },

  created() {
    this.inputArticle = window.debounce(this.searchProduct, 500);
  },

  methods: {
    searchProduct() {
      if (this.search === null || this.search.length < 7) return;

      for (let i = 0; i < this.entires.length; i++) {
        if (this.entires[i].name === this.search) {
          return;
        }
      }

      this.isLoading = true;

      axios
        .get("/data/findProductById/" + this.search)
        .then(response => {
          console.log(response);
          this.entires = response.data.result;
          this.isLoading = false;
        })
        .catch(error => {
          console.log(error);
          this.entires = [];
          this.isLoading = false;
        });
    },
    onChangeCount(value) {
      if (value.match(/^\d+(\.\d+)?$/)) {
        if (this.price != null && !isNaN(this.price)) {
          this.total = (parseFloat(value) * parseFloat(this.price)).toFixed(2);
        }
      }
    },

    onChangePrice(value) {
      if (value.match(/^\d+(\.\d+)?$/)) {
        if (this.count != null && !isNaN(this.count)) {
          this.total = (parseFloat(value) * parseFloat(this.count)).toFixed(2);
        }
      }
    },

    onChangeTotal(value) {
      if (value.match(/^\d+(\.\d+)?$/)) {
        if (this.count != null && !isNaN(this.count)) {
          this.price = (parseFloat(value) / parseFloat(this.count)).toFixed(2);
        }
      }
    },

    addProduct() {
      if (this.$refs.form.validate()) {
        for (let i = 0; i < this.products.length; i++) {
          if (this.code == this.products[i].code) {
            return;
          }
        }

        let product = {
          code: this.product.id,
          article: this.product.article,
          name: this.product.name,
          count: this.count,
          price: this.price,
          total: this.total
        };

        axios
          .post("/product", product)
          .then(response => {
            this.$set(this.ids, response.data.id, {
              price: product.price,
              count: product.count
            });
            product.id = response.data.id;
          })
          .catch(function(error) {
            console.log(error);
          });

        this.products.push(product);
        this.count = null;
        this.price = null;
        this.total = null;
        this.product = {};
        this.entires = [];
        this.dialog = false;
      }
    },

    removeProduct(item, i) {
      this.products.splice(i, 1);
      this.$delete(this.ids, item.id);
    },

    closeDialog() {
      this.count = null;
      this.price = null;
      this.total = null;
      this.product = {};
      this.entires = [];
      this.dialog = false;
    }
  }
};
</script>