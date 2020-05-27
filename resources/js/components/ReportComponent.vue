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
        <v-btn color="primary" depressed @click="searchReport(1)">Поиск</v-btn>
      </v-col>
    </v-row>
    <p class="title">Всего проектов: {{ total }}</p>
    <v-row>
      <v-col cols="12">
        <v-data-table
          hide-default-footer
          :headers="headers"
          :items="projects"
          :items-per-page="itemsPerPage"
          item-key="id"
          class="elevation-1"
        >
          <template v-slot:item.isClosed="{ item }">{{ item.isClosed ? "Закрыт" : "В работе" }}</template>
          <template v-slot:item.wonMoney="{ item }">{{ item.wonMoney.toLocaleString() }}</template>
          <template v-slot:item.loseMoney="{ item }">{{ item.loseMoney.toLocaleString() }}</template>
        </v-data-table>
        <v-pagination v-model="page" :length="length" :total-visible="7"></v-pagination>
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
    itemsPerPage: 20,
    page: 1,
    length: 1,
    total: 0,
    dates: [],
    projects: [],
    headers: [
      { text: "Название проекта", value: "name" },
      { text: "Дилер", value: "dealer.name" },
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
      return parseFloat(
        this.projects
          .reduce((accumulator, item) => {
            return accumulator + item.total;
          }, 0)
          .toFixed(2)
      ).toLocaleString();
    },

    totalWon() {
      return parseFloat(
        this.projects
          .reduce((accumulator, item) => {
            return accumulator + item.wonMoney;
          }, 0)
          .toFixed(2)
      ).toLocaleString();
    },

    totalLose() {
      return parseFloat(
        this.projects
          .reduce((accumulator, item) => {
            return accumulator + item.loseMoney;
          }, 0)
          .toFixed(2)
      ).toLocaleString();
    }
  },

  watch: {
    page(val) {
      this.searchReport(val);
    }
  },

  methods: {
    searchReport(page) {
      // console.log("Click", val);
      axios
        .post("/project/report?page=" + page, {
          dates: this.dates
        })
        .then(response => {
          // console.log(response);
          this.page = response.data.current_page;
          this.itemsPerPage = response.data.per_page;
          this.length = Math.ceil(response.data.total / response.data.per_page);
          this.total = response.data.total;
          this.projects = response.data.data;
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