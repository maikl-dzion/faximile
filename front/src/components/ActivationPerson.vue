<template>
    <v-dialog v-model="dialog" max-width="500px">

        <template v-slot:activator="{on}">
            <v-btn color="success" dark class="mb-2" v-on="on" style="border-radius:0px;">Активировать работника</v-btn>
        </template>

        <v-card>
            <v-card-title ><div class="headline"
                      style="font-style: italic; font-size: 15px !important; color:green"
                      >{{formTitle}}</div>
            </v-card-title>

            <v-card-text><v-container><v-row>

                        <v-col cols="12" sm="12" md="12">
                            <!--<input type="hidden" v-model="item.person_id = personItem.id">-->
                            <v-select
                                    label='Выбрать работника'
                                    v-model='personItem'
                                    :items='getUsers'
                                    item-value='id'
                                    item-text='username'
                                    return-object >
                            </v-select>
                        </v-col>

                        <!--<v-col cols="12" sm="12" md="12">-->
                            <!--<input type="hidden" v-model="item.company_id = companyItem.id">-->
                            <!--<v-select-->
                                    <!--label='Выбрать документ'-->
                                    <!--v-model='companyItem'-->
                                    <!--:items='companyList'-->
                                    <!--item-value='id'-->
                                    <!--item-text='company_name'-->
                                    <!--return-object >-->
                            <!--</v-select>-->
                        <!--</v-col>-->

            </v-row></v-container></v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                <v-btn color="blue darken-1" text @click="save">Послать приглашение</v-btn>
            </v-card-actions>
        </v-card>

    </v-dialog>

</template>


<script>
    export default {
        data: () => ({
            pageTitle : 'Активация работника',
            formTitle : 'Активация работника',

            personItem  : {},
            companyItem : {},
            companyList : [],
            modelItem : {
                "id": 0,

                "document_name"  : '',
                "company_name": "",
                "username"  : "",

                "user_state": 0,
                "doctype_id": 2,
                "person_id" : 2,
                "company_id": 3,
                "verify_state": 0,

                "login": "",
                "password"  : "",
                "role": 0,


            },
            item : {},
            itemIndex : -1,
            dialog : false,
        }),

        computed: {
            getUsers() {
                let users = [];
                for(var i in this.personsList) {
                    var user = this.personsList[i];
                    if(!user.user_state) {
                        users.push(user)
                    }
                }
                return users;
            }
        },

        created() {
            this.item = Object.assign({}, this.modelItem);
            this.getTableData('company', 'companyList');
            this.getTableData('person' , 'personsList');
        },

        watch: {
            dialog(val) {
                val || this.close();
            }
        },

        methods: {

            close() {
                this.dialog = false;
                this.$nextTick(() => {
                    this.item = Object.assign({}, this.modelItem);
                    this.itemIndex = -1;
                });
            },

            save() {
                const host = window.location.host + '/faximile/front/app/person-register-page/';
                this.personItem['register_page'] = host
                const apiUrl = 'post/person/activation';
                this.send(apiUrl, "post", this.personItem).then(response => {
                    let state = this.saveResponse(response, 'Приглашение отправлено работнику');
                    if(state) {
                        const eventName = 'activation_person';
                        this.sendEventBus(eventName, {'active' : state});
                        this.getTableData('person' , 'personsList');
                    }
                    this.close();
                });
            },
        }
    };
</script>

<style>

</style>
