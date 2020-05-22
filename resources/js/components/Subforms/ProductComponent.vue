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
                      <v-text-field
                        v-model="product.article"
                        label="Артикул"
                        outlined
                        :rules="[v => !!v || 'Введите артикул']"
                        v-if="isDisabled"
                      ></v-text-field>
                      <v-autocomplete
                        v-else
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
                        v-model="product.name"
                        label="Название"
                        outlined
                        :rules="[v => !!v || 'Введите название']"
                        v-if="isDisabled"
                      ></v-text-field>
                      <v-text-field
                        v-else
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
                <v-switch label="Ввести данные вручную" v-model="isDisabled"></v-switch>
                <v-spacer></v-spacer>
                <v-btn outlined color="indigo" @click="closeDialog">Отмена</v-btn>
                <v-btn outlined color="indigo" @click="addProduct">Добавить</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-row>

        <v-data-table
          v-if="productsData.length"
          :headers="headers"
          :items="productsData"
          item-key="name"
          class="elevation-2"
        >
          <template v-slot:item="{ item, index }">
            <tr>
              <td>{{ item.name }}</td>
              <td>{{ item.pivot.count }}</td>
              <td>{{ item.pivot.price }}</td>
              <td>{{ item.pivot.count * item.pivot.price }}</td>
              <td>
                <v-icon @click="removeProduct(item, index)">mdi-minus-circle-outline</v-icon>
              </td>
            </tr>
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
    isDisabled: false,
    isLoading: false,
    products: [],
    entires: [],
    search: null,
    product: {
      article: null,
      id: null,
      name: null
    },
    count: null,
    price: null,
    total: null
  }),

  watch: {
    search() {
      this.inputArticle();
    }
  },

  created() {
    this.inputArticle = window.debounce(this.searchProduct, 500);
  },

  methods: {
    searchProduct() {
      if (this.search === null || this.search.length < 4) return;

      for (let i = 0; i < this.entires.length; i++) {
        if (this.entires[i].name === this.search) {
          return;
        }
      }

      this.isLoading = true;

      axios
        .get("/data/findProductById/" + this.search)
        .then(response => {
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
        for (let i = 0; i < this.productsData.length; i++) {
          if (this.product.article == this.productsData[i].article) {
            return;
          }
        }

        let product = {
          code: this.product.id,
          article: this.product.article,
          name: this.product.name
        };

        axios
          .post("/product", product)
          .then(response => {
            product.id = response.data.id;
            product.pivot = {
              price: this.price,
              count: this.count,
              total: this.total
            };
            this.productsData.push(product);
            this.closeDialog();
          })
          .catch(function(error) {
            console.log(error);
          });
      }
    },

    removeProduct(item, index) {
      this.productsData.splice(index, 1);
    },

    closeDialog() {
      this.$refs.form.reset();
      this.count = null;
      this.price = null;
      this.total = null;
      this.product = {
        article: null,
        id: null,
        name: null
      };
      this.entires = [];
      this.dialog = false;
    }
  }
};
</script>

<style scoped>
.v-data-table {
  margin-top: 14px;
}
</style>