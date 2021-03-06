<template>
  <v-container fluid>
    <v-form ref="form" v-model="valid" lazy-validation>
      <h3 align="center">{{isEdit ? 'Изменение проекта' : 'Создание проекта'}}</h3>

      <v-row>
        <v-col cols="12" md="6">
          <DealerComponent
            v-if="(isEdit && project.dealer.id) || !isEdit"
            :dealerData="project.dealer"
            :isEdit="isEdit"
          ></DealerComponent>
        </v-col>
        <v-col cols="12" md="6">
          <CustomerComponent
            v-if="(isEdit && project.customer.id) || !isEdit"
            :customerData="project.customer"
            :isEdit="isEdit"
          ></CustomerComponent>
        </v-col>
        <v-col cols="12">
          <ProjectComponent :isEdit="isEdit" :projectData="project.project"></ProjectComponent>
        </v-col>
        <v-col cols="12">
          <FileComponent :documentsData="project.documents"></FileComponent>
        </v-col>
        <v-col cols="12" md="5">
          <OpponentComponent :opponentsData="project.opponents"></OpponentComponent>
        </v-col>
        <v-col cols="12" md="7">
          <ProductComponent :productsData="project.products"></ProductComponent>
        </v-col>
      </v-row>

      <v-btn
        block
        color="indigo"
        outlined
        @click="openDialog"
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

  data: () => ({
    alert: false,
    valid: true,
    dialog: false,
    errors: [],
    project: {
      project: {
        address: null,
        date: new Date().toISOString().substr(0, 10),
        time: new Date().toISOString().substr(0, 10),
        tender_date: null,
        isTenderWon: false,
        isClosed: false,
        close_date: null
      },
      dealer: {},
      customer: {},
      opponents: [],
      products: [],
      documents: []
    }
  }),

  computed: {
    isEdit() {
      return this.$route.name === "edit";
    }
  },

  mounted() {
    if (this.isEdit) {
      if (window.project == undefined) {
        axios
          .get("/project/" + this.$route.params.id + "/edit")
          .then(response => {
            this.project = response.data;
            this.project.products.forEach(item => {
              item.pivot.total = item.pivot.count * item.pivot.price;
            });
          })
          .catch(error => {
            console.log(error);
          });
      } else {
        this.project = window.project;
        this.project.products.forEach(item => {
          item.pivot.total = item.pivot.count * item.pivot.price;
        });
      }
    } else {
      if (this.$route.params.address) {
        this.project.project.address = this.$route.params.address;
      }
    }
  },

  methods: {
    addForm() {
      let request = this.prepareProject();
      this.sendRequest("post", "/project", request, () => {
        this.$router.push({
          name: "home"
        });
      });
    },

    editForm() {
      let request = this.prepareProject();
      this.sendRequest(
        "put",
        "/project/" + this.$route.params.id,
        request,
        () => {
          this.$router.go(-1);
        }
      );
    },

    prepareProject() {
      let request = {
        products: {}
      };

      request.project = this.project.project;
      request.project.kladrId = this.$route.params.kladrId;
      request.customer = this.project.customer;
      request.dealer = this.project.dealer;
      request.documents = this.project.documents.map(item => {
        return item.id;
      });
      request.opponents = this.project.opponents.map(item => {
        return item.id;
      });
      this.project.products.forEach(item => {
        request.products[item.id] = {
          count: item.pivot.count,
          price: item.pivot.price
        };
      });

      return request;
    },

    sendRequest(type, path, request, callback) {
      axios[type](path, request)
        .then(response => {
          this.dialog = false;
          callback();
        })
        .catch(error => {
          this.errors = error.response.data.errors;
          this.dialog = false;
          this.alert = true;
        });
    },

    validate() {
      return this.$refs.form.validate();
    },

    openDialog() {
      if (this.validate()) {
        this.dialog = true;
      }
    }
  }
};
</script>