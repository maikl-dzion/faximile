(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2df1357e"],{"9f53":function(t,e,i){"use strict";var n=i("cb50"),s=i.n(n);s.a},a19e:function(t,e,i){"use strict";i.r(e);var n=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",[i("top-header",{attrs:{page_role:"3"},scopedSlots:t._u([{key:"top-menu",fn:function(){},proxy:!0}])}),[i("div",{staticClass:"text-center"},[i("v-dialog",{attrs:{width:"300"},model:{value:t.signDialog,callback:function(e){t.signDialog=e},expression:"signDialog"}},[i("v-card",[i("v-card-title",{staticClass:"headline green lighten-2",staticStyle:{color:"white"},attrs:{"primary-title":""}},[t._v("\r\n                            Электронная подпись\r\n                    ")]),i("v-card-text",[i("div",{staticStyle:{"text-align":"justify"}},[t._v("\r\n                            Чтобы подписать документ,\r\n                            введите код отправленный Вам на телефон\r\n                        ")]),i("div",{staticStyle:{"text-align":"center"}},[i("v-col",{attrs:{cols:"12",sm:"12",md:"12"}},[i("v-text-field",{attrs:{label:"код",solo:""},model:{value:t.signCode,callback:function(e){t.signCode=e},expression:"signCode"}})],1)],1),i("div",{staticStyle:{"font-weight":"bold","font-size":"16px","text-align":"center"}},[t._v("\r\n                            "+t._s(t.docItem.document_name)+"\r\n                        ")])]),i("v-divider"),i("v-card-actions",[i("v-spacer"),i("v-btn",{attrs:{color:"success",text:""},on:{click:t.sign}},[t._v("\r\n                            Подписать документ\r\n                        ")])],1)],1)],1)],1)],i("v-container",{staticStyle:{margin:"0px !important"},attrs:{fluid:""}},[i("v-toolbar",{attrs:{dense:""}},[i("v-toolbar-title",{staticStyle:{"font-size":"14px"}},[t._v("Список документов работника")]),i("v-spacer"),i("v-btn",{attrs:{icon:""}},[i("v-icon",[t._v("mdi-magnify")])],1),i("v-btn",{attrs:{icon:""}},[i("v-icon",[t._v("mdi-dots-vertical")])],1)],1),i("div",{staticClass:"data-list-wrapper"},[i("table",{staticClass:"data-list",staticStyle:{width:"100%"}},[i("tr",[i("th",{staticStyle:{"max-width":"10px"}},[t._v("№")]),i("th",{staticStyle:{"max-width":"20px"}},[t._v("ID док.")]),i("th",[t._v("***")]),i("th",[t._v("Название документа")]),i("th",[t._v("Дата создания")]),i("th",[t._v("Статус документа")])]),t._l(t.documentsUser,(function(e,n){return i("tr",[i("td",[t._v(t._s(n+1))]),i("td",[t._v(t._s(e.doc_id))]),i("td",[i("a",{attrs:{href:t.getRootUrl+e.path,target:"_blank"}},[i("v-icon",{staticStyle:{cursor:"pointer"},attrs:{color:"green"}},[t._v("mdi-file-document")])],1)]),i("td",{staticStyle:{"text-align":"left"}},[t._v("\r\n                    "+t._s(e.document_name)+"\r\n                ")]),i("td",[t._v(t._s(e.doc_create_at))]),i("td",{staticStyle:{width:"20%"}},[e.sign_user?[i("v-chip",{staticClass:"ma-2",attrs:{color:"green",outlined:"",pill:""}},[i("v-icon",{attrs:{left:""}},[t._v("mdi-checkbox-marked-circle")]),t._v("\r\n                            подписан\r\n                        ")],1)]:[i("v-btn",{staticClass:"ma-2",staticStyle:{"border-radius":"0px"},attrs:{color:"success"},on:{click:function(i){return t.signOpen(e)}}},[i("v-icon",{attrs:{left:""}},[t._v("mdi-signature-freehand")]),t._v("\r\n                                ПОДПИСАТЬ\r\n                        ")],1)]],2)])}))],2)])],1)],2)},s=[],a={data:function(){return{pageTitle:"Список документов работника",signDialog:!1,signCode:"",documentsUser:[],docItem:{},user:{},userId:0}},computed:{},created:function(){this.getFaximileData("person",null,"documentsUser"),this.getPerson()},methods:{getPerson:function(){var t=this;this.userId=this.store("user_id");var e="faximile/get-person/id/"+this.userId;this.send(e).then((function(e){t.user=e}))},signOpen:function(t){var e=this;this.docItem=t;var i="faximile/phone/check/phone/"+this.userId+"/0";this.send(i,"get").then((function(t){if("send"in t&&t["send"])e.signDialog=!0;else{var i="Не удалось отправить сообщение на телефон";e.alertWindow(i)}}))},checkCode:function(){var t=this,e="faximile/phone/check/code/"+this.userId+"/"+this.signCode;this.send(e,"get").then((function(e){if("check"in e&&e["check"])t.sign();else{var i="Не удалось проверить код,попробуйте еще раз";t.alertWindow(i)}}))},sign:function(){var t=this,e=this.userId,i=this.docItem.doc_id,n="documents/sign/user/"+e+"/"+i;this.send(n,"get").then((function(e){"sign_result"in e&&e["sign_result"]&&(t.signDialog=!1,t.getFaximileData("person",null,"documentsUser")),t.alertWindow(e["message"])}))}}},r=a,c=(i("9f53"),i("2877")),o=i("6544"),l=i.n(o),d=i("8336"),u=i("b0af"),v=i("99d9"),h=i("cc20"),g=i("62ad"),f=i("a523"),_=i("169a"),m=i("ce7e"),p=i("132d"),x=i("2fa4"),b=i("8654"),C=i("71d9"),y=i("2a7f"),w=Object(c["a"])(r,n,s,!1,null,null,null);e["default"]=w.exports;l()(w,{VBtn:d["a"],VCard:u["a"],VCardActions:v["a"],VCardText:v["b"],VCardTitle:v["c"],VChip:h["a"],VCol:g["a"],VContainer:f["a"],VDialog:_["a"],VDivider:m["a"],VIcon:p["a"],VSpacer:x["a"],VTextField:b["a"],VToolbar:C["a"],VToolbarTitle:y["a"]})},cb50:function(t,e,i){}}]);
//# sourceMappingURL=chunk-2df1357e.d6a21b8d.js.map