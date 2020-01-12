<template>
  <v-container fluid>
    <v-row>
      <v-col cols="12" sm="12">
        <v-form ref="form" v-model="valid" lazy-validation>
          <v-text-field
            v-model="form_name"
            label="Название объекта"
            hint="Название объекта"
            outlined
            clearable
            required
            :rules="[v => !!v || 'Name is required']"
          ></v-text-field>
          <v-text-field
            v-model="form_address"
            label="Адрес объекта"
            hint="Адрес объекта"
            outlined
            clearable
            required
            :rules="[v => !!v || 'Address is required']"
          ></v-text-field>
          <v-text-field
            v-model="form_customer"
            label="Заказчик"
            hint="Заказчик"
            outlined
            clearable
            required
            :rules="[v => !!v || 'Customer is required']"
          ></v-text-field>
          <v-text-field
            v-model="form_opponents"
            label="Конкуренты"
            hint="Конкуренты (если есть)"
            outlined
            clearable
          ></v-text-field>
          <v-text-field
            v-model="form_contacts"
            label="Контактные данные"
            hint="Контактные данные"
            outlined
            clearable
            required
            :rules="[v => !!v || 'Contacts are required']"
          ></v-text-field>
          <v-text-field
            v-model="form_time"
            label="Срок реализации проекта"
            hint="Срок реализации проекта"
            outlined
            clearable
            required
            :rules="[v => !!v || 'Time is required']"
          ></v-text-field>
          <v-text-field
            v-model="form_manager"
            label="Менеджер"
            hint="Менеджер"
            outlined
            clearable
            required
            :rules="[v => !!v || 'Manager is required']"
          ></v-text-field>
          <v-btn block color="indigo" outlined @click="validate">Добавить форму</v-btn>
        </v-form>
      </v-col>
    </v-row>

    <v-row justify="center">
      <v-dialog v-model="dialog" persistent scrollable width="600px">
        <template v-slot:activator="{ on }">
          <!-- <v-btn block color="indigo" outlined v-on="on">Добавить форму</v-btn> -->
          <v-btn block color="indigo" outlined v-on="on">Добавить форму</v-btn>
          <!-- TODO: Как кнопку лучше сделать? -->
        </template>
        <v-card>
          <v-card-title>Все верно?</v-card-title>
          <v-divider></v-divider>
          <v-card-text style="height: 300px;">
            <p>Название объекта: {{ form_name }}</p>
            <p>Адрес объекта: {{ form_address }}</p>
            <p>Заказчик: {{ form_customer }}</p>
            <p>Конкуренты: {{ form_opponents }}</p>
            <p>Контактные данные: {{ form_contacts }}</p>
            <p>Срок реализации проекта: {{ form_time }}</p>
            <p>Менеджер: {{ form_manager }}</p>
            <p>ДОБАВИТЬ ОСТАЛЬНОЕ</p>
          </v-card-text>
          <v-divider></v-divider>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn depressed color="error" @click="dialog = false">Отмена</v-btn>
            <v-btn depressed color="success" @click="addForm">Добавить</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>
  </v-container>
</template>

<script>
export default {
  data: () => ({
    dialog: false,
    valid: true,
    form_name: "",
    form_address: "",
    form_customer: "",
    form_opponents: "",
    form_contacts: "",
    form_time: "",
    form_manager: "",
  }),

  methods: {
    addForm() {
      this.dialog = false;
    },

    validate() {
      if (this.$refs.form.validate()) {
        // this.snackbar = true;
        this.dialog = true;
      }
    }
  }
};
</script>