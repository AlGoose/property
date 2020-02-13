<template>
  <v-card class="mx-auto" v-if="projectData">
    <v-card-title>Проект</v-card-title>
    <v-card-text>
      <v-text-field
        v-model="projectData.name"
        label="Название"
        outlined
        :readonly="isEdit"
        :rules="[v => !!v || 'Введите название']"
      ></v-text-field>
      <v-text-field
        v-model="projectData.address"
        label="Адрес"
        outlined
        :rules="[v => !!v || 'Введите адрес']"
        readonly
      ></v-text-field>
      <v-textarea
        v-model="projectData.work"
        height="200"
        no-resize
        outlined
        label="Проделанная работа"
      ></v-textarea>
      <!-- @change="sendData" -->

      <v-row>
        <v-col cols="12" md="4">
          <v-menu
            v-model="dateMenu"
            :close-on-content-click="false"
            :nudge-right="20"
            transition="scale-transition"
            offset-y
            min-width="290px"
          >
            <template v-slot:activator="{ on }">
              <v-text-field
                v-model="projectData.date"
                label="Срок реализации"
                readonly
                outlined
                v-on="on"
                :rules="[v => !!v || 'Выберите дату']"
              ></v-text-field>
            </template>
            <v-date-picker
              no-title
              scrollable
              locale="Rus"
              v-model="projectData.date"
              @input="dateMenu = false"
            ></v-date-picker>
          </v-menu>
        </v-col>

        <v-col cols="12" md="4">
          <v-menu
            v-model="timeMenu"
            :close-on-content-click="false"
            :nudge-right="20"
            transition="scale-transition"
            offset-y
            min-width="290px"
            :disabled="isEdit"
          >
            <template v-slot:activator="{ on }">
              <v-text-field
                v-model="projectData.time"
                label="Дата заключения"
                readonly
                outlined
                v-on="on"
                :rules="[v => !!v || 'Выберите дату']"
              ></v-text-field>
            </template>
            <v-date-picker
              no-title
              scrollable
              locale="Rus"
              v-model="projectData.time"
              @input="timeMenu = false"
            ></v-date-picker>
          </v-menu>
        </v-col>

        <v-col cols="12" md="4">
          <v-menu
            v-model="tenderMenu"
            :close-on-content-click="false"
            :nudge-right="20"
            transition="scale-transition"
            offset-y
            min-width="290px"
            :disabled="isEdit"
          >
            <template v-slot:activator="{ on }">
              <v-text-field
                v-model="projectData.tender_date"
                label="Дата тендера"
                readonly
                outlined
                v-on="on"
              ></v-text-field>
            </template>
            <v-date-picker
              no-title
              scrollable
              locale="Rus"
              v-model="projectData.tender_date"
              @input="tenderMenu = false"
            ></v-date-picker>
          </v-menu>
        </v-col>
      </v-row>
    </v-card-text>
  </v-card>
</template>

<script>
export default {
  props: ["isEdit", "projectData"],

  data: () => ({
    dateMenu: false,
    timeMenu: false,
    tenderMenu: false
  })
};
</script>