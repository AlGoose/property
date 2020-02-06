<template>
  <v-container fluid>
    <v-form ref="form" v-model="valid" lazy-validation>
      <h3 align="center">{{isEdit ? 'Изменение проекта' : 'Создание проекта'}}</h3>

      <v-row>
        <v-col cols="6">
          <DealerComponent :dealerData="testData.dealer" :isEdit="isEdit" @dealer="saveDealer"></DealerComponent>
        </v-col>
        <v-col cols="6">
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
          ></ProjectComponent>
        </v-col>
        <v-col cols="5">
          <OpponentComponent :opponentsData="testData.opponents" @opponents="saveOpponents"></OpponentComponent>
        </v-col>
        <v-col cols="7">
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
              <v-btn depressed color="success" @click="isEdit ? editForm() : addForm()">{{isEdit ? 'Изменить' : 'Добавить'}}</v-btn>
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

export default {
  components: {
    DealerComponent,
    CustomerComponent,
    OpponentComponent,
    ProductComponent,
    ProjectComponent
  },
  directives: { mask },

  mounted() {
    if (this.$route.name === "edit") {
      this.isEdit = true;
      if (window.project == undefined) {
        axios
          .get("/project/" + this.$route.params.id + "/edit")
          .then(response => {
            // console.log("AXIOS");
            // console.log(response);

            this.testData = response.data;
          })
          .catch(error => {
            console.log(error);
          });
      } else {
        // console.log("BLADE");
        // console.log(window.project);
        this.testData = window.project;
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
    testData: {},
    isEdit: false,
    dialog: false,
    address: null,
    formData: {},
    valid: true
  }),

  methods: {
    addForm() {
      // console.log('ADDFORM');
      this.formData.project.kladrId = this.$route.params.kladrId;
      axios
        .post("/project", this.formData)
        .then(response => {
          // console.log(response);
          this.dialog = false;
          this.$router.push({
            name: "home"
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
      // console.log('EDITFORM');
      axios
        .put("/project/" + this.$route.params.id, this.formData)
        .then(response => {
          this.dialog = false;
          this.$router.go(-1);
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
    }
  }
};
</script>