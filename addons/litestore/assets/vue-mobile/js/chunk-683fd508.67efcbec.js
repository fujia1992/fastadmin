(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-683fd508"],{"014b":function(t,e,n){"use strict";var i=n("e53d"),r=n("07e3"),o=n("8e60"),a=n("63b6"),s=n("9138"),c=n("ebfd").KEY,u=n("294c"),f=n("dbdb"),l=n("45f2"),d=n("62a0"),p=n("5168"),_=n("ccb9"),g=n("6718"),h=n("47ee"),v=n("9003"),m=n("e4ae"),b=n("f772"),y=n("36c3"),k=n("1bc3"),S=n("aebd"),x=n("a159"),w=n("0395"),C=n("bf0b"),L=n("d9f6"),O=n("c3a1"),E=C.f,T=L.f,D=w.f,P=i.Symbol,A=i.JSON,j=A&&A.stringify,M="prototype",N=p("_hidden"),R=p("toPrimitive"),G={}.propertyIsEnumerable,I=f("symbol-registry"),F=f("symbols"),V=f("op-symbols"),$=Object[M],B="function"==typeof P,H=i.QObject,z=!H||!H[M]||!H[M].findChild,q=o&&u(function(){return 7!=x(T({},"a",{get:function(){return T(this,"a",{value:7}).a}})).a})?function(t,e,n){var i=E($,e);i&&delete $[e],T(t,e,n),i&&t!==$&&T($,e,i)}:T,J=function(t){var e=F[t]=x(P[M]);return e._k=t,e},W=B&&"symbol"==typeof P.iterator?function(t){return"symbol"==typeof t}:function(t){return t instanceof P},K=function(t,e,n){return t===$&&K(V,e,n),m(t),e=k(e,!0),m(n),r(F,e)?(n.enumerable?(r(t,N)&&t[N][e]&&(t[N][e]=!1),n=x(n,{enumerable:S(0,!1)})):(r(t,N)||T(t,N,S(1,{})),t[N][e]=!0),q(t,e,n)):T(t,e,n)},Y=function(t,e){m(t);var n,i=h(e=y(e)),r=0,o=i.length;while(o>r)K(t,n=i[r++],e[n]);return t},Q=function(t,e){return void 0===e?x(t):Y(x(t),e)},X=function(t){var e=G.call(this,t=k(t,!0));return!(this===$&&r(F,t)&&!r(V,t))&&(!(e||!r(this,t)||!r(F,t)||r(this,N)&&this[N][t])||e)},U=function(t,e){if(t=y(t),e=k(e,!0),t!==$||!r(F,e)||r(V,e)){var n=E(t,e);return!n||!r(F,e)||r(t,N)&&t[N][e]||(n.enumerable=!0),n}},Z=function(t){var e,n=D(y(t)),i=[],o=0;while(n.length>o)r(F,e=n[o++])||e==N||e==c||i.push(e);return i},tt=function(t){var e,n=t===$,i=D(n?V:y(t)),o=[],a=0;while(i.length>a)!r(F,e=i[a++])||n&&!r($,e)||o.push(F[e]);return o};B||(P=function(){if(this instanceof P)throw TypeError("Symbol is not a constructor!");var t=d(arguments.length>0?arguments[0]:void 0),e=function(n){this===$&&e.call(V,n),r(this,N)&&r(this[N],t)&&(this[N][t]=!1),q(this,t,S(1,n))};return o&&z&&q($,t,{configurable:!0,set:e}),J(t)},s(P[M],"toString",function(){return this._k}),C.f=U,L.f=K,n("6abf").f=w.f=Z,n("355d").f=X,n("9aa9").f=tt,o&&!n("b8e3")&&s($,"propertyIsEnumerable",X,!0),_.f=function(t){return J(p(t))}),a(a.G+a.W+a.F*!B,{Symbol:P});for(var et="hasInstance,isConcatSpreadable,iterator,match,replace,search,species,split,toPrimitive,toStringTag,unscopables".split(","),nt=0;et.length>nt;)p(et[nt++]);for(var it=O(p.store),rt=0;it.length>rt;)g(it[rt++]);a(a.S+a.F*!B,"Symbol",{for:function(t){return r(I,t+="")?I[t]:I[t]=P(t)},keyFor:function(t){if(!W(t))throw TypeError(t+" is not a symbol!");for(var e in I)if(I[e]===t)return e},useSetter:function(){z=!0},useSimple:function(){z=!1}}),a(a.S+a.F*!B,"Object",{create:Q,defineProperty:K,defineProperties:Y,getOwnPropertyDescriptor:U,getOwnPropertyNames:Z,getOwnPropertySymbols:tt}),A&&a(a.S+a.F*(!B||u(function(){var t=P();return"[null]"!=j([t])||"{}"!=j({a:t})||"{}"!=j(Object(t))})),"JSON",{stringify:function(t){var e,n,i=[t],r=1;while(arguments.length>r)i.push(arguments[r++]);if(n=e=i[1],(b(e)||void 0!==t)&&!W(t))return v(e)||(e=function(t,e){if("function"==typeof n&&(e=n.call(this,t,e)),!W(e))return e}),i[1]=e,j.apply(A,i)}}),P[M][R]||n("35e8")(P[M],R,P[M].valueOf),l(P,"Symbol"),l(Math,"Math",!0),l(i.JSON,"JSON",!0)},"0395":function(t,e,n){var i=n("36c3"),r=n("6abf").f,o={}.toString,a="object"==typeof window&&window&&Object.getOwnPropertyNames?Object.getOwnPropertyNames(window):[],s=function(t){try{return r(t)}catch(e){return a.slice()}};t.exports.f=function(t){return a&&"[object Window]"==o.call(t)?s(t):r(i(t))}},"0434":function(t,e,n){},"0a49":function(t,e,n){var i=n("9b43"),r=n("626a"),o=n("4bf8"),a=n("9def"),s=n("cd1c");t.exports=function(t,e){var n=1==t,c=2==t,u=3==t,f=4==t,l=6==t,d=5==t||l,p=e||s;return function(e,s,_){for(var g,h,v=o(e),m=r(v),b=i(s,_,3),y=a(m.length),k=0,S=n?p(e,y):c?p(e,0):void 0;y>k;k++)if((d||k in m)&&(g=m[k],h=b(g,k,v),t))if(n)S[k]=h;else if(h)switch(t){case 3:return!0;case 5:return g;case 6:return k;case 2:S.push(g)}else if(f)return!1;return l?-1:u||f?f:S}}},1169:function(t,e,n){var i=n("2d95");t.exports=Array.isArray||function(t){return"Array"==i(t)}},1654:function(t,e,n){"use strict";var i=n("71c1")(!0);n("30f1")(String,"String",function(t){this._t=String(t),this._i=0},function(){var t,e=this._t,n=this._i;return n>=e.length?{value:void 0,done:!0}:(t=i(e,n),this._i+=t.length,{value:t,done:!1})})},"23ff":function(t,e,n){"use strict";var i=n("8672"),r=n.n(i);r.a},"28a5":function(t,e,n){"use strict";var i=n("aae3"),r=n("cb7c"),o=n("ebd6"),a=n("0390"),s=n("9def"),c=n("5f1b"),u=n("520a"),f=n("79e5"),l=Math.min,d=[].push,p="split",_="length",g="lastIndex",h=4294967295,v=!f(function(){RegExp(h,"y")});n("214f")("split",2,function(t,e,n,f){var m;return m="c"=="abbc"[p](/(b)*/)[1]||4!="test"[p](/(?:)/,-1)[_]||2!="ab"[p](/(?:ab)*/)[_]||4!="."[p](/(.?)(.?)/)[_]||"."[p](/()()/)[_]>1||""[p](/.?/)[_]?function(t,e){var r=String(this);if(void 0===t&&0===e)return[];if(!i(t))return n.call(r,t,e);var o,a,s,c=[],f=(t.ignoreCase?"i":"")+(t.multiline?"m":"")+(t.unicode?"u":"")+(t.sticky?"y":""),l=0,p=void 0===e?h:e>>>0,v=new RegExp(t.source,f+"g");while(o=u.call(v,r)){if(a=v[g],a>l&&(c.push(r.slice(l,o.index)),o[_]>1&&o.index<r[_]&&d.apply(c,o.slice(1)),s=o[0][_],l=a,c[_]>=p))break;v[g]===o.index&&v[g]++}return l===r[_]?!s&&v.test("")||c.push(""):c.push(r.slice(l)),c[_]>p?c.slice(0,p):c}:"0"[p](void 0,0)[_]?function(t,e){return void 0===t&&0===e?[]:n.call(this,t,e)}:n,[function(n,i){var r=t(this),o=void 0==n?void 0:n[e];return void 0!==o?o.call(n,r,i):m.call(String(r),n,i)},function(t,e){var i=f(m,t,this,e,m!==n);if(i.done)return i.value;var u=r(t),d=String(this),p=o(u,RegExp),_=u.unicode,g=(u.ignoreCase?"i":"")+(u.multiline?"m":"")+(u.unicode?"u":"")+(v?"y":"g"),b=new p(v?u:"^(?:"+u.source+")",g),y=void 0===e?h:e>>>0;if(0===y)return[];if(0===d.length)return null===c(b,d)?[d]:[];var k=0,S=0,x=[];while(S<d.length){b.lastIndex=v?S:0;var w,C=c(b,v?d:d.slice(S));if(null===C||(w=l(s(b.lastIndex+(v?0:S)),d.length))===k)S=a(d,S,_);else{if(x.push(d.slice(k,S)),x.length===y)return x;for(var L=1;L<=C.length-1;L++)if(x.push(C[L]),x.length===y)return x;S=k=w}}return x.push(d.slice(k)),x}]})},"30f1":function(t,e,n){"use strict";var i=n("b8e3"),r=n("63b6"),o=n("9138"),a=n("35e8"),s=n("481b"),c=n("8f60"),u=n("45f2"),f=n("53e2"),l=n("5168")("iterator"),d=!([].keys&&"next"in[].keys()),p="@@iterator",_="keys",g="values",h=function(){return this};t.exports=function(t,e,n,v,m,b,y){c(n,e,v);var k,S,x,w=function(t){if(!d&&t in E)return E[t];switch(t){case _:return function(){return new n(this,t)};case g:return function(){return new n(this,t)}}return function(){return new n(this,t)}},C=e+" Iterator",L=m==g,O=!1,E=t.prototype,T=E[l]||E[p]||m&&E[m],D=T||w(m),P=m?L?w("entries"):D:void 0,A="Array"==e&&E.entries||T;if(A&&(x=f(A.call(new t)),x!==Object.prototype&&x.next&&(u(x,C,!0),i||"function"==typeof x[l]||a(x,l,h))),L&&T&&T.name!==g&&(O=!0,D=function(){return T.call(this)}),i&&!y||!d&&!O&&E[l]||a(E,l,D),s[e]=D,s[C]=h,m)if(k={values:L?D:w(g),keys:b?D:w(_),entries:P},y)for(S in k)S in E||o(E,S,k[S]);else r(r.P+r.F*(d||O),e,k);return k}},"32fc":function(t,e,n){var i=n("e53d").document;t.exports=i&&i.documentElement},"45f2":function(t,e,n){var i=n("d9f6").f,r=n("07e3"),o=n("5168")("toStringTag");t.exports=function(t,e,n){t&&!r(t=n?t:t.prototype,o)&&i(t,o,{configurable:!0,value:e})}},"47ee":function(t,e,n){var i=n("c3a1"),r=n("9aa9"),o=n("355d");t.exports=function(t){var e=i(t),n=r.f;if(n){var a,s=n(t),c=o.f,u=0;while(s.length>u)c.call(t,a=s[u++])&&e.push(a)}return e}},"481b":function(t,e){t.exports={}},"4fbe":function(t,e,n){},"50ed":function(t,e){t.exports=function(t,e){return{value:e,done:!!t}}},5168:function(t,e,n){var i=n("dbdb")("wks"),r=n("62a0"),o=n("e53d").Symbol,a="function"==typeof o,s=t.exports=function(t){return i[t]||(i[t]=a&&o[t]||(a?o:r)("Symbol."+t))};s.store=i},5399:function(t,e,n){"use strict";var i=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"cube-page",class:t.type},[n("header",{staticClass:"header"},[n("h1",[t._v(t._s(t.title))]),n("i",{staticClass:"cubeic-back",on:{click:t.back}},[n("van-icon",{attrs:{size:"20px",name:"arrow-left"}})],1)]),n("div",{staticClass:"wrapper"},[n("section",{directives:[{name:"show",rawName:"v-show",value:t.desc,expression:"desc"}],staticClass:"desc"},[t._t("desc",[t._v(t._s(t.desc))])],2),n("main",{staticClass:"content"},[t._t("content",[t._v(t._s(t.content))])],2)])])},r=[],o={props:{title:{type:String,default:"",required:!0},type:{type:String,default:""},desc:{type:String,default:""},content:{type:String,default:""}},methods:{back:function(){window.history.length>1?this.$router.back():this.$router.push("/")}}},a=o,s=(n("7dda"),n("cf63"),n("2877")),c=Object(s["a"])(a,i,r,!1,null,"a378effe",null);e["a"]=c.exports},"53e2":function(t,e,n){var i=n("07e3"),r=n("241e"),o=n("5559")("IE_PROTO"),a=Object.prototype;t.exports=Object.getPrototypeOf||function(t){return t=r(t),i(t,o)?t[o]:"function"==typeof t.constructor&&t instanceof t.constructor?t.constructor.prototype:t instanceof Object?a:null}},"5d58":function(t,e,n){t.exports=n("d8d6")},"5d6b":function(t,e,n){var i=n("e53d").parseInt,r=n("a1ce").trim,o=n("e692"),a=/^[-+]?0[xX]/;t.exports=8!==i(o+"08")||22!==i(o+"0x16")?function(t,e){var n=r(String(t),3);return i(n,e>>>0||(a.test(n)?16:10))}:i},6718:function(t,e,n){var i=n("e53d"),r=n("584a"),o=n("b8e3"),a=n("ccb9"),s=n("d9f6").f;t.exports=function(t){var e=r.Symbol||(r.Symbol=o?{}:i.Symbol||{});"_"==t.charAt(0)||t in e||s(e,t,{value:a.f(t)})}},"67bb":function(t,e,n){t.exports=n("f921")},"69d3":function(t,e,n){n("6718")("asyncIterator")},"6abf":function(t,e,n){var i=n("e6f3"),r=n("1691").concat("length","prototype");e.f=Object.getOwnPropertyNames||function(t){return i(t,r)}},"6c1c":function(t,e,n){n("c367");for(var i=n("e53d"),r=n("35e8"),o=n("481b"),a=n("5168")("toStringTag"),s="CSSRuleList,CSSStyleDeclaration,CSSValueList,ClientRectList,DOMRectList,DOMStringList,DOMTokenList,DataTransferItemList,FileList,HTMLAllCollection,HTMLCollection,HTMLFormElement,HTMLSelectElement,MediaList,MimeTypeArray,NamedNodeMap,NodeList,PaintRequestList,Plugin,PluginArray,SVGLengthList,SVGNumberList,SVGPathSegList,SVGPointList,SVGStringList,SVGTransformList,SourceBufferList,StyleSheetList,TextTrackCueList,TextTrackList,TouchList".split(","),c=0;c<s.length;c++){var u=s[c],f=i[u],l=f&&f.prototype;l&&!l[a]&&r(l,a,u),o[u]=o.Array}},"71c1":function(t,e,n){var i=n("3a38"),r=n("25eb");t.exports=function(t){return function(e,n){var o,a,s=String(r(e)),c=i(n),u=s.length;return c<0||c>=u?t?"":void 0:(o=s.charCodeAt(c),o<55296||o>56319||c+1===u||(a=s.charCodeAt(c+1))<56320||a>57343?t?s.charAt(c):o:t?s.slice(c,c+2):a-56320+(o-55296<<10)+65536)}}},7445:function(t,e,n){var i=n("63b6"),r=n("5d6b");i(i.G+i.F*(parseInt!=r),{parseInt:r})},7514:function(t,e,n){"use strict";var i=n("5ca1"),r=n("0a49")(5),o="find",a=!0;o in[]&&Array(1)[o](function(){a=!1}),i(i.P+i.F*a,"Array",{find:function(t){return r(this,t,arguments.length>1?arguments[1]:void 0)}}),n("9c6c")(o)},"765d":function(t,e,n){n("6718")("observable")},"7dda":function(t,e,n){"use strict";var i=n("bcd4"),r=n.n(i);r.a},"7e90":function(t,e,n){var i=n("d9f6"),r=n("e4ae"),o=n("c3a1");t.exports=n("8e60")?Object.defineProperties:function(t,e){r(t);var n,a=o(e),s=a.length,c=0;while(s>c)i.f(t,n=a[c++],e[n]);return t}},"7f12":function(t,e,n){"use strict";var i=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("footer",[t._v("─── Copyright 2016-2028 ® By.Hawk86104 ───")])},r=[],o=(n("d466"),n("2877")),a={},s=Object(o["a"])(a,i,r,!1,null,null,null);e["a"]=s.exports},8436:function(t,e){t.exports=function(){}},8672:function(t,e,n){},"8f60":function(t,e,n){"use strict";var i=n("a159"),r=n("aebd"),o=n("45f2"),a={};n("35e8")(a,n("5168")("iterator"),function(){return this}),t.exports=function(t,e,n){t.prototype=i(a,{next:r(1,n)}),o(t,e+" Iterator")}},9003:function(t,e,n){var i=n("6b4c");t.exports=Array.isArray||function(t){return"Array"==i(t)}},9138:function(t,e,n){t.exports=n("35e8")},a159:function(t,e,n){var i=n("e4ae"),r=n("7e90"),o=n("1691"),a=n("5559")("IE_PROTO"),s=function(){},c="prototype",u=function(){var t,e=n("1ec9")("iframe"),i=o.length,r="<",a=">";e.style.display="none",n("32fc").appendChild(e),e.src="javascript:",t=e.contentWindow.document,t.open(),t.write(r+"script"+a+"document.F=Object"+r+"/script"+a),t.close(),u=t.F;while(i--)delete u[c][o[i]];return u()};t.exports=Object.create||function(t,e){var n;return null!==t?(s[c]=i(t),n=new s,s[c]=null,n[a]=t):n=u(),void 0===e?n:r(n,e)}},a1ce:function(t,e,n){var i=n("63b6"),r=n("25eb"),o=n("294c"),a=n("e692"),s="["+a+"]",c="​",u=RegExp("^"+s+s+"*"),f=RegExp(s+s+"*$"),l=function(t,e,n){var r={},s=o(function(){return!!a[t]()||c[t]()!=c}),u=r[t]=s?e(d):a[t];n&&(r[n]=u),i(i.P+i.F*s,"String",r)},d=l.trim=function(t,e){return t=String(r(t)),1&e&&(t=t.replace(u,"")),2&e&&(t=t.replace(f,"")),t};t.exports=l},aae3:function(t,e,n){var i=n("d3f4"),r=n("2d95"),o=n("2b4c")("match");t.exports=function(t){var e;return i(t)&&(void 0!==(e=t[o])?!!e:"RegExp"==r(t))}},ac6a:function(t,e,n){for(var i=n("cadf"),r=n("0d58"),o=n("2aba"),a=n("7726"),s=n("32e9"),c=n("84f2"),u=n("2b4c"),f=u("iterator"),l=u("toStringTag"),d=c.Array,p={CSSRuleList:!0,CSSStyleDeclaration:!1,CSSValueList:!1,ClientRectList:!1,DOMRectList:!1,DOMStringList:!1,DOMTokenList:!0,DataTransferItemList:!1,FileList:!1,HTMLAllCollection:!1,HTMLCollection:!1,HTMLFormElement:!1,HTMLSelectElement:!1,MediaList:!0,MimeTypeArray:!1,NamedNodeMap:!1,NodeList:!0,PaintRequestList:!1,Plugin:!1,PluginArray:!1,SVGLengthList:!1,SVGNumberList:!1,SVGPathSegList:!1,SVGPointList:!1,SVGStringList:!1,SVGTransformList:!1,SourceBufferList:!1,StyleSheetList:!0,TextTrackCueList:!1,TextTrackList:!1,TouchList:!1},_=r(p),g=0;g<_.length;g++){var h,v=_[g],m=p[v],b=a[v],y=b&&b.prototype;if(y&&(y[f]||s(y,f,d),y[l]||s(y,l,v),c[v]=d,m))for(h in i)y[h]||o(y,h,i[h],!0)}},b9e9:function(t,e,n){n("7445"),t.exports=n("584a").parseInt},bcd4:function(t,e,n){},bf0b:function(t,e,n){var i=n("355d"),r=n("aebd"),o=n("36c3"),a=n("1bc3"),s=n("07e3"),c=n("794b"),u=Object.getOwnPropertyDescriptor;e.f=n("8e60")?u:function(t,e){if(t=o(t),e=a(e,!0),c)try{return u(t,e)}catch(n){}if(s(t,e))return r(!i.f.call(t,e),t[e])}},c207:function(t,e){},c367:function(t,e,n){"use strict";var i=n("8436"),r=n("50ed"),o=n("481b"),a=n("36c3");t.exports=n("30f1")(Array,"Array",function(t,e){this._t=a(t),this._i=0,this._k=e},function(){var t=this._t,e=this._k,n=this._i++;return!t||n>=t.length?(this._t=void 0,r(1)):r(0,"keys"==e?n:"values"==e?t[n]:[n,t[n]])},"values"),o.Arguments=o.Array,i("keys"),i("values"),i("entries")},ccb9:function(t,e,n){e.f=n("5168")},cd1c:function(t,e,n){var i=n("e853");t.exports=function(t,e){return new(i(t))(e)}},cf63:function(t,e,n){"use strict";var i=n("4fbe"),r=n.n(i);r.a},d466:function(t,e,n){"use strict";var i=n("0434"),r=n.n(i);r.a},d8d6:function(t,e,n){n("1654"),n("6c1c"),t.exports=n("ccb9").f("iterator")},e692:function(t,e){t.exports="\t\n\v\f\r   ᠎             　\u2028\u2029\ufeff"},e814:function(t,e,n){t.exports=n("b9e9")},e853:function(t,e,n){var i=n("d3f4"),r=n("1169"),o=n("2b4c")("species");t.exports=function(t){var e;return r(t)&&(e=t.constructor,"function"!=typeof e||e!==Array&&!r(e.prototype)||(e=void 0),i(e)&&(e=e[o],null===e&&(e=void 0))),void 0===e?Array:e}},ebfd:function(t,e,n){var i=n("62a0")("meta"),r=n("f772"),o=n("07e3"),a=n("d9f6").f,s=0,c=Object.isExtensible||function(){return!0},u=!n("294c")(function(){return c(Object.preventExtensions({}))}),f=function(t){a(t,i,{value:{i:"O"+ ++s,w:{}}})},l=function(t,e){if(!r(t))return"symbol"==typeof t?t:("string"==typeof t?"S":"P")+t;if(!o(t,i)){if(!c(t))return"F";if(!e)return"E";f(t)}return t[i].i},d=function(t,e){if(!o(t,i)){if(!c(t))return!0;if(!e)return!1;f(t)}return t[i].w},p=function(t){return u&&_.NEED&&c(t)&&!o(t,i)&&f(t),t},_=t.exports={KEY:i,NEED:!1,fastKey:l,getWeak:d,onFreeze:p}},f921:function(t,e,n){n("014b"),n("c207"),n("69d3"),n("765d"),t.exports=n("584a").Symbol},ff5b:function(t,e,n){"use strict";n.r(e);var i=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"page_goods"},[n("cube-page",{attrs:{type:"Goods-view",title:"商品详情"}},[n("div",{attrs:{slot:"content"},slot:"content"},[n("van-swipe",{attrs:{autoplay:3e3,"indicator-color":t.gcfg.BackgroundColor}},t._l(t.detail.imgs_url,function(t,e){return n("van-swipe-item",{key:e},[n("img",{directives:[{name:"lazy",rawName:"v-lazy",value:t,expression:"image"}],staticStyle:{width:"100%",height:"100%"}})])}),1),n("van-cell-group",[n("van-cell",{staticStyle:{"font-size":"1.2em","margin-bottom":"-0.5em","font-weight":"bold"},attrs:{title:t.detail.goods_name,border:!1}}),n("van-cell",{attrs:{value:"","title-class":"titlec"}},[n("template",{slot:"icon"},[n("van-icon",{staticStyle:{"margin-right":"0.3em"},attrs:{name:"discount",color:t.gcfg.BackgroundColor,size:"1.8em"}})],1),n("template",{slot:"title"},[n("span",{staticClass:"van-cell-text"},[t._v("特价：")]),n("van-tag",{attrs:{mark:"",size:"large",type:"danger"}},[t._v("￥"+t._s(t.goods_price))])],1),0!=t.line_price?[n("span",{staticClass:"van-cell-text yuanjia"},[t._v("原价：￥"+t._s(t.line_price))])]:t._e()],2),n("van-cell",{attrs:{title:"库存："+t.stock_num,value:"销量:"+t.detail.goods_sales}})],1),n("div",{staticClass:"modal_cont_box"},[n("div",{staticClass:"til_conview"},[n("van-row",{staticClass:"in-title"},[n("van-col",[n("van-icon",{attrs:{name:"coupon",color:t.gcfg.BackgroundColor,size:"2em"}})],1),n("van-col",[n("span",{staticClass:"til_con"},[t._v(" 规格数量 ")])])],1)],1),null!=t.specData?n("div",t._l(t.specData.spec_attr,function(e,i){return n("div",{key:i,staticClass:"tmall-types"},[n("div",{staticClass:"tipstxt"},[t._v(t._s(e.group_name)+"：")]),n("van-radio-group",{staticClass:"radio_c",attrs:{"data-id":e.group_id},on:{change:function(e){return t.RonChange(e,i)}},model:{value:t.goods_spec_arr[i],callback:function(e){t.$set(t.goods_spec_arr,i,e)},expression:"goods_spec_arr[attr_idx]"}},t._l(e.spec_items,function(e,i){return n("van-radio",{key:i,staticClass:"radio_cc",attrs:{disabled:e.hidden,"checked-color":t.gcfg.BackgroundColor,name:e.item_id}},[t._v(t._s(e.spec_value))])}),1)],1)}),0):t._e(),n("div",{staticClass:"tmall-types",staticStyle:{display:"inline-flex"}},[n("div",{staticClass:"tipstxt",staticStyle:{"margin-bottom":"0","line-height":"31px"}},[t._v("购买数量：")]),n("van-stepper",{attrs:{"disable-input":"",integer:"",integer:"",min:1,max:999},model:{value:t.goods_num,callback:function(e){t.goods_num=e},expression:"goods_num"}})],1)]),n("div",{staticClass:"modal_cont_box"},[n("div",{staticClass:"til_conview"},[n("van-row",{staticClass:"in-title"},[n("van-col",[n("van-icon",{attrs:{name:"column",color:t.gcfg.BackgroundColor,size:"2em"}})],1),n("van-col",[n("span",{staticClass:"til_con"},[t._v(" 商品描述 ")])])],1)],1),n("div",{staticClass:"goods-cont-li"},[n("div",{domProps:{innerHTML:t._s(t.detail.content)}})]),n("Copyright",{staticStyle:{"margin-bottom":"4em"}})],1),n("van-goods-action",[n("van-goods-action-mini-btn",{attrs:{icon:"cart",text:"购物车",info:0==t.cartnum?"":t.cartnum,"link-type":"switchTab",to:"/CartIndex"}}),n("van-goods-action-mini-btn",{attrs:{icon:"shop","link-type":"switchTab",to:"/",text:"店铺"}}),n("van-goods-action-big-btn",{attrs:{loading:t.addcart_loading,text:"加入购物车"},on:{click:t.addcart}}),n("van-goods-action-big-btn",{attrs:{text:"立即购买",primary:""},on:{click:t.ByNow}})],1)],1)])],1)},r=[],o=n("5d58"),a=n.n(o),s=n("67bb"),c=n.n(s);function u(t){return u="function"===typeof c.a&&"symbol"===typeof a.a?function(t){return typeof t}:function(t){return t&&"function"===typeof c.a&&t.constructor===c.a&&t!==c.a.prototype?"symbol":typeof t},u(t)}function f(t){return f="function"===typeof c.a&&"symbol"===u(a.a)?function(t){return u(t)}:function(t){return t&&"function"===typeof c.a&&t.constructor===c.a&&t!==c.a.prototype?"symbol":u(t)},f(t)}n("7514"),n("ac6a");var l=n("e814"),d=n.n(l),p=(n("28a5"),n("5399")),_=n("7f12"),g=n("c290"),h=n("b970"),v={data:function(){return{id:this.$route.query.id,detail:[],gcfg:[],cartnum:0,addcart_loading:!1,specData:[],goods_sku_id:0,goods_price:0,stock_num:0,line_price:0,goods_num:1,goods_spec_arr:[],sku_hidden_arr:[]}},components:{CubePage:p["a"],Copyright:_["a"]},methods:{addcart:function(){var t=this;t.addcart_loading=!0;var e="/addons/litestore/api.cart/add";g["b"](e,{goods_id:t.id,goods_num:t.goods_num,goods_sku_id:t.goods_sku_id},function(e){console.log(e.data),h["c"].success(e.msg),t.addcart_loading=!1,t.cartnum=e.data.cart_total_num})},ByNow:function(){var t=this;this.$router.push({path:"/CartIndex/cartcheck",query:{type:"buyNow",goods_id:t.id,goods_num:t.goods_num,goods_sku_id:t.goods_sku_id}})},axios_Request:function(){var t="/addons/litestore/api.goods/detail",e=this;g["a"](t,{goods_id:e.id},function(t){console.log(t.data),"20"===t.data.detail.spec_type?e.initManySpecData(t.data):(e.goods_sku_id=t.data.detail.spec[0].spec_sku_id,e.goods_price=t.data.detail.spec[0].goods_price,e.line_price=t.data.detail.spec[0].line_price,e.stock_num=t.data.detail.spec[0].stock_num),"20"===t.data.detail.spec_type&&e.make_sku_showData(t.data.specData,0),e.detail=t.data.detail,e.specData=t.data.specData})},initManySpecData:function(t){var e=this;for(var n in t.specData.spec_list)if(t.specData.spec_list[n].form.stock_num>=0){var i=t.specData.spec_list[n].spec_sku_id.split("_");for(var r in e.goods_sku_id=t.detail.spec[n].spec_sku_id,e.goods_price=t.detail.spec[n].goods_price,e.line_price=t.detail.spec[n].line_price,e.stock_num=t.detail.spec[n].stock_num,i)e.goods_spec_arr[r]=d()(i[r]);break}for(var o in e.sku_hidden_arr=[],t.specData.spec_list)t.specData.spec_list[o].form.stock_num<0&&e.sku_hidden_arr.push(t.specData.spec_list[o].spec_sku_id.split("_"))},RonChange:function(t,e){var n=this.goods_spec_arr;this.make_good_sel_sku(n,e),this.updateSpecGoods(),this.make_sku_showData(this.specData,e)},make_good_sel_sku:function(t,e){var n=this;if(n.check_good_sel_sku(t));else{var i=this.specData.spec_list;i.forEach(function(n,i,r){if(n.form.stock_num>=0){var o=n.spec_sku_id.split("_");o.forEach(function(n,i,r){i==e&&t[i]==n&&(t=o)})}})}t.forEach(function(e,n,i){t[n]=d()(e)}),n.goods_spec_arr=t},check_good_sel_sku:function(t){var e=!0,n=this.sku_hidden_arr;return n.forEach(function(n,i,r){var o=0;n.forEach(function(e,n,i){e==t[n]&&o++}),o==n.length&&(e=!1)}),e},updateSpecGoods:function(){var t=this.goods_spec_arr.join("_"),e=this.specData.spec_list,n=e.find(function(e){return e.spec_sku_id==t});"object"===f(n)&&(this.goods_sku_id=n.spec_sku_id,this.goods_price=n.form.goods_price,this.line_price=n.form.line_price,this.stock_num=n.form.stock_num)},make_sku_showData:function(t,e){var n=this,i=t.spec_attr;i.forEach(function(t,e,n){t.spec_items.forEach(function(t,e,n){t.hidden=!1})}),i.forEach(function(t,e,r){n.for_eachsku_showData(i,e)})},for_eachsku_showData:function(t,e){var n=this.sku_hidden_arr,i=this.goods_spec_arr;n.forEach(function(n,r,o){var a=0;n.forEach(function(t,n,r){n!=e&&t==i[n]&&a++}),a==i.length-1&&t.forEach(function(t,i,r){t.spec_items.forEach(function(t,i,r){t.item_id==n[e]&&(t.hidden=!0)})})})}},created:function(){this.axios_Request(),this.gcfg=this.$store.getters.getGcfg;var t="/addons/litestore/api.cart/getTotalNum",e=this;g["a"](t,{},function(t){e.cartnum=t.data.cart_total_num})},watch:{"$store.state.gcfg":function(){this.gcfg=this.$store.getters.getGcfg}}},m=v,b=(n("23ff"),n("2877")),y=Object(b["a"])(m,i,r,!1,null,null,null);e["default"]=y.exports}}]);
//# sourceMappingURL=chunk-683fd508.67efcbec.js.map