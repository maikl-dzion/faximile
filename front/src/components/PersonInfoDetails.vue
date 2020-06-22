<template><v-row justify="center">

        <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
            <template v-slot:activator="{ on }">
                <v-btn color="primary" dark v-on="on">Open Dialog</v-btn>
            </template>
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click="dialog = false">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>User Info</v-toolbar-title>
                    <v-spacer></v-spacer>

                </v-toolbar>
                <v-list three-line subheader>
                    <v-subheader>User Controls</v-subheader>
                    <v-list-item><v-list-item-content>
                        <slot name="user-info"></slot>
                    </v-list-item-content></v-list-item>
                </v-list>

                <!--<v-divider></v-divider>-->
                <!--<v-list three-line subheader>-->
                    <!--<v-subheader>General</v-subheader>-->
                    <!--<v-list-item><v-list-item-content>-->
                    <!--</v-list-item-content></v-list-item>-->
                <!--</v-list>-->

            </v-card>
            {{reversedDialog}}
        </v-dialog>

</v-row></template>


<script>
    export default {
        props :['show'],
        data () {
            return {
                dialog: this.show ,
                notifications: false,
                sound: true,
                widgets: false,
            }
        },

        computed: {

            // reversedDialog() {
            //     this.dialog = this.show;
            //     return this.dialog;
            // }
        },

        watch : {
            di(value) {
                this.dialog = value;
            },
        }
    }
</script>