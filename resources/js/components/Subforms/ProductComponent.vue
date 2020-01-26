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
                    <v-text-field v-model="code" label="Артикул" solo></v-text-field>
                  </v-col>
                  <v-col cols="12" md="8">
                    <v-text-field v-model="name" label="Название" solo></v-text-field>
                  </v-col>
                  <v-col cols="12" md="4">
                    <v-text-field v-model="count" label="Количество" solo @change="onChangeCount"></v-text-field>
                  </v-col>
                  <v-col cols="12" md="4">
                    <v-text-field v-model="price" label="Цена за единицу" solo @change="onChangePrice"></v-text-field>
                  </v-col>
                  <v-col cols="12" md="4">
                    <v-text-field v-model="total" label="Итоговая сумма" solo @change="onChangeTotal"></v-text-field>
                  </v-col>
                </v-row>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn outlined color="indigo" @click="dialog = false">Отмена</v-btn>
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
    code: null,
    name: null,
    count: null,
    price: null,
    total: null
  }),

  methods: {
    onChangeCount(value) {
      this.total = (parseFloat(value)*parseFloat(this.price)).toFixed(2);
    },

    onChangePrice(value) {
      this.total = (parseFloat(value)*parseFloat(this.count)).toFixed(2);
    },

    onChangeTotal(value) {
      this.price = (parseFloat(value)/parseFloat(this.count)).toFixed(2);
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

      // axios
      //   .post("http://property.test/addProduct", test)
      //   .then(function(response) {
      //     console.log(response);
      //   })
      //   .catch(function(error) {
      //     console.log(error);
      //   });

      this.products.push(product);
      this.code = "";
      this.name = "";
      this.count = "";
      this.price = "";
      this.total = "";
      this.dialog = false;
    },

    removeProduct(item) {
      this.products.splice(this.products.indexOf(item), 1);
    }
  }
};
</script>