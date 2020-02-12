<template>
  <v-container fluid>
    <v-form ref="form" v-model="valid" lazy-validation>
      <h3 align="center">{{isEdit ? 'Изменение проекта' : 'Создание проекта'}}</h3>

      <v-row>
        <v-col cols="12" md="6">
          <DealerComponent :dealerData="testData.dealer" :isEdit="isEdit" @dealer="saveDealer"></DealerComponent>
        </v-col>
        <v-col cols="12" md="6">
          <CustomerComponent
            :customerData="testData.customer"
            :isEdit="isEdit"
            @customer="saveCustomer"
          ></CustomerComponent>
        </v-col>
        <v-col cols="12">
          <ProjectComponent
            :address_prop="address"
            :isEdit="isEdit"
            :projectData="testData"
            @project="saveProject"
            @files="saveFiles"
          ></ProjectComponent>
        </v-col>
        <v-col cols="12">
          <FileComponent :filesData="testData.files" @files="saveFiles"></FileComponent>
        </v-col>
        <v-col cols="12" md="5">
          <OpponentComponent :opponentsData="testData.opponents" @opponents="saveOpponents"></OpponentComponent>
        </v-col>
        <v-col cols="12" md="7">
          <ProductComponent :productsData="testData.products" @products="saveProducts"></ProductComponent>
        </v-col>
      </v-row>

      <v-btn
        block
        color="indigo"
        outlined
        @click="validate"
      >{{isEdit ? 'Изменить проект' : 'Добавить проект'}}</v-btn>

      <v-row justify="center">
        <v-dialog v-model="dialog" width="250px">
          <v-card>
            <v-card-title>Все верно?</v-card-title>
            <v-card-actions class="justify-center">
              <v-btn depressed color="error" @click="dialog = false">Отмена</v-btn>
              <v-btn
                depressed
                color="success"
                @click="isEdit ? editForm() : addForm()"
              >{{isEdit ? 'Изменить' : 'Добавить'}}</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-row>
    </v-form>

    <div class="text-center">
      <v-dialog v-model="alert" width="500">
        <v-card>
          <v-card-title class="headline red lighten-2" primary-title>Ошибка</v-card-title>
          <v-card-text>
            <ul>
              <li v-for="(item, i) in errors" :key="i">{{item[0]}}</li>
            </ul>
          </v-card-text>
          <v-divider></v-divider>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="primary" text @click="alert = false">I accept</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>
  </v-container>
</template>

<script>
import { mask } from "vue-the-mask";
import DealerComponent from "./Subforms/DealerComponent";
import CustomerComponent from "./Subforms/CustomerComponent";
import OpponentComponent from "./Subforms/OpponentComponent";
import ProductComponent from "./Subforms/ProductComponent";
import ProjectComponent from "./Subforms/ProjectComponent";
import FileComponent from "./Subforms/FileComponent";

export default {
  components: {
    DealerComponent,
    CustomerComponent,
    OpponentComponent,
    ProductComponent,
    ProjectComponent,
    FileComponent
  },
  directives: { mask },

  mounted() {
    if (this.$route.name === "edit") {
      console.log("EDIT");
      this.isEdit = true;
      if (window.project == undefined) {
        console.log("AXIOS");

        axios
          .get("/project/" + this.$route.params.id + "/edit")
          .then(response => {
            this.testData = response.data;
            console.log("EDIT_MODE_DATA", this.testData);
            this.address = this.testData.address;
            console.log("FORM_ADDRESS", this.address);
          })
          .catch(error => {
            console.log(error);
          });
      } else {
        console.log("BLADE");
        this.testData = window.project;
        this.address = this.testData.address;
        console.log("FORM_ADDRESS", this.address);
      }
    } else {
      if (this.$route.params.address) {
        this.address = this.$route.params.address;
      }
    }
  },

  data: () => ({
    alert: false,
    errors: [],
    testData: {
      date: new Date().toISOString().substr(0, 10),
      time: new Date().toISOString().substr(0, 10),
      tender_date: null
    },
    isEdit: false,
    dialog: false,
    address: null,
    formData: {},
    formFiles: [],
    valid: true
  }),

  methods: {
    addForm() {
      this.formData.project.kladrId = this.$route.params.kladrId;
      axios
        .post("/project", this.formData)
        .then(response => {
          console.log(response.data);

          let formData = new FormData();
          this.formFiles.forEach(file => {
            formData.append("files[]", file);
          });

          axios
            .post("/project/" + response.data + "/files", formData, {
              headers: {
                "Content-Type": "multipart/form-data"
              }
            })
            .then(response => {
              console.log(response.data);
              this.dialog = false;
              // this.$router.push({
              //   name: "home"
              // });
            })
            .catch(error => {
              console.log(error);
            });
        })
        .catch(error => {
          console.log("ERROOOOOOOR", error.response);
          this.errors = error.response.data.errors;
          this.dialog = false;
          this.alert = true;
        });
    },

    editForm() {
      axios
        .put("/project/" + this.$route.params.id + "/files", this.formData)
        .then(response => {
          let dataForm = new FormData();
          this.formFiles.forEach(file => {
            console.log(file.name);
            dataForm.append("files[]", file);
          });

          axios
            .put("/project/" + this.$route.params.id + "/files", dataForm, {
              headers: {
                "Content-Type": "multipart/form-data"
              }
            })
            .then(response => {
              this.dialog = false;
              this.$router.go(-1);
            })
            .catch(error => {
              console.log(error);
            });
        })
        .catch(error => {
          console.log(error);
        });
    },

    validate() {
      if (this.$refs.form.validate()) {
        this.dialog = true;
      }
    },

    saveDealer(value) {
      this.formData.dealer = value;
      // console.log("FormData | ", this.formData);
    },

    saveCustomer(value) {
      this.formData.customer = value;
      // console.log("FormData | ", this.formData);
    },

    saveProject(value) {
      this.formData.project = value;
      // console.log("FormData | ", this.formData);
    },

    saveOpponents(value) {
      this.formData.opponents = value;
      // console.log("FormData | ", this.formData);
    },

    saveProducts(value) {
      this.formData.products = value;
      // console.log("FormData | ", this.formData);
    },

    saveFiles(value) {
      this.formFiles = value;
      // console.log("FormFiles | ", this.formFiles);
    }
  }
};
</script>