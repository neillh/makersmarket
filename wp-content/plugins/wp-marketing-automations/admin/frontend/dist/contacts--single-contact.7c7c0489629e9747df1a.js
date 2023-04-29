(window.webpackJsonp=window.webpackJsonp||[]).push([[5],{787:function(t,e,n){"use strict";var a=n(130),o=n(272),r=Object(a.a)(o.b.contact),c=r.getStateProp,s=r.getLoading,i=r.getError,u=Object(a.a)(o.b.funnel),l=u.getStateProp,b=u.getLoading,f=u.getError,m=Object(a.a)(o.b.automation),g=m.getStateProp,p=m.getLoading,d=m.getError,O=Object(a.a)(o.b.orders),j=O.getStateProp,w=O.getLoading,v=O.getError,y=Object(a.a)(o.b.conversation),h=y.getStateProp,_=y.getLoading,S=Object(a.a)(o.b.sms),E=S.getStateProp,C=S.getLoading,P=Object(a.a)(o.b.subscription),k=P.getStateProp,L=P.getLoading,M=P.getError;e.a={getContactLoading:s,getContact:function(){return c("contact")},getContactListData:function(){return c("listData")},getError:function(){var t=[],e=i(),n=e&&(e.hasOwnProperty("message")?e.message:__("Unknown Error Occurred","wp-marketing-automations"));return t.push(n),t.filter(Boolean).length>0?t:null},getFunnels:function(){return l("funnel")},getFunnelLoading:b,getFunnelError:f,getFunnelContactId:function(){return l("contact_id")},getAutomations:function(){return g("automation")},getAutomationLoading:p,getAutomationError:d,getAutomationContactId:function(){return g("contact_id")},getOrders:function(){return j("orders")},getOrderLoading:w,getOrderError:v,getOrderContactId:function(){return j("contact_id")},getOrdersTotal:function(){return j("total_count")},getOrdersLimit:function(){return j("limit")},getOrdersOffset:function(){return j("offset")},getOrderPage:function(){return parseInt(j("offset"))/parseInt(j("limit"))+1},getConversations:function(){return h("conversations")},getConversationContactId:function(){return h("contact_id")},getConversationTotal:function(){return h("total_count")},getConversationLimit:function(){return h("limit")},getConversationOffset:function(){return h("offset")},getConversationPage:function(){return parseInt(h("offset"))/parseInt(h("limit"))+1},getConversationLoading:_,getSubscriptions:function(){return k("subscriptions")},getSubscriptionsContactId:function(){return k("contact_id")},getSubscriptionsTotal:function(){return k("total_count")},getSubscriptionsLimit:function(){return k("limit")},getSubscriptionsOffset:function(){return k("offset")},getSubscriptionPage:function(){return parseInt(k("offset"))/parseInt(k("limit"))+1},getSubscriptionLoading:L,getSubscriptionError:M,getSMSConversations:function(){return E("conversations")},getSMSConversationContactId:function(){return E("contact_id")},getSMSConversationTotal:function(){return E("total_count")},getSMSConversationLimit:function(){return E("limit")},getSMSConversationOffset:function(){return E("offset")},getSMSConversationPage:function(){return parseInt(E("offset"))/parseInt(E("limit"))+1},getSMSConversationLoading:C}},788:function(t,e,n){"use strict";var a=n(127),o=n(0),r=n(2);e.a=function(t,e,n){var c=arguments.length>3&&void 0!==arguments[3]?arguments[3]:"",s=bwfcrm_contacts_data&&bwfcrm_contacts_data.header_data?bwfcrm_contacts_data.header_data:{},i=Object(a.a)(),u=i.setActiveMultiple,l=i.resetHeaderMenu,b=i.setL2NavType,f=i.setL2Nav,m=i.setBackLink,g=i.setL2Title,p=i.setL2Content,d=i.setBackLinkLabel,O=i.setPageHeader;return Object(o.useEffect)((function(){l(),!e&&b("menu"),!e&&f(s.contacts_nav),u({leftNav:"contacts",rightNav:t}),e&&m(e),e&&d("All Contacts"),n&&g(n),n&&"Export"===n&&(c&&p(c),b("menu"),f({export:{name:Object(r.__)("All","wp-marketing-automations"),link:"admin.php?page=autonami&path=/export"}})),!e&&c&&p(c),O("Contacts")}),[t,n]),!0}},789:function(t,e,n){"use strict";var a=n(129),o=n(5),r=n(272),c=n(787),s=n(3);function i(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(t);e&&(a=a.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,a)}return n}function u(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?i(Object(n),!0).forEach((function(e){l(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):i(Object(n)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}function l(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}e.a=function(){var t=Object(a.a)(r.b.contact),e=t.fetch,n=t.setStateProp,i=Object(a.a)(r.b.funnel),b=i.fetch,f=i.setStateProp,m=Object(a.a)(r.b.automation),g=m.fetch,p=m.setStateProp,d=Object(a.a)(r.b.orders),O=d.fetch,j=d.setStateProp,w=Object(a.a)(r.b.conversation),v=w.fetch,y=w.setStateProp,h=Object(a.a)(r.b.subscription),_=h.fetch,S=h.setStateProp,E=Object(a.a)(r.b.sms),C=E.fetch,P=E.setStateProp,k=c.a.getContact();return{fetch:function(t){e("GET",Object(o.k)("/contacts/".concat(t)))},setContactMeta:function(t,e){n("contact",u(u({},k),{},{fields:u(u({},k.fields),{},l({},t,e))}))},setStateProps:function(t,e,a){Object(s.isEmpty)(e)?n("contact",t):n("contact",u(u({},t),{},l({},e,a)))},setSingleContactListData:function(t){n("listData",t)},fetchFunnel:function(t){f("contact_id",t),b("GET",Object(o.k)("/contacts/".concat(t,"/funnels")))},fetchAutomation:function(t){p("contact_id",t),g("GET",Object(o.k)("/contacts/".concat(t,"/automations")))},fetchOrders:function(t,e,n){j("contact_id",t),O("GET",Object(o.k)("/contacts/".concat(t,"/orders/?limit=")+n+"&offset="+e))},setOrderValue:function(t,e){j(t,e)},setContactField:function(t){t&&Object(s.size)(t)>0&&n("contact",u(u({},k),t))},fetchConversations:function(t,e,n){y("contact_id",t),v("GET",Object(o.k)("/contacts/".concat(t,"/engagements?mode=1&offset=").concat(e,"&limit=").concat(n)))},setConversationsProp:function(t,e){y(t,e)},fetchSubscription:function(t,e,n){S("contact_id",t),_("GET",Object(o.k)("/contacts/".concat(t,"/subscriptions?offset=").concat(e,"&limit=").concat(n)))},fetchSMSConversations:function(t,e,n){P("contact_id",t),C("GET",Object(o.k)("/contacts/".concat(t,"/engagements?mode=2&offset=").concat(e,"&limit=").concat(n)))},fetchWhatAppConversations:function(t,e,n){P("contact_id",t),C("GET",Object(o.k)("/contacts/".concat(t,"/engagements?mode=3&offset=").concat(e,"&limit=").concat(n)))},setSMSConversationsProp:function(t,e){P(t,e)}}}},842:function(t,e,n){"use strict";var a=n(0),o=n(2),r=n(4),c=(n(926),n(5)),s=n(3),i=n(12),u=n.n(i),l=n(165),b=n(8);function f(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(t);e&&(a=a.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,a)}return n}function m(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?f(Object(n),!0).forEach((function(e){g(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):f(Object(n)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}function g(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}function p(t,e,n,a,o,r,c){try{var s=t[r](c),i=s.value}catch(t){return void n(t)}s.done?e(i):Promise.resolve(i).then(a,o)}function d(t,e){return function(t){if(Array.isArray(t))return t}(t)||function(t,e){if("undefined"==typeof Symbol||!(Symbol.iterator in Object(t)))return;var n=[],a=!0,o=!1,r=void 0;try{for(var c,s=t[Symbol.iterator]();!(a=(c=s.next()).done)&&(n.push(c.value),!e||n.length!==e);a=!0);}catch(t){o=!0,r=t}finally{try{a||null==s.return||s.return()}finally{if(o)throw r}}return n}(t,e)||function(t,e){if(!t)return;if("string"==typeof t)return O(t,e);var n=Object.prototype.toString.call(t).slice(8,-1);"Object"===n&&t.constructor&&(n=t.constructor.name);if("Map"===n||"Set"===n)return Array.from(t);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return O(t,e)}(t,e)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function O(t,e){(null==e||e>t.length)&&(e=t.length);for(var n=0,a=new Array(e);n<e;n++)a[n]=t[n];return a}e.a=function(t){var e=t.contact,n=Object(a.useContext)(c.d),i=d(Object(a.useState)({type:"email",title:"",body:"Hi ".concat(e.f_name,",")}),2),f=i[0],g=i[1],O=d(Object(a.useState)({status:!1,message:""}),2),j=O[0],w=O[1],v=d(Object(a.useState)(!1),2),y=v[0],h=v[1],_=[{value:"email",label:Object(o.__)("Email","wp-marketing-automations")},{value:"sms",label:Object(o.__)("SMS","wp-marketing-automations")}];Object(c.nb)()&&_.push({value:"whatsapp",label:Object(o.__)("WhatsApp","wp-marketing-automations")});var S=function(){var a,r=(a=regeneratorRuntime.mark((function a(){var r;return regeneratorRuntime.wrap((function(a){for(;;)switch(a.prev=a.next){case 0:return r={title:f.title,type:f.type,message:f.body},"sms"===f.type&&delete r.title,h(!0),a.prev=3,a.next=6,u()({path:Object(c.k)("/contacts/".concat(e.id,"/sendmessage")),method:"POST",data:r,headers:{"Content-Type":"application/json"}}).then((function(e){if(200!=e.code)throw Error(Object(c.i)(null==e?void 0:e.message));n(Object(o.__)("Message sent sucessfully","wp-marketing-automations")),t.setSendMessageModel({status:!1,contactInfo:{}}),t.hasOwnProperty("onSuccess")&&t.onSuccess(!0)})).catch((function(t){throw Error(Object(c.i)(null==t?void 0:t.message))}));case 6:a.next=12;break;case 8:a.prev=8,a.t0=a.catch(3),w({status:!0,message:a.t0.message}),h(!1);case 12:Object(c.Z)(n,2e3);case 13:case"end":return a.stop()}}),a,null,[[3,8]])})),function(){var t=this,e=arguments;return new Promise((function(n,o){var r=a.apply(t,e);function c(t){p(r,n,o,c,s,"next",t)}function s(t){p(r,n,o,c,s,"throw",t)}c(void 0)}))});return function(){return r.apply(this,arguments)}}();return Object(a.createElement)(r.Modal,{onRequestClose:function(){return t.setSendMessageModel({status:!1,contactInfo:{}})},shouldCloseOnClickOutside:!1,className:"bwf-admin-modal bwf-admin-modal-large bwf-message-modal"},Object(a.createElement)("div",{className:"bwf-modal-header"},Object(a.createElement)("div",{className:"bwf-modal-heading"},Object(o.__)("Send Message")),Object(a.createElement)("span",{onClick:function(){t.setSendMessageModel({status:!1,contactInfo:{}})},className:"bwf-modal-close"},Object(a.createElement)(b.a,{icon:"close",color:"#353030"}))),Object(a.createElement)("div",{className:"bwf-form-fields"},j.status&&Object(a.createElement)(r.Notice,{status:"error",onRemove:function(){return w(!1)},className:"bwf-send-message-notice"},j.message?j.message:Object(o.__)("Title and body are mandetory field. Kindly fill all fields","wp-marketing-automations")),Object(a.createElement)("div",{className:"bwf-message-wrap"},Object(a.createElement)("div",{className:"bwf-pmb-16 bwf-pt-0 bwf-bb-grey",key:1},Object(a.createElement)("div",{className:"bwf-w-120 bwf_bold"},Object(o.__)("To","wp-marketing-automations")),Object(a.createElement)("div",null,Object(a.createElement)("div",{className:"bwf-highlight-content"},Object(a.createElement)("b",null,e.f_name+" "+e.l_name),"email"==f.type?" ( "+e.email+" ) ":e.contact_no?" ( "+e.contact_no+" ) ":""))),Object(a.createElement)("div",{className:"bwf-pmb-16 bwf-bb-grey"},Object(a.createElement)("div",{className:"bwf-w-120 bwf_bold"},Object(o.__)("Type","wp-marketing-automations")),Object(a.createElement)(r.SelectControl,{value:f.type,className:"bwf-w-150",options:_,onChange:function(t){["sms","whatsapp"].includes(t)&&(Object(s.isEmpty)(e.contact_no)?w({status:!0,message:Object(o.__)("Phone number missing from contact details.","wp-marketing-automations")}):("sms"!==t||Object(c.j)()||w({status:!0,message:Object(o.__)("Connect with SMS provider to send messages.","wp-marketing-automations")}),"whatsapp"!==t||Object(c.ob)()||w({status:!0,message:Object(o.__)("WhatsApp configuration is missing.","wp-marketing-automations")}))),g(m(m({},f),{},{type:t}))}})),"email"==f.type&&Object(a.createElement)("div",{className:"bwf-pmb-16 bwf-bb-grey"},Object(a.createElement)("div",{className:"bwf-w-120 bwf_bold"},Object(o.__)("Subject","wp-marketing-automations")),Object(a.createElement)(r.TextControl,{type:"text",value:f.title,placeholder:Object(o.__)("Title comes here …","wp-marketing-automations"),onChange:function(t){return g(m(m({},f),{},{title:t}))},disabled:y})),Object(a.createElement)("div",{className:"bwf-pmb-16 bwf-message-textarea"},"email"==f.type?Object(a.createElement)(l.a,{keyid:"bwf-email-edittor",content:f.body,setContent:function(t){return g(m(m({},f),{},{body:t}))},height:200}):Object(a.createElement)(r.TextareaControl,{placeholder:Object(o.__)("Write your message here …","wp-marketing-automations"),value:f.body,onChange:function(t){return g(m(m({},f),{},{body:t}))},disabled:y,rows:8})),Object(a.createElement)("div",{className:"bwf_text_right bwf-message-footer"},Object(a.createElement)("div",null),Object(a.createElement)("div",null,Object(a.createElement)(r.Button,{className:"bwf-cancel-btn",onClick:function(){return t.setSendMessageModel({status:!1,contactInfo:{}})},disabled:y},Object(o.__)("Cancel","wp-marketing-automations")),Object(a.createElement)(r.Button,{isPrimary:!0,isBusy:y,onClick:function(){var t=!1;Object(s.isEmpty)(f.body)&&(t=!0,w({status:!0,message:Object(o.__)("Please enter message body.","wp-marketing-automations")})),!t&&Object(s.isEmpty)(f.type)&&(t=!0,w({status:!0,message:Object(o.__)("Please select message type.","wp-marketing-automations")})),!t&&"email"==f.type&&Object(s.isEmpty)(f.title)&&(t=!0,w({status:!0,message:Object(o.__)("Please enter the subject for the message.","wp-marketing-automations")})),t||"whatsapp"!=f.type||Object(c.ob)()||(t=!0,w({status:!0,message:Object(o.__)("WhatsApp service is not configured yet.","wp-marketing-automations")})),t||(w({status:!1,message:""}),g(m(m({},f),{},{loading:!0})),S())},disabled:!("sms"!=f.type||!Object(s.isEmpty)(e.contact_no)&&Object(c.j)())},Object(o.__)("Send","wp-marketing-automations")))))))}},926:function(t,e,n){}}]);