(window.webpackJsonp=window.webpackJsonp||[]).push([[77],{1149:function(e,t,a){"use strict";a.r(t);var n=a(0),r=a(4),c=a(2),l=a(797),i=a(796),m=a(5),s=a(63),o=a(256),b=a(56),d=a(86),f=a(16),u=a(3),j=a(51),O=a(8);function p(e){return function(e){if(Array.isArray(e))return v(e)}(e)||function(e){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(e))return Array.from(e)}(e)||E(e)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function w(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){if("undefined"==typeof Symbol||!(Symbol.iterator in Object(e)))return;var a=[],n=!0,r=!1,c=void 0;try{for(var l,i=e[Symbol.iterator]();!(n=(l=i.next()).done)&&(a.push(l.value),!t||a.length!==t);n=!0);}catch(e){r=!0,c=e}finally{try{n||null==i.return||i.return()}finally{if(r)throw c}}return a}(e,t)||E(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function E(e,t){if(e){if("string"==typeof e)return v(e,t);var a=Object.prototype.toString.call(e).slice(8,-1);return"Object"===a&&e.constructor&&(a=e.constructor.name),"Map"===a||"Set"===a?Array.from(e):"Arguments"===a||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(a)?v(e,t):void 0}}function v(e,t){(null==t||t>e.length)&&(t=e.length);for(var a=0,n=new Array(t);a<t;a++)n[a]=e[a];return n}function _(e){return(_="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}var g=function(e){var t=e.mappedFields,a=e.formHeaders;if(!t)return Object(n.createElement)(r.Notice,{status:"error",isDismissible:!1,className:"bwf-c-mapping-notice"},Object(n.createElement)("b",null,Object(c.__)("Error: ","wp-marketing-automations")),Object(c.__)("No email mapping found. Email mapping is must. Click edit to map email.","wp-marketing-automations"));var l=function(e){return"email"!==t[e]?"continue":a.find((function(t){return t.index.toString()===e}))?{v:null}:{v:Object(n.createElement)(r.Notice,{status:"error",isDismissible:!1,className:"bwf-c-mapping-notice"},Object(n.createElement)("b",null,Object(c.__)("Error: ","wp-marketing-automations")),Object(c.__)("Invalid email mapping found. Email mapping is must. Click edit to map email.","wp-marketing-automations"))}};for(var i in t){var m=l(i);if("continue"!==m&&"object"===_(m))return m.v}return Object(n.createElement)(r.Notice,{status:"error",isDismissible:!1,className:"bwf-c-mapping-notice"},Object(n.createElement)("b",null,Object(c.__)("Error: ","wp-marketing-automations")),Object(c.__)("No email mapping found. Email mapping is must. Click edit to map email.","wp-marketing-automations"))},y=function(e){var t=e.mappedFields,a=e.formHeaders;if(!t)return Object(n.createElement)(r.Notice,{status:"warning",isDismissible:!1,className:"bwf-c-mapping-notice"},Object(n.createElement)("b",null,Object(c.__)("Warning: ","wp-marketing-automations")),Object(c.__)("Invalid / Empty Mapping Data. Click edit to map fields.","wp-marketing-automations"));var l=function(e){if(!a.find((function(t){return t.index.toString()===e})))return{v:Object(n.createElement)(r.Notice,{status:"warning",isDismissible:!1,className:"bwf-c-mapping-notice"},Object(n.createElement)("b",null,Object(c.__)("Warning: ","wp-marketing-automations")),Object(c.__)("Detected some invalid field mappings. Maybe your form fields have been changed. Click edit to map fields.","wp-marketing-automations"))}};for(var i in t){var m=l(i);if("object"===_(m))return m.v}return null};t.default=function(){var e=Object(l.a)(),t=e.getFeed,a=e.getFormFields,E=e.getFormHeaders,v=e.getLoading,_=e.getUpdateStatusStatus,N=Object(i.a)(),k=N.fetchMappingData,h=N.setEditMapMode,S=N.setStep,C=N.updateStatus,x=N.resetUpdateStatusStatus,A=t(),F=A.id,I=A.data,D=A.valid,T=w(Object(n.useState)(!D),2),H=T[0],M=T[1],P=a(),L=Array.isArray(P)?P.map((function(e){return e.fields})).reduce((function(e,t){return[].concat(p(e),p(t))}),[]):[],U=E(),R=I.mapped_fields,Y=v(),B=w(Object(n.useState)(!1),2),Q=B[0],J=B[1];Object(n.useEffect)((function(){D&&k(F)}),[F]);var W=_(),q=Object(n.useContext)(m.d);Object(n.useEffect)((function(){W&&(1===W&&q(Object(c.__)("Loading…","wp-marketing-automations")),2===W&&q(Object(c.__)("Status Changed Successfully!","wp-marketing-automations")),3===W&&q(Object(c.__)("Unable to change status","wp-marketing-automations")),3!==W&&2!==W||setTimeout((function(){q(""),x()}),2e3))}),[W]);var z=Object(m.I)(),V=!!A&&!!A.data&&!!A.data.incentivize_email;return Object(n.createElement)(n.Fragment,null,Object(n.createElement)(s.a,null),Object(n.createElement)("div",{className:"bwf_clear_20"}),Object(n.createElement)("div",{className:"bwfcrm-overview-wrap"},Object(n.createElement)(r.Card,{className:"bwf-crm-form-feed-report-detail-wrap"},Object(n.createElement)(r.CardHeader,{className:"bwf-crm-form-feed-report-header"},Object(n.createElement)("span",{className:"bwf-form-title"},Object(c.__)("Overview","wp-marketing-automations")),Object(n.createElement)("div",{className:"bwf-display-flex bwf-form-status-header"},D&&Object(n.createElement)(b.a,{label:Object(c.__)("Quick Actions","wp-marketing-automations"),menuPosition:"bottom right",renderContent:function(e){var t=e.onToggle;return Object(n.createElement)(d.a,{isClickable:!0,onInvoke:function(){var e=2===A.status?3:2;C(F,e),t()}},Object(n.createElement)(f.a,{justify:"flex-start"},Object(n.createElement)(f.c,null,2===A.status?"Deactivate":"Activate")))}}))),Object(n.createElement)(r.CardBody,null,H&&Object(n.createElement)(r.Notice,{className:"bwf-error-notice",isDismissible:!1,status:"error",onRemove:function(){return M(!1)}},Object(c.__)("Saved form does not exist.","wp-marketing-automations")),Object(n.createElement)("div",{className:"bwf-form-feed-list"},Object(n.createElement)("div",{className:"bwf-form-feed-list-label"},Object(c.__)("Title","wp-marketing-automations")),Object(n.createElement)("div",{className:"bwf-form-feed-list-value"},A.title)),Object(n.createElement)("div",{className:"bwf-form-feed-list"},Object(n.createElement)("div",{className:"bwf-form-feed-list-label"},Object(c.__)("Source","wp-marketing-automations")),Object(n.createElement)("div",{className:"bwf-form-feed-list-value"},z&&z[A.source]?z[A.source]:A.source,!!A.form_link&&A.data.hasOwnProperty("form_id")&&Object(n.createElement)(n.Fragment,null," ( ",Object(n.createElement)("a",{href:A.form_link,target:"_blank",className:"bwf-a-no-underline"},"#"+A.data.form_id)," )"))),Object(n.createElement)("div",{className:"bwf-form-feed-list"},Object(n.createElement)("div",{className:"bwf-form-feed-list-label"},Object(c.__)("Status","wp-marketing-automations")),Object(n.createElement)("div",{className:"bwf-form-feed-list-value"},1===A.status?Object(c.__)("Draft","wp-marketing-automations"):2===A.status?Object(c.__)("Active","wp-marketing-automations"):Object(c.__)("Inactive","wp-marketing-automations"))),Object(n.createElement)("div",{className:"bwf-form-feed-list"},Object(n.createElement)("div",{className:"bwf-form-feed-list-label"},Object(c.__)("Submissions","wp-marketing-automations")),Object(n.createElement)("div",{className:"bwf-form-feed-list-value"},A.contacts_created)))),Object(n.createElement)("div",{className:"bwf_clear_20"}),Object(n.createElement)(r.Card,{className:"bwf-crm-form-feed-report-detail-wrap"},Object(n.createElement)(r.CardHeader,{className:"bwf-crm-form-feed-report-header"},Object(n.createElement)("span",{className:"bwf-form-title"},Object(c.__)("Mapping","wp-marketing-automations")),Object(n.createElement)("div",{className:"bwf-display-flex bwf-form-status-header"},D&&Object(n.createElement)(b.a,{label:Object(c.__)("Quick Actions","wp-marketing-automations"),menuPosition:"bottom right",renderContent:function(e){var t=e.onToggle;return Object(n.createElement)(d.a,{isClickable:!0,onInvoke:function(){h(!0),S("mapping"),t()}},Object(n.createElement)(f.a,{justify:"flex-start"},Object(n.createElement)(f.c,null,Object(c.__)("Edit","wp-marketing-automations"))))}}))),Object(n.createElement)(r.CardBody,{className:"bwf-crm-overview-full"},!!D&&Object(n.createElement)(n.Fragment,null,!Y&&U.length>0&&L.length>0?Object(n.createElement)("div",{className:"bwf-form-feed-list"},Object(n.createElement)("div",{className:"bwf-form-feed-list-label"},Object(c.__)("Field Mapping","wp-marketing-automations")),Object(n.createElement)("div",{className:"bwf-form-feed-list-value"},Object(n.createElement)(y,{mappedFields:R,formHeaders:U}),Object(n.createElement)(g,{mappedFields:R,formHeaders:U}),Object(n.createElement)("table",{className:"bwf-overview-table"},Object(n.createElement)("thead",null,Object(n.createElement)("tr",null,Object(n.createElement)("th",null,Object(c.__)("Field","wp-marketing-automations")),Object(n.createElement)("th",null,Object(c.__)("Autonami","wp-marketing-automations")))),Object(n.createElement)("tbody",null,Object.keys(R).map((function(e){var t=R[e],a=U.find((function(t){return t.index.toString()===e.toString()})),r=L.find((function(e){return e.id.toString()===t.toString()}));return Object(n.createElement)("tr",{key:e},Object(n.createElement)("td",null,Object(n.createElement)("div",{className:"bwf-c-field-label-wrapper"},!a&&Object(n.createElement)("span",{className:"bwf-mr-10"},Object(n.createElement)(O.a,{icon:"info",color:"red"})),a&&a.header?a.header:e)),Object(n.createElement)("td",null,r&&r.name?r.name:t))})))))):Object(n.createElement)(n.Fragment,null,Object(n.createElement)("div",{className:"bwf-form-feed-list-label"},Object(c.__)("Field Mapping","wp-marketing-automations")),Object(n.createElement)("div",{className:"bwf-display-flex",style:{justifyContent:"flex-start",gap:"20px",padding:"12px 0"}},Object(n.createElement)("div",{className:"bwf-placeholder-temp bwf-w-210 bwf-h-15"}),Object(n.createElement)("div",{className:"bwf-placeholder-temp bwf-w-300 bwf-h-15"})),Object(n.createElement)("div",{className:"bwf-display-flex",style:{justifyContent:"flex-start",gap:"20px",padding:"12px 0"}},Object(n.createElement)("div",{className:"bwf-placeholder-temp bwf-w-210 bwf-h-15"}),Object(n.createElement)("div",{className:"bwf-placeholder-temp bwf-w-300 bwf-h-15"})),Object(n.createElement)("div",{className:"bwf-display-flex",style:{justifyContent:"flex-start",gap:"20px",padding:"12px 0"}},Object(n.createElement)("div",{className:"bwf-placeholder-temp bwf-w-210 bwf-h-15"}),Object(n.createElement)("div",{className:"bwf-placeholder-temp bwf-w-300 bwf-h-15"})))),Object(n.createElement)("div",{className:"bwf-form-feed-list"},Object(n.createElement)("div",{className:"bwf-form-feed-list-label"},Object(c.__)("Contact Profile","wp-marketing-automations")),Object(n.createElement)("div",{className:"bwf-form-feed-list-value"},Object(n.createElement)("table",{className:"bwf-overview-table"},Object(n.createElement)("tbody",null,A.data.hasOwnProperty("tags")&&!Object(u.isEmpty)(A.data.tags)&&Object(n.createElement)("tr",null,Object(n.createElement)("td",null,Object(c.__)("Tags","wp-marketing-automations")),Object(n.createElement)("td",null,A.data.tags.map((function(e){return Object(n.createElement)(j.a,{label:e.value})})))),A.data.hasOwnProperty("lists")&&!Object(u.isEmpty)(A.data.lists)&&Object(n.createElement)("tr",null,Object(n.createElement)("td",null,Object(c.__)("Lists","wp-marketing-automations")),Object(n.createElement)("td",null,A.data.lists.map((function(e){return Object(n.createElement)(j.a,{label:e.value})})))),Object(n.createElement)("tr",null,Object(n.createElement)("td",null,Object(c.__)("Update existing contacts","wp-marketing-automations")),Object(n.createElement)("td",null,A.data.hasOwnProperty("update_existing")&&A.data.update_existing?"Yes":"No")),Object(n.createElement)("tr",null,Object(n.createElement)("td",null,Object(c.__)("Trigger automations","wp-marketing-automations")),Object(n.createElement)("td",null,A.data.hasOwnProperty("trigger_events")&&A.data.trigger_events?"Yes":"No")))))))),Object(n.createElement)("div",{className:"bwf_clear_20"}),Object(n.createElement)(r.Card,{className:"bwf-crm-form-feed-report-detail-wrap"},Object(n.createElement)(r.CardHeader,{className:"bwf-crm-form-feed-report-header"},Object(n.createElement)("span",{className:"bwf-form-title"},Object(c.__)("Email Notification","wp-marketing-automations")),Object(n.createElement)("div",{className:"bwf-display-flex bwf-form-status-header"},D&&Object(n.createElement)(b.a,{label:Object(c.__)("Quick Actions","wp-marketing-automations"),menuPosition:"bottom right",renderContent:function(e){var t=e.onToggle;return Object(n.createElement)(d.a,{isClickable:!0,onInvoke:function(){h(!0),S("double_optin"),t()}},Object(n.createElement)(f.a,{justify:"flex-start"},Object(n.createElement)(f.c,null,Object(c.__)("Edit","wp-marketing-automations"))))}}))),Object(n.createElement)(r.CardBody,null,Object(n.createElement)("div",{className:"bwf-form-feed-list"},Object(n.createElement)("div",{className:"bwf-form-feed-list-label"},Object(c.__)("Enabled","wp-marketing-automations")),Object(n.createElement)("div",{className:"bwf-form-feed-list-value"},V?"Yes":"No")),!!V&&Object(n.createElement)(n.Fragment,null,Object(n.createElement)("div",{className:"bwf-form-feed-list"},Object(n.createElement)("div",{className:"bwf-form-feed-list-label"},Object(c.__)("Email","wp-marketing-automations")),Object(n.createElement)("div",{className:"bwf-form-feed-list-value"},Object(n.createElement)(r.Button,{isSecondary:!0,className:"bwf-mr-5",onClick:function(){return J(!0)}},Object(c.__)("View Details","wp-marketing-automations")),Q&&Object(n.createElement)(o.a,{isOpen:Q,isLoading:!1,onRequestClose:function(){return J(!1)},subject:A.data.incentive_email.content[0].subject,preHeader:A.data.incentive_email.content[0].preheader,body:"editor"!==A.data.incentive_email.content[0].type?Object(n.createElement)("div",{dangerouslySetInnerHTML:{__html:A.data.incentive_email.content[0].body}}):Object(n.createElement)("div",{dangerouslySetInnerHTML:{__html:A.data.incentive_email.content[0].editor.body}}),type:A.data.incentive_email.content[0].type,sourceType:2,mode:1,utm:A.data.incentive_email.content[0].utmEnabled?A.data.incentive_email.content[0].utm:{}}))),Object(n.createElement)("div",{className:"bwf-form-feed-list"},Object(n.createElement)("div",{className:"bwf-form-feed-list-label"},Object(c.__)("Don't send email to subscribed contacts","wp-marketing-automations")),Object(n.createElement)("div",{className:"bwf-form-feed-list-value"},A.data.not_send_to_subscribed?"Yes":"No")),Object(n.createElement)("div",{className:"bwf-form-feed-list"},Object(n.createElement)("div",{className:"bwf-form-feed-list-label"},Object(c.__)("Confirmation Redirect URL","wp-marketing-automations")),Object(n.createElement)("div",{className:"bwf-form-feed-list-value"},!!A.data.redirect_url&&A.data.redirect_url)),A.data.hasOwnProperty("add_tag_enable")&&A.data.add_tag_enable&&A.data.hasOwnProperty("tag_to_add")&&!Object(u.isEmpty)(A.data.tag_to_add)&&Object(n.createElement)("div",{className:"bwf-form-feed-list"},Object(n.createElement)("div",{className:"bwf-form-feed-list-label"},Object(c.__)("Confirmation Tags","wp-marketing-automations")),Object(n.createElement)("div",{className:"bwf-form-feed-list-value"},A.data.tag_to_add.map((function(e){return Object(n.createElement)(j.a,{key:e.id,label:e.value,id:e.id,screenReaderLabel:e.value})}))))),Object(n.createElement)("div",{className:"bwf-form-feed-list"},Object(n.createElement)("div",{className:"bwf-form-feed-list-label"},Object(c.__)("Auto-confirm Contacts","wp-marketing-automations")),Object(n.createElement)("div",{className:"bwf-form-feed-list-value"},A.data.marketing_status?"Yes":"No"))))))}}}]);