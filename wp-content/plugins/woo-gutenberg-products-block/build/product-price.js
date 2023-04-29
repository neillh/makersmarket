(window.webpackWcBlocksJsonp=window.webpackWcBlocksJsonp||[]).push([[30],{145:function(e,c){},175:function(e,c){},193:function(e,c,r){"use strict";r.r(c),r.d(c,"Block",(function(){return d}));var t=r(0),a=r(4),n=r.n(a),l=r(84),i=r(22),s=r(25),o=r(109),u=r(110),p=r(97),m=r(50);r(266);const d=e=>{var c,r;const{className:a,textAlign:m,isDescendentOfSingleProductTemplate:d}=e,{parentClassName:b}=Object(s.useInnerBlockLayoutContext)(),{product:y}=Object(s.useProductDataContext)(),g=Object(o.a)(e),O=Object(u.a)(e),_=Object(p.a)(e),v=n()("wc-block-components-product-price",a,g.className,{[b+"__product-price"]:b},_.className);if(!y.id&&!d)return Object(t.createElement)(l.a,{align:m,className:v});const j={...g.style,..._.style},N={...O.style},P=y.prices,f=d?Object(i.getCurrencyFromPriceResponse)():Object(i.getCurrencyFromPriceResponse)(P),w=P.price!==P.regular_price,E=n()({[b+"__product-price__value"]:b,[b+"__product-price__value--on-sale"]:w});return Object(t.createElement)(l.a,{align:m,className:v,regularPriceStyle:j,priceStyle:j,priceClassName:E,currency:f,price:d?"5000":P.price,minPrice:null==P||null===(c=P.price_range)||void 0===c?void 0:c.min_amount,maxPrice:null==P||null===(r=P.price_range)||void 0===r?void 0:r.max_amount,regularPrice:d?"5000":P.regular_price,regularPriceClassName:n()({[b+"__product-price__regular"]:b}),spacingStyle:N})};c.default=e=>e.isDescendentOfSingleProductTemplate?Object(t.createElement)(d,e):Object(m.withProductDataContext)(d)(e)},266:function(e,c){},38:function(e,c,r){"use strict";var t=r(6),a=r.n(t),n=r(0),l=r(139),i=r(4),s=r.n(i);r(145);const o=e=>({thousandSeparator:null==e?void 0:e.thousandSeparator,decimalSeparator:null==e?void 0:e.decimalSeparator,fixedDecimalScale:!0,prefix:null==e?void 0:e.prefix,suffix:null==e?void 0:e.suffix,isNumericString:!0});c.a=e=>{var c;let{className:r,value:t,currency:i,onValueChange:u,displayType:p="text",...m}=e;const d="string"==typeof t?parseInt(t,10):t;if(!Number.isFinite(d))return null;const b=d/10**i.minorUnit;if(!Number.isFinite(b))return null;const y=s()("wc-block-formatted-money-amount","wc-block-components-formatted-money-amount",r),g=null!==(c=m.decimalScale)&&void 0!==c?c:null==i?void 0:i.minorUnit,O={...m,...o(i),decimalScale:g,value:void 0,currency:void 0,onValueChange:void 0},_=u?e=>{const c=+e.value*10**i.minorUnit;u(c)}:()=>{};return Object(n.createElement)(l.a,a()({className:y,displayType:p},O,{value:b,onValueChange:_}))}},84:function(e,c,r){"use strict";var t=r(0),a=r(1),n=r(38),l=r(4),i=r.n(l),s=r(22);r(175);const o=e=>{let{currency:c,maxPrice:r,minPrice:l,priceClassName:o,priceStyle:u={}}=e;return Object(t.createElement)(t.Fragment,null,Object(t.createElement)("span",{className:"screen-reader-text"},Object(a.sprintf)(
/* translators: %1$s min price, %2$s max price */
Object(a.__)("Price between %1$s and %2$s","woo-gutenberg-products-block"),Object(s.formatPrice)(l),Object(s.formatPrice)(r))),Object(t.createElement)("span",{"aria-hidden":!0},Object(t.createElement)(n.a,{className:i()("wc-block-components-product-price__value",o),currency:c,value:l,style:u})," — ",Object(t.createElement)(n.a,{className:i()("wc-block-components-product-price__value",o),currency:c,value:r,style:u})))},u=e=>{let{currency:c,regularPriceClassName:r,regularPriceStyle:l,regularPrice:s,priceClassName:o,priceStyle:u,price:p}=e;return Object(t.createElement)(t.Fragment,null,Object(t.createElement)("span",{className:"screen-reader-text"},Object(a.__)("Previous price:","woo-gutenberg-products-block")),Object(t.createElement)(n.a,{currency:c,renderText:e=>Object(t.createElement)("del",{className:i()("wc-block-components-product-price__regular",r),style:l},e),value:s}),Object(t.createElement)("span",{className:"screen-reader-text"},Object(a.__)("Discounted price:","woo-gutenberg-products-block")),Object(t.createElement)(n.a,{currency:c,renderText:e=>Object(t.createElement)("ins",{className:i()("wc-block-components-product-price__value","is-discounted",o),style:u},e),value:p}))};c.a=e=>{let{align:c,className:r,currency:a,format:l="<price/>",maxPrice:s,minPrice:p,price:m,priceClassName:d,priceStyle:b,regularPrice:y,regularPriceClassName:g,regularPriceStyle:O,spacingStyle:_}=e;const v=i()(r,"price","wc-block-components-product-price",{["wc-block-components-product-price--align-"+c]:c});l.includes("<price/>")||(l="<price/>",console.error("Price formats need to include the `<price/>` tag."));const j=y&&m!==y;let N=Object(t.createElement)("span",{className:i()("wc-block-components-product-price__value",d)});return j?N=Object(t.createElement)(u,{currency:a,price:m,priceClassName:d,priceStyle:b,regularPrice:y,regularPriceClassName:g,regularPriceStyle:O}):void 0!==p&&void 0!==s?N=Object(t.createElement)(o,{currency:a,maxPrice:s,minPrice:p,priceClassName:d,priceStyle:b}):m&&(N=Object(t.createElement)(n.a,{className:i()("wc-block-components-product-price__value",d),currency:a,value:m,style:b})),Object(t.createElement)("span",{className:v,style:_},Object(t.createInterpolateElement)(l,{price:N}))}}}]);