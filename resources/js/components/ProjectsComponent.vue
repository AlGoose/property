<template>
  <div>
    <v-data-table
      :headers="headers"
      :items="fruits"
      :items-per-page="itemsPerPage"
      hide-default-footer
      class="elevation-3"
      @click:row="openProject"
    >
    </v-data-table>
  </div>
</template>


<script>
export default {
  mounted: function() {
  //  let data = JSON.parse(this.projects)["data"];
   /* for (let i = 0; i < data.length; i++) {
      this.fruits.push(data[i]);
    }*/
    axios.get('/project').then((data)=>{ console.log(data)})
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
        // { text: "Opponents", value: "opponents" },
        { text: "Contacts", value: "contacts" },
        { text: "Date", value: "date" },
        // { text: "Work", value: "work" },
        // { text: "Products", value: "products" },
        { text: "Manager", value: "manager" }
      ],
      fruits: []
    };
  },

  methods: {
    openProject(value) {
      window.location = '/project/' + value.id;
    }
  }
};
</script>

<style scoped>
* {
  padding-bottom: 14px;
}
</style>
