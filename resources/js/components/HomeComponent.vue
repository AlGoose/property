<template>
  <v-container fluid>
    <v-card class="mx-auto">
      <v-card-title>Поиск объекта</v-card-title>
      <v-card-text>
        <v-row>
          <v-col cols="12" sm="6">
            <v-autocomplete
              v-model="regionData.selected"
              :items="regionData.regions"
              :search-input.sync="regionSearch"
              color="grey"
              label="Область / Край"
              outlined
              hide-details
              hide-no-data
              no-filter
              return-object
              item-text="name"
              :loading="isLoading"
              :disabled="isDisabled"
            >
              <template v-slot:item="{ item }">{{item.name}} {{item.typeShort}}</template>
              <template v-slot:selection="{ item }">{{item.name}} {{item.typeShort}}</template>
            </v-autocomplete>
          </v-col>

          <v-col cols="12" sm="6">
            <v-autocomplete
              v-model="districtData.selected"
              :items="districtData.districts"
              :search-input.sync="districtSearch"
              color="grey"
              label="Регион / Район"
              outlined
              hide-details
              hide-no-data
              no-filter
              return-object
              item-text="name"
              :disabled="isDisabled"
              :loading="isLoading"
            >
              <template v-slot:item="{ item }">{{item.name}} {{item.typeShort}}</template>
              <template v-slot:selection="{ item }">{{item.name}} {{item.typeShort}}</template>
            </v-autocomplete>
          </v-col>

          <v-col cols="12" sm="6">
            <v-autocomplete
              v-model="cityData.selected"
              :items="cityData.cities"
              :search-input.sync="citySearch"
              color="grey"
              label="Город / Населенный пункт"
              outlined
              hide-details
              hide-no-data
              no-filter
              return-object
              item-text="name"
              :disabled="isDisabled"
              :loading="isLoading"
            >
              <template v-slot:item="{ item }">{{item.typeShort}}.{{item.name}}</template>
              <template v-slot:selection="{ item }">{{item.typeShort}}.{{item.name}}</template>
            </v-autocomplete>
          </v-col>

          <v-col cols="12" sm="6">
            <v-autocomplete
              v-model="streetData.selected"
              :items="streetData.streets"
              :search-input.sync="streetSearch"
              color="grey"
              label="Улица"
              hint="Для поиска улицы сначала нужно выбрать Город / Населенный пункт"
              outlined
              hide-no-data
              hide-details="auto"
              no-filter
              return-object
              item-text="name"
              :disabled="isDisabled"
              :loading="isLoading"
            >
              <template v-slot:item="{ item }">{{item.typeShort}}.{{item.name}}</template>
              <template v-slot:selection="{ item }">{{item.typeShort}}.{{item.name}}</template>
            </v-autocomplete>
          </v-col>

          <v-col cols="12" sm="4">
            <v-autocomplete
              v-model="buildingData.selected"
              :items="buildingData.buildings"
              :search-input.sync="buildingSearch"
              color="grey"
              label="Дом"
              hint="Для поиска дома сначала нужно выбрать улицу"
              outlined
              hide-no-data
              hide-details="auto"
              no-filter
              return-object
              item-text="name"
              :disabled="isDisabled"
              :loading="isLoading"
            >
              <template v-slot:item="{ item }">{{item.name}}</template>
              <template v-slot:selection="{ item }">{{item.name}}</template>
            </v-autocomplete>
          </v-col>

          <v-col cols="12" sm="4">
            <v-switch label="Ввести адрес вручную" v-model="isDisabled"></v-switch>
          </v-col>

          <v-col cols="12" sm="12" v-if="isDisabled">
            <p class="title">Введите адрес вручную</p>
            <v-text-field v-model="address" single-line solo>
              <template v-slot:prepend-inner>{{fullAddress}}</template>
            </v-text-field>
          </v-col>
        </v-row>

        <v-expansion-panels multiple focusable>
          <v-expansion-panel v-for="(item, i) in entries" :key="i">
            <v-expansion-panel-header class="title">{{item.address}}</v-expansion-panel-header>
            <v-expansion-panel-content>
              <p class="title">Название</p>
              <p class="body-1">{{item.name}}</p>
              <p class="title">Срок</p>
              <p class="body-1">{{item.date}}</p>
              <p class="title">Дата создания</p>
              <p class="body-1">{{item.time}}</p>
              <v-checkbox v-model="selected" label="Проверено?" :value="i"></v-checkbox>
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-expansion-panels>

        <v-btn
          :disabled="addressLength"
          block
          color="indigo"
          outlined
          @click="createForm"
        >Добавить форму</v-btn>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script>
export default {
  data: () => ({
    regionData: {
      regions: [],
      selected: null,
      regionId: null
    },
    districtData: {
      districts: [],
      selected: null,
      districtId: null
    },
    cityData: {
      cities: [],
      selected: null,
      cityId: null
    },
    streetData: {
      streets: [],
      selected: null,
      streetId: null
    },
    buildingData: {
      buildings: [],
      selected: null,
      buildingId: null
    },
    regionSearch: null,
    districtSearch: null,
    citySearch: null,
    streetSearch: null,
    buildingSearch: null,
    selected: [],
    address: "",
    textLimit: 60,
    entries: [],
    isLoading: false,
    isDisabled: false,
    model: null,
    search: null
  }),

  created() {
    this.inputRegion = window.debounce(this.searchRegion, 500);
    this.inputDistrict = window.debounce(this.searchDistrict, 500);
    this.inputCity = window.debounce(this.searchCity, 500);
    this.inputStreet = window.debounce(this.searchStreet, 500);
    this.inputBuilding = window.debounce(this.searchBuilding, 500);
  },

  computed: {
    addressLength() {
      return !(this.entries.length === this.selected.length);
    },

    fullAddress() {
      let fullAddress = "";

      if (
        this.regionData.selected != null &&
        this.regionData.selected != undefined
      ) {
        fullAddress +=
          this.regionData.selected.name +
          " " +
          this.regionData.selected.typeShort +
          ", ";
      }

      if (
        this.districtData.selected != null &&
        this.districtData.selected != undefined
      ) {
        fullAddress +=
          this.districtData.selected.name +
          " " +
          this.districtData.selected.typeShort +
          ", ";
      }

      if (
        this.cityData.selected != null &&
        this.cityData.selected != undefined
      ) {
        fullAddress +=
          this.cityData.selected.typeShort +
          " " +
          this.cityData.selected.name +
          ", ";
      }

      if (
        this.streetData.selected != null &&
        this.streetData.selected != undefined
      ) {
        fullAddress +=
          this.streetData.selected.typeShort +
          " " +
          this.streetData.selected.name +
          ", ";
      }

      if (
        this.buildingData.selected != null &&
        this.buildingData.selected != undefined
      ) {
        fullAddress += this.buildingData.selected.name + " ";
      }

      return fullAddress;
    }
  },

  watch: {
    regionSearch() {
      this.inputRegion();
    },

    districtSearch() {
      this.inputDistrict();
    },

    citySearch() {
      this.inputCity();
    },

    streetSearch() {
      this.inputStreet();
    },

    buildingSearch() {
      this.inputBuilding();
    }
  },

  methods: {
    createForm() {
      let address = this.fullAddress + this.address;
      this.$router.push({
        name: "form",
        params: { address: address }
      });
    },

    // searchAddress() {
    //   axios
    //     .post("/addresses", { address: this.address })
    //     .then(response => {
    //       this.entries = response.data;
    //     })
    //     .catch(error => {
    //       console.log(error);
    //     });
    // }

    searchRegion() {
      console.log("REGION");
      if (this.regionSearch === null) return;

      axios
        .post("/kladr", {
          contentType: "region",
          query: this.regionSearch
        })
        .then(response => {
          console.log(response.data.result);
          this.regionData.regions = response.data.result;
        })
        .catch(error => {
          console.log(error);
        });
    },

    searchDistrict() {
      console.log("DISTRICT");
      if (this.districtSearch === null) return;

      axios
        .post("/kladr", {
          contentType: "district",
          query: this.districtSearch
        })
        .then(response => {
          console.log(response.data.result);
          this.districtData.districts = response.data.result;
        })
        .catch(error => {
          console.log(error);
        });
    },

    searchCity() {
      console.log("CITY");
      if (this.citySearch === null) return;

      axios
        .post("/kladr", {
          contentType: "city",
          query: this.citySearch
        })
        .then(response => {
          console.log(response.data.result);
          this.cityData.cities = response.data.result;
        })
        .catch(error => {
          console.log(error);
        });
    },

    searchStreet() {
      console.log("STREET");
      if (
        this.streetSearch === null ||
        this.cityData.selected === null ||
        this.cityData.selected === undefined
      )
        return;

      axios
        .post("/kladr/street", {
          contentType: "street",
          cityId: this.cityData.selected.id,
          query: this.streetSearch
        })
        .then(response => {
          console.log(response.data.result);
          this.streetData.streets = response.data.result;
        })
        .catch(error => {
          console.log(error);
        });
    },

    searchBuilding() {
      console.log("BUILDING");
      if (
        this.buildingSearch === null ||
        this.streetData.selected === null ||
        this.streetData.selected === undefined
      )
        return;

      axios
        .post("/kladr/building", {
          contentType: "building",
          streetId: this.streetData.selected.id,
          query: this.buildingSearch
        })
        .then(response => {
          console.log(response.data.result);
          this.buildingData.buildings = response.data.result;
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
};
</script>

<style scoped>
p {
  color: black;
}
</style>