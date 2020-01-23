<template>
  <div>
    <div class="row justify-content-center">
      <v-data-table
        :headers="headers"
        :items="fruits"
        :items-per-page="itemsPerPage"
        hide-default-footer
        class="elevation-3"
        @click:row="openProject"
      ></v-data-table>
    </div>
    <div class="text-center">
      <v-pagination v-model="page" :length="length" :total-visible="7"></v-pagination>
    </div>
  </div>
</template>


<script>
export default {
  mounted: function() {
    let newThis = this;

    if (window.projects == undefined) {
      axios
        .get("/project")
        .then(function(response) {
          newThis.itemsPerPage = response.data.per_page;
          newThis.length = Math.ceil(
            response.data.total / response.data.per_page
          );
          newThis.fruits = response.data.data;
        })
        .catch(function(error) {
          console.log(error);
        });
    } else {
      newThis.itemsPerPage = window.projects.per_page;
      newThis.length = Math.ceil(window.projects.total / window.projects.per_page);
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
        { text: "Customer", value: "customer" },
        { text: "Dealer", value: "dealer" },
        { text: "Date", value: "date" },
        { text: "Manager", value: "manager" }
      ],
      fruits: []
    };
  },

  watch: {
    page: function(val) {
      let newThis = this;
      axios
        .get("/project?page=" + val)
        .then(function(response) {
          newThis.itemsPerPage = response.data.per_page;
          newThis.length = Math.ceil(
            response.data.total / response.data.per_page
          );
          newThis.fruits = response.data.data;
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
