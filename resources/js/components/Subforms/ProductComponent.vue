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
                  <v-col cols="12" md="12">
                    <v-autocomplete
                      v-model="product"
                      :items="entires"
                      :search-input.sync="search"
                      color="grey"
                      label="Артикул"
                      outlined
                      item-text="name"
                      item-value="name"
                      return-object
                    ></v-autocomplete>
                  </v-col>
                  <v-col cols="12" md="4">
                    <v-text-field v-model="code" label="Артикул" solo readonly></v-text-field>
                  </v-col>
                  <v-col cols="12" md="8">
                    <v-text-field v-model="name" label="Название" solo readonly></v-text-field>
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
    product: null,
    code: null,
    name: null,
    count: null,
    price: null,
    total: null,
    isLoading: false
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

      let newThis = this;
      this.isLoading = true;
      this.entires = [];

      axios
        .get("/product/findById/" + val)
        .then(function(response) {
          console.log(response.data.result);
          response.data.result.forEach(item => {
            let product = {
              code: item.article,
              name: item.name
            };
            newThis.entires.push(product);
            newThis.isLoading = false;
          });
          newThis.isLoading = false;
        })
        .catch(function(error) {
          console.log(error);
          newThis.isLoading = false;
        });
    },

    product(val) {
      console.log(val);
      this.name = val != null ? val.name : "";
      this.code = val != null ? val.code : "";
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
        code: this.code,
        name: this.name,
        count: this.count,
        price: this.price,
        total: this.total
      };

      axios
        .post("/product", product)
        .then(function(response) {
          console.log(response);
        })
        .catch(function(error) {
          console.log(error);
        });

      this.products.push(product);
      this.code = null;
      this.name = null;
      this.count = null;
      this.price = null;
      this.total = null;
      this.product = null;
      this.dialog = false;
    },

    removeProduct(item) {
      this.products.splice(this.products.indexOf(item), 1);
    },

    closeDialog() {
      this.code = null;
      this.name = null;
      this.count = null;
      this.price = null;
      this.total = null;
      this.product = null;
      this.dialog = false;
    }
  }
};
</script>