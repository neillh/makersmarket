(window.webpackJsonp=window.webpackJsonp||[]).push([[21],{1146:function(e,t,n){"use strict";n.r(t);var a=n(0),r=n(3),c=n(2),o=n(5),i=n(87);function l(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){if("undefined"==typeof Symbol||!(Symbol.iterator in Object(e)))return;var n=[],a=!0,r=!1,c=void 0;try{for(var o,i=e[Symbol.iterator]();!(a=(o=i.next()).done)&&(n.push(o.value),!t||n.length!==t);a=!0);}catch(e){r=!0,c=e}finally{try{a||null==i.return||i.return()}finally{if(r)throw c}}return n}(e,t)||function(e,t){if(!e)return;if("string"==typeof e)return s(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(e);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return s(e,t)}(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function s(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,a=new Array(t);n<t;n++)a[n]=e[n];return a}t.default=function(e){var t=l(Object(a.useState)(!1),2),n=t[0],s=t[1];return Object(a.createElement)("div",{className:"bwf-c-s-data"},Object(a.createElement)("div",{className:"bwf-c-s-section"},Object(a.createElement)("div",{className:"button button-primary",onClick:function(){return s(!0)}},Object(c.__)("Add Note","wp-marketing-automations")),Object(a.createElement)("div",{className:"bwf_clear_30"}),Object(a.createElement)("div",{className:"bwf-notes-wrap"},[{id:1,created_date:"2021-02-03 12:34:01",title:"Note 1",private:1,body:"This is note body 1",created_username:"Admin"},{id:2,created_date:"2021-02-04 12:34:01",title:"Note 2",private:0,body:"This is note body 2",created_username:"Admin"}].map((function(e){var t,n,i,l,m=new Date(e.created_date);return Object(a.createElement)("div",{className:"bwf-note-item",key:e.id},Object(a.createElement)("div",{className:"bwf-note-text"},Object(a.createElement)("div",{className:"bwf-h4"},e.title,1==e.private&&Object(a.createElement)("div",{className:"bwf-note-highlight"},Object(c.__)("Sent to Contact","wp-marketing-automations"))),Object(a.createElement)("div",{className:"bwf-p"},e.body)),Object(a.createElement)("div",{className:"bwf-note-meta"},Object(a.createElement)("span",{className:"bwf-note-added"},!Object(r.isEmpty)(e.created_username)&&Object(a.createElement)(a.Fragment,null,"By"," ",Object(a.createElement)("span",null,e.created_username))," ","on"," ",Object(a.createElement)("span",null,Object(o.J)(e.created_date))," ","at"," ",Object(a.createElement)("span",null,(n=(t=m).getHours(),i=t.getMinutes(),l=n>=12?"PM":"AM",(n=(n%=12)||12)+":"+(i=i<10?"0"+i:i)+" "+l))),Object(a.createElement)("span",{className:"bwf-note-sep"},"|"),Object(a.createElement)("a",{href:"#",onClick:function(e){e.preventDefault(),s(!0)}},Object(c.__)("Edit","wp-marketing-automations")),Object(a.createElement)("span",{className:"bwf-note-sep"},"|"),Object(a.createElement)("a",{href:"#",className:"link-danger",onClick:function(e){e.preventDefault(),s(!0)}},Object(c.__)("Delete","wp-marketing-automations"))))})))),Object(a.createElement)(i.a,{isOpen:n,onRequestClose:function(){return s(!1)}}))}},1183:function(e,t,n){"use strict";n.r(t);var a=n(0),r=n(3),c=n(2),o=n(12),i=n.n(o),l=n(4),s=n(9),m=n.n(s),b=n(787),u=n(130);function d(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(e);t&&(a=a.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,a)}return n}function f(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?d(Object(n),!0).forEach((function(t){p(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):d(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}function p(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}function O(e,t){if(null==e)return{};var n,a,r=function(e,t){if(null==e)return{};var n,a,r={},c=Object.keys(e);for(a=0;a<c.length;a++)n=c[a],t.indexOf(n)>=0||(r[n]=e[n]);return r}(e,t);if(Object.getOwnPropertySymbols){var c=Object.getOwnPropertySymbols(e);for(a=0;a<c.length;a++)n=c[a],t.indexOf(n)>=0||Object.prototype.propertyIsEnumerable.call(e,n)&&(r[n]=e[n])}return r}var j=function(){var e=Object(u.a)("singleContactNotes"),t=e.getStateProp;return f(f({},O(e,["getStateProp"])),{},{getNotes:function(){return t("notes")},isLoading:function(){return t("isLoading")},getPageNumber:function(){return parseInt(t("offset"))/parseInt(t("limit"))+1},getPerPageCount:function(){return parseInt(t("limit"))},getTotalCount:function(){return parseInt(t("total"))},getContactId:function(){return parseInt(t("contactId"))}})},g=n(129),y=n(5);function w(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(e);t&&(a=a.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,a)}return n}function v(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?w(Object(n),!0).forEach((function(t){E(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):w(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}function E(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}function h(e,t){if(null==e)return{};var n,a,r=function(e,t){if(null==e)return{};var n,a,r={},c=Object.keys(e);for(a=0;a<c.length;a++)n=c[a],t.indexOf(n)>=0||(r[n]=e[n]);return r}(e,t);if(Object.getOwnPropertySymbols){var c=Object.getOwnPropertySymbols(e);for(a=0;a<c.length;a++)n=c[a],t.indexOf(n)>=0||Object.prototype.propertyIsEnumerable.call(e,n)&&(r[n]=e[n])}return r}var _=function(){var e=Object(g.a)("singleContactNotes"),t=e.fetch,n=e.setStateProp;return v(v({},h(e,["fetch","setStateProp"])),{},{fetch:function(e,a,r){n("contactId",r);var c={offset:e,limit:a};t("GET",Object(y.k)("/contacts/".concat(r,"/notes")),c)},setStateValue:function(e,t){n(e,t)}})},N=n(789),k=n(103),P=n(135),C=(n(415),n(8));function S(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(e);t&&(a=a.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,a)}return n}function T(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?S(Object(n),!0).forEach((function(t){x(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):S(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}function x(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}function I(e,t,n,a,r,c,o){try{var i=e[c](o),l=i.value}catch(e){return void n(e)}i.done?t(l):Promise.resolve(l).then(a,r)}function D(e){return function(){var t=this,n=arguments;return new Promise((function(a,r){var c=e.apply(t,n);function o(e){I(c,a,r,o,i,"next",e)}function i(e){I(c,a,r,o,i,"throw",e)}o(void 0)}))}}function A(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){if("undefined"==typeof Symbol||!(Symbol.iterator in Object(e)))return;var n=[],a=!0,r=!1,c=void 0;try{for(var o,i=e[Symbol.iterator]();!(a=(o=i.next()).done)&&(n.push(o.value),!t||n.length!==t);a=!0);}catch(e){r=!0,c=e}finally{try{a||null==i.return||i.return()}finally{if(r)throw c}}return n}(e,t)||function(e,t){if(!e)return;if("string"==typeof e)return B(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(e);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return B(e,t)}(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function B(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,a=new Array(t);n<t;n++)a[n]=e[n];return a}t.default=function(e){var t=Object(a.useContext)(y.d),n={id:0,cid:0,type:"",created_by:"0",created_date:"0000-00-00 00:00:00",private:0,title:"",body:"",modified_by:null,modified_date:null,date_time:"0000-00-00 00:00:00"},o=e.contactId,s=b.a.getContact(),u=A(Object(a.useState)(!1),2),d=u[0],f=u[1],p=A(Object(a.useState)(!1),2),O=p[0],g=p[1],w=A(Object(a.useState)(n),2),v=w[0],E=w[1],h=A(Object(a.useState)({message:"",type:1}),2),S=h[0],x=h[1],I=[{value:"",label:Object(c.__)("Select Type","wp-marketing-automations")}].concat(Object(y.s)()),B=Object(N.a)().fetchConversations,M=j(),R=_(),F=R.fetch,L=R.setStateValue,J=M.getNotes(),q=M.isLoading(),H=M.getPerPageCount(),K=M.getPageNumber(),U=M.getTotalCount(),V=M.getContactId(),Z=function(){var e=D(regeneratorRuntime.mark((function e(n){var a;return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:if(a={title:n.title,body:n.body,type:n.type,private:n.send,modified_by:Object(y.u)()},Object(r.isEmpty)(n.id)){e.next=14;break}return E(T(T({},v),{},{loading:!0})),e.prev=3,e.next=6,i()({path:Object(y.k)("/contacts/".concat(s.id,"/notes/").concat(n.id)),method:"POST",data:{notes:a},headers:{"Content-Type":"application/json"}}).then((function(e){E(T(T({},v),{},{success:!0,loading:!0,message:e.message})),t(e.message),setTimeout((function(){var e=J.map((function(e){return e.id==n.id&&(e.title=n.title,e.body=n.body,e.type=n.type,e.private=n.send),e}));L("notes",e),f(!1),E({})}),1e3)})).catch((function(e){throw Error(Object(y.i)(null==e?void 0:e.message))}));case 6:e.next=12;break;case 8:e.prev=8,e.t0=e.catch(3),g(e.t0.message),E(T(T({},v),{},{loading:!1}));case 12:e.next=24;break;case 14:return a.created_by=Object(y.u)(),e.prev=15,e.next=18,i()({path:Object(y.k)("/contacts/".concat(s.id,"/notes/")),method:"POST",data:{notes:a},headers:{"Content-Type":"application/json"}}).then((function(e){200==e.code?(E(T(T({},v),{},{success:!0,loading:!0,message:e.message})),t(e.message),setTimeout((function(){f(!1),E({}),F((K-1)*H,H,s.id),B(s.id,0,25)}),1e3)):(E(T(T({},v),{},{error:!0,loading:!0,message:e.message,delete:!0})),t(e.message),setTimeout((function(){f(!1),E({})}),1e3))})).catch((function(e){throw Error(Object(y.i)(null==e?void 0:e.message))}));case 18:e.next=24;break;case 20:e.prev=20,e.t1=e.catch(15),g(e.t1.message),E(T(T({},v),{},{loading:!1}));case 24:Object(y.Z)(t,2e3);case 25:case"end":return e.stop()}}),e,null,[[3,8],[15,20]])})));return function(t){return e.apply(this,arguments)}}(),$=function(){var e=D(regeneratorRuntime.mark((function e(n){return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.prev=0,e.next=3,i()({path:Object(y.k)("/contacts/".concat(s.id,"/notes/").concat(n,"/")),method:"POST",headers:{"X-HTTP-Method-Override":"DELETE"}}).then((function(e){t(e.message),setTimeout((function(){f(!1),F((K-1)*H,H,s.id),E({})}),1e3)})).catch((function(e){throw Error(Object(y.i)(null==e?void 0:e.message))}));case 3:e.next=8;break;case 5:e.prev=5,e.t0=e.catch(0),E(T(T({},v),{},{error:e.t0.message,loading:!1,delete:!0}));case 8:Object(y.Z)(t,2e3);case 9:case"end":return e.stop()}}),e,null,[[0,5]])})));return function(t){return e.apply(this,arguments)}}();Object(a.useEffect)((function(){(Object(r.isEmpty)(J)||parseInt(o)!==parseInt(V))&&F((K-1)*H,H,o)}),[K,H]);return Object(a.createElement)("div",{className:"bwf-c-s-data"},d&&Object(a.createElement)(l.Modal,{onRequestClose:function(){return f(!1)},className:"bwf-admin-modal bwf-message-modal "+(v.loading?"bwf-admin-modal-no-header ":" ")+(v.delete?" bwf-admin-modal-squeezy ":"bwf-admin-modal-large")},Object(a.createElement)("div",{className:"bwf-modal-header"},Object(a.createElement)("div",{className:"bwf-modal-heading"},v.delete?Object(c.__)("Delete Note","wp-marketing-automations"):0==v.id?Object(c.__)("Add Note","wp-marketing-automations"):Object(c.__)("Edit Note","wp-marketing-automations")),Object(a.createElement)("span",{onClick:function(){return f(!1)},className:"bwf-modal-close"},Object(a.createElement)(C.a,{icon:"close",color:"#353030"}))),v.delete?Object(a.createElement)("div",{className:"bwf-form-buttons"},Object(a.createElement)("div",{className:"bwf-h4 bwf-h4-grey",style:{margin:0}},v.error?v.error:Object(a.createElement)(a.Fragment,null,Object(c.__)("You are about to delete ","wp-marketing-automations"),Object(a.createElement)("strong",null,v.entityName),Object(c.__)(". This action cannot be undone. Cancel to stop, Delete to proceed.","wp-marketing-automations"))),Object(a.createElement)("div",{className:"bwf_clear_24"}),v.error?Object(a.createElement)("div",{className:"bwf_text_right"},Object(a.createElement)(l.Button,{isPrimary:!0,onClick:function(){E({}),f(!1)}},Object(c.__)("OK","wp-marketing-automations"))):Object(a.createElement)("div",{className:"bwf_text_right"},Object(a.createElement)(l.Button,{className:"bwf-cancel-btn",onClick:function(){E({}),f(!1)},disabled:v.loading&&v.deleteconfirm},Object(c.__)("Cancel","wp-marketing-automations")),Object(a.createElement)(l.Button,{isPrimary:!0,isBusy:v.loading&&v.deleteconfirm,disabled:v.loading&&v.deleteconfirm,onClick:function(){E(T(T({},v),{},{loading:!0,deleteconfirm:!0,deleteid:v.deleteid,delete:!0})),$(v.deleteid)}},Object(c.__)("Delete","wp-marketing-automations")))):Object(a.createElement)("div",{className:"bwf-form-fields"},O&&Object(a.createElement)(l.Notice,{status:"error",onRemove:function(){return g(!1)}},O),Object(a.createElement)("div",{className:"bwf-message-wrap"},Object(a.createElement)("div",{className:"bwf-pmb-16 bwf-pt-0 bwf-bb-grey"},Object(a.createElement)("div",{className:"bwf-w-120 bwf_bold"},Object(c.__)("Type","wp-marketing-automations")),Object(a.createElement)(l.SelectControl,{value:v.type,className:"bwf-w-150",options:I,onChange:function(e){return E(T(T({},v),{},{type:e}))},disabled:v.loading})),Object(a.createElement)("div",{className:"bwf-pmb-16 bwf-bb-grey"},Object(a.createElement)("div",{className:"bwf-w-120 bwf_bold"},Object(c.__)("Title","wp-marketing-automations")),Object(a.createElement)(l.TextControl,{type:"text",value:v.title,placeholder:Object(c.__)("Title comes here …","wp-marketing-automations"),onChange:function(e){return E(T(T({},v),{},{title:e}))},disabled:v.loading})),Object(a.createElement)("div",{className:"bwf-message-textarea"},Object(a.createElement)(l.TextareaControl,{placeholder:Object(c.__)("Write your note here","wp-marketing-automations"),value:v.body,onChange:function(e){return E(T(T({},v),{},{body:e}))},disabled:v.loading,rows:5})),Object(a.createElement)("div",{className:"bwf-message-footer"},0==parseInt(v.id)?Object(a.createElement)("div",{className:"bwf-note-send"},Object(a.createElement)(l.ToggleControl,{label:Object(c.__)("Send Note to Contact","wp-marketing-automations"),className:"bwf-tooglecontrol-advance",checked:Boolean(parseInt(v.send)),onChange:function(e){return E(T(T({},v),{},{send:e?"1":"0"}))}})):Object(a.createElement)("div",null),Object(a.createElement)("div",{className:"bwf_text_right"},Object(a.createElement)(l.Button,{className:"bwf-cancel-btn",onClick:function(){return f(!1)}},Object(c.__)("Cancel")),Object(a.createElement)(l.Button,{isPrimary:!0,onClick:function(){Object(r.isEmpty)(v.title)||Object(r.isEmpty)(v.body)||Object(r.isEmpty)(v.type)?g(Object(c.__)("Title, type and body are mandatory field. Kindly fill them.","wp-marketing-automations")):(g(!1),E(T(T({},v),{},{loading:!0})),Z(v))},disabled:v.loading,isBusy:v.loading},0==v.id?Object(c.__)("Add","wp-marketing-automations"):Object(c.__)("Save","wp-marketing-automations"))))))),Object(a.createElement)("div",{className:"bwf-c-s-section"},Object(a.createElement)(P.a,{message:S.message,type:S.type,removeMessage:function(){return x({message:"",type:1})}}),Object(a.createElement)("div",{className:"button button-primary",onClick:function(){E(n),f(!0)}},Object(c.__)("Add Note","wp-marketing-automations")),Object(a.createElement)("div",{className:"bwf_clear_30"}),q?Object(a.createElement)(a.Fragment,null,[0,1,2,3,4,5,6,7,8,9].map((function(e){return Object(a.createElement)("div",{className:"bwf-notes-wrap bwf-placeholder-content",key:e},Object(a.createElement)("div",{className:"bwf-note-item"},Object(a.createElement)("div",{className:"bwf-note-text"},Object(a.createElement)("div",{className:"is-placeholder long",style:{width:"100%"}}),Object(a.createElement)("div",{className:"is-placeholder long",style:{width:"100%"}})),Object(a.createElement)("div",{className:"bwf-note-meta"})))}))):Object(a.createElement)(a.Fragment,null,Object(a.createElement)("div",{className:"bwf-notes-wrap"},Object(r.isEmpty)(J)?Object(a.createElement)("div",{className:"bwf-empty-note-item"},Object(c.__)("No Notes Available","wp-marketing-automations")):J.map((function(e){return Object(a.createElement)("div",{className:"bwf-note-item",key:e.id},Object(a.createElement)("div",{className:"bwf-note-text"},Object(a.createElement)("div",{className:"bwf-h4"},e.title,1==e.private&&Object(a.createElement)("div",{className:"bwf-note-highlight"},Object(c.__)("Sent to Contact","wp-marketing-automations"))),Object(a.createElement)("div",{className:"bwf-p",dangerouslySetInnerHTML:{__html:e.body}})),Object(a.createElement)("div",{className:"bwf-note-meta"},Object(a.createElement)("span",{className:"bwf-note-added"},!Object(r.isEmpty)(e.created_username)&&Object(a.createElement)(a.Fragment,null,"By"," ",Object(a.createElement)("span",null,e.created_username))," ","on"," ",Object(a.createElement)("span",null,Object(y.J)(e.created_date))," ","at"," ",Object(a.createElement)("span",null,m()(e.created_date).format("hh:mm A"))),Object(a.createElement)("span",{className:"bwf-note-sep"},"|"),Object(a.createElement)("a",{href:"#",onClick:function(t){t.preventDefault(),E(e),f(!0)}},Object(c.__)("Edit","wp-marketing-automations")),Object(a.createElement)("span",{className:"bwf-note-sep"},"|"),Object(a.createElement)("a",{href:"#",className:"link-danger",onClick:function(t){t.preventDefault(),E(T(T({},v),{},{loading:!0,delete:!0,deleteid:e.id,entityName:e.title})),f(!0)}},Object(c.__)("Delete","wp-marketing-automations"))))}))),parseInt(U)>0&&Object(a.createElement)(k.a,{page:K,perPage:H,total:U,onPageChange:function(e){L("offset",(e-1)*H),F((e-1)*H,H,s.id)},onPerPageChange:function(e){e!==H&&(L("limit",e),F(offset,e,s.id))}}))))}}}]);