(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-72fb80f6"],{"1d8e":function(t,e,s){"use strict";s.r(e);var a=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"myorder"},[a("cube-page",{attrs:{type:"myorder-view",title:"我的订单"}},[a("div",{attrs:{slot:"content"},slot:"content"},[a("van-tabs",{staticClass:"tab-margin",attrs:{color:t.gcfg.BackgroundColor},on:{change:t.OnChange},model:{value:t.active,callback:function(e){t.active=e},expression:"active "}},[a("van-tab",{attrs:{title:"全部订单"}},t._l(t.OrderList,function(e,s){return a("div",{key:s,staticClass:"oneorder"},[a("van-cell",{attrs:{title:"订单编号："+e.order_no}},[a("van-tag",{attrs:{slot:"right-icon",type:e.showType},slot:"right-icon"},[t._v(t._s(e.showText))])],1),a("van-cell",{attrs:{"is-link":"",to:"./myorder/detail?id="+e.id}},[a("div",{staticClass:"tile_sp",attrs:{slot:"title"},slot:"title"},t._l(e.goods,function(e,s){return a("span",{key:s},[t._v("\n\t\t\t\t            "+t._s(e.goods_name)+" * "+t._s(e.total_num)+" "),a("br")])}),0)]),a("van-cell",{attrs:{title:"合计:￥"+e.pay_price+" 数量: "+e.goods.length,label:e.creattime_text}},[a("div",{attrs:{slot:"right-icon"},slot:"right-icon"},[a("van-button",{staticClass:"bt-zs",attrs:{size:"small",to:"./myorder/detail?id="+e.id}},[t._v("订单详情")])],1)])],1)}),0),a("van-tab",{attrs:{title:"未付款"}},t._l(t.OrderList,function(e,s){return"danger"==e.showType?a("div",{key:s,staticClass:"oneorder"},[a("van-cell",{attrs:{title:"订单编号："+e.order_no}},[a("van-tag",{attrs:{slot:"right-icon",type:e.showType},slot:"right-icon"},[t._v(t._s(e.showText))])],1),a("van-cell",{attrs:{"is-link":"",to:"./myorder/detail?id="+e.id}},[a("div",{staticClass:"tile_sp",attrs:{slot:"title"},slot:"title"},t._l(e.goods,function(e,s){return a("span",{key:s},[t._v("\n\t\t\t\t            "+t._s(e.goods_name)+" * "+t._s(e.total_num)+" "),a("br")])}),0)]),a("van-cell",{attrs:{title:"合计:￥"+e.pay_price+" 数量: "+e.goods.length,label:e.creattime_text}},[a("div",{attrs:{slot:"right-icon"},slot:"right-icon"},[a("van-button",{staticClass:"bt-zs",attrs:{size:"small",to:"./myorder/detail?id="+e.id}},[t._v("订单详情")])],1)])],1):t._e()}),0),a("van-tab",{attrs:{title:"待发货"}},t._l(t.OrderList,function(e,s){return"success"==e.showType?a("div",{key:s,staticClass:"oneorder"},[a("van-cell",{attrs:{title:"订单编号："+e.order_no}},[a("van-tag",{attrs:{slot:"right-icon",type:e.showType},slot:"right-icon"},[t._v(t._s(e.showText))])],1),a("van-cell",{attrs:{"is-link":"",to:"./myorder/detail?id="+e.id}},[a("div",{staticClass:"tile_sp",attrs:{slot:"title"},slot:"title"},t._l(e.goods,function(e,s){return a("span",{key:s},[t._v("\n\t\t\t\t            "+t._s(e.goods_name)+" * "+t._s(e.total_num)+" "),a("br")])}),0)]),a("van-cell",{attrs:{title:"合计:￥"+e.pay_price+" 数量: "+e.goods.length,label:e.creattime_text}},[a("div",{attrs:{slot:"right-icon"},slot:"right-icon"},[a("van-button",{staticClass:"bt-zs",attrs:{size:"small",to:"./myorder/detail?id="+e.id}},[t._v("订单详情")])],1)])],1):t._e()}),0),a("van-tab",{attrs:{title:"待收货"}},t._l(t.OrderList,function(e,s){return"primary"==e.showType?a("div",{key:s,staticClass:"oneorder"},[a("van-cell",{attrs:{title:"订单编号："+e.order_no}},[a("van-tag",{attrs:{slot:"right-icon",type:e.showType},slot:"right-icon"},[t._v(t._s(e.showText))])],1),a("van-cell",{attrs:{"is-link":"",to:"./myorder/detail?id="+e.id}},[a("div",{staticClass:"tile_sp",attrs:{slot:"title"},slot:"title"},t._l(e.goods,function(e,s){return a("span",{key:s},[t._v("\n\t\t\t\t            "+t._s(e.goods_name)+" * "+t._s(e.total_num)+" "),a("br")])}),0)]),a("van-cell",{attrs:{title:"合计:￥"+e.pay_price+" 数量: "+e.goods.length,label:e.creattime_text}},[a("div",{attrs:{slot:"right-icon"},slot:"right-icon"},[a("van-button",{staticClass:"bt-zs",attrs:{size:"small",to:"./myorder/detail?id="+e.id}},[t._v("订单详情")])],1)])],1):t._e()}),0)],1),t.isNoData?a("div",{staticClass:"liteshop-notcont",staticStyle:{"margin-top":"130px"}},[a("div",{staticClass:"img"},[a("img",{attrs:{src:s("5c25")}})]),a("span",{staticClass:"cont"},[t._v("订单空空如也")])]):t._e()],1)]),a("cube-view")],1)},i=[],r=(s("ac6a"),s("ce11")),o=s("5399"),n=s("c290"),c={components:{CubePage:o["a"],CubeView:r["a"]},data:function(){return{OrderList:[],active:this.$route.query.showType,isNoData:!0,gcfg:[]}},created:function(){this.get_my_order(),this.gcfg=this.$store.getters.getGcfg},watch:{"$store.state.gcfg":function(){this.gcfg=this.$store.getters.getGcfg},$route:function(t,e){console.log(t),console.log(e),"/my/myorder"==t.path&&"/my/myorder/detail"==e.path&&this.get_my_order()}},methods:{get_my_order:function(){var t="/addons/litestore/api.order/my",e=this;n["a"](t,{},function(t){console.log(t.data),t.data.forEach(function(t,e,s){"20"==t.pay_status&&"10"==t.freight_status&&"10"==t.order_status&&"10"==t.receipt_status&&(s[e].showText="待发货",s[e].showType="success",s[e].showactive=2),"20"==t.pay_status&&"20"==t.freight_status&&"10"==t.order_status&&"10"==t.receipt_status&&(s[e].showText="待收货",s[e].showType="primary",s[e].showactive=3),"10"==t.pay_status&&"10"==t.freight_status&&"10"==t.order_status&&"10"==t.receipt_status&&(s[e].showText="待付款",s[e].showType="danger",s[e].showactive=1),"20"==t.pay_status&&"20"==t.freight_status&&"30"==t.order_status&&"20"==t.receipt_status&&(s[e].showText="已完成",s[e].showType="")}),e.OrderList=t.data,e.check_is_noData()})},check_is_noData:function(){var t=this,e=!0;t.OrderList.forEach(function(s,a,i){0==t.active?e=!1:s.showactive==t.active&&(e=!1)}),t.isNoData=e},OnChange:function(){var t=this;t.check_is_noData()}}},l=c,d=(s("6476"),s("2877")),u=Object(d["a"])(l,a,i,!1,null,null,null);e["default"]=u.exports},"37d6":function(t,e,s){},"4ba3":function(t,e,s){},"4fbe":function(t,e,s){},5399:function(t,e,s){"use strict";var a=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"cube-page",class:t.type},[s("header",{staticClass:"header"},[s("h1",[t._v(t._s(t.title))]),s("i",{staticClass:"cubeic-back",on:{click:t.back}},[s("van-icon",{attrs:{size:"20px",name:"arrow-left"}})],1)]),s("div",{staticClass:"wrapper"},[s("section",{directives:[{name:"show",rawName:"v-show",value:t.desc,expression:"desc"}],staticClass:"desc"},[t._t("desc",[t._v(t._s(t.desc))])],2),s("main",{staticClass:"content"},[t._t("content",[t._v(t._s(t.content))])],2)])])},i=[],r={props:{title:{type:String,default:"",required:!0},type:{type:String,default:""},desc:{type:String,default:""},content:{type:String,default:""}},methods:{back:function(){window.history.length>1?this.$router.back():this.$router.push("/")}}},o=r,n=(s("7dda"),s("cf63"),s("2877")),c=Object(n["a"])(o,a,i,!1,null,"a378effe",null);e["a"]=c.exports},"5c25":function(t,e,s){t.exports=s.p+"img/no-data.edbbd919.png"},6476:function(t,e,s){"use strict";var a=s("4ba3"),i=s.n(a);i.a},"7dda":function(t,e,s){"use strict";var a=s("bcd4"),i=s.n(a);i.a},ac6a:function(t,e,s){for(var a=s("cadf"),i=s("0d58"),r=s("2aba"),o=s("7726"),n=s("32e9"),c=s("84f2"),l=s("2b4c"),d=l("iterator"),u=l("toStringTag"),_=c.Array,v={CSSRuleList:!0,CSSStyleDeclaration:!1,CSSValueList:!1,ClientRectList:!1,DOMRectList:!1,DOMStringList:!1,DOMTokenList:!0,DataTransferItemList:!1,FileList:!1,HTMLAllCollection:!1,HTMLCollection:!1,HTMLFormElement:!1,HTMLSelectElement:!1,MediaList:!0,MimeTypeArray:!1,NamedNodeMap:!1,NodeList:!0,PaintRequestList:!1,Plugin:!1,PluginArray:!1,SVGLengthList:!1,SVGNumberList:!1,SVGPathSegList:!1,SVGPointList:!1,SVGStringList:!1,SVGTransformList:!1,SourceBufferList:!1,StyleSheetList:!0,TextTrackCueList:!1,TextTrackList:!1,TouchList:!1},h=i(v),g=0;g<h.length;g++){var p,f=h[g],y=v[f],m=o[f],b=m&&m.prototype;if(b&&(b[d]||n(b,d,_),b[u]||n(b,u,f),c[f]=_,y))for(p in a)b[p]||r(b,p,a[p],!0)}},bcd4:function(t,e,s){},c153:function(t,e,s){"use strict";var a=s("37d6"),i=s.n(a);i.a},ce11:function(t,e,s){"use strict";var a=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("transition",{attrs:{name:"page-move"}},[s("router-view",{staticClass:"cube-view"})],1)},i=[],r={methods:{}},o=r,n=(s("c153"),s("2877")),c=Object(n["a"])(o,a,i,!1,null,null,null);e["a"]=c.exports},cf63:function(t,e,s){"use strict";var a=s("4fbe"),i=s.n(a);i.a}}]);
//# sourceMappingURL=chunk-72fb80f6.0fb8fbea.js.map