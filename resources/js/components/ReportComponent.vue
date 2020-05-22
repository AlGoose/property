<template>
  <v-container fluid>
    <v-row>
      <v-col cols="12" sm="12" md="6" lg="4">
        <v-date-picker v-model="dates" locale="Rus" range></v-date-picker>
      </v-col>
      <v-col cols="12" sm="12" md="6" lg="8">
        <v-text-field
          v-model="dateRangeText"
          label="Выбранные даты"
          prepend-icon="mdi-calendar"
          readonly
        ></v-text-field>
        <v-btn color="primary" depressed @click="searchReport">Поиск</v-btn>
      </v-col>
    </v-row>
    <p class="title">Всего проектов: {{ this.projects.length }}</p>
    <v-row>
      <v-col cols="12">
        <v-data-table dense :headers="headers" :items="projects" item-key="id" class="elevation-1">
          <template v-slot:item.isClosed="{ item }">{{ item.isClosed ? "Закрыт" : "В работе" }}</template>
        </v-data-table>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="4">
        <p class="title">Всего: {{totalMoney}} руб.</p>
      </v-col>
      <v-col cols="4">
        <p class="title">Всего выиграно: {{totalWon}} руб.</p>
      </v-col>
      <v-col cols="4">
        <p class="title">Всего проиграно: {{totalLose}} руб.</p>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  data: () => ({
    dates: [],
    projects: [],
    headers: [
      { text: "Название проекта", value: "name" },
      { text: "Дата открытия", value: "time" },
      { text: "Статус", value: "isClosed" },
      { text: "Сумма выигрыша", value: "wonMoney" },
      { text: "Сумма проигрыша", value: "loseMoney" }
    ]
  }),
  computed: {
    dateRangeText() {
      return this.dates.sort().join(" ~ ");
    },

    totalMoney() {
      return this.projects
        .reduce((accumulator, item) => {
          return accumulator + item.total;
        }, 0)
        .toFixed(2);
    },

    totalWon() {
      return this.projects
        .reduce((accumulator, item) => {
          return accumulator + item.wonMoney;
        }, 0)
        .toFixed(2);
    },

    totalLose() {
      return this.projects
        .reduce((accumulator, item) => {
          return accumulator + item.loseMoney;
        }, 0)
        .toFixed(2);
    }
  },

  methods: {
    searchReport() {
      // console.log("Click");
      axios
        .post("/project/report", {
          dates: this.dates
        })
        .then(response => {
          console.log(response);
          this.projects = response.data;
          this.countMoney();
        })
        .catch(error => {
          console.log(error);
        });
    },

    countMoney() {
      this.projects.forEach(item => {
        let summa = item.products
          .reduce((accumulator, item) => {
            return accumulator + item.pivot.price * item.pivot.count;
          }, 0)
          .toFixed(2);

        item.total = parseFloat(summa);
        if (item.isClosed) {
          if (item.isTenderWon) {
            item.wonMoney = parseFloat(summa);
            item.loseMoney = 0;
          } else {
            item.wonMoney = 0;
            item.loseMoney = parseFloat(summa);
          }
        } else {
          item.wonMoney = 0;
          item.loseMoney = 0;
        }
      });
    }
  }
};
</script>

<style scoped>
.v-pagination {
  margin-top: 20px;
}
</style>