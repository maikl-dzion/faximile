(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-579ccfc0"],{"0487":function(t,a,e){"use strict";e.r(a);var n=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",[e("top-header",{attrs:{page_role:"4"},scopedSlots:t._u([{key:"top-menu",fn:function(){},proxy:!0}])}),e("v-container",{attrs:{fluid:""}},[e("v-toolbar",{attrs:{dense:""}},[e("v-toolbar-title",{staticStyle:{"font-size":"14px"}},[t._v("Список документов работодателя")]),e("v-spacer"),e("v-btn",{attrs:{icon:""}},[e("v-icon",[t._v("mdi-magnify")])],1),e("v-btn",{attrs:{icon:""}},[e("v-icon",[t._v("mdi-dots-vertical")])],1)],1),e("div",{staticClass:"data-list-wrapper"},[e("table",{staticClass:"data-list",staticStyle:{width:"100%"}},[e("tr",[e("th",{staticStyle:{"max-width":"10px"}},[t._v("№")]),e("th",{staticStyle:{"max-width":"20px"}},[t._v("ID док.")]),e("th",[t._v("***")]),e("th",[t._v("Название документа")]),e("th",[t._v("Работник")]),e("th",[t._v("Дата создания")]),e("th",[t._v("Статус документа")])]),t._l(t.documentsCompany,(function(a,n){return e("tr",[e("td",[t._v(t._s(n+1))]),e("td",[t._v(t._s(a.doc_id))]),e("td",[e("a",{attrs:{href:t.getRootUrl+a.path,target:"_blank"}},[e("v-icon",{staticStyle:{cursor:"pointer"},attrs:{color:"green"}},[t._v("mdi-file-document")])],1)]),e("td",{},[t._v("\r\n              "+t._s(a.document_name)+"\r\n            ")]),e("td",{},[t._v("\r\n              "+t._s(a.username)+"\r\n            ")]),e("td",[t._v(t._s(a.doc_create_at))]),e("td",{staticStyle:{width:"20%"}},[a.sign_user?[e("v-chip",{staticClass:"ma-2",attrs:{color:"green",outlined:"",pill:""}},[e("v-icon",{attrs:{left:""}},[t._v("mdi-checkbox-marked-circle")]),t._v("\r\n                    подписан\r\n                  ")],1)]:[e("v-chip",{staticClass:"ma-2",attrs:{color:"red",outlined:"",pill:""}},[e("v-icon",{attrs:{left:""}},[t._v("mdi-close-circle")]),t._v("\r\n                    не подписан\r\n                  ")],1)]],2)])}))],2)])],1)],1)},i=[],c={data:function(){return{pageTitle:"Список документов работодателя",items:[],dialog:!1,headers:[],documentsCompany:[]}},computed:{},created:function(){this.getFaximileData("company",null,"documentsCompany")},methods:{editItem:function(t){},deleteItem:function(t){},close:function(){this.dialog=!1},save:function(){}}},o=c,r=(e("e6ba"),e("2877")),s=e("6544"),l=e.n(s),d=e("8336"),v=e("cc20"),u=e("a523"),_=e("132d"),p=e("2fa4"),m=e("71d9"),f=e("2a7f"),h=Object(r["a"])(o,n,i,!1,null,null,null);a["default"]=h.exports;l()(h,{VBtn:d["a"],VChip:v["a"],VContainer:u["a"],VIcon:_["a"],VSpacer:p["a"],VToolbar:m["a"],VToolbarTitle:f["a"]})},"37c5":function(t,a,e){},e6ba:function(t,a,e){"use strict";var n=e("37c5"),i=e.n(n);i.a}}]);
//# sourceMappingURL=chunk-579ccfc0.afc63021.js.map