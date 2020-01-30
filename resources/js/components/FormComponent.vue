<template>
  <v-container fluid>
    <h3 align="center" v-if="$route.name === 'edit'">Изменение проекта</h3>
    <h3 align="center" v-else>Создание проекта</h3>

    <v-row>
      <v-col cols="6">
        <DealerComponent @dealer="saveDealer"></DealerComponent>
      </v-col>
      <v-col cols="6">
        <CustomerComponent @customer="saveCustomer"></CustomerComponent>
      </v-col>
      <v-col cols="12">
        <ProjectComponent v-bind:address_prop="address" @project="saveProject"></ProjectComponent>
      </v-col>
      <v-col cols="6">
        <OpponentComponent @opponents="saveOpponents"></OpponentComponent>
      </v-col>
      <v-col cols="6">
        <ProductComponent @products="saveProducts"></ProductComponent>
      </v-col>
    </v-row>
    <!-- <v-form ref="form" v-model="valid" lazy-validation> -->
    <v-btn
      v-if="$route.name === 'edit'"
      block
      color="indigo"
      outlined
      @click="validate"
    >Изменить форму</v-btn>
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
    console.log("FORM", this.$route.params.address);
    let newThis = this;
    if (this.$route.name === "edit") {
      this.mode = "edit";
      if (window.project == undefined) {
        axios
          .get("/project/" + this.$route.params.id + "/edit")
          .then(function(response) {
            console.log("AXIOS");
            console.log(response);
            for (let prop in newThis.dealer) {
              newThis.dealer[prop].data = response.data.dealer[prop];
            }
            for (let prop in newThis.form) {
              if (prop === "opponents") {
                response.data.opponents.forEach(item => {
                  newThis.form.opponents.data.push(item.name);
                });
              } else {
                newThis.form[prop].data = response.data[prop];
              }
            }
          })
          .catch(function(error) {
            console.log(error);
          });
      } else {
        console.log("BLADE");
        console.log(window.project);
        for (let prop in newThis.dealer) {
          newThis.dealer[prop].data = window.project.dealer[prop];
        }
        for (let prop in newThis.form) {
          if (prop === "opponents") {
            window.project.opponents.forEach(item => {
              newThis.form.opponents.data.push(item.name);
            });
          } else {
            newThis.form[prop].data = window.project[prop];
          }
        }
        window.project = undefined;
      }
    } else {
      if (this.$route.params.address) {
        console.log("SIGN");

        this.address = this.$route.params.address;
      }
    }
  },

  data: () => ({
    mode: "create",
    dialog: false,
    address: null,
    formData: {}
  }),

  methods: {
    addForm() {
      // let res = {
      //   dealer: 1,
      //   project: { name: "", address: "" },
      //   products: {
      //     1: { count: 5, price: 100 },
      //     25: { count: 5, price: 100 }
      //   },
      //   oponents: [1, 2, 3]
      // };
      // for (let prop in this.dealer) {
      //   res.dealer[prop] = this.dealer[prop].data;
      // }
      // for (let prop in this.form) {
      //   res.project[prop] = this.form[prop].data;
      // }

      axios
        .post("/project", this.formData)
        .then(response => {
          console.log(response);
          // newThis.$refs.form.reset();
          // newThis.form.opponents.opponent = "";
          // newThis.form.opponents.data = [];
          // newThis.dialog = false;
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