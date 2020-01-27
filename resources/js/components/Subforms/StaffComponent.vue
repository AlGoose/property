<template>
  <div>
    <v-autocomplete
      v-model="agent"
      :items="agents"
      color="grey"
      label="Представитель"
      outlined
      item-text="name"
      item-value="name"
      return-object
    ></v-autocomplete>
    <p class="subtitle">Имя: {{agent ? agent.name : ''}}</p>
    <p class="subtitle">Телефон: {{agent ? agent.phone : ''}}</p>
    <p class="subtitle">Почта: {{agent ? agent.email : ''}}</p>

    <v-row justify="center">
      <v-dialog v-model="dialog" persistent max-width="600px">
        <template v-slot:activator="{ on }">
          <v-btn color="primary" dark v-on="on" outlined>Добавить представителя</v-btn>
        </template>
        <v-card>
          <v-card-title>
            <span class="headline">Добавить представителя</span>
          </v-card-title>
          <v-card-text>
            <v-row>
              <v-col cols="12">
                <v-text-field v-model="agentForm.name" label="Имя" outlined></v-text-field>
              </v-col>
              <v-col cols="12" md="6">
                <v-text-field v-model="agentForm.phone" label="Телефон" outlined></v-text-field>
              </v-col>
              <v-col cols="12" md="6">
                <v-text-field v-model="agentForm.email" label="Почта" outlined></v-text-field>
              </v-col>
            </v-row>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn outlined color="indigo" @click="closeDialog">Отмена</v-btn>
            <v-btn outlined color="indigo" @click="addAgent">Добавить</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>
  </div>
</template>

<script>
export default {
  props: ["dealer_id"],

  data: () => ({
    dialog: false,
    isLoading: false,
    agent: null,
    entries: [],
    agents: [
      { name: "5", phone: "5", email: "5" },
      { name: "1", phone: "1", email: "1" }
    ],
    agentForm: {
      name: null,
      phone: null,
      email: null
    }
  }),

  watch: {
    dealer_id(val) {
      let newThis = this;
      console.log(val);
      axios
        .get("/dealer/getStaff/1")
        .then(function(response) {
          console.log(response);
          newThis.agents = response.data;
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    agent(val) {
      console.log('StaffComponent', val);
      this.$emit('staff', val);
    }
  },

  methods: {
    addAgent() {
      this.agents.push(this.agentForm);
      this.agent = this.agentForm;
      this.dialog = false;
      this.agentForm = {
        name: null,
        phone: null,
        email: null
      };
    },

    closeDialog() {
      this.dialog = false;
      this.agentForm = {
        name: null,
        phone: null,
        email: null
      };
    }
  }
};
</script>

<style scoped>
p {
  color: black;
}
</style>