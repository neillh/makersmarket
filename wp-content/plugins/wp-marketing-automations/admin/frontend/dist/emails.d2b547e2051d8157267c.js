(window.webpackJsonp=window.webpackJsonp||[]).push([[12],{1139:function(e,t,n){"use strict";n.r(t);var a=n(0),r=n(239),o=n(2),i=n(4),c=n(5),s=n(787),l=n(3),u=n(821),m=n(789),f=n(8);function b(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){if("undefined"==typeof Symbol||!(Symbol.iterator in Object(e)))return;var n=[],a=!0,r=!1,o=void 0;try{for(var i,c=e[Symbol.iterator]();!(a=(i=c.next()).done)&&(n.push(i.value),!t||n.length!==t);a=!0);}catch(e){r=!0,o=e}finally{try{a||null==c.return||c.return()}finally{if(r)throw o}}return n}(e,t)||function(e,t){if(!e)return;if("string"==typeof e)return p(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(e);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return p(e,t)}(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function p(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,a=new Array(t);n<t;n++)a[n]=e[n];return a}t.default=function(e){var t=e.contactId,n=b(Object(a.useState)({}),2),p=n[0],d=n[1],y=b(Object(a.useState)(!1),2),g=y[0],v=y[1],j=s.a.getContact(),O=s.a.getConversationLimit,w=s.a.getConversations,h=s.a.getConversationOffset,_=s.a.getConversationTotal,k=s.a.getConversationPage,S=s.a.getConversationLoading,A=s.a.getConversationContactId,E=Object(m.a)().fetchConversations,C=w(),L=S(),I=h(),N=_(),M=O(),T=k(),D=A();Object(a.useEffect)((function(){(Object(l.isEmpty)(C)||t!=D)&&E(t,I,M)}),[]);var x=[{key:"subject",label:Object(o.__)("Subject","wp-marketing-automations"),isLeftAligned:!1},{key:"sent_time",label:Object(o.__)("Sent On","wp-marketing-automations"),isLeftAligned:!0},{key:"last_activity",label:Object(o.__)("Last Activity","wp-marketing-automations"),isLeftAligned:!0},{key:"opens",label:Object(o.__)("Opens","wp-marketing-automations"),isLeftAligned:!0},{key:"clicks",label:Object(o.__)("Clicks","wp-marketing-automations"),isLeftAligned:!0},{key:"view",label:"",isLeftAligned:!0,cellClassName:"bwf-w-60"}],P=function(e){var t=e.c_status?function(e){switch(parseInt(e)){case 1:return Object(a.createElement)("span",{className:"bwf-tags bwf-tag-gray"},Object(o.__)("Draft","wp-marketing-automations"));case 3:return Object(a.createElement)("span",{className:"bwf-tags bwf-tag-red"},Object(o.__)("Failed","wp-marketing-automations"));case 4:return Object(a.createElement)("span",{className:"bwf-tags bwf-tag-orange"},Object(o.__)("Bounced","wp-marketing-automations"));default:return Object(a.createElement)(a.Fragment,null)}}(e.c_status):"-";return Object(a.createElement)(a.Fragment,null,Object(a.createElement)("span",{className:"bwf-mr-10"},e.subject?e.subject:"-"),t)},R=C&&C.length>0?C.map((function(e){return"ID"in e&&parseInt(e.ID)>0&&[{display:P(e),value:e.subject},{display:Object(c.K)(e.created_at,!1),value:e.created_at},{display:e.o_interaction?(t=JSON.parse(e.o_interaction),n=t[t.length-1],Object(c.K)(n,!1,!1)):"-",value:""},{display:e.open?e.open:0,value:e.open},{display:e.click?e.click:0,value:e.click},{display:Object(a.createElement)(i.Button,{className:"bwf-w-60 bwf-display-flex",onClick:function(){d(e),v(!0)}},Object(a.createElement)(f.a,{icon:"view"})),value:null}];var t,n})):[];return Object(a.createElement)(a.Fragment,null,Object(a.createElement)("div",{className:"bwf-c-s-data"},Object(a.createElement)("div",{className:"bwf-c-s-section"},Object(a.createElement)(r.a,{className:"contact-single-table contact-emails",headers:x,rows:R,query:{paged:T},totalRows:N,rowsPerPage:M,isLoading:L,showMenu:!1,onPageChange:function(e,n){return E(t,(e-1)*M,M)},onQueryChange:function(e){return"per_page"===e?function(e){return e!==M&&E(t,I,e)}:function(){return{}}},emptyMessage:Object(o.__)("No emails found","wp-marketing-automations")}))),Object(a.createElement)(u.a,{onRequestClose:function(){return v(!1)},conversation:p,contact:j,isOpen:g}))}},1147:function(e,t,n){"use strict";n.r(t);var a=n(0),r=n(239),o=n(2),i=n(5),c=n(4),s=n(87),l=n(256),u=n(48),m=n.n(u);function f(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){if("undefined"==typeof Symbol||!(Symbol.iterator in Object(e)))return;var n=[],a=!0,r=!1,o=void 0;try{for(var i,c=e[Symbol.iterator]();!(a=(i=c.next()).done)&&(n.push(i.value),!t||n.length!==t);a=!0);}catch(e){r=!0,o=e}finally{try{a||null==c.return||c.return()}finally{if(r)throw o}}return n}(e,t)||function(e,t){if(!e)return;if("string"==typeof e)return b(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(e);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return b(e,t)}(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function b(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,a=new Array(t);n<t;n++)a[n]=e[n];return a}t.default=function(){var e=f(Object(a.useState)(!1),2),t=e[0],n=e[1],u=f(Object(a.useState)(!1),2),b=u[0],p=u[1],d=[{key:"subject",label:Object(o.__)("Subject","wp-marketing-automations"),isLeftAligned:!1},{key:"sent_time",label:Object(o.__)("Sent On","wp-marketing-automations"),isLeftAligned:!0},{key:"last_activity",label:Object(o.__)("Last Activity","wp-marketing-automations"),isLeftAligned:!0},{key:"opens",label:Object(o.__)("Opens","wp-marketing-automations"),isLeftAligned:!0},{key:"clicks",label:Object(o.__)("Clicks","wp-marketing-automations"),isLeftAligned:!0},{key:"view",label:"",isLeftAligned:!0,cellClassName:"bwf-col-action"}],y=[[{display:"Test Subject",value:""},{display:Object(i.K)("2021-02-01 12:00:00"),value:""},{display:Object(i.K)("2021-02-05 00:00:00"),value:""},{display:1,value:""},{display:1,value:""},{display:Object(a.createElement)(c.Button,{icon:"visibility",onClick:function(e){e.preventDefault(),p(!0)}}),value:null}]],g=m()(),v={};return v[g.format("YYYY-MM-DD")+" 10:23:00"]="Opened",v[g.format("YYYY-MM-DD")+" 12:11:00"]="Clicked",Object(a.createElement)(a.Fragment,null,Object(a.createElement)("div",{className:"bwf_clear_10"}),Object(a.createElement)("div",{className:"bwf-c-s-data"},Object(a.createElement)("div",{className:"bwf-c-s-section"},Object(a.createElement)(r.a,{className:"contact-single-table contact-emails",headers:d,rows:y,query:{paged:1},totalRows:1,rowsPerPage:1,isLoading:!1,showMenu:!1,emptyMessage:Object(o.__)("No direct emails found","wp-marketing-automations")})),Object(a.createElement)(s.a,{isOpen:t,onRequestClose:function(){return n(!1)}}),Object(a.createElement)(l.a,{isOpen:b,isLoading:!1,onRequestClose:p,subject:"Dummy Subject",body:Object(a.createElement)("div",{dangerouslySetInnerHTML:{__html:"<p>Hi John,</p> <p> this is test mail.</p><p>thanks</p>"}}),sourceType:2,mode:1,contact:{},conversation:{c_status:"2",created_at:g.subtract(1,"days").format(),mode:"1",source:{type:"1",name:"Autonami",updated_at:g.format()},updated_at:g.format()},template:{timeline:v}})))}},821:function(e,t,n){"use strict";var a=n(0),r=n(2),o=n(12),i=n.n(o),c=n(5),s=n(256);function l(e,t,n,a,r,o,i){try{var c=e[o](i),s=c.value}catch(e){return void n(e)}c.done?t(s):Promise.resolve(s).then(a,r)}function u(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){if("undefined"==typeof Symbol||!(Symbol.iterator in Object(e)))return;var n=[],a=!0,r=!1,o=void 0;try{for(var i,c=e[Symbol.iterator]();!(a=(i=c.next()).done)&&(n.push(i.value),!t||n.length!==t);a=!0);}catch(e){r=!0,o=e}finally{try{a||null==c.return||c.return()}finally{if(r)throw o}}return n}(e,t)||function(e,t){if(!e)return;if("string"==typeof e)return m(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(e);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return m(e,t)}(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function m(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,a=new Array(t);n<t;n++)a[n]=e[n];return a}t.a=function(e){var t=e.conversation,n=(t=void 0===t?{}:t).ID,o=t.mode,m=t.type,f=e.onRequestClose,b=e.isOpen,p=e.contact,d=void 0===p?{}:p,y=u(Object(a.useState)(null),2),g=y[0],v=y[1],j=u(Object(a.useState)(!1),2),O=j[0],w=j[1],h=u(Object(a.useState)(null),2),_=h[0],k=h[1],S=function(){var e,t=(e=regeneratorRuntime.mark((function e(){var t;return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:if(n&&!(!parseInt(n)>0)){e.next=2;break}return e.abrupt("return");case 2:return w(!0),e.prev=3,e.next=6,i()({method:"GET",path:Object(c.k)("/contacts/".concat(d.id,"/engagement/").concat(parseInt(n),"?mode=1"))});case 6:"result"in(t=e.sent)&&v(t.result),e.next=14;break;case 10:e.prev=10,e.t0=e.catch(3),console.log(e.t0),e.t0&&"message"in e.t0?k(e.t0.message):Object(r.__)("There are some technical difficulties while getting emails. Please contact support.","wp-marketing-automations");case 14:w(!1);case 15:case"end":return e.stop()}}),e,null,[[3,10]])})),function(){var t=this,n=arguments;return new Promise((function(a,r){var o=e.apply(t,n);function i(e){l(o,a,r,i,c,"next",e)}function c(e){l(o,a,r,i,c,"throw",e)}i(void 0)}))});return function(){return t.apply(this,arguments)}}();Object(a.useEffect)((function(){b&&S()}),[b]);return Object(a.createElement)(s.a,{isOpen:b,isLoading:O,onRequestClose:function(){f(),v(null),k(null)},error:_,subject:parseInt(o)<2&&g&&g.subject,body:g&&Object(a.createElement)("div",{dangerouslySetInnerHTML:{__html:g.body}}),sourceType:m,mode:o,contact:e.contact,conversation:e.conversation,template:g})}}}]);