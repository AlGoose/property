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
        :rules="[v => !!v || 'Введите название']"
      ></v-text-field>
      <v-text-field
        v-model="projectData.address"
        label="Адрес"
        outlined
        :rules="[v => !!v || 'Введите адрес']"
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
              @change="sendData"
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
              @change="sendData"
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
    timeMenu: false,
    tenderMenu: false,
    tempFiles: [],
    files: []
  }),

  watch: {
    address_prop(value) {
      this.$set(this.projectData, "address", this.address_prop);
      if (this.projectData.files) {
        this.files = this.projectData.files;
      }
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
        time: this.projectData.time,
        tender_date: this.projectData.tender_date
      });

      this.$emit("files", this.files);
    },

    test() {
      axios
        .get("/file/14")
        .then(function() {
          console.log("SUCCESS!!");
        })
        .catch(function() {
          console.log("FAILURE!!");
        });
    }

    // removeFile(index) {
    //   this.files.splice(index, 1);
    //   this.sendData();
    // },

    // addFiles(files) {
    //   // let sendFiles = [];
    //   files.forEach(file => {
    //     for (let i = 0; i < this.files.length; i++) {
    //       if (this.files[i].name === file.name) {
    //         return;
    //       }
    //     }
    //     this.files.push(file);
    //     // sendFiles.push(file);
    //   });
    //   this.tempFiles = [];

    //   this.sendData();
    // }
  }
};
</script>