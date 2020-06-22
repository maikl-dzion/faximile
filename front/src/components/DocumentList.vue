<template><div class="document-list">
<!----- Document List Start -------->

    <!--<pre>{{documentList}}</pre>-->

    <template v-if="userRole == 3"     ><!------ РАБОТНИК ----->

          <h3>Работник</h3>
          <pre>{{documentList}}</pre>

    </template>
    <template v-else-if="userRole == 4"><!------ КОМПАНИЯ ----->

          <h3>Компания</h3>
          <pre>{{documentList}}</pre>

    </template>
    <template v-else >                <!------ ИСПОЛНИТЕЛЬ ----->

        <v-toolbar color="white" dense>
            <v-toolbar-title style="font-size: 14px">{{pageTitle}}</v-toolbar-title>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-spacer></v-spacer>
        </v-toolbar>

        <div class="data-list-wrapper" >
            <table class="data-list" style="width:100%">

                <tr>
                    <th v-for="(value, index) in headers"
                        v-html="value.title" >
                    </th>

                    <th>Действия</th>
                </tr>

                <tr v-for="(value, index) in documentList">

                    <td>{{index + 1}}</td>
                    <td>{{value.doc_id}}</td>
                    <td><a :href="rootUrl + '/' + value.path" target="_blank">
                        <v-icon color="green">mdi-file</v-icon>
                    </a></td>
                    <td>{{value.document_name}}</td>
                    <td>{{value.company_name}}</td>
                    <td>{{value.username}}</td>
                    <td>{{value.doc_create_at}}</td>

                    <td>
                        <template v-if="value.sign_user">
                            <v-chip
                                    color="success" class="ma-2" outlined pill>
                                <v-icon left>mdi-checkbox-marked-circle</v-icon>
                                подписан
                            </v-chip>
                        </template>
                        <template v-else>
                            <v-chip class="ma-2"
                                    color="red" outlined pill>
                                <v-icon left>mdi-close-circle</v-icon>
                                не подписан
                            </v-chip>
                        </template>
                    </td>

                    <td style="width: 20px;">
                        <template v-if="value.sign_user">
                            <v-btn @click="verifyDocument(value)" class="ma-2"
                                   style="border-radius: 0px;width:80%; padding:0px !important;"
                                   color="success">
                                <v-icon left>mdi-signature-freehand</v-icon>
                                <v-icon left>mdi-account-edit</v-icon>
                            </v-btn>
                        </template>
                    </td>

                    <td><v-btn @click="deleteDocument(value)" color="red" icon>
                            <v-icon>mdi-progress-close</v-icon>
                        </v-btn>
                    </td>
                </tr>

            </table>
        </div>

    </template>

<!----- / Document List End -------->
</div></template>

<script>

export default {
    data: () => ({
        userRole : 2,
        pageTitle: 'Список документов',
        documentList : [],
        dialog: false,

        headers: [
            {title: "№"        , field: "num"},
            {title: "ID док."  , field: "doc_id"},
            {title: "Просмотр" , field: "view"},
            {title: "Документ" , field: "document_name"},
            {title: "Компания" , field: "company_name"},
            {title: "Сотрудник", field: "username"},
            {title: "Дата создания", field: "doc_create_at"},
            {title: "Статус"   , field: "verify_state"},
            {title: "Проверить <br> подпись", field: "check_verify"},
        ],

    }),

    created() {

        this.getUploadsFilesUrl();

        let entityName = 'executor';
        this.userRole = this.store('role');
        switch (this.userRole) {
            case 1 : break;
            case 2 : break;
            case 3 : entityName = 'person';   break;
            case 4 : entityName = 'company'; break;
        }

        this.getUserDocumentList(this.userRole, 'documentList');
    },

    methods: {

        deleteDocument(item) {
            lg(item.document_name)
        },

        verifyDocument(item) {
            this.signUserVerify(item.doc_id);
        },

    },
};
</script>

<style>

</style>
