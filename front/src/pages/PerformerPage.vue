<template>
    <div class="performer-page">

        <top-header page_role="2">
            <template v-slot:top-menu>

                <!--<li><router-link to="/create-object-page">-->
                     <!--Создание объектов</router-link>-->
                <!--</li>-->

                <!--<li><router-link to="/chat-page">-->
                     <!--Чат сообщений </router-link>-->
                <!--</li>-->

            </template>
        </top-header>

        <v-container fluid>

            <template><v-card>

                    <v-tabs v-model="tab"  >
                        <v-tab >Список документов</v-tab>
                        <v-tab >Список работников</v-tab>
                        <v-tab >Список компаний</v-tab>
                    </v-tabs>

                    <v-tabs-items v-model="tab" >

                        <v-tab-item ><div class="tabPanelContainer">
                            <DocumentList></DocumentList>
                        </div></v-tab-item>

                        <v-tab-item ><div class="tabPanelContainer">
                            <EmployeeList>
                                <template v-slot:top-panel >
                                    <div style="display: flex; padding:8px 0px 0px 0px" >
                                        <ActivationPerson /> &nbsp; &nbsp; &nbsp;
                                    </div>
                                </template>
                            </EmployeeList>
                        </div></v-tab-item>

                        <v-tab-item ><div class="tabPanelContainer">
                            <CompanyList></CompanyList>
                        </div></v-tab-item>

                    </v-tabs-items>

            </v-card></template>

        </v-container>

</div></template>

<script>

import DocumentList from '../components/DocumentList';
import EmployeeList from '../components/EmployeeList';

export default {

    data: () => ({
        tab : null,
        pageTitle: 'Список документов',
        dialog: false,
    }),

    components: {
        DocumentList,
        EmployeeList,
    },

    created() {},

    methods: {

    },

};
</script>

<style>

</style>
