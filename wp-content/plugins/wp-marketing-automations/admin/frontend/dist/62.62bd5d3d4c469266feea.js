(window.webpackJsonp=window.webpackJsonp||[]).push([[62],{1161:function(e,t,n){"use strict";n.r(t),n.d(t,"default",(function(){return ue}));var r=n(0),a=n(806),i=n(2),o=n(4),s=n(12),u=n.n(s),c=n(5),l=n(130);function f(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function b(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?f(Object(n),!0).forEach((function(t){m(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):f(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}function m(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}function p(e,t){if(null==e)return{};var n,r,a=function(e,t){if(null==e)return{};var n,r,a={},i=Object.keys(e);for(r=0;r<i.length;r++)n=i[r],t.indexOf(n)>=0||(a[n]=e[n]);return a}(e,t);if(Object.getOwnPropertySymbols){var i=Object.getOwnPropertySymbols(e);for(r=0;r<i.length;r++)n=i[r],t.indexOf(n)>=0||Object.prototype.propertyIsEnumerable.call(e,n)&&(a[n]=e[n])}return a}var d=function(){var e=Object(l.a)("unsubscriberList"),t=e.getStateProp;return b(b({},p(e,["getStateProp"])),{},{getUnsubscribers:function(){return t("unsubscribers")},getPageNumber:function(){return parseInt(t("offset"))/parseInt(t("limit"))+1},getPerPageCount:function(){return parseInt(t("limit"))},getOffset:function(){return parseInt(t("offset"))},getTotalCount:function(){return parseInt(t("total"))},getLoadingStatus:function(){return t("isLoading")}})},O=n(129);function h(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function g(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?h(Object(n),!0).forEach((function(t){v(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):h(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}function v(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}function y(e,t){if(null==e)return{};var n,r,a=function(e,t){if(null==e)return{};var n,r,a={},i=Object.keys(e);for(r=0;r<i.length;r++)n=i[r],t.indexOf(n)>=0||(a[n]=e[n]);return a}(e,t);if(Object.getOwnPropertySymbols){var i=Object.getOwnPropertySymbols(e);for(r=0;r<i.length;r++)n=i[r],t.indexOf(n)>=0||Object.prototype.propertyIsEnumerable.call(e,n)&&(a[n]=e[n])}return a}var j=function(){var e=Object(O.a)("unsubscriberList"),t=e.fetch,n=e.setStateProp;return g(g({},y(e,["fetch","setStateProp"])),{},{fetch:function(e,n,r){var a=e.s,i=(e.page,e.filter,e.path,{offset:n,limit:r,search:a,filters:y(e,["s","page","filter","path"])});t("GET",Object(c.k)("/settings/unsubscribers"),i)},setUnsubscriberListValues:function(e,t){n(e,t)}})},w=n(81),E=n(219),_=n(8);function k(e,t,n,r,a,i,o){try{var s=e[i](o),u=s.value}catch(e){return void n(e)}s.done?t(u):Promise.resolve(u).then(r,a)}function S(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){if("undefined"==typeof Symbol||!(Symbol.iterator in Object(e)))return;var n=[],r=!0,a=!1,i=void 0;try{for(var o,s=e[Symbol.iterator]();!(r=(o=s.next()).done)&&(n.push(o.value),!t||n.length!==t);r=!0);}catch(e){a=!0,i=e}finally{try{r||null==s.return||s.return()}finally{if(a)throw i}}return n}(e,t)||function(e,t){if(!e)return;if("string"==typeof e)return P(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(e);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return P(e,t)}(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function P(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}var x=function(e){var t=e.query,n=Object(r.useContext)(c.d),a=j().fetch,s=S(Object(r.useState)(""),2),l=s[0],f=s[1],b=d().getPerPageCount,m=S(Object(r.useState)(""),2),p=m[0],O=m[1],h=S(Object(r.useState)({loading:!1,status:!1}),2),g=h[0],v=h[1],y=b();Object(r.useEffect)((function(){!0===g.status&&g.success&&a(t,0,y)}),[g]);var P=function(){var e,t=(e=regeneratorRuntime.mark((function e(t){var r;return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return v({status:!0,loading:!0}),e.prev=1,e.next=4,u()({path:Object(c.k)("/settings/unsubscribers"),method:"POST",data:{unsubscribers:t}});case 4:if(200!=(r=e.sent).code){e.next=11;break}n(r.message),f(""),v({}),e.next=12;break;case 11:throw Error(Object(c.i)(null==r?void 0:r.message));case 12:e.next=18;break;case 14:e.prev=14,e.t0=e.catch(1),v({status:!0,loading:!1}),O(e.t0.message);case 18:Object(c.Z)(n,1e3);case 19:case"end":return e.stop()}}),e,null,[[1,14]])})),function(){var t=this,n=arguments;return new Promise((function(r,a){var i=e.apply(t,n);function o(e){k(i,r,a,o,s,"next",e)}function s(e){k(i,r,a,o,s,"throw",e)}o(void 0)}))});return function(e){return t.apply(this,arguments)}}(),x=function(){if(""!=l.trim()){var e="",t=!1,n=-1!==l.indexOf(",")?l.split(","):l.split("\n");(n=n.map((function(e){return e.trim()}))).forEach((function(n){Object(w.a)(n)||(Object(E.a)(e)||(e+=","),e+=n,t=!0)})),t?O(Object(i.__)("Invalid Email : ","wp-marketing-automations")+e):(O(""),P(n))}else O(Object(i.__)("Please enter some emails to unsubscribe.","wp-marketing-automations"))};return Object(r.createElement)(r.Fragment,null,Object(r.createElement)(o.Button,{isSecondary:!0,onClick:function(){v({status:!0})}},Object(i.__)("Bulk Unsubscribe","wp-marketing-automations")),g.status&&Object(r.createElement)(o.Modal,{onRequestClose:function(){return v({status:!1})},className:"bwf-admin-modal bwf-admin-modal-squeezy bwf-h--core-header"},Object(r.createElement)("div",{className:"bwf-modal-header"},Object(r.createElement)("div",{className:"bwf-modal-heading"},Object(i.__)("Bulk Unsubscribe","wp-marketing-automations")),Object(r.createElement)("span",{onClick:function(){return v({status:!1})},className:"bwf-modal-close"},Object(r.createElement)(_.a,{icon:"close",color:"#353030"}))),Object(r.createElement)(r.Fragment,null,Object(r.createElement)("div",{onKeyPress:function(e){e.key}},p&&Object(r.createElement)(o.Notice,{status:"error",onRemove:function(){return O("")}},p),Object(r.createElement)(o.TextareaControl,{placeholder:Object(i.__)("Enter emails comma (,) separated or each in new line to unsubscribe multiple emails.","wp-marketing-automations"),spellCheck:!1,style:{height:"200px"},value:l,onChange:f,disabled:g.loading}),Object(r.createElement)("p",{style:{marginTop:"-10px"}},Object(r.createElement)("i",null,Object(i.__)("This will unsubscribe users from all emails marked as promotional."))),Object(r.createElement)("div",{className:"bwf_clear_10"}),Object(r.createElement)("div",{className:"bwf_text_right"},Object(r.createElement)(o.Button,{onClick:function(){return v({status:!0})},className:"bwf-cancel-btn"},Object(i.__)("Cancel","wp-marketing-automations")),Object(r.createElement)(o.Button,{isPrimary:!0,onClick:x,isBusy:g.loading,disabled:g.loading},Object(i.__)("Unsubscribe","wp-marketing-automations")))))))},C=n(239),R=n(27),A=n(31),N=n.n(A),T=n(110),M=n.n(T),L=n(30),I=n.n(L),U=n(3),q=n(15),B=n(14),D=n.n(B),F=n(17);function H(e,t,n,r,a,i,o){try{var s=e[i](o),u=s.value}catch(e){return void n(e)}s.done?t(u):Promise.resolve(u).then(r,a)}var K=function(e){return e.recipient},$={name:"recipient",className:"bwf-search-bwf-unsubscriber-result",options:function(e){return(t=regeneratorRuntime.mark((function e(t){var n,r;return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:if(!N()(t)){e.next=2;break}return e.abrupt("return",[]);case 2:return n={search:t,limit:5,offset:0},e.next=5,u()({path:Object(c.k)("/settings/unsubscribers?"+Object(F.stringify)(n)),method:"GET"});case 5:return r=e.sent,e.abrupt("return",I()(r,"result")&&I()(r.result,"items")?r.result.items:[]);case 7:case"end":return e.stop()}}),e)})),n=function(){var e=this,n=arguments;return new Promise((function(r,a){var i=t.apply(e,n);function o(e){H(i,r,a,o,s,"next",e)}function s(e){H(i,r,a,o,s,"throw",e)}o(void 0)}))},function(e){return n.apply(this,arguments)})(e);var t,n},isDebounced:!0,getOptionIdentifier:function(e){return e.id},getOptionKeywords:function(e){return[e.recipient]},getFreeTextOptions:function(e,t){return[{key:"name",label:Object(r.createElement)("span",{key:"name",className:"bwf-search-result-name"},D()({mixedString:Object(i.__)("All unsubscribers with names that include {{query /}}","wp-marketing-automations"),components:{query:Object(r.createElement)("strong",{className:"components-form-token-field__suggestion-match"},e)}})),value:{id:e,name:e,lists:t.map((function(e){return I()(e,"value")?e.value:e})),searchTerm:e}}]},getOptionLabel:function(e,t){var n=Object(c.h)(K(e),t)||{};return Object(r.createElement)("span",{key:"name",className:"bwf-search-result-name","aria-label":K(e)},n.suggestionBeforeMatch,Object(r.createElement)("strong",{className:"components-form-token-field__suggestion-match"},n.suggestionMatch),n.suggestionAfterMatch)},getOptionCompletion:function(e){return e}};function G(e,t,n,r,a,i,o){try{var s=e[i](o),u=s.value}catch(e){return void n(e)}s.done?t(u):Promise.resolve(u).then(r,a)}var J=function(e){var t=e.query,n=t.hasOwnProperty("s")?t.s:"",a=N()(n)?[]:[{key:n,label:Object(i.__)("Search List: ","wp-marketing-automations")+n,bwfLabelSource:"bwfcrm_lists",isSearchTerm:!0}],o=function(){var e,n=(e=regeneratorRuntime.mark((function e(n){var r,a,i,o;return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:if(M()(n)){e.next=2;break}return e.abrupt("return");case 2:if(N()(n)||(r=n[n.length-1],(n=[])[0]=r),a=n.find((function(e){return I()(e,"searchTerm")})),!(Object(U.isUndefined)(a)&&n.length>0&&n[0].hasOwnProperty("recipient"))){e.next=7;break}return Object(q.k)({s:n[0].recipient},"/settings/unsubscribers",t),e.abrupt("return");case 7:if(i=Object(U.isUndefined)(a)?void 0:a.searchTerm,o=I()(t,"s")&&!N()(t.s)?t.s:"",i!==o){e.next=11;break}return e.abrupt("return");case 11:Object(q.k)({s:i},"/settings/unsubscribers",t);case 12:case"end":return e.stop()}}),e)})),function(){var t=this,n=arguments;return new Promise((function(r,a){var i=e.apply(t,n);function o(e){G(i,r,a,o,s,"next",e)}function s(e){G(i,r,a,o,s,"throw",e)}o(void 0)}))});return function(e){return n.apply(this,arguments)}}();return Object(r.createElement)(R.a,{autocompleter:$,multiple:!1,allowFreeTextSearch:!0,inlineTags:!0,selected:a,onChange:o,placeholder:Object(i.__)("Search by Contact","wp-marketing-automations"),showClearButton:!0,disabled:!1})},V=n(195),z=n(792),Q=n(128);function X(e,t,n,r,a,i,o){try{var s=e[i](o),u=s.value}catch(e){return void n(e)}s.done?t(u):Promise.resolve(u).then(r,a)}var Z=function(e){var t=e.isOpen,n=e.unsubs,a=e.onSuccess,o=e.onError,s=e.onRequestClose,l=Object(z.a)(function(){var e,t=(e=regeneratorRuntime.mark((function e(t){return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,u()({path:Object(c.k)("/settings/unsubscribers"),method:"POST",headers:{"X-HTTP-Method-Override":"DELETE"},data:{unsubscribers_ids:t}});case 2:return e.abrupt("return",e.sent);case 3:case"end":return e.stop()}}),e)})),function(){var t=this,n=arguments;return new Promise((function(r,a){var i=e.apply(t,n);function o(e){X(i,r,a,o,s,"next",e)}function s(e){X(i,r,a,o,s,"throw",e)}o(void 0)}))});return function(e){return t.apply(this,arguments)}}(),{onSuccess:function(){setTimeout((function(){return l.reset()}),2500),a&&a(),s&&s()},onError:function(){o&&o()}});return Object(r.createElement)(Q.a,{modalTitle:Object(i.__)("Delete Unsubscriber"),deleteEntityName:1===n.length?n[0].recipient:"".concat(n.length," Unsubscriber"),confirmButtonText:Object(i.__)("Delete","wp-marketing-automations"),cancelButtonText:Object(i.__)("Cancel","wp-marketing-automations"),onConfirm:function(){return l.mutate(n.map((function(e){return e.id})))},errorMessage:l.isError&&(l.error&&l.error.message?l.error.message:Object(i.__)("Unable to delete unsubscribers","wp-marketing-automations")),onRequestClose:function(){return!!s&&s()},isOpen:t,isDelete:!0})};function W(){return(W=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e}).apply(this,arguments)}function Y(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){if("undefined"==typeof Symbol||!(Symbol.iterator in Object(e)))return;var n=[],r=!0,a=!1,i=void 0;try{for(var o,s=e[Symbol.iterator]();!(r=(o=s.next()).done)&&(n.push(o.value),!t||n.length!==t);r=!0);}catch(e){a=!0,i=e}finally{try{r||null==s.return||s.return()}finally{if(a)throw i}}return n}(e,t)||function(e,t){if(!e)return;if("string"==typeof e)return ee(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(e);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return ee(e,t)}(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function ee(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}var te=[{id:"delete",icon:"trash",hint:Object(i.__)("Delete Tasks","wp-marketing-automations")}],ne=function(e){var t=e.floatingBarProps,n=void 0===t?{}:t,a=e.resetSelection,i=e.onSuccess,o=Y(Object(r.useState)([]),2),s=o[0],u=o[1],c=Y(Object(r.useState)(!1),2),l=c[0],f=c[1],b=function(){a&&a(),u([])},m=Object(r.useCallback)((function(e,t){switch(e){case"delete":u(t),f(!0)}}),[]);return Object(r.createElement)(r.Fragment,null,Object(r.createElement)(V.a,W({actions:te,onAction:m},n)),Object(r.createElement)(Z,{unsubs:s,isOpen:l,onSuccess:function(){i&&i("delete",s),b()},onError:b,onRequestClose:function(){return f(!1)}}))},re=n(36);function ae(e,t){var n;if("undefined"==typeof Symbol||null==e[Symbol.iterator]){if(Array.isArray(e)||(n=function(e,t){if(!e)return;if("string"==typeof e)return ie(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(e);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return ie(e,t)}(e))||t&&e&&"number"==typeof e.length){n&&(e=n);var r=0,a=function(){};return{s:a,n:function(){return r>=e.length?{done:!0}:{done:!1,value:e[r++]}},e:function(e){throw e},f:a}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var i,o=!0,s=!1;return{s:function(){n=e[Symbol.iterator]()},n:function(){var e=n.next();return o=e.done,e},e:function(e){s=!0,i=e},f:function(){try{o||null==n.return||n.return()}finally{if(s)throw i}}}}function ie(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}var oe=function(e){var t=e.query,n=j(),a=d(),s=n.fetch,u=n.setUnsubscriberListValues,l=a.getUnsubscribers,f=a.getPageNumber,b=a.getPerPageCount,m=a.getTotalCount,p=a.getOffset,O=a.getLoadingStatus,h=l(),g=p(),v=b(),y=O(),w=m(),E=f();Object(r.useEffect)((function(){s(t,g,v)}),[g,v,t]);var _=Object(r.useMemo)((function(){var e={};if(Array.isArray(h)){var t,n=ae(h);try{for(n.s();!(t=n.n()).done;){var r=t.value;e[r.id]=r}}catch(e){n.e(e)}finally{n.f()}}return e}),[h]),k=Object(V.b)(_),S=k.singleSelectProps,P=k.selectAllProps,x=k.floatingBarProps,R=k.resetSelection,A=[{key:"checkbox",label:Object(r.createElement)(o.CheckboxControl,P),isLeftAligned:!1,required:!0,cellClassName:"bwf-col-action bwf-w-45"},{key:"contact",label:Object(i.__)("Contact","wp-marketing-automations"),isLeftAligned:!0,cellClassName:"bwf-w-300"},{key:"date",label:Object(i.__)("Date","wp-marketing-automations"),isLeftAligned:!0},{key:"source",label:Object(i.__)("Source","wp-marketing-automations"),cellClassName:"bwf-w-300",isLeftAligned:!0}],N=function(e){e!==v&&u("limit",e)},T=function(e){if(e.hasOwnProperty("automation_id")&&!Object(U.isEmpty)(e.automation_id))switch(parseInt(e.source_type)){case 1:return Object(r.createElement)("a",{href:"admin.php?page=autonami-automations&edit=".concat(e.automation_id),className:"bwf-a-no-underline"},e.automation_name);case 2:return Object(r.createElement)(re.a,{href:"admin.php?page=autonami&path=/broadcast/".concat(e.automation_id),type:"bwf-crm",className:"bwf-a-no-underline"},e.automation_name)}return Object(r.createElement)(r.Fragment,null,e.automation_name)},M=h?h.map((function(e){return[{display:S[e.id]&&Object(r.createElement)(o.CheckboxControl,S[e.id]),value:""},{display:e.recipient,value:e.recipient},{display:Object(c.K)(e.date),value:e.date},{display:T(e),value:e.automation_name}]})):[];return Object(r.createElement)(r.Fragment,null,Object(r.createElement)(C.a,{className:"has-search bwf-table-bulk-action",headers:A,showMenu:!1,rows:M,query:{paged:E},rowsPerPage:v,totalRows:w,isLoading:y,onPageChange:function(e,t){u("offset",(e-1)*v)},onQueryChange:function(e){return"per_page"!==e?function(){}:N},actions:[Object(r.createElement)(J,{key:"search",query:t})],emptyMessage:Object(i.__)("No unsubscribers available","wp-marketing-automations")}),Object(r.createElement)(ne,{floatingBarProps:x,resetSelection:R,onSuccess:function(){return s(t,0,v)}}))},se=n(63);function ue(){Object(a.a)("unsubscribers",!1,"");var e=Object(q.i)();return Object(c.f)("Unsubscribers"),Object(r.createElement)(r.Fragment,null,Object(r.createElement)(se.a,null),Object(r.createElement)("div",{className:"bwfan_page_unsubscribers"},Object(r.createElement)(x,{query:e}),Object(r.createElement)("div",{className:"bwf_clear_20"}),Object(r.createElement)(oe,{query:e})))}},792:function(e,t,n){"use strict";n.d(t,"a",(function(){return b}));var r=n(11),a=n(7),i=n.n(a),o=n(25),s=n(13),u=n(26),c=n(245),l=function(e){function t(t,n){var r;return(r=e.call(this)||this).client=t,r.setOptions(n),r.bindMethods(),r.updateResult(),r}Object(u.a)(t,e);var n=t.prototype;return n.bindMethods=function(){this.mutate=this.mutate.bind(this),this.reset=this.reset.bind(this)},n.setOptions=function(e){this.options=this.client.defaultMutationOptions(e)},n.onUnsubscribe=function(){var e;this.listeners.length||(null==(e=this.currentMutation)||e.removeObserver(this))},n.onMutationUpdate=function(e){this.updateResult();var t={listeners:!0};"success"===e.type?t.onSuccess=!0:"error"===e.type&&(t.onError=!0),this.notify(t)},n.getCurrentResult=function(){return this.currentResult},n.reset=function(){this.currentMutation=void 0,this.updateResult(),this.notify({listeners:!0})},n.mutate=function(e,t){return this.mutateOptions=t,this.currentMutation&&this.currentMutation.removeObserver(this),this.currentMutation=this.client.getMutationCache().build(this.client,Object(r.a)({},this.options,{variables:void 0!==e?e:this.options.variables})),this.currentMutation.addObserver(this),this.currentMutation.execute()},n.updateResult=function(){var e=this.currentMutation?this.currentMutation.state:Object(c.b)();this.currentResult=Object(r.a)({},e,{isLoading:"loading"===e.status,isSuccess:"success"===e.status,isError:"error"===e.status,isIdle:"idle"===e.status,mutate:this.mutate,reset:this.reset})},n.notify=function(e){var t=this;o.a.batch((function(){t.mutateOptions&&(e.onSuccess?(null==t.mutateOptions.onSuccess||t.mutateOptions.onSuccess(t.currentResult.data,t.currentResult.variables,t.currentResult.context),null==t.mutateOptions.onSettled||t.mutateOptions.onSettled(t.currentResult.data,null,t.currentResult.variables,t.currentResult.context)):e.onError&&(null==t.mutateOptions.onError||t.mutateOptions.onError(t.currentResult.error,t.currentResult.variables,t.currentResult.context),null==t.mutateOptions.onSettled||t.mutateOptions.onSettled(void 0,t.currentResult.error,t.currentResult.variables,t.currentResult.context))),e.listeners&&t.listeners.forEach((function(e){e(t.currentResult)}))}))},t}(n(52).a),f=n(242);function b(e,t,n){var a=i.a.useRef(!1),u=i.a.useState(0)[1],c=Object(s.k)(e,t,n),b=Object(f.b)(),m=i.a.useRef();m.current?m.current.setOptions(c):m.current=new l(b,c);var p=m.current.getCurrentResult();i.a.useEffect((function(){a.current=!0;var e=m.current.subscribe(o.a.batchCalls((function(){a.current&&u((function(e){return e+1}))})));return function(){a.current=!1,e()}}),[]);var d=i.a.useCallback((function(e,t){m.current.mutate(e,t).catch(s.i)}),[]);if(p.error&&m.current.options.useErrorBoundary)throw p.error;return Object(r.a)({},p,{mutate:d,mutateAsync:p.mutate})}},806:function(e,t,n){"use strict";var r=n(127),a=n(0);t.a=function(e,t,n){var i=arguments.length>3&&void 0!==arguments[3]?arguments[3]:"",o=bwfcrm_contacts_data&&bwfcrm_contacts_data.header_data?bwfcrm_contacts_data.header_data:{},s=Object(r.a)(),u=s.setActiveMultiple,c=s.resetHeaderMenu,l=s.setL2NavType,f=s.setL2Nav,b=s.setBackLink,m=s.setL2Title,p=s.setPageHeader,d=s.setL2Content;return Object(a.useEffect)((function(){c(),!t&&l("menu"),!t&&f(o.settings_nav),u({leftNav:"settings",rightNav:e}),t&&b(t),n&&m(n),p("Settings"),d(i)}),[e,n]),!0}}}]);