<template>
    <v-dialog v-model="dialog" max-width="500px">

        <template v-slot:activator="{on}">
            <v-btn color="success" dark class="mb-2" v-on="on" style="border-radius:0px;">Добавить компанию</v-btn>
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
                            <v-text-field v-model="item.company_name" label="Имя компании" ></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="12" md="12">
                            <v-text-field v-model="item.inn" label="ИНН" required></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="12" md="12">
                            <v-text-field v-model="item.phone" label="Телефон" required></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="12" md="12">
                            <v-text-field v-model="item.email" label="Email" ></v-text-field>
                        </v-col>

                    </v-row>
                </v-container>
            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                <v-btn color="blue darken-1" text @click="saveItem(saveUrl, item)">Save</v-btn>
            </v-card-actions>
        </v-card>

    </v-dialog>

</template>


<script>
    export default {
        data: () => ({
            saveUrl   : 'documents/add/company',
            pageTitle : 'Добавить новую компанию',
            item      : {},
            itemIndex : -1,
            dialog    : false,

            modelItem : {
                "id": 0,
                "company_name": '',
                "inn": "",
                "phone": "",
                "email": "",
                "post": "",
            },

        }),

        computed: {
            formTitle() {
                return this.itemIndex === -1 ? "Добавить компанию" : "Редактировать компанию";
            }
        },

        watch: {
            dialog(val) {
                val || this.close();
            }
        },

        created() {
            this.item = Object.assign({}, this.modelItem);
            // this.getTableData('company', 'companyList');
            // this.getPersonDocuments();
            // this.getTableData('doctype', 'docTypeList');
            // this.getTableData('person' , 'personsList');
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

            // save() {
            //     const apiUrl = 'documents/add/company';
            //     this.save(apiUrl, this.item);
            // },
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
