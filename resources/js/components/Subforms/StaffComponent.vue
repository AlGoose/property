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
    <p class="title font-weight-bold">Имя: {{agent ? agent.name : ''}}</p>
    <p class="title font-weight-bold">Телефон: {{agent ? agent.phone : ''}}</p>
    <p class="title font-weight-bold">Почта: {{agent ? agent.email : ''}}</p>

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
            <v-btn outlined color="indigo" @click="dialog = false">Отмена</v-btn>
            <v-btn outlined color="indigo" @click="addAgent">Добавить</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>
  </div>
</template>

<script>
export default {
  mounted() {
    console.log("JOPA");
  },
  data: () => ({
    dialog: false,
    isLoading: false,
    agent: null,
    search: null,
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

//   computed: {
//     agents() {
//       return [
//         { name: "5", phone: "5", email: "5" },
//         { name: "1", phone: "1", email: "1" }
//       ];
//     }
//   },

  watch: {
    search(val) {
      console.log(val);
      //   if (this.isLoading) return;
      //   this.isLoading = true;
      //   fetch("/addresses?address=" + val)
      //     .then(res => res.json())
      //     .then(res => {
      //       console.log(res);
      //       this.entries = res;
      //     })
      //     .catch(err => {
      //       console.log(err);
      //     })
      //     .finally(() => (this.isLoading = false));
    },

    agent(val) {}
  },

  methods: {
    addAgent() {
      this.agents.push(this.agentForm);
      this.agent = this.agentForm;
      this.dialog = false;
    }
  }
};
</script>