<template>
  <v-card class="mx-auto card" height="100%">
    <v-card-title>Документы</v-card-title>
    <v-card-text>
      <v-file-input
        v-model="files"
        label="Добавить файл(-ы)"
        multiple
        outlined
        :show-size="1000"
        prepend-icon
        @change="addFiles"
      ></v-file-input>

      <v-list flat v-if="documentsData != undefined && documentsData.length != 0">
        <v-list-item-group>
          <v-list-item v-for="(file, index) in documentsData" :key="index">
            <v-list-item-title v-text="file.name"></v-list-item-title>
            <v-icon @click="removeFile(index)">mdi-minus-circle-outline</v-icon>
          </v-list-item>
        </v-list-item-group>
      </v-list>
    </v-card-text>
  </v-card>
</template>

<script>
export default {
  props: ["documentsData"],

  data: () => ({
    files: []
  }),

  methods: {
    removeFile(index) {
      this.documentsData.splice(index, 1);
    },

    addFiles(documents) {
      if (documents.length === 0) return;

      let counter = 0;
      let formData = new FormData();

      documents.forEach(file => {
        for (let i = 0; i < this.documentsData.length; i++) {
          if (this.documentsData[i].name === file.name) {
            return;
          }
        }
        formData.append("files[]", file);
        counter++;
      });

      if (counter === 0) {
        this.files = [];
        return;
      }

      axios
        .post("/file", formData, {
          headers: {
            "Content-Type": "multipart/form-data"
          }
        })
        .then(response => {
          response.data.forEach(file => {
            this.documentsData.push(file);
          });
          this.files = [];
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
};
</script>