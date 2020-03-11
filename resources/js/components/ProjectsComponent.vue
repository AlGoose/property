<template>
  <v-container fluid>
    <v-data-table
      :headers="headers"
      :items="fruits"
      :items-per-page="itemsPerPage"
      hide-default-footer
      class="elevation-3"
      @click:row="openProject"
    >
      <template v-slot:item.isTenderWon="{ item }">{{ item.isTenderWon ? "Да" : "Нет" }}</template>
      <template v-slot:item.isClosed="{ item }">{{ item.isClosed ? "Да" : "Нет" }}</template>
    </v-data-table>
    <div class="text-center">
      <v-pagination v-model="page" :length="length" :total-visible="7"></v-pagination>
    </div>
  </v-container>
</template>


<script>
export default {
  mounted: function() {
    if (window.projects == undefined) {
      axios
        .get("/project")
        .then(response => {
          this.itemsPerPage = response.data.per_page;
          this.length = Math.ceil(response.data.total / response.data.per_page);
          this.fruits = response.data.data;
          this.countFullPrice();
        })
        .catch(function(error) {
          console.log(error);
        });
    } else {
      this.itemsPerPage = window.projects.per_page;
      this.length = Math.ceil(window.projects.total / window.projects.per_page);
      this.fruits = window.projects.data;
      this.countFullPrice();
      window.projects = undefined;
    }
  },

  data() {
    return {
      itemsPerPage: 1,
      page: 1,
      length: 1,
      headers: [
        { text: "Название", value: "name" },
        { text: "Адрес", value: "address" },
        { text: "Дилер", value: "dealer" },
        { text: "Срок реализации", value: "date" },
        { text: "Дата заключения", value: "time" },
        { text: "Дата тендера", value: "tender_date" },
        { text: "Победа", value: "isTenderWon" },
        { text: "Закрыто", value: "isClosed" },
        { text: "Дата закрытия", value: "close_date" },
        { text: "Общая стоимость (₽)", value: "total" },
        { text: "Менеджер", value: "manager" }
      ],
      fruits: []
    };
  },

  watch: {
    page: function(val) {
      axios
        .get("/project?page=" + val)
        .then(response => {
          this.itemsPerPage = response.data.per_page;
          this.length = Math.ceil(response.data.total / response.data.per_page);
          this.fruits = response.data.data;
          this.countFullPrice();
        })
        .catch(function(error) {
          console.log(error);
        });
    }
  },

  methods: {
    openProject(value) {
      this.$router.push({
        name: "show",
        params: { id: value.id }
      });
    },

    countFullPrice() {
      this.fruits.forEach(item => {
        item.total = item.products.reduce((accumulator, item) => {
          return accumulator + item.pivot.price * item.pivot.count;
        }, 0);
      });
    }
  }
};
</script>

<style scoped>
* {
  padding-bottom: 14px;
}
.container {
  max-width: 80%;
}
</style>
