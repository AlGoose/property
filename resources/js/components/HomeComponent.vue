<template>
  <v-container fluid>
    <v-row>
      <v-col cols="12" sm="12">
        <v-text-field
          v-model="address"
          label="Введите адрес объекта"
          hint="Адрес объекта"
          outlined
          clearable
        ></v-text-field>
        <v-btn block color="indigo" outlined href="./project/create">Добавить форму</v-btn>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  data: () => ({
    address: ""
  }),

  methods: {}
};
</script>