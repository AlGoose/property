<template>
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
</template>


<script>
export default {
  mounted: function() {
    let newThis = this;

    if (window.projects == undefined) {
      axios
        .get("/project")
        .then(function(response) {
          newThis.fruits = response.data.data;
        })
        .catch(function(error) {
          console.log(error);
        });
    } else {
      this.fruits = window.projects.data;
      window.projects = undefined;
    }
  },

  data() {
    return {
      itemsPerPage: 5,
      headers: [
        {
          text: "Name",
          align: "left",
          value: "name"
        },
        { text: "Address", value: "address" },
        { text: "Customer", value: "customer" },
        { text: "Contacts", value: "contacts" },
        { text: "Date", value: "date" },
        { text: "Manager", value: "manager" }
      ],
      fruits: []
    };
  },

  methods: {
    openProject(value) {
      // window.location = "/project/" + value.id;
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
