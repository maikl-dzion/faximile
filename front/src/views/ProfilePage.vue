<template><div style="" >

    <top-header page_role="2">
        <template v-slot:top-menu >

            <li><a  @click="tabPanel = 'documents'"    >Документы</a></li>
            <li><a  @click="tabPanel = 'person_list'"  >Сотрудники</a></li>
            <li><a  @click="tabPanel = 'company_list'" >Компании</a></li>

            <li><router-link to="/create-object-page"  >
                Добавление объектов
            </router-link></li>

            <li><router-link to="/chat-page"  >
                Чат сообщений
            </router-link></li>

        </template>
    </top-header>

    <v-container fluid>





    </v-container>

</div></template>

<script>
    export default {
        data: () => ({

            pageTitle : 'Список документов',
            tabPanel  : 'documents',
            items : [],
            dialog  : false,
            headers : [
                { title: "№"   , field: "num"},
                { title: "ID док."   , field: "doc_id"},
                { title: "***"   , field: "view"},
                { title: "Документ"   , field: "document_name"},
                { title: "Компания"   , field: "company_name" },
                { title: "Сотрудник"  , field: "username" },
                { title: "Дата создания"  , field: "doc_create_at" },
                { title: "Статус"     , field: "verify_state" },
                { title: "Проверить <br> подпись"     , field: "check_verify" },
                //{ text: "ДЕЙСТВИЯ"    , value: "actions", sortable: false }
            ],

        }),

        computed: {
            formTitle() {
                return this.editedIndex === -1 ?
                    "Добавить документ" : "Редактировать документ";
            }
        },

        watch: {
            // dialog(val) {val || this.close();}
        },

        created() {
            this.getFaximileData('executor', null, 'items');

            // const eventName = 'activation_person';
            // this.getEventBus(eventName, resp => {
            //     this.getFaximileData('executor', null, 'items');
            // });
        },

        methods: {

            deleteDocument(item) {
                lg(item.document_name)
            },

            verifyDocument(item) {
                this.signUserVerify(item.doc_id);
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
