(window.webpackJsonp=window.webpackJsonp||[]).push([[65],{1151:function(e,t,n){"use strict";n.r(t);var r=n(0),a=n(17),c=n(773),i=n(15),o=n(4),u=n(2),l=n(846),s=n(784),m=n(36);function b(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){if("undefined"==typeof Symbol||!(Symbol.iterator in Object(e)))return;var n=[],r=!0,a=!1,c=void 0;try{for(var i,o=e[Symbol.iterator]();!(r=(i=o.next()).done)&&(n.push(i.value),!t||n.length!==t);r=!0);}catch(e){a=!0,c=e}finally{try{r||null==o.return||o.return()}finally{if(a)throw c}}return n}(e,t)||function(e,t){if(!e)return;if("string"==typeof e)return p(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(e);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return p(e,t)}(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function p(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}var f=Object(r.lazy)((function(){return n.e(71).then(n.bind(null,1113))})),d=Object(r.lazy)((function(){return Promise.all([n.e(0),n.e(60)]).then(n.bind(null,1124))})),y=Object(r.lazy)((function(){return n.e(43).then(n.bind(null,1125))})),h=Object(r.lazy)((function(){return n.e(59).then(n.bind(null,1116))}));t.default=function(e){var t=location&&location.search?Object(a.parse)(location.search.substring(1)):{},n=e.campaignId,p=s.a.getCampaignData(),g=p.hasOwnProperty("type")?parseInt(p.type):1,j=Object(l.a)().data,O=j||{},v=O.reached,w=void 0!==v&&v,E=O.daily_limit,_=void 0===E?0:E,k=b(Object(r.useState)(!0),2),x=k[0],I=k[1];return Object(r.createElement)(r.Fragment,null,"1"===p.type&&!!w&&!!x&&Object(r.createElement)(o.Notice,{className:"bwf-error-notice",status:"warning",onRemove:function(){return I(!1)}},"".concat(Object(u.__)("Daily sending limit of","wp-marketing-automations")," ").concat(_," ").concat(Object(u.__)(" emails has been reached today. To send out more emails, go to ","wp-marketing-automations")," "),Object(r.createElement)(m.a,{href:"admin.php?page=autonami&path=/settings",className:"bwf-a-no-underline"},Object(u.__)("Settings > Emails","wp-marketing-automations")),Object(u.__)(" to increase the limit.","wp-marketing-automations")),Object(r.createElement)(c.b,{history:Object(i.d)()},Object(r.createElement)(c.c,null,Object(r.createElement)(c.a,{exact:!0,path:["/broadcast/".concat(n,"/overview"),"/broadcast/".concat(n)],render:function(){return Object(r.createElement)(d,{campaignType:g})}}),Object(r.createElement)(c.a,{exact:!0,path:"/broadcast/".concat(n,"/analytics"),render:function(){return Object(r.createElement)(f,{query:t,campaignId:n,campaignType:g})}}),Object(r.createElement)(c.a,{exact:!0,path:"/broadcast/".concat(n,"/engagements"),render:function(){return Object(r.createElement)(y,{campaignId:n,campaignType:g})}}),Object(r.createElement)(c.a,{exact:!0,path:"/broadcast/".concat(n,"/orders"),render:function(){return Object(r.createElement)(h,{campaignId:n,campaignType:g})}}))))}},846:function(e,t,n){"use strict";var r=n(12),a=n.n(r),c=n(5),i=n(782);function o(e,t,n,r,a,c,i){try{var o=e[c](i),u=o.value}catch(e){return void n(e)}o.done?t(u):Promise.resolve(u).then(r,a)}var u=function(){var e,t=(e=regeneratorRuntime.mark((function e(){var t;return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,a()({path:Object(c.k)("/messages/daily-limit")});case 2:if((t=e.sent)&&t.result&&200==t.code){e.next=5;break}throw Error(__("Unable to get daily limit","wp-marketing-automations"));case 5:return e.abrupt("return",t.result);case 6:case"end":return e.stop()}}),e)})),function(){var t=this,n=arguments;return new Promise((function(r,a){var c=e.apply(t,n);function i(e){o(c,r,a,i,u,"next",e)}function u(e){o(c,r,a,i,u,"throw",e)}i(void 0)}))});return function(){return t.apply(this,arguments)}}();t.a=function(){return Object(i.a)(["bwfcrm-get-daily-limit"],u,{retry:!1})}}}]);