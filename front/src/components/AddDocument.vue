<template>
    <v-dialog v-model="dialog" max-width="500px">

            <template v-slot:activator="{on}">
                <v-btn color="success" dark class="mb-2" v-on="on" style="border-radius:0px;">Новый документ</v-btn>
            </template>

            <v-card>
                <v-card-title >
                    <div class="headline"
                         style="font-style: italic; font-size: 15px !important; color:green"
                     >{{formTitle}}</div>
                </v-card-title>

                <v-card-text>
                    <v-container>

                        <v-row>

                            <v-col cols="12" sm="12" md="12">
                                <input type="hidden" v-model="item.person_id = personItem.id">
                                <v-select
                                        label='Выбрать работника'
                                        v-model='personItem'
                                        :items='personsList'
                                        item-value='id'
                                        item-text='username'
                                        return-object >
                                </v-select>
                            </v-col>

                            <v-col cols="12" sm="12" md="12">
                                    <input type="hidden" v-model="item.doctype_id = docTypeItem.id">
                                    <v-select
                                            label='Выбрать документ'
                                            v-model='docTypeItem'
                                            :items='docTypeList'
                                            item-value='id'
                                            item-text='docname'
                                            return-object >
                                    </v-select>
                            </v-col>

                            <!--<v-col cols="12" sm="12" md="12">-->
                                <!--<input type="hidden" v-model="item.company_id = companyItem.id">-->
                                <!--<v-select-->
                                        <!--label='Выбрать компанию'-->
                                        <!--v-model='companyItem'-->
                                        <!--:items='companyList'-->
                                        <!--item-value='id'-->
                                        <!--item-text='company_name'-->
                                        <!--return-object >-->
                                <!--</v-select>-->
                            <!--</v-col>-->

                        </v-row>
                    </v-container>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                    <v-btn color="blue darken-1" text @click="save">Save</v-btn>
                </v-card-actions>
            </v-card>

        </v-dialog>

</template>


<script>
    export default {
        data: () => ({
            pageTitle : 'Добавить новый документ',
            companyItem : {},
            personItem  : {},
            docTypeItem : {},
            docTypeList : [],
            modelItem : {
                "id": 0,
                "doc_status": 0,
                "doc_name"  : '',
                "create_at" : "",
                "update_at" : '',
                "doctype_id": 2,
                "person_id" : 2,
                "company_id": 3,
                "verify_status": 0,
                "username"  : "",
                "login": "",
                "password"  : "",
                "role": 0,
                "company_name": "",
                "docname": "",
            },
            item : {},
            itemIndex : -1,
            dialog : false,
        }),

        computed: {
            formTitle() {
                return this.itemIndex === -1 ? "Добавить документ" : "Редактировать документ";
            }
        },

        watch: {
            dialog(val) {
                val || this.close();
            }
        },

        created() {
            this.item = Object.assign({}, this.modelItem);
            // this.getPersonDocuments();
            this.getTableData('doctype', 'docTypeList');
            this.getTableData('person' , 'personsList');
            // this.getCompanyDocuments();
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
                const apiUrl = 'documents/add/document';
                this.send(apiUrl, "post", this.item).then(response => {
                     this.saveResponse(response);
                     this.close();
                });
            },
        }
    };
</script>

<style>
    .v-data-table-header {
        background: gray;
    }

    .v-data-table-header tr th span{
        color:white
    }
</style>
