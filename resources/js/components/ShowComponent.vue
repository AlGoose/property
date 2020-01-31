<template>
    <div>
        <v-container>
            <!-- //TODO: Размеры уточнить -->
            <v-card outlined>
                <v-card-title>{{project.name.data}}</v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col cols="12" lg="6">
                            <v-card class="mx-auto">
                                <v-card-title>Проект</v-card-title>
                                <v-card-text>
                                    <v-list dense>
                                        <v-list-item two-line v-for="(item, i) in project" :key="'E'+ i">
                                            <v-list-item-content>
                                                <v-list-item-title>{{item.label}}:</v-list-item-title>
                                                <v-list-item-subtitle>
                                                    {{item.data}}
                                                </v-list-item-subtitle>
                                            </v-list-item-content>
                                        </v-list-item>
                                    </v-list>
                                </v-card-text>
                            </v-card>
                        </v-col>
                        <v-col cols="12" lg="6">
                            <v-card class="mx-auto">
                                <v-card-title>Конкуренты</v-card-title>
                                <v-card-text>
                                    <div class="list" v-for="(item, i) in opponents" :key="'F'+ i">
                                        <p>{{item.name}}</p>
                                    </div>
                                </v-card-text>
                            </v-card>
                        </v-col>
                        <v-col cols="12" lg="6">
                            <v-card class="mx-auto">
                                <v-card-title>Дилер</v-card-title>
                                <v-card-text>
                                    <div class="list" v-for="(item, i) in dealer" :key="'A'+ i">
                                        <p>{{item.label}} : {{item.data}}</p>
                                    </div>

                                    <div class="list" v-for="(item, i) in dealer_staff" :key="'B'+ i">
                                        <p>{{item.label}} : {{item.data}}</p>
                                    </div>
                                </v-card-text>
                            </v-card>
                        </v-col>

                        <v-col cols="12" lg="6">
                            <v-card class="mx-auto">
                                <v-card-title>Заказчик</v-card-title>
                                <v-card-text>
                                    <div class="list" v-for="(item, i) in customer" :key="'C'+ i">
                                        <p>{{item.label}} : {{item.data}}</p>
                                    </div>

                                    <div class="list" v-for="(item, i) in customer_staff" :key="'D'+ i">
                                        <p>{{item.label}} : {{item.data}}</p>
                                    </div>
                                </v-card-text>
                            </v-card>
                        </v-col>


                        <v-col cols="12" lg="12">
                            <v-card class="mx-auto">
                                <v-card-title>Детали</v-card-title>
                                <v-card-text>

                                    <div class="list" v-for="(item, i) in products" :key="'G'+ i">
                                        <p>{{item.name}}</p>
                                    </div>
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>

                </v-card-text>
                <v-card-actions class="row justify-content-center">
                    <v-btn class="ma-2" outlined color="indigo" @click="back">Назад</v-btn>
                    <v-btn
                            class="ma-2"
                            outlined
                            color="teal"
                            v-if="user_id === auth_id"
                            @click="edit"
                    >Редактировать
                    </v-btn>
                    <v-btn
                            class="ma-2"
                            outlined
                            color="error"
                            v-if="user_id === auth_id"
                            @click="validate"
                    >Удалить
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-container>

        <v-row justify="center">
            <v-dialog v-model="dialog" width="200px">
                <v-card>
                    <v-card-title>Точно удалить?</v-card-title>
                    <v-card-actions>
                        <v-btn depressed color="success" @click="dialog = false">Отмена</v-btn>
                        <v-btn depressed color="error" @click="remove">Удалить</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-row>
    </div>
</template>


<script>
    export default {
        mounted: function () {
            this.project_id = this.$route.params.id;
            this.auth_id = window.current_user.id;

            let form;

            if (window.project == undefined) {
                axios
                    .get("/project/" + this.project_id)
                    .then(response => {
                        this.initial(response.data);
                    })
                    .catch(error => {
                        console.log(error);
                    });
            } else {
                this.initial(window.project);
            }
        },

        data() {
            return {
                dialog: false,
                auth_id: "",
                user_id: "",
                project_id: "",
                dealer: {
                    inn: {
                        label: "ИНН",
                        data: ""
                    },
                    kpp: {
                        label: "КПП",
                        data: ""
                    },
                    name: {
                        label: "Имя",
                        data: ""
                    },
                    address: {
                        label: "Адрес",
                        data: ""
                    }
                },
                dealer_staff: {
                    name: {
                        label: "Представитель",
                        data: ""
                    },
                    phone: {
                        label: "Телефон",
                        data: ""
                    },
                    email: {
                        label: "Почта",
                        data: ""
                    }
                },
                customer: {
                    inn: {
                        label: "ИНН",
                        data: ""
                    },
                    kpp: {
                        label: "КПП",
                        data: ""
                    },
                    name: {
                        label: "Имя",
                        data: ""
                    },
                    address: {
                        label: "Адрес",
                        data: ""
                    }
                },
                customer_staff: {
                    name: {
                        label: "Представитель",
                        data: ""
                    },
                    phone: {
                        label: "Телефон",
                        data: ""
                    },
                    email: {
                        label: "Почта",
                        data: ""
                    }
                },
                project: {
                    name: {
                        label: "Название",
                        data: ""
                    },
                    address: {
                        label: "Адрес",
                        data: ""
                    },
                    work: {
                        label: "Работа",
                        data: ""
                    },
                    date: {
                        label: "Дата",
                        data: ""
                    },
                    time: {
                        label: "Время",
                        data: ""
                    }
                },
                opponents: [],
                products: []
            };
        },

        methods: {
            initial(form) {
                for (let prop in this.dealer) {
                    this.dealer[prop].data = form.dealer[prop];
                }
                for (let prop in this.dealer_staff) {
                    this.dealer_staff[prop].data = form.dealer_staff[prop];
                }

                for (let prop in this.customer) {
                    this.customer[prop].data = form.customer[prop];
                }
                for (let prop in this.customer_staff) {
                    this.customer_staff[prop].data = form.customer_staff[prop];
                }

                for (let prop in this.project) {
                    this.project[prop].data = form[prop];
                }

                this.opponents = form.opponents;
                this.products = form.products; //FIXME: Где количество с ценами? Pivot таблица не вся отдается

                this.user_id = form.user_id;
                window.project = undefined;
            },

            remove() {
                axios
                    .delete("/project/" + this.project_id)
                    .then(response => {
                        this.$router.push("/project");
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },

            edit() {
                console.log(window.project);
                this.$router.push({
                    name: "edit",
                    params: {mode: "edit"}
                });
            },

            back() {
                this.$router.push("/project");
            },

            validate() {
                this.dialog = true;
            }
        }
    };
</script>

<style scoped>
    * {
        padding-bottom: 14px;
    }

    .list {
        /*height: 30px;*/
    }

    p {
        color: black;
        margin-bottom: 0;
    }
</style>
