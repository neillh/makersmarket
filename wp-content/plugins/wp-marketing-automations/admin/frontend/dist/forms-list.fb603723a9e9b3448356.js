(window.webpackJsonp=window.webpackJsonp||[]).push([[14],{1165:function(e,t,n){"use strict";n.r(t);var r=n(0),a=n(5),o=n(12),c=n.n(o),i=n(2),s=n(56),u=n(86),l=n(16),f=n(4),m=n(239),p=n(17),b=(n(243),n(246),n(43),n(15)),d=n(8),O=n(796);function h(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function g(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?h(Object(n),!0).forEach((function(t){y(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):h(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}function y(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}function v(e,t,n,r,a,o,c){try{var i=e[o](c),s=i.value}catch(e){return void n(e)}i.done?t(s):Promise.resolve(s).then(r,a)}function j(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){if("undefined"==typeof Symbol||!(Symbol.iterator in Object(e)))return;var n=[],r=!0,a=!1,o=void 0;try{for(var c,i=e[Symbol.iterator]();!(r=(c=i.next()).done)&&(n.push(c.value),!t||n.length!==t);r=!0);}catch(e){a=!0,o=e}finally{try{r||null==i.return||i.return()}finally{if(a)throw o}}return n}(e,t)||function(e,t){if(!e)return;if("string"==typeof e)return w(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(e);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return w(e,t)}(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function w(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}var S=function(e){var t=Object(r.useContext)(a.d),n=j(Object(r.useState)(!1),2),o=n[0],s=n[1],u=j(Object(r.useState)(""),2),l=u[0],m=u[1],p=j(Object(r.useState)({}),2),h=(p[0],p[1]),y=j(Object(r.useState)(""),2),w=y[0],S=y[1],_=Object(O.a)().setListData,k=e.query,E=e.onCloseModal;Object(r.useEffect)((function(){s(!1),h({}),S("")}),[]);var P=function(){var e,n=(e=regeneratorRuntime.mark((function e(){var n;return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:if(w){e.next=3;break}return m("Form name is required feed"),e.abrupt("return");case 3:return m(""),s(!0),e.prev=5,e.next=8,c()({path:Object(a.k)("/form-feeds/"),method:"POST",data:{name:w}});case 8:if(!((n=e.sent)&&n.result&&n.result.id)){e.next=17;break}h({}),Object(a.F)()||(bwfcrm_contacts_data.first_form_id=1),t(n.message),_(n.result),Object(b.k)(g(g({},k),{},{path:"/forms/".concat(n.result.id)}),"/forms/".concat(n.result.id)),e.next=18;break;case 17:throw Error(Object(a.i)(null==n?void 0:n.message));case 18:e.next=25;break;case 20:e.prev=20,e.t0=e.catch(5),m(e.t0.message),s(!1),h({});case 25:Object(a.Z)(t,2e3);case 26:case"end":return e.stop()}}),e,null,[[5,20]])})),function(){var t=this,n=arguments;return new Promise((function(r,a){var o=e.apply(t,n);function c(e){v(o,r,a,c,i,"next",e)}function i(e){v(o,r,a,c,i,"throw",e)}c(void 0)}))});return function(){return n.apply(this,arguments)}}();return Object(r.createElement)(f.Modal,{className:"bwf-admin-modal bwf-admin-modal-squeezy bwf-h--core-header",onRequestClose:function(){return E()}},Object(r.createElement)("div",{className:"bwf-modal-header"},Object(r.createElement)("div",{className:"bwf-modal-heading"},Object(i.__)("Add Form","wp-marketing-automations")),Object(r.createElement)("span",{onClick:function(){return E()},className:"bwf-modal-close"},Object(r.createElement)(d.a,{icon:"close",color:"#353030"}))),l&&Object(r.createElement)(f.Notice,{status:"error",onRemove:function(){return m("")}},l),Object(r.createElement)("div",{className:"bwf-form-fields",onKeyPress:function(e){"Enter"==e.key&&P()}},Object(r.createElement)(f.TextControl,{placeholder:Object(i.__)("Enter form name","wp-marketing-automations"),label:Object(i.__)("Name","wp-marketing-automations"),value:w,autoFocus:!0,onChange:function(e){return S(e)},disabled:o,autoComplete:"off"}),Object(r.createElement)("div",{className:"bwf_text_right bwf-form-buttons"},Object(r.createElement)(f.Button,{onClick:function(){return E()},className:"bwf-cancel-btn"},Object(i.__)("Cancel","wp-marketing-automations")),Object(r.createElement)(f.Button,{isPrimary:!0,disabled:o,isBusy:o,onClick:P},Object(i.__)("Add","wp-marketing-automations")))))},_=n(128);function k(e,t,n,r,a,o,c){try{var i=e[o](c),s=i.value}catch(e){return void n(e)}i.done?t(s):Promise.resolve(s).then(r,a)}function E(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){if("undefined"==typeof Symbol||!(Symbol.iterator in Object(e)))return;var n=[],r=!0,a=!1,o=void 0;try{for(var c,i=e[Symbol.iterator]();!(r=(c=i.next()).done)&&(n.push(c.value),!t||n.length!==t);r=!0);}catch(e){a=!0,o=e}finally{try{r||null==i.return||i.return()}finally{if(a)throw o}}return n}(e,t)||function(e,t){if(!e)return;if("string"==typeof e)return P(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(e);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return P(e,t)}(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function P(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}var C=function(e){var t=Object(r.useContext)(a.d),n=E(Object(r.useState)(""),2),o=n[0],s=n[1],u=e.isOpen,l=e.feedId,f=e.onRequestClose,m=e.feedName,p=function(){var e,n=(e=regeneratorRuntime.mark((function e(){return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.prev=0,e.next=3,c()({path:Object(a.k)("/form-feeds/".concat(l)),method:"POST",headers:{"X-HTTP-Method-Override":"DELETE"}});case 3:e.sent,t("Form deleted"),b(),e.next=12;break;case 8:e.prev=8,e.t0=e.catch(0),s(e.t0&&e.t0.message?e.t0.message:Object(i.__)("Unknown error occurred","wp-marketing-automations")),console.log(o);case 12:Object(a.Z)(t,2e3);case 13:case"end":return e.stop()}}),e,null,[[0,8]])})),function(){var t=this,n=arguments;return new Promise((function(r,a){var o=e.apply(t,n);function c(e){k(o,r,a,c,i,"next",e)}function i(e){k(o,r,a,c,i,"throw",e)}c(void 0)}))});return function(){return n.apply(this,arguments)}}(),b=function(){f(!o),s("")};return Object(r.createElement)(_.a,{modalTitle:Object(i.__)("Delete Form","wp-marketing-automations"),confirmButtonText:Object(i.__)("Delete","wp-marketing-automations"),cancelButtonText:Object(i.__)("Cancel","wp-marketing-automations"),deleteEntityName:m,onConfirm:p,errorMessage:o,onRequestClose:b,isOpen:u,isDelete:!0})},T=n(27),x=n(133),A=function(e){var t=e.query;return Object(r.createElement)(T.a,{autocompleter:x.e,multiple:!1,allowFreeTextSearch:!0,inlineTags:!0,onChange:function(e){Array.isArray(e)&&e.length>0&&e[0]&&e[0].key&&Object(b.k)({},"/forms/".concat(e[0].key),t)},placeholder:Object(i.__)("Search by name","wp-marketing-automations"),showClearButton:!0,disabled:!1,bwfEnableEmptySearch:!0})},M=n(6),R=n.n(M),D=n(63),F=n(87),I=n(127),N=n(3),L=function(){var e=arguments.length>1?arguments[1]:void 0,t=arguments.length>2&&void 0!==arguments[2]?arguments[2]:"",n=arguments.length>3&&void 0!==arguments[3]?arguments[3]:{},a=bwfcrm_contacts_data&&bwfcrm_contacts_data.header_data?bwfcrm_contacts_data.header_data:{},o=Object(I.a)(),c=o.setActiveMultiple,i=o.setL2NavType,s=o.setL2Nav,u=o.resetHeaderMenu,l=o.setPageHeader,f=o.setL2Content,m=o.setPageCountData;return Object(r.useEffect)((function(){u(),c({leftNav:"forms",rightNav:e}),i("menu"),s(a.forms_nav),l("Forms"),t&&f(t),!Object(N.isEmpty)(n)&&m(n)}),[e,n]),!0},B=n(244),q=n(195),U=n(792);function H(e,t,n,r,a,o,c){try{var i=e[o](c),s=i.value}catch(e){return void n(e)}i.done?t(s):Promise.resolve(s).then(r,a)}function z(e){return function(){var t=this,n=arguments;return new Promise((function(r,a){var o=e.apply(t,n);function c(e){H(o,r,a,c,i,"next",e)}function i(e){H(o,r,a,c,i,"throw",e)}c(void 0)}))}}var G=function(e){var t=e.isOpen,n=e.tasks,o=e.onSuccess,s=e.onError,u=e.onRequestClose,l=e.actionType,f=n?n.length:0,m=Object(U.a)(function(){var e=z(regeneratorRuntime.mark((function e(t){return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,c()({path:Object(a.k)("/bulk-action/form"),method:"POST",headers:{"X-HTTP-Method-Override":"DELETE"},data:{ids:t}});case 2:return e.abrupt("return",e.sent);case 3:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}(),{onSuccess:function(){setTimeout((function(){return m.reset()}),2500),o&&o()},onError:function(){s&&s()}}),p=function(){var e=z(regeneratorRuntime.mark((function e(){return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,m.mutate(n.map((function(e){return e.id})));case 2:return e.abrupt("return");case 3:case"end":return e.stop()}}),e)})));return function(){return e.apply(this,arguments)}}(),b={delete:{title:Object(i._n)("Delete Form","Delete Forms",f,"wp-marketing-automations"),description:Object(r.createElement)(r.Fragment,null,Object(i.__)("You are about to delete ","wp-marketing-automations"),Object(i._n)("form","forms",f,"wp-marketing-automations"),Object(i.__)(". This action cannot be undone. Cancel to stop, Delete to proceed.","wp-marketing-automations")),confirmBtn:Object(i.__)("Delete","wp-marketing-automations"),cancelBtn:Object(i.__)("Cancel","wp-marketing-automations"),successMsg:Object(i.__)("Bulk action executed successfully","wp-marketing-automations"),errorMsg:Object(i.__)("Unable to delete form feeds","wp-marketing-automations"),confirmDescription:Object(i.__)("This action is irreversible","wp-marketing-automations")}};return Object(r.createElement)(_.a,{modalTitle:b.hasOwnProperty(l)&&b[l].hasOwnProperty("title")?b[l].title:Object(i._n)("Perform Actions","Perform Actions",f,"wp-marketing-automations"),deleteDescriptionText:b.hasOwnProperty(l)&&b[l].hasOwnProperty("description")?b[l].description:"",confirmButtonText:b.hasOwnProperty(l)&&b[l].hasOwnProperty("confirmBtn")?b[l].confirmBtn:Object(i.__)("Confirm","wp-marketing-automations"),cancelButtonText:b.hasOwnProperty(l)&&b[l].hasOwnProperty("cancelBtn")?b[l].cancelBtn:Object(i.__)("Cancel","wp-marketing-automations"),onConfirm:p,isLoading:m.isLoading,successMessage:m.isSuccess&&(b.hasOwnProperty(l)&&b[l].hasOwnProperty("successMsg")?b[l].successMsg:Object(i.__)("Actions Done!","wp-marketing-automations")),errorMessage:m.isError&&(m.error&&m.error.message?m.error.message:b.hasOwnProperty(l)&&b[l].hasOwnProperty("errorMsg")?b[l].errorMsg:Object(i.__)("Unable to perform action","wp-marketing-automations")),onRequestClose:function(){return!!u&&u()},isOpen:t,confirmDescription:b.hasOwnProperty(l)&&b[l].hasOwnProperty("confirmDescription")?b[l].confirmDescription:"",isDelete:!0})};function $(){return($=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e}).apply(this,arguments)}function J(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){if("undefined"==typeof Symbol||!(Symbol.iterator in Object(e)))return;var n=[],r=!0,a=!1,o=void 0;try{for(var c,i=e[Symbol.iterator]();!(r=(c=i.next()).done)&&(n.push(c.value),!t||n.length!==t);r=!0);}catch(e){a=!0,o=e}finally{try{r||null==i.return||i.return()}finally{if(a)throw o}}return n}(e,t)||function(e,t){if(!e)return;if("string"==typeof e)return Z(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(e);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return Z(e,t)}(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function Z(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}var K=function(e){var t=e.floatingBarProps,n=void 0===t?{}:t,a=e.resetSelection,o=e.onSuccess,c=e.screenType,s=e.screenTypeId,u=e.automationId,l=J(Object(r.useState)([]),2),f=l[0],m=l[1],p=J(Object(r.useState)(!1),2),b=p[0],d=p[1],O=J(Object(r.useState)(""),2),h=O[0],g=O[1],y=function(){a&&a(),m([])};Object(r.useEffect)((function(){y()}),[c]);var v=Object(r.useCallback)((function(e,t){m(t),g(e),d(!0)}),[]);return Object(r.createElement)(r.Fragment,null,Object(r.createElement)(q.a,$({actions:[{id:"delete",icon:"trash",hint:Object(i.__)("Delete","wp-marketing-automations")}],onAction:v},n)),Object(r.createElement)(G,{tasks:f,isOpen:b,onSuccess:function(){o&&o(h,f),y()},onError:y,onRequestClose:function(){return d(!1)},screenType:c,screenTypeId:s,actionType:h,automationId:u}))};function Q(e,t,n,r,a,o,c){try{var i=e[o](c),s=i.value}catch(e){return void n(e)}i.done?t(s):Promise.resolve(s).then(r,a)}function V(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){if("undefined"==typeof Symbol||!(Symbol.iterator in Object(e)))return;var n=[],r=!0,a=!1,o=void 0;try{for(var c,i=e[Symbol.iterator]();!(r=(c=i.next()).done)&&(n.push(c.value),!t||n.length!==t);r=!0);}catch(e){a=!0,o=e}finally{try{r||null==i.return||i.return()}finally{if(a)throw o}}return n}(e,t)||X(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function X(e,t){if(e){if("string"==typeof e)return W(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);return"Object"===n&&e.constructor&&(n=e.constructor.name),"Map"===n||"Set"===n?Array.from(e):"Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)?W(e,t):void 0}}function W(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}var Y={1:"bwf-tags bwf-tag-gray",2:"bwf-tags bwf-tag-green",3:"bwf-tags bwf-tag-gray"};t.default=function(e){var t=e.selected;Object(a.f)("Forms");var n=location&&location.search?Object(p.parse)(location.search.substring(1)):{},o=V(Object(r.useState)(25),2),h=o[0],g=o[1],y=V(Object(r.useState)(0),2),v=y[0],j=y[1],w=V(Object(r.useState)(0),2),_=w[0],k=w[1],E=V(Object(r.useState)([]),2),P=E[0],T=E[1],x=V(Object(r.useState)(!1),2),M=x[0],I=x[1],N=V(Object(r.useState)(!1),2),U=N[0],H=N[1],z=V(Object(r.useState)(),2),G=z[0],$=z[1],J=V(Object(r.useState)(!1),2),Z=J[0],W=J[1],ee=V(Object(r.useState)({}),2),te=ee[0],ne=ee[1],re=V(Object(r.useState)(!1),2),ae=re[0],oe=re[1],ce=V(Object(r.useState)(0),2),ie=ce[0],se=ce[1],ue=V(Object(r.useState)(""),2),le=ue[0],fe=ue[1],me=Object(O.a)().setListData,pe=bwfcrm_contacts_data&&bwfcrm_contacts_data.form_nice_names?bwfcrm_contacts_data.form_nice_names:{},be=Object(r.useRef)(new AbortController),de=Object(r.createElement)(f.Button,{isPrimary:!0,className:"bwf-display-flex",onClick:function(){Object(a.gb)()?I(!0):W(!0)}},!Object(a.gb)()&&Object(r.createElement)(d.a,{icon:"lock",size:16,color:"#fff"}),Object(r.createElement)("span",null,Object(i.__)("Add New Form","wp-marketing-automations")));L("Forms",t,de,te);var Oe={all:0,active:2,inactive:3},he=function(){var e,n=(e=regeneratorRuntime.mark((function e(){var n,r,o,s,u;return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return H(!0),e.prev=1,e.next=4,c()({method:"GET",path:Object(a.k)("/form-feeds?offset=".concat(v,"&limit=").concat(h,"&status=").concat(Oe[t])),signal:be.current.signal});case 4:if((n=e.sent)&&n.result&&Array.isArray(n.result)){e.next=8;break}return $(Object(i.__)("Blank response returned","wp-marketing-automations")),e.abrupt("return");case 8:r=n.total_count,o=n.result,s=n.limit,u=n.offset,r&&k(parseInt(r)),s&&g(parseInt(s)),u&&j(parseInt(u)),o&&T(o),n.hasOwnProperty("count_data")?ne(n.count_data):ne({}),H(!1),e.next=20;break;case 17:e.prev=17,e.t0=e.catch(1),"AbortError"===e.t0.name?console.log(e.t0.message):($(e.t0&&e.t0.message?e.t0.message:Object(i.__)("Unknown Error Occurred","wp-marketing-automations")),H(!1));case 20:case"end":return e.stop()}}),e,null,[[1,17]])})),function(){var t=this,n=arguments;return new Promise((function(r,a){var o=e.apply(t,n);function c(e){Q(o,r,a,c,i,"next",e)}function i(e){Q(o,r,a,c,i,"throw",e)}c(void 0)}))});return function(){return n.apply(this,arguments)}}();Object(r.useEffect)((function(){Object(a.gb)()&&he()}),[t]),Object(r.useEffect)((function(){return function(){be.current.abort()}}),[]);var ge=Object(r.useMemo)((function(){var e={};if(Array.isArray(P)){var t,n=function(e,t){var n;if("undefined"==typeof Symbol||null==e[Symbol.iterator]){if(Array.isArray(e)||(n=X(e))||t&&e&&"number"==typeof e.length){n&&(e=n);var r=0,a=function(){};return{s:a,n:function(){return r>=e.length?{done:!0}:{done:!1,value:e[r++]}},e:function(e){throw e},f:a}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var o,c=!0,i=!1;return{s:function(){n=e[Symbol.iterator]()},n:function(){var e=n.next();return c=e.done,e},e:function(e){i=!0,o=e},f:function(){try{c||null==n.return||n.return()}finally{if(i)throw o}}}}(P);try{for(n.s();!(t=n.n()).done;){var r=t.value;e[r.id]=r}}catch(e){n.e(e)}finally{n.f()}}return e}),[P]),ye=Object(q.b)(ge),ve=ye.singleSelectProps,je=ye.selectAllProps,we=ye.floatingBarProps,Se=ye.resetSelection,_e=[{key:"select_feed",label:Object(r.createElement)(f.CheckboxControl,je),isLeftAligned:!0,required:!0,cellClassName:"bwf-col-action bwf-w-45"},{key:"action",label:"",isLeftAligned:!0,required:!0,cellClassName:"bwf-col-action bwf-w-45"},{key:"name",label:Object(i.__)("Name","wp-marketing-automations"),isLeftAligned:!0,required:!0},{key:"created_on",label:Object(i.__)("Created On","wp-marketing-automations"),isLeftAligned:!0,required:!0,cellClassName:"bwf-w-180"},{key:"form",label:Object(i.__)("Form","wp-marketing-automations"),isLeftAligned:!0,cellClassName:"bwf-w-210"},{key:"submissions",label:Object(i.__)("Submissions","wp-marketing-automations"),isLeftAligned:!0,cellClassName:"bwf-col-center bwf-w-150"},{key:"status",label:Object(i.__)("Status","wp-marketing-automations"),isLeftAligned:!0,cellClassName:"bwf-w-90 bwf-col-center"}],ke=function(e){e!==h&&(g(h),he())},Ee=function(e){return Object(r.createElement)(s.a,{label:Object(i.__)("Quick Actions","wp-marketing-automations"),menuPosition:"bottom right",renderContent:function(t){var n=t.onToggle;return Object(r.createElement)(r.Fragment,null,Object(r.createElement)(u.a,{isClickable:!0,onInvoke:function(){me(e),Object(b.k)({page:"autonami",path:"/forms/".concat(e.id)},"/forms/".concat(e.id),{}),n()}},Object(r.createElement)(l.a,{justify:"flex-start"},Object(r.createElement)(l.c,null,Object(r.createElement)(d.a,{icon:"view"})),Object(r.createElement)(l.c,null,Object(i.__)("View","wp-marketing-automations")))),Object(r.createElement)(u.a,{isClickable:!0,onInvoke:function(){se(e.id),fe(e.title),oe(!0),n()}},Object(r.createElement)(l.a,{justify:"flex-start"},Object(r.createElement)(l.c,null,Object(r.createElement)(d.a,{icon:"trash"})),Object(r.createElement)(l.c,null,Object(i.__)("Delete","wp-marketing-automations")))))}})},Pe=function(e){return Object(r.createElement)("a",{href:"admin.php?page=autonami&path=/forms/"+e.id,type:"bwf-crm",className:"bwf-a-no-underline",onClick:function(t){t.preventDefault(),me(e),Object(b.k)({page:"autonami",path:"/forms/".concat(e.id)},"/forms/".concat(e.id),{})}},Object(r.createElement)("b",null,e.title))},Ce=Array.isArray(P)?P.map((function(e){return[{display:Object(r.createElement)(f.CheckboxControl,ve[e.id]),value:null},{display:Ee(e),value:null},{display:Pe(e),value:e.title},{display:e.created_on?Object(a.K)(e.created_on,!1,!1):" - ",value:e.created_on},{display:e.source in pe?pe[e.source]:"-",value:e.source},{display:e.contacts_created&&parseInt(e.contacts_created)>0?e.contacts_created:"-",value:e.contacts_created},{display:Object(r.createElement)("span",{className:Y[null==e?void 0:e.status]},e.status_text),value:e.status}]})):[],Te=R()("bwfcrm-forms-list-table",{"has-search":!0}),xe=[{type:"icon",data:{class:"bwf-pb-gap",content:Object(r.createElement)(d.a,{icon:"zero-forms",width:"90",height:"90"})}},{type:"content",data:{class:"bwf-h2 bwf-pb-10",content:Object(i.__)("Create Lead Capture Forms","wp-marketing-automations")}},{type:"content",data:{class:"bwf-h4-1 bwf-pb-gap",content:Object(i.__)("Integrates with popular form plugins such as WPForms, Elementor, Gravity Forms & more. Push Contacts into CRM & nurture leads to become customers.","wp-marketing-automations")}},{type:"buttons",data:{buttons:[{text:Object(i.__)("Integrate New Form","wp-marketing-automations"),proCheck:!0,onClick:function(){Object(a.gb)()?I(!0):W(!0)}}]}}];return Object(r.createElement)(r.Fragment,null,Object(a.F)()?Object(r.createElement)(r.Fragment,null,Object(r.createElement)(D.a,null),G&&Object(r.createElement)(f.Notice,{status:"error"},G),Object(r.createElement)(m.a,{className:Te,title:"",rows:Ce,headers:_e,query:{paged:v/h+1},rowsPerPage:h,totalRows:_,isLoading:U,onPageChange:function(e,t){j((e-1)*h),he()},onQueryChange:function(e){return"per_page"!==e?function(){}:ke},rowHeader:!0,showMenu:!1,actions:[Object(r.createElement)(A,{key:"search",query:n})],emptyMessage:Object(i.__)("No forms found","wp-marketing-automations")}),Object(r.createElement)(C,{feedName:le,feedId:ie,isOpen:ae,onRequestClose:function(e){oe(!1),e&&he()}})):Object(r.createElement)(B.a,{data:xe}),M&&Object(r.createElement)(S,{query:n,onCloseModal:function(){return I(!1)}}),Object(r.createElement)(F.a,{isOpen:Z,onRequestClose:function(){return W(!1)}}),Object(r.createElement)(K,{floatingBarProps:we,resetSelection:Se,onSuccess:function(){return he()}}))}},792:function(e,t,n){"use strict";n.d(t,"a",(function(){return m}));var r=n(11),a=n(7),o=n.n(a),c=n(25),i=n(13),s=n(26),u=n(245),l=function(e){function t(t,n){var r;return(r=e.call(this)||this).client=t,r.setOptions(n),r.bindMethods(),r.updateResult(),r}Object(s.a)(t,e);var n=t.prototype;return n.bindMethods=function(){this.mutate=this.mutate.bind(this),this.reset=this.reset.bind(this)},n.setOptions=function(e){this.options=this.client.defaultMutationOptions(e)},n.onUnsubscribe=function(){var e;this.listeners.length||(null==(e=this.currentMutation)||e.removeObserver(this))},n.onMutationUpdate=function(e){this.updateResult();var t={listeners:!0};"success"===e.type?t.onSuccess=!0:"error"===e.type&&(t.onError=!0),this.notify(t)},n.getCurrentResult=function(){return this.currentResult},n.reset=function(){this.currentMutation=void 0,this.updateResult(),this.notify({listeners:!0})},n.mutate=function(e,t){return this.mutateOptions=t,this.currentMutation&&this.currentMutation.removeObserver(this),this.currentMutation=this.client.getMutationCache().build(this.client,Object(r.a)({},this.options,{variables:void 0!==e?e:this.options.variables})),this.currentMutation.addObserver(this),this.currentMutation.execute()},n.updateResult=function(){var e=this.currentMutation?this.currentMutation.state:Object(u.b)();this.currentResult=Object(r.a)({},e,{isLoading:"loading"===e.status,isSuccess:"success"===e.status,isError:"error"===e.status,isIdle:"idle"===e.status,mutate:this.mutate,reset:this.reset})},n.notify=function(e){var t=this;c.a.batch((function(){t.mutateOptions&&(e.onSuccess?(null==t.mutateOptions.onSuccess||t.mutateOptions.onSuccess(t.currentResult.data,t.currentResult.variables,t.currentResult.context),null==t.mutateOptions.onSettled||t.mutateOptions.onSettled(t.currentResult.data,null,t.currentResult.variables,t.currentResult.context)):e.onError&&(null==t.mutateOptions.onError||t.mutateOptions.onError(t.currentResult.error,t.currentResult.variables,t.currentResult.context),null==t.mutateOptions.onSettled||t.mutateOptions.onSettled(void 0,t.currentResult.error,t.currentResult.variables,t.currentResult.context))),e.listeners&&t.listeners.forEach((function(e){e(t.currentResult)}))}))},t}(n(52).a),f=n(242);function m(e,t,n){var a=o.a.useRef(!1),s=o.a.useState(0)[1],u=Object(i.k)(e,t,n),m=Object(f.b)(),p=o.a.useRef();p.current?p.current.setOptions(u):p.current=new l(m,u);var b=p.current.getCurrentResult();o.a.useEffect((function(){a.current=!0;var e=p.current.subscribe(c.a.batchCalls((function(){a.current&&s((function(e){return e+1}))})));return function(){a.current=!1,e()}}),[]);var d=o.a.useCallback((function(e,t){p.current.mutate(e,t).catch(i.i)}),[]);if(b.error&&p.current.options.useErrorBoundary)throw b.error;return Object(r.a)({},b,{mutate:d,mutateAsync:b.mutate})}},796:function(e,t,n){"use strict";var r=n(129),a=n(5),o=n(265),c=n(797),i=n(12),s=n.n(i),u=n(0);function l(e,t,n,r,a,o,c){try{var i=e[o](c),s=i.value}catch(e){return void n(e)}i.done?t(s):Promise.resolve(s).then(r,a)}function f(e){return function(){var t=this,n=arguments;return new Promise((function(r,a){var o=e.apply(t,n);function c(e){l(o,r,a,c,i,"next",e)}function i(e){l(o,r,a,c,i,"throw",e)}c(void 0)}))}}function m(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function p(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?m(Object(n),!0).forEach((function(t){b(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):m(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}function b(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}function d(e,t){if(null==e)return{};var n,r,a=function(e,t){if(null==e)return{};var n,r,a={},o=Object.keys(e);for(r=0;r<o.length;r++)n=o[r],t.indexOf(n)>=0||(a[n]=e[n]);return a}(e,t);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(e);for(r=0;r<o.length;r++)n=o[r],t.indexOf(n)>=0||Object.prototype.propertyIsEnumerable.call(e,n)&&(a[n]=e[n])}return a}t.a=function(){var e,t,n,i=Object(u.useContext)(a.d),l=Object(r.a)(o.b.feed),m=l.fetch,O=l.setStateProp,h=d(l,["fetch","setStateProp"]),g=Object(c.a)(),y=g.getFeed,v=g.getSelections,j=g.getSelectionOptions,w=y(),S=(w||{}).source,_=void 0===S?"":S,k=v(),E=j();return p(p({},h),{},{fetch:function(e){m("GET",Object(a.k)("/form-feeds/".concat(e)))},setFeed:function(e){return O("feed",e)},setStep:function(e){return O("step",e)},syncSelection:function(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{},n=arguments.length>2&&void 0!==arguments[2]&&arguments[2];m("POST",Object(a.k)("/form-feeds/".concat(e,"/sync-selection")),{selection:t,form:_,return_all_options:n},{syncSelection:!0})},fetchMappingData:function(e){m("GET",Object(a.k)("/form-feeds/".concat(e,"/mapping-data")),{},{mappingDataFetch:!0})},setFormSource:function(e){var t=w?p(p({},w),{},{source:e}):{source:e};O("feed",t)},setSelection:function(e,t){if(E){var n=9999,r=p({},k);Object.keys(E).map((function(a){var o=E[a];o.slug===e&&(n=parseInt(a),r=p(p({},r),{},b({},e,t))),a>n&&o.slug in r&&delete r[o.slug]})),O("selection",r),O("updateFeed",!1),O("mapData",{}),O("updateStatus",1)}else O("selection",p(p({},k),{},b({},e,t)))},resetAll:function(){O("step","selection"),O("feed",null),O("selectionOptions",{}),O("selectionTotal",10),O("selection",{}),O("error",null),O("editMapFieldsMode",!1)},resetSelection:function(){O("selectionOptions",{}),O("selectionTotal",10),O("selection",{})},setFeedStatus:function(e){return O("feed",p(p({},w),{},{status:e}))},setEditMapMode:function(e){return O("editMapFieldsMode",e)},setError:function(e){return O("error",e)},updateStepTwo:(n=f(regeneratorRuntime.mark((function e(t,n,r){var o;return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return O("updateStepTwoStatus",1),e.prev=1,e.next=4,s()({path:Object(a.k)("/form-feeds/".concat(t)),method:"POST",data:p(p({},n),{},{status:r})});case 4:(o=e.sent)&&o.result&&o.result.id&&(O("updateStepTwoStatus",2),O("feed",o.result)),e.next=12;break;case 8:e.prev=8,e.t0=e.catch(1),O("updateStepTwoStatus",3),console.log(e.t0);case 12:case"end":return e.stop()}}),e,null,[[1,8]])}))),function(e,t,r){return n.apply(this,arguments)}),resetUpdateStepTwoStatus:function(){return O("updateStepTwoStatus",null)},updateStepThree:(t=f(regeneratorRuntime.mark((function e(t,n,r){var o,c,u;return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return O("updateStepThreeStatus",1),e.prev=1,o=p(p({},n),{},{status:r}),c={content:JSON.stringify(o)},e.next=6,s()({path:Object(a.k)("/form-feeds/".concat(t,"/save-email-data")),method:"POST",data:c});case 6:(u=e.sent)&&u.result&&u.result.id&&(i("Form Updated"),Object(a.Z)(i,2e3),O("editMapFieldsMode",!1),O("updateStepThreeStatus",2),O("feed",u.result)),e.next=14;break;case 10:e.prev=10,e.t0=e.catch(1),O("updateStepThreeStatus",3),console.log(e.t0);case 14:case"end":return e.stop()}}),e,null,[[1,10]])}))),function(e,n,r){return t.apply(this,arguments)}),resetUpdateStepThreeStatus:function(){return O("updateStepThreeStatus",null)},setIncentivizeEmail:function(e){var t=w&&w.data?w.data:{};t.incentivize_email=e,O("feed",p(p({},w),{},{data:t}))},setMarketingStatus:function(e){var t=w&&w.data?w.data:{};t.marketing_status=e,O("feed",p(p({},w),{},{data:t}))},setAddTagEnabled:function(e){var t=w&&w.data?w.data:{};t.add_tag_enable=e,O("feed",p(p({},w),{},{data:t}))},setTagList:function(e){var t=w&&w.data?w.data:{};t.tag_to_add=e,O("feed",p(p({},w),{},{data:t}))},setRedirectMode:function(e){var t=w&&w.data?w.data:{};t.redirect_mode=e,O("feed",p(p({},w),{},{data:t}))},setRedirectUrl:function(e){var t=w&&w.data?w.data:{};t.redirect_url=e,O("feed",p(p({},w),{},{data:t}))},setNotSendToSubscribed:function(e){var t=w&&w.data?w.data:{};t.not_send_to_subscribed=e,O("feed",p(p({},w),{},{data:t}))},updateStatus:(e=f(regeneratorRuntime.mark((function e(t,n){var r;return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return O("updateStatusStatus",1),e.prev=1,e.next=4,s()({path:Object(a.k)("/form-feeds/".concat(t,"/status")),method:"POST",data:{status:n}});case 4:(r=e.sent)&&r.result&&r.result.id&&(O("updateStatusStatus",2),O("feed",r.result)),e.next=12;break;case 8:e.prev=8,e.t0=e.catch(1),O("updateStatusStatus",3),console.log(e.t0);case 12:case"end":return e.stop()}}),e,null,[[1,8]])}))),function(t,n){return e.apply(this,arguments)}),resetUpdateStatusStatus:function(){return O("updateStatusStatus",null)},setListData:function(e){return O("listData",e)}})}},797:function(e,t,n){"use strict";var r=n(130),a=n(265);function o(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function c(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?o(Object(n),!0).forEach((function(t){i(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):o(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}function i(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}function s(e,t){if(null==e)return{};var n,r,a=function(e,t){if(null==e)return{};var n,r,a={},o=Object.keys(e);for(r=0;r<o.length;r++)n=o[r],t.indexOf(n)>=0||(a[n]=e[n]);return a}(e,t);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(e);for(r=0;r<o.length;r++)n=o[r],t.indexOf(n)>=0||Object.prototype.propertyIsEnumerable.call(e,n)&&(a[n]=e[n])}return a}t.a=function(){var e=Object(r.a)(a.b.feed),t=e.getStateProp;return c(c({},s(e,["getStateProp"])),{},{getFeed:function(){return t("feed")},getStep:function(){var e=t("editMapFieldsMode"),n=t("step");return e&&"selection"===n?"mapping":n},getSelectionOptions:function(){return t("selectionOptions")},getSelectionOptionsTotal:function(){return t("selectionTotal")},getSelections:function(){return t("selection")},getSelectionValue:function(e){var n=t("selection");return n&&n[e]?n[e]:""},getLoading:function(){return t("isLoading")},getFormFields:function(){return t("fields")},getFormHeaders:function(){return t("headers")},getUpdateStepTwoStatus:function(){return t("updateStepTwoStatus")},getUpdateStepThreeStatus:function(){return t("updateStepThreeStatus")},getUpdateStatusStatus:function(){return t("updateStatusStatus")},getEditMapMode:function(){return t("editMapFieldsMode")},getListData:function(){return t("listData")}})}}}]);