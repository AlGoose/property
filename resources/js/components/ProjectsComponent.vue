<template>
  <v-container fluid>
    <v-data-table
      :headers="headers"
      :items="fruits"
      :items-per-page="itemsPerPage"
      hide-default-footer
      class="elevation-3"
      @click:row="openProject"
    ></v-data-table>
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
        })
        .catch(function(error) {
          console.log(error);
        });
    } else {
      this.itemsPerPage = window.projects.per_page;
      this.length = Math.ceil(window.projects.total / window.projects.per_page);
      this.fruits = window.projects.data;
      window.projects = undefined;
    }
  },

  data() {
    return {
      itemsPerPage: 1,
      page: 1,
      length: 1,
      headers: [
        {
          text: "Name",
          align: "left",
          value: "name"
        },
        { text: "Address", value: "address" },
        { text: "Dealer", value: "dealer" },
        { text: "Date", value: "date" },
        { text: "Time", value: "time" },
        { text: "Manager", value: "manager" }
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
    }
  }
};
</script>

<style scoped>
* {
  padding-bottom: 14px;
}
</style>
