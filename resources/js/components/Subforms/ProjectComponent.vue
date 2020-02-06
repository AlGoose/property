<template>
  <v-card class="mx-auto" v-if="projectData">
    <v-card-title>Проект</v-card-title>
    <v-card-text>
      <v-text-field
        v-model="projectData.name"
        label="Название"
        outlined
        @change="sendData"
        :readonly="isEdit"
        :rules="[v => !!v || 'Name is required']"
      ></v-text-field>
      <v-text-field
        v-model="projectData.address"
        label="Адрес"
        outlined
        :rules="[v => !!v || 'Address is required']"
        @change="sendData"
        readonly
      ></v-text-field>
      <v-textarea
        v-model="projectData.work"
        height="200"
        no-resize
        outlined
        label="Проделанная работа"
        @change="sendData"
      ></v-textarea>
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
              <v-text-field
                v-model="projectData.date"
                label="Срок реализации"
                readonly
                outlined
                v-on="on"
                :rules="[v => !!v || 'Date is required']"
              ></v-text-field>
            </template>
            <v-date-picker
              no-title
              scrollable
              locale="Rus"
              v-model="projectData.date"
              @input="dateMenu = false"
              @change="sendData"
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
            :disabled="isEdit"
          >
            <template v-slot:activator="{ on }">
              <v-text-field
                v-model="projectData.time"
                label="Дата заключения"
                readonly
                outlined
                v-on="on"
                :rules="[v => !!v || 'Date is required']"
              ></v-text-field>
            </template>
            <v-date-picker
              no-title
              scrollable
              locale="Rus"
              v-model="projectData.time"
              @input="timeMenu = false"
              @change="sendData"
            ></v-date-picker>
          </v-menu>
        </v-col>
      </v-row>
    </v-card-text>
  </v-card>
</template>

<script>
export default {
  props: ["address_prop", "isEdit", "projectData"],

  data: () => ({
    dateMenu: false,
    timeMenu: false
  }),

  watch: {
    address_prop(value) {
      this.$set(this.projectData, 'address', this.address_prop);
      this.sendData();
    }
  },

  methods: {
    sendData() {
      this.$emit("project", {
        name: this.projectData.name,
        address: this.projectData.address,
        work: this.projectData.work,
        date: this.projectData.date,
        time: this.projectData.time
      });
    }
  }
};
</script>