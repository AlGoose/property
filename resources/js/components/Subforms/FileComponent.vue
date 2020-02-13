<template>
  <v-card class="mx-auto card" height="100%">
    <v-card-title>Документы</v-card-title>
    <v-card-text>
      <v-file-input
        v-model="tempFiles"
        label="Добавить файл(-ы)"
        multiple
        outlined
        :show-size="1000"
        prepend-icon
        @change="addFiles"
      ></v-file-input>

      <v-list flat v-if="files != undefined && files.length != 0">
        <v-list-item-group>
          <v-list-item v-for="(file, index) in files" :key="index">
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
  props: ["filesData"],

  data: () => ({
    tempFiles: [],
    files: [],
    ids: []
  }),

  watch: {
    filesData(value) {
      console.log(value);
      //   val.forEach(item => {
      //     this.opponents.push(item.name);
      //     this.ids.push(item.id);
      //   });
    },

    ids(value) {
      console.log('BOBER');
      console.log(value);
        this.$emit("files", value);
    }
  },

  methods: {
    removeFile(index) {
      this.files.splice(index, 1);
      //   this.sendData();
    },

    addFiles(files) {
      if (files.length === 0) return;

      let counter = 0;
      let formData = new FormData();

      files.forEach(file => {
        for (let i = 0; i < this.files.length; i++) {
          if (this.files[i].name === file.name) {
            return;
          }
        }
        formData.append("files[]", file);
        counter++;
      });

      if (counter === 0) {
        this.tempFiles = [];
        return;
      }

      axios
        .post("/file", formData, {
          headers: {
            "Content-Type": "multipart/form-data"
          }
        })
        .then(response => {
          // console.log(response.data);
          this.files = response.data;
          this.files.forEach(file => {
            this.ids.push(file.id);
          });
          this.tempFiles = [];
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
};
</script>