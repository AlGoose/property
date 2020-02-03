<template>
  <v-container fluid>
     <v-form
             ref="form"
             v-model="valid"
             lazy-validation>
    <h3 align="center" v-if="$route.name === 'edit'">Изменение проекта</h3>
    <h3 align="center" v-else>Создание проекта</h3>

    <v-row>
      <v-col cols="6">
        <DealerComponent :dealerData="testData.dealer" :isEdit="isEdit"  @dealer="saveDealer"></DealerComponent>
      </v-col>
      <v-col cols="6">
        <CustomerComponent :customerData="testData.customer" :isEdit="isEdit" @customer="saveCustomer"></CustomerComponent>
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
    <!-- <v-form ref="form" v-model="valid" lazy-validation> -->
    <v-btn v-if="$route.name === 'edit'" block color="indigo" @click="dialog = true" outlined>Изменить форму</v-btn>
    <v-btn v-else block color="indigo" outlined @click="dialog = true">Добавить форму</v-btn>
    <!-- </v-form> -->

    <v-row justify="center">
      <v-dialog v-model="dialog" width="210px">
        <v-card>
          <v-card-title>Все верно?</v-card-title>
          <v-card-actions>
            <v-btn depressed color="error" @click="dialog = false">Отмена</v-btn>
            <v-btn depressed color="success" @click="addForm">Добавить</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>
     </v-form>
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
      console.log('mounted')

    let newThis = this;
    if (this.$route.name === "edit") {
      this.isEdit = true;
      if (window.project == undefined) {
        axios
          .get("/project/" + this.$route.params.id + "/edit")
          .then(response => {
            console.log("AXIOS");
            console.log(response);

            this.testData = response.data;
          })
          .catch(error => {
            console.log(error);
          });
      } else {
        console.log("BLADE");
        console.log(window.project);
        this.testData = window.project;

      }
    } else {
      if (this.$route.params.address) {
        this.address = this.$route.params.address;
      }
    }
  },

  data: () => ({
    testData: {},
    isEdit: false,
    dialog: false,
    address: null,
    formData: {},
      valid: true,
  }),
    watch:{
        testData(val){
          console.log('change testData')
      }
    },

  methods: {
    addForm() {
        this.validate();
        if(!this.valid) return;
      axios
        .post("/project", this.formData)
        .then(response => {
          this.dialog = false;
          this.$router.push({
            name: "home"
          });
        })
        .catch(error => {
          console.log(error);
        });
    },
      validate () {
          if (this.$refs.form.validate()) {
              this.snackbar = true
          }
      },
    saveDealer(value) {
      this.formData.dealer = value;
      console.log("FormData | ", this.formData);
    },

    saveCustomer(value) {
      this.formData.customer = value;
      console.log("FormData | ", this.formData);
    },

    saveProject(value) {
      this.formData.project = value;
      console.log("FormData | ", this.formData);
    },

    saveOpponents(value) {
      this.formData.opponents = value;
      console.log("FormData | ", this.formData);
    },

    saveProducts(value) {
      this.formData.products = value;
      console.log("FormData | ", this.formData);
    }
  }
};
</script>