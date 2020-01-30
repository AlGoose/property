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
                    <v-text-field v-model="product.name" label="Название" solo flat readonly></v-text-field>
                  </v-col>
                  <v-col cols="12" md="4">
                    <v-text-field
                      v-model="count"
                      label="Количество"
                      outlined
                      @change="onChangeCount"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" md="4">
                    <v-text-field
                      v-model="price"
                      label="Цена за единицу"
                      outlined
                      @change="onChangePrice"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" md="4">
                    <v-text-field
                      v-model="total"
                      label="Итоговая сумма"
                      outlined
                      @change="onChangeTotal"
                    ></v-text-field>
                  </v-col>
                </v-row>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn outlined color="indigo" @click="closeDialog">Отмена</v-btn>
                <v-btn outlined color="indigo" @click="addProduct">Добавить</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-row>

        <v-simple-table class="table" v-if="products.length">
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
        </v-simple-table>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
export default {
  data: () => ({
    dialog: false,
    products: [],
    entires: [],
    search: null,
    product: {},
    count: null,
    price: null,
    total: null,
    isLoading: false,
    ids: []
  }),

  watch: {
    search(val) {
      console.log(val);
      if (this.isLoading) return;
      if (val === null || val.length < 7) return;

      let isValid = true;
      for (let i = 0; i < this.entires.length; i++) {
        if (this.entires[i].name === val) {
          isValid = false;
        }
      }
      if (!isValid) return;

      this.isLoading = true;
      this.entires = [];

      axios
        .get("/data/findProductById/" + val)
        .then(response => {
          console.log(response.data.result);
          this.entires = response.data.result;
          this.isLoading = false;
        })
        .catch(error => {
          console.log(error);
          this.isLoading = false;
        });
    },

    ids(val) {
      this.$emit("products", val);
    }
  },

  methods: {
    onChangeCount(value) {
      this.total = (parseFloat(value) * parseFloat(this.price)).toFixed(2);
    },

    onChangePrice(value) {
      this.total = (parseFloat(value) * parseFloat(this.count)).toFixed(2);
    },

    onChangeTotal(value) {
      this.price = (parseFloat(value) / parseFloat(this.count)).toFixed(2);
    },

    addProduct() {
      let isValid = true;
      for (let i = 0; i < this.products.length; i++) {
        if (this.code === this.products[i].code) {
          isValid = false;
          return;
        }
      }
      if (!isValid) return;

      let product = {
        code: this.product.article,
        name: this.product.name,
        count: this.count,
        price: this.price,
        total: this.total
      };

      axios
        .post("/product", product)
        .then(response => {
          console.log(response);
          this.ids.push(response.data.id);
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
    },

    removeProduct(item, i) {
      this.products.splice(i, 1);
      this.ids.splice(i, 1);
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