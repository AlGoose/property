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
                        <v-form ref="form" v-model="valid" lazy-validation>
                            <v-row>
                                <v-col cols="12">
                                    <v-text-field
                                            v-model="agentForm.name"
                                            label="Имя"
                                            outlined
                                            :rules="[v => !!v || 'Введите имя']"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" md="6">

                                    <v-text-field
                                            v-model="agentForm.phone"
                                            label="Телефон"
                                            outlined
                                            v-mask="'+7(###)###-##-##'"
                                            validate-on-blur
                                            :rules="phoneRules"
                                    >
                                    </v-text-field>
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-text-field
                                            v-model="agentForm.email"
                                            label="Почта"
                                            outlined
                                            :rules="emailRules"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                        </v-form>
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
    import {TheMask} from 'vue-the-mask'
    import {mask} from 'vue-the-mask'
    export default {
        props: ["entity", "mode"],
        components: {TheMask},
        directives: {mask},
        data: () => ({
            phoneRules: [
                v => !!v || "Введите номер телефона",
                v => v.length == 16 || "Неверный формат номера"
            ],
            emailRules: [
                v => !!v || "Введите почту",
                v =>
                    /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(
                        v
                    ) || "Неверный формат почты"
            ],
            valid: true,
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
                if (this.entity.current_staff !== undefined) {
                    this.agent_id = this.entity.current_staff.id;
                }
            }
        },
        watch: {
            entity(val) {

                if (this.entity.contacts === undefined) {
                    this.agent_id = null;
                    (this.agents = []), (this.dialog = true);
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
                if (this.$refs.form.validate()) {
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
                }
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