<template>
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
        v-if="this.user_id === this.auth_id"
      >Редактировать</v-btn>
      <v-btn
        class="ma-2"
        outlined
        color="error"
        v-if="this.user_id === this.auth_id"
        @click="remove"
      >Удалить</v-btn>
    </v-card-actions>
  </v-card>
</template>


<script>
export default {
  mounted: function() {
    this.data = JSON.parse(this.project);
  },

  props: ["project", "auth_id", "user_id", "project_id"],

  data() {
    return {
      data: ""
    };
  },

  methods: {
    remove() {
      axios
        .delete("/project/" + this.project_id)
        .then(function(response) {
            window.location = "/project";
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    edit() {},

    back() {
      window.location = "/project";
    }
  }
};
</script>

<style scoped>
* {
  padding-bottom: 14px;
}
</style>
