<template>
  <div>
    <template v-if="entity.contacts||agent_id">
      <v-autocomplete
        v-model="agent_id"
        :items="agents"
        color="grey"
        label="Представитель"
        outlined
        item-text="name"
        item-value="id"
      ></v-autocomplete>
      <p class="subtitle">Имя: {{agent ? agent.name : ''}}</p>
      <p class="subtitle">Телефон: {{agent ? agent.phone : ''}}</p>
      <p class="subtitle">Почта: {{agent ? agent.email : ''}}</p>
    </template>
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
  props: ["entity", "mode"],

  data: () => ({
    dialog: false,
    isLoading: false,
    agent_id: null,
    agents: [],

    agentForm: {
      name: null,
      phone: null,
      email: null
    }
  }),
  computed: {
    agent() {
      return this.agents.filter(item => this.agent_id === item.id).pop();
    }
  },
  mounted() {
    if (this.entity.contacts === undefined) {
      this.dialog = true;
    } else {
      this.agents = this.entity.contacts;
    }
  },
  watch: {
    entity(val) {
      if (this.entity.contacts === undefined) {
        this.agent_id = null;
        this.agents = [],
        this.dialog = true;
      } else {
        this.agents = this.entity.contacts;
      }
    },

    agent_id(val) {
      this.$emit("staff", val);
    }
  },

  methods: {
    addAgent() {
      this.dialog = false;

      axios.post("/staff", this.agentForm).then(request => {
        this.agents.push(request.data);
        this.agent_id = request.data.id;
      }),
        (this.agentForm = {
          name: null,
          phone: null,
          email: null
        });
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