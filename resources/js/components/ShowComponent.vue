<template>
  <div class="row justify-content-center">
    <v-card max-width="800" outlined>
      <v-list-item three-line>
        <v-list-item-content>
          <v-text-field :value="data.name" label="Имя" outlined readonly></v-text-field>
          <v-text-field :value="data.address" label="Адрес" outlined readonly></v-text-field>
          <v-text-field :value="data.customer" label="Заказчик" outlined readonly></v-text-field>
          <v-text-field :value="data.opponents" label="Конкуренты" outlined readonly></v-text-field>
          <v-text-field :value="data.contacts" label="Контакты" outlined readonly></v-text-field>
          <v-text-field :value="data.date" label="Дата" outlined readonly></v-text-field>
        </v-list-item-content>
      </v-list-item>

      <v-card-actions>
        <v-btn class="ma-2" outlined color="indigo" @click="back">Назад</v-btn>
        <v-btn
          class="ma-2"
          outlined
          color="teal"
          v-if="user_id === auth_id"
          @click="edit"
        >Редактировать</v-btn>
        <v-btn
          class="ma-2"
          outlined
          color="error"
          v-if="user_id === auth_id"
          @click="remove"
        >Удалить</v-btn>
      </v-card-actions>
    </v-card>
  </div>
</template>


<script>
export default {
  mounted: function() {
    let newThis = this;
    this.project_id = this.$route.params.id;
    this.auth_id = window.current_user.id;

    if (window.project == undefined) {
      axios
        .get("/project/" + this.project_id)
        .then(function(response) {
          newThis.data = response.data;
          newThis.user_id = newThis.data.user_id;
        })
        .catch(function(error) {
          console.log(error);
        });
    } else {
      this.data = window.project;
      this.user_id = this.data.user_id;
      window.project = undefined;
    }
  },

  data() {
    return {
      data: "",
      auth_id: "",
      user_id: "",
      project_id: ""
    };
  },

  methods: {
    remove() {
      axios
        .delete("/project/" + this.project_id)
        .then(function(response) {
          //  window.location = "/project";
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    edit() {
      console.log(window.project);
      this.$router.push({
        name: "edit",
        params: { mode: "edit" }
      });
    },

    back() {
      this.$router.push("/project");
    }
  }
};
</script>

<style scoped>
* {
  padding-bottom: 14px;
}
</style>
