<template>
  <v-card class="mx-auto">
    <v-card-title>Проект</v-card-title>
    <v-card-text>
      <v-text-field v-model="name" label="Название" outlined></v-text-field>
      <v-text-field v-model="address" label="Адрес" outlined></v-text-field>
      <v-textarea v-model="work" height="200" no-resize outlined label="Проделанная работа"></v-textarea>
      <v-row>
        <v-col md="6">
          <v-menu
            v-model="dateMenu"
            :close-on-content-click="false"
            :nudge-right="20"
            transition="scale-transition"
            offset-y
            min-width="290px"
          >
            <template v-slot:activator="{ on }">
              <v-text-field v-model="date" label="Срок реализации" readonly outlined v-on="on"></v-text-field>
            </template>
            <v-date-picker
              no-title
              scrollable
              locale="Rus"
              v-model="date"
              @input="dateMenu = false"
            ></v-date-picker>
          </v-menu>
        </v-col>
        <v-col md="6">
          <v-menu
            v-model="timeMenu"
            :close-on-content-click="false"
            :nudge-right="20"
            transition="scale-transition"
            offset-y
            min-width="290px"
          >
            <template v-slot:activator="{ on }">
              <v-text-field v-model="time" label="Дата заключения" readonly outlined v-on="on"></v-text-field>
            </template>
            <v-date-picker
              no-title
              scrollable
              locale="Rus"
              v-model="time"
              @input="timeMenu = false"
            ></v-date-picker>
          </v-menu>
        </v-col>
      </v-row>
    </v-card-text>
  </v-card>
</template>

<script>
export default {
  data: () => ({
    name: null,
    address: null,
    work: null,
    date: new Date().toISOString().substr(0, 10),
    time: new Date().toISOString().substr(0, 10),
    dateMenu: false,
    timeMenu: false
  })
};
</script>