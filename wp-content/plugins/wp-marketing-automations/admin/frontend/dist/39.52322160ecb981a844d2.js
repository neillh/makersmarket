(window.webpackJsonp=window.webpackJsonp||[]).push([[39],{1098:function(e,t,a){},1188:function(e,t,a){"use strict";a.r(t);var r=a(0),c=a(864),n=a(2),i=a(127),l=a(5),s=a(15),b=a(4),o=a(269),m=a(838),f=a(818),u=a(12),O=a.n(u),w=a(3),g=a(63),d=a(56),j=a(86),p=a(16),v=a(8),E=a(386),_=a(277);function k(e,t){var a=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),a.push.apply(a,r)}return a}function h(e){for(var t=1;t<arguments.length;t++){var a=null!=arguments[t]?arguments[t]:{};t%2?k(Object(a),!0).forEach((function(t){N(e,t,a[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(a)):k(Object(a)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(a,t))}))}return e}function N(e,t,a){return t in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}function y(e,t,a,r,c,n,i){try{var l=e[n](i),s=l.value}catch(e){return void a(e)}l.done?t(s):Promise.resolve(s).then(r,c)}function L(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){if("undefined"==typeof Symbol||!(Symbol.iterator in Object(e)))return;var a=[],r=!0,c=!1,n=void 0;try{for(var i,l=e[Symbol.iterator]();!(r=(i=l.next()).done)&&(a.push(i.value),!t||a.length!==t);r=!0);}catch(e){c=!0,n=e}finally{try{r||null==l.return||l.return()}finally{if(c)throw n}}return a}(e,t)||function(e,t){if(!e)return;if("string"==typeof e)return P(e,t);var a=Object.prototype.toString.call(e).slice(8,-1);"Object"===a&&e.constructor&&(a=e.constructor.name);if("Map"===a||"Set"===a)return Array.from(e);if("Arguments"===a||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(a))return P(e,t)}(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function P(e,t){(null==t||t>e.length)&&(t=e.length);for(var a=0,r=new Array(t);a<t;a++)r[a]=e[a];return r}var S=function(e){var t=e.id,a=Object(r.useContext)(l.d),c=L(Object(r.useState)(!1),2),s=c[0],u=c[1],k=L(Object(r.useState)([]),2),N=k[0],P=k[1],S=Object(l.R)(),C=Object(m.a)(),D=C.getSingleLinkLoad,x=C.getSingleLinkData,T=C.getLinkActionList,A=C.getLinkActionSchemaList,I=C.getLinkEditableStatus,R=C.getSingleLinkListData,B=Object(f.a)().setSingleLinkValue,H=x(),U=R(),M=H.title,V=void 0===M?"":M,F=H.desc,q=void 0===F?"":F,G=H.redirect_url,J=void 0===G?"":G,Y=H.redirect_url_title,Z=void 0===Y?"":Y,Q=H.actions,W=void 0===Q?{}:Q,$=H.action_run,z=void 0===$?"once":$,K=H.status,X=H.total_clicked,ee=H.hash,te=H.add_contact_note,ae=void 0!==te&&te,re=H.enable_auto_login,ce=void 0!==re&&re,ne=H.auto_login,ie=void 0===ne?{unit:"days",text:7}:ne,le=D(),se=T(),be=A(),oe=I(),me=Object(i.a)().setL2Title,fe=function(){return le&&U.hasOwnProperty("title")&&""!=U.title?U.title:le?Object(r.createElement)("div",{className:"bwf-placeholder-temp bwf-h-15 bwf-w-90"}):V};Object(r.useEffect)((function(){return me(fe()),function(){return B("listData",{})}}),[]),Object(r.useEffect)((function(){me(fe())}),[H,le,U]);var ue=function(){var e={};return Object.keys(W).map((function(t){if((!Object(w.isEmpty)(W[t])||be.hasOwnProperty(t)&&Object(w.isEmpty)(be[t]))&&(e[t]=W[t]),Object(w.isObject)(e[t])){var a={};Object.keys(e[t]).map((function(r){Object(w.isEmpty)(e[t][r])||(a[r]=e[t][r])})),e[t]=a}})),e},Oe=function(){var e,r=(e=regeneratorRuntime.mark((function e(){var r,c,i=arguments;return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:if(r=i.length>0&&void 0!==i[0]?i[0]:2,we()){e.next=15;break}return u(!0),a(Object(n.__)("Updating link trigger…","wp-marketing-automations")),c={title:V,redirect_url:J,redirect_url_title:Z,action_run:z,desc:q,actions:ue(),status:r,add_contact_note:ae,enable_auto_login:ce,auto_login:ie},e.prev=5,e.next=8,O()({path:Object(l.k)("/link-trigger/".concat(t)),method:"POST",data:c}).then((function(e){200==e.code&&(B("data",e.result),B("isEditable",!1)),a(e.message),u(!1)}));case 8:e.next=14;break;case 10:e.prev=10,e.t0=e.catch(5),a(e.t0.message),u(!1);case 14:Object(l.Z)(a,1e3);case 15:case"end":return e.stop()}}),e,null,[[5,10]])})),function(){var t=this,a=arguments;return new Promise((function(r,c){var n=e.apply(t,a);function i(e){y(n,r,c,i,l,"next",e)}function l(e){y(n,r,c,i,l,"throw",e)}i(void 0)}))});return function(){return r.apply(this,arguments)}}(),we=function(){var e=!1,t=[];Object(w.isEmpty)(V)&&(e=!0,t.push(Object(n.__)("Name is required field.","wp-marketing-automations"))),Object(w.isEmpty)(J)&&(e=!0,t.push(Object(n.__)("Redirect URL is required field.","wp-marketing-automations")));var a=!1;return Object.keys(W).map((function(e){be.hasOwnProperty(e)&&!Object(w.isEmpty)(be[e])&&Object(w.isEmpty)(W[e])&&"new"!==e&&(a=!0),Object(w.isObject)(W[e])&&Object.keys(W[e]).map((function(t){Object(w.isEmpty)(W[e][t])&&(a=!0)}))})),a&&(e=!0,t.push(Object(n.__)("Please fill all action values.","wp-marketing-automations"))),P(t),e},ge=function(){var e=S+"/?bwfan-link-trigger="+ee;return Object(r.createElement)("div",{className:"bwf-link-trigger-copy"},Object(r.createElement)("div",{key:0,className:"bwf-smart-url"},e),Object(r.createElement)("div",{key:1,className:"bwf-copy-icon",onClick:function(){return t=e,(r=document.createElement("textarea")).value=t,document.body.appendChild(r),r.select(),document.execCommand("copy"),document.body.removeChild(r),a(Object(n.__)("Link copied","wp-marketing-automations")),void Object(l.Z)(a,1e3);var t,r}},Object(r.createElement)(v.a,{icon:"copy"})))};return Object(r.createElement)(r.Fragment,null,le?U.hasOwnProperty("status")&&parseInt(U.status)>0?Object(r.createElement)("div",{className:"bwfcrm-overview-wrap"},Object(r.createElement)(b.Card,{className:"bwf-crm-link-trigger-detail-wrap"},Object(r.createElement)(b.CardHeader,{className:"bwf-crm-link-trigger-header"},Object(r.createElement)("span",{className:"bwf-link-trigger-title"},Object(n.__)("Overview","wp-marketing-automations")),Object(r.createElement)("div",{className:"bwf-display-flex bwf-bulk-card-header"},Object(r.createElement)("div",{className:"bwf-placeholder-temp bwf-h-15 bwf-w-90"}))),Object(r.createElement)(b.CardBody,null,[1,2,3,4,5,6,7].map((function(e){return Object(r.createElement)("div",{className:"bwf-link-trigger-list",key:e},Object(r.createElement)("div",{className:"bwf-link-trigger-list-label"},Object(r.createElement)("div",{className:"bwf-placeholder-temp bwf-h-15 bwf-w-90"})),Object(r.createElement)("div",{className:"bwf-link-trigger-list-value"},Object(r.createElement)("div",{className:"bwf-placeholder-temp bwf-h-15 bwf-w-210"})))}))))):Object(r.createElement)("div",{className:"bwfcrm-overview-wrap bwfcrm-link-trigger-loading"},Object(r.createElement)(b.Card,{className:"bwf-crm-link-trigger-report-detail-wrap"},Object(r.createElement)(b.CardHeader,{className:"bwf-crm-link-trigger-header"},Object(r.createElement)("span",{className:"bwf-link-trigger-title"},Object(n.__)("Link Trigger","wp-marketing-automations"))),Object(r.createElement)(b.CardBody,{className:"bwf-crm-link-trigger-content"},Object(r.createElement)("div",{className:"bwf-section"},Object(r.createElement)("div",{className:"bwf_clear_20"}),Object(r.createElement)("div",{className:"bwf-placeholder-temp bwf-h-15 bwf-w-90"}),Object(r.createElement)("div",{className:"bwf_clear_10"}),Object(r.createElement)("div",{className:"bwf-placeholder-temp bwf-h-30 bwf-w-100p"}),Object(r.createElement)("div",{className:"bwf_clear_20"}),Object(r.createElement)("div",{className:"bwf-placeholder-temp bwf-h-15 bwf-w-90"}),Object(r.createElement)("div",{className:"bwf_clear_10"}),Object(r.createElement)("div",{className:"bwf-placeholder-temp bwf-h-60 bwf-w-100p"}),Object(r.createElement)("div",{className:"bwf_clear_20"}),Object(r.createElement)("div",{className:"bwf-placeholder-temp bwf-h-15 bwf-w-90"}),Object(r.createElement)("div",{className:"bwf_clear_10"}),Object(r.createElement)("div",{className:"bwf-placeholder-temp bwf-h-30 bwf-w-100p"}),Object(r.createElement)("div",{className:"bwf_clear_20"}),Object(r.createElement)("div",null,Object(r.createElement)("div",{className:"bwf-placeholder-temp bwf-h-15 bwf-w-90"}),[1,2,3].map((function(e){return Object(r.createElement)("div",{key:e},Object(r.createElement)("div",{className:"bwf_clear_20"}),Object(r.createElement)("div",{className:"bwf-display-flex bwf-space-between"},Object(r.createElement)("div",{className:"bwf-display-flex gap-20"},Object(r.createElement)("div",{className:"bwf-placeholder-temp bwf-w-120 bwf-h-15"}),Object(r.createElement)("div",{className:"bwf-placeholder-temp bwf-w-550 bwf-h-15"})),Object(r.createElement)("div",{className:"bwf-placeholder-temp bwf-h-15 bwf-w-30"})))}))),Object(r.createElement)("div",{className:"bwf_clear_20"}),Object(r.createElement)("div",{className:"bwf-placeholder-temp bwf-h-15 bwf-w-90"}),Object(r.createElement)("div",{className:"bwf_clear_20"}),Object(r.createElement)("div",{className:"bwf-placeholder-temp bwf-h-30 bwf-w-100p"}),Object(r.createElement)("div",{className:"bwf_clear_30"}),Object(r.createElement)("div",{className:"bwf-display-flex bwf-space-between"},Object(r.createElement)("div",{className:"bwf-placeholder-temp bwf-w-210 bwf-h-15"}),Object(r.createElement)("div",{className:"bwf-placeholder-temp bwf-h-15 bwf-w-30"})),Object(r.createElement)("div",{className:"bwf_clear_30"}),Object(r.createElement)("div",{className:"bwf-display-flex bwf-space-between"},Object(r.createElement)("div",{className:"bwf-placeholder-temp bwf-w-210 bwf-h-15"}),Object(r.createElement)("div",{className:"bwf-placeholder-temp bwf-h-15 bwf-w-30"}))),Object(r.createElement)("div",{className:"bwf_clear_20"}),Object(r.createElement)("hr",null),Object(r.createElement)("div",{className:"bwf_clear_10"}),Object(r.createElement)("div",{className:"bwf-section"},Object(r.createElement)("div",{className:"bwf_text_right"},Object(r.createElement)("div",{className:"bwf-placeholder-temp bwf-h-36 bwf-w-120"}))),Object(r.createElement)("div",{className:"bwf_clear_10"})))):oe||0===parseInt(K)?Object(r.createElement)("div",{className:"bwfcrm-overview-wrap"},Object(r.createElement)(b.Card,{className:"bwf-crm-link-trigger-detail-wrap"},Object(r.createElement)(b.CardHeader,{className:"bwf-crm-link-trigger-header"},Object(r.createElement)("span",{className:"bwf-link-trigger-title"},Object(n.__)("Link Trigger","wp-marketing-automations"))),Object(r.createElement)(b.CardBody,{className:"bwf-crm-link-trigger-content"},!Object(w.isEmpty)(N)&&Object(r.createElement)("div",{className:"bwf-section"},Object(r.createElement)(g.a,null),Object(r.createElement)(b.Notice,{status:"error",is:!0,isDismissible:!0,className:"bwf-mtb-10",onRemove:function(){return P([])}},N.map((function(e,t){return Object(r.createElement)(r.Fragment,{key:t},e,Object(r.createElement)("br",null))})))),Object(r.createElement)("div",{className:"bwf-section"},Object(r.createElement)(b.TextControl,{value:V,label:Object(r.createElement)("div",{className:"bwf-h4"},Object(n.__)("Name","wp-marketing-automations")),className:"bwf-input-field",placeholder:Object(n.__)("Enter Name","wp-marketing-automations"),onChange:function(e){B("data",h(h({},H),{},{title:e}))}}),Object(r.createElement)("div",{className:"bwf_clear_10"}),Object(r.createElement)(b.TextareaControl,{label:Object(r.createElement)("div",{className:"bwf-h4"},Object(n.__)("Description","wp-marketing-automations")),type:"text",value:q,placeholder:Object(n.__)("Enter Description","wp-marketing-automations"),onChange:function(e){B("data",h(h({},H),{},{desc:e}))}}),Object(r.createElement)("div",{className:"bwf_clear_8"}),Object(r.createElement)("div",{className:"bwf-h4"},Object(n.__)("Redirect URL","wp-marketing-automations")),Object(r.createElement)("div",{className:"bwf_clear_8"}),Object(r.createElement)(E.a,{title:Z,url:J,setValue:function(e){B("data",h(h({},H),{},{redirect_url:Object(w.isEmpty)(e)?"":e.label,redirect_url_title:Object(w.isEmpty)(e)?"":e.title}))},showOverview:!0}),Object(r.createElement)("div",{className:"bwf_clear_20"}),Object(r.createElement)("div",null,Object(r.createElement)("div",{className:"bwf-h4"},Object(n.__)("Actions")),Object(r.createElement)("div",{className:"bwf_clear_8"}),Object(r.createElement)(o.b,{actions:se,actionSchema:be,value:Object(w.isEmpty)(W)?{new:{}}:W,setActionValue:function(e){B("data",h(h({},H),{},{actions:e}))}})),Object(r.createElement)("div",{className:"bwf_clear_20"}),Object(r.createElement)("div",null,Object(r.createElement)(b.ToggleControl,{label:Object(n.__)("Run multiple times","wp-marketing-automations"),className:"bwf-tooglecontrol-advance",onChange:function(e){B("data",h(h({},H),{},{action_run:e?"multiple":"once"}))},checked:"multiple"===z,help:"If enable then link will run multiple times"})),Object(r.createElement)("div",{className:"bwf_clear_20"}),Object(r.createElement)("div",null,Object(r.createElement)(b.ToggleControl,{label:Object(n.__)("Log Actions as Contact Note","wp-marketing-automations"),className:"bwf-tooglecontrol-advance",onChange:function(e){B("data",h(h({},H),{},{add_contact_note:e}))},checked:!!ae})),Object(r.createElement)("div",{className:"bwf_clear_20"}),Object(r.createElement)("div",null,Object(r.createElement)(b.ToggleControl,{label:Object(n.__)("Enable Auto Login","wp-marketing-automations"),className:"bwf-tooglecontrol-advance",onChange:function(e){B("data",h(h({},H),{},{enable_auto_login:e}))},checked:!!ce})),Object(r.createElement)("div",{className:"bwf_clear_10"}),!!ce&&Object(r.createElement)(r.Fragment,null,Object(r.createElement)(_.a,{units:[{value:"hours",label:Object(n.__)("Hours","wp-marketing-automations")},{value:"days",label:Object(n.__)("Days","wp-marketing-automations")},{value:"weeks",label:Object(n.__)("Weeks","wp-marketing-automations")},{value:"months",label:Object(n.__)("Months","wp-marketing-automations")}],onChange:function(e){B("data",h(h({},H),{},{auto_login:e}))},value:ie}),Object(r.createElement)("div",{className:"hint"},Object(n.__)("Auto Login will expire after ","wp-marketing-automations")+(ie.hasOwnProperty("text")?parseInt(ie.text):"-")+" "+(ie.hasOwnProperty("unit")?ie.unit:"")+". "+Object(n.__)("This would work for all user roles except Administrator and Editor.","wp-marketing-automations")))),Object(r.createElement)("div",{className:"bwf_clear_20"}),Object(r.createElement)("hr",null),Object(r.createElement)("div",{className:"bwf_clear_10"}),Object(r.createElement)("div",{className:"bwf-section"},Object(r.createElement)("div",{className:"bwf_text_right"},Object(r.createElement)(b.Button,{onClick:function(){Oe(2,!0)},className:"bwf-ml-10",isPrimary:!0,isBusy:s},Object(n.__)("Save","wp-marketing-automations"))))))):Object(r.createElement)("div",{className:"bwfcrm-overview-wrap"},Object(r.createElement)(b.Card,{className:"bwf-crm-link-trigger-detail-wrap"},Object(r.createElement)(b.CardHeader,{className:"bwf-crm-link-trigger-header"},Object(r.createElement)("span",{className:"bwf-link-trigger-title"},Object(n.__)("Overview","wp-marketing-automations")),Object(r.createElement)("div",{className:"bwf-display-flex bwf-bulk-card-header"},Object(r.createElement)("span",null,2===parseInt(K)?"Active":"Inactive"),Object(r.createElement)(d.a,{label:Object(n.__)("Quick Actions","wp-marketing-automations"),menuPosition:"bottom right",renderContent:function(e){var t=e.onToggle;return Object(r.createElement)(r.Fragment,null,Object(r.createElement)(j.a,{isClickable:!0,onInvoke:function(){Oe(2===parseInt(K)?1:2,!0),t()}},Object(r.createElement)(p.a,{justify:"flex-start"},Object(r.createElement)(p.c,null,2!==parseInt(K)?"Activate":"Deactivate"))),Object(r.createElement)(j.a,{isClickable:!0,onInvoke:function(){B("isEditable",!0),t()}},Object(r.createElement)(p.a,{justify:"flex-start"},Object(r.createElement)(p.c,null,Object(n.__)("Edit","wp-marketing-automations")))))}}))),Object(r.createElement)(b.CardBody,null,Object(r.createElement)("div",{className:"bwf-link-trigger-list"},Object(r.createElement)("div",{className:"bwf-link-trigger-list-label"},Object(n.__)("Title","wp-marketing-automations")),Object(r.createElement)("div",{className:"bwf-link-trigger-list-value"},V)),Object(r.createElement)("div",{className:"bwf-link-trigger-list"},Object(r.createElement)("div",{className:"bwf-link-trigger-list-label"},Object(n.__)("Description","wp-marketing-automations")),Object(r.createElement)("div",{className:"bwf-link-trigger-list-value"},Object(w.isEmpty)(q)?"-":q)),Object(r.createElement)("div",{className:"bwf-link-trigger-list"},Object(r.createElement)("div",{className:"bwf-link-trigger-list-label"},Object(n.__)("Trigger URL","wp-marketing-automations")),Object(r.createElement)("div",{className:"bwf-link-trigger-list-value"},Object(w.isEmpty)(S)?"-":ge())),Object(r.createElement)("div",{className:"bwf-link-trigger-list"},Object(r.createElement)("div",{className:"bwf-link-trigger-list-label"},Object(n.__)("Destination URL","wp-marketing-automations")),Object(r.createElement)("div",{className:"bwf-link-trigger-list-value"},Object(w.isEmpty)(J)?"-":J)),Object(r.createElement)("div",{className:"bwf-link-trigger-list"},Object(r.createElement)("div",{className:"bwf-link-trigger-list-label"},Object(n.__)("Actions","wp-marketing-automations")),Object(r.createElement)("div",{className:"bwf-link-trigger-list-value"},Object(r.createElement)(o.a,{actions:se,actionSchema:be,value:W}))),Object(r.createElement)("div",{className:"bwf-link-trigger-list"},Object(r.createElement)("div",{className:"bwf-link-trigger-list-label"},Object(n.__)("Total Clicks","wp-marketing-automations")),Object(r.createElement)("div",{className:"bwf-link-trigger-list-value"},parseInt(X)>0?parseInt(X):"-")),Object(r.createElement)("div",{className:"bwf-link-trigger-list"},Object(r.createElement)("div",{className:"bwf-link-trigger-list-label"},Object(n.__)("Runs","wp-marketing-automations-crm")),Object(r.createElement)("div",{className:"bwf-link-trigger-list-value"},"once"===z?"Once":"Multiple Times")),Object(r.createElement)("div",{className:"bwf-link-trigger-list"},Object(r.createElement)("div",{className:"bwf-link-trigger-list-label"},Object(n.__)("Log Actions as Contact Note","wp-marketing-automations")),Object(r.createElement)("div",{className:"bwf-link-trigger-list-value"},ae?"Yes":"No")),Object(r.createElement)("div",{className:"bwf-link-trigger-list"},Object(r.createElement)("div",{className:"bwf-link-trigger-list-label"},Object(n.__)("Auto Login","wp-marketing-automations")),Object(r.createElement)("div",{className:"bwf-link-trigger-list-value"},ce?"Yes":"No"))))))};a(1098),t.default=function(e){var t=e.match.params.id,a=Object(s.i)(),o=Object(f.a)().fetchSingleLink,u=Object(i.a)(),O=u.setBackLink,d=u.setBackLinkLabel,j=u.setL2Nav,p=(0,Object(m.a)().getSingleLinkError)();if(Object(c.a)(Object(n.__)("Link Triggers","wp-marketing-automations")),Object(r.useEffect)((function(){Object(l.f)("Link Triggers #"+t),j(""),O(Object(s.f)({},"/link-triggers",a)),d(Object(n.__)("All Link Triggers","wp-marketing-automations"))}),[]),Object(r.useEffect)((function(){o(t)}),[]),!Object(w.isEmpty)(p)){var v="No link trigger entry found with provided ID.";return p.hasOwnProperty("message")&&!Object(w.isEmpty)(p.message)&&(v=p.message),Object(r.createElement)(b.Notice,{status:"error",isDismissible:!1,className:"bwf-mtb-10"},v)}return Object(r.createElement)("div",{className:"bwf-link-trigger-edit-section"},Object(r.createElement)(g.a,null),Object(r.createElement)(S,{id:t}))}},818:function(e,t,a){"use strict";var r=a(129),c=a(5);function n(e,t){var a=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),a.push.apply(a,r)}return a}function i(e){for(var t=1;t<arguments.length;t++){var a=null!=arguments[t]?arguments[t]:{};t%2?n(Object(a),!0).forEach((function(t){l(e,t,a[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(a)):n(Object(a)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(a,t))}))}return e}function l(e,t,a){return t in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}function s(e,t){if(null==e)return{};var a,r,c=function(e,t){if(null==e)return{};var a,r,c={},n=Object.keys(e);for(r=0;r<n.length;r++)a=n[r],t.indexOf(a)>=0||(c[a]=e[a]);return c}(e,t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);for(r=0;r<n.length;r++)a=n[r],t.indexOf(a)>=0||Object.prototype.propertyIsEnumerable.call(e,a)&&(c[a]=e[a])}return c}t.a=function(){var e=Object(r.a)("linkTriggerListData"),t=e.fetch,a=e.setStateProp,n=s(e,["fetch","setStateProp"]),l=Object(r.a)("singleLinkTriggerData"),b=l.fetch,o=l.setStateProp;return i(i({},n),{},{fetchAll:function(e,a,r){var n=arguments.length>3&&void 0!==arguments[3]?arguments[3]:"",i=e.s,l={offset:a,limit:r,search:i,status:n};t("GET",Object(c.k)("/link-triggers"),l)},setLinkListValues:function(e,t){a(e,t)},fetchSingleLink:function(e){o("error",{}),b("GET",Object(c.k)("/link-trigger/".concat(e)))},setSingleLinkValue:function(e,t){o(e,t)}})}},838:function(e,t,a){"use strict";var r=a(130);function c(e,t){var a=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),a.push.apply(a,r)}return a}function n(e){for(var t=1;t<arguments.length;t++){var a=null!=arguments[t]?arguments[t]:{};t%2?c(Object(a),!0).forEach((function(t){i(e,t,a[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(a)):c(Object(a)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(a,t))}))}return e}function i(e,t,a){return t in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}function l(e,t){if(null==e)return{};var a,r,c=function(e,t){if(null==e)return{};var a,r,c={},n=Object.keys(e);for(r=0;r<n.length;r++)a=n[r],t.indexOf(a)>=0||(c[a]=e[a]);return c}(e,t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);for(r=0;r<n.length;r++)a=n[r],t.indexOf(a)>=0||Object.prototype.propertyIsEnumerable.call(e,a)&&(c[a]=e[a])}return c}t.a=function(){var e=Object(r.a)("linkTriggerListData"),t=e.getStateProp,a=l(e,["getStateProp"]),c=Object(r.a)("singleLinkTriggerData"),i=c.getStateProp,s=c.getLoading;return n(n({},a),{},{getAllLists:function(){return t("data")},getPageNumber:function(){return parseInt(t("offset"))/parseInt(t("limit"))+1},getCountData:function(){return t("countData")},getPerPageCount:function(){return parseInt(t("limit"))},getPageOffset:function(){return parseInt(t("offset"))},getTotalCount:function(){return parseInt(t("total_count"))},getActions:function(){return t("actions")},getSingleLinkLoad:function(){return s()},getSingleLinkData:function(){return i("data")},getLinkActionList:function(){return i("actionList")},getLinkActionSchemaList:function(){return i("actionSchema")},getLinkEditableStatus:function(){return i("isEditable")},getSingleLinkError:function(){return i("error")},getSingleLinkListData:function(){return i("listData")}})}},864:function(e,t,a){"use strict";var r=a(127),c=a(0),n=a(3);t.a=function(){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"",t=arguments.length>2&&void 0!==arguments[2]?arguments[2]:"all",a=arguments.length>3&&void 0!==arguments[3]?arguments[3]:0,i=bwfcrm_contacts_data&&bwfcrm_contacts_data.header_data?bwfcrm_contacts_data.header_data:{},l=Object(r.a)(),s=l.setActiveMultiple,b=l.setL2NavType,o=l.setL2Nav,m=l.resetHeaderMenu,f=l.setPageHeader,u=l.setPageCountData,O=l.setL2Content;return Object(c.useEffect)((function(){m(),s({leftNav:"link-triggers",rightNav:t}),b("menu"),o(i.links_triggers_nav),f("Link Triggers"),!Object(n.isEmpty)(a)&&u({templates:a}),e&&O(e)}),[a]),!0}}}]);