webpackJsonp([21,22],[,function(n,t,e){"use strict";var o=e(0),a=e(11);o.default.use(a.a);var c=new a.a({routes:[{path:"/",redirect:"/login"},{path:"/home",component:function(n){return e.e(7).then(function(){var t=[e(25)];n.apply(null,t)}.bind(this)).catch(e.oe)},children:[{path:"/",component:function(n){return e.e(16).then(function(){var t=[e(23)];n.apply(null,t)}.bind(this)).catch(e.oe)}},{path:"/websetting",component:function(n){return e.e(9).then(function(){var t=[e(36)];n.apply(null,t)}.bind(this)).catch(e.oe)}},{path:"/seosetting",component:function(n){return e.e(11).then(function(){var t=[e(33)];n.apply(null,t)}.bind(this)).catch(e.oe)}},{path:"/advertisement",component:function(n){return e.e(18).then(function(){var t=[e(17)];n.apply(null,t)}.bind(this)).catch(e.oe)}},{path:"/blogroll",component:function(n){return e.e(17).then(function(){var t=[e(19)];n.apply(null,t)}.bind(this)).catch(e.oe)}},{path:"/user",component:function(n){return e.e(0).then(function(){var t=[e(35)];n.apply(null,t)}.bind(this)).catch(e.oe)},beforRouteEnter:function(n,t,e){this.data={aaa:"aaa"},e(this.data)}},{path:"/authority",component:function(n){return e.e(4).then(function(){var t=[e(18)];n.apply(null,t)}.bind(this)).catch(e.oe)}},{path:"/group",component:function(n){return e.e(3).then(function(){var t=[e(24)];n.apply(null,t)}.bind(this)).catch(e.oe)}},{path:"/review",component:function(n){return e.e(12).then(function(){var t=[e(32)];n.apply(null,t)}.bind(this)).catch(e.oe)}},{path:"/sort",component:function(n){return e.e(10).then(function(){var t=[e(34)];n.apply(null,t)}.bind(this)).catch(e.oe)}},{path:"/novel",component:function(n){return e.e(2).then(function(){var t=[e(27)];n.apply(null,t)}.bind(this)).catch(e.oe)}},{path:"/novel_new",component:function(n){return e.e(14).then(function(){var t=[e(30)];n.apply(null,t)}.bind(this)).catch(e.oe)}},{path:"/novel_edit",component:function(n){return e.e(15).then(function(){var t=[e(29)];n.apply(null,t)}.bind(this)).catch(e.oe)}},{path:"/novel_chapter",component:function(n){return e.e(1).then(function(){var t=[e(28)];n.apply(null,t)}.bind(this)).catch(e.oe)}},{path:"/chapter",component:function(n){return e.e(19).then(function(){var t=[e(20)];n.apply(null,t)}.bind(this)).catch(e.oe)}},{path:"/chapter_edit",component:function(n){return e.e(6).then(function(){var t=[e(21)];n.apply(null,t)}.bind(this)).catch(e.oe)}},{path:"/chapter_new",component:function(n){return e.e(5).then(function(){var t=[e(22)];n.apply(null,t)}.bind(this)).catch(e.oe)}},{path:"/read",component:function(n){return e.e(13).then(function(){var t=[e(31)];n.apply(null,t)}.bind(this)).catch(e.oe)}}]},{path:"/login",component:function(n){return e.e(8).then(function(){var t=[e(26)];n.apply(null,t)}.bind(this)).catch(e.oe)}}]});c.beforeEach(function(n,t,e){var o=sessionStorage.getItem("access_token");"/login"===n.path?e():null===o?c.push("/login"):e()}),t.a=c},function(n,t){},,function(n,t,e){e(9);var o=e(6)(e(8),e(10),null,null);n.exports=o.exports},,,,function(n,t){},function(n,t){},function(n,t){n.exports={render:function(){var n=this,t=n.$createElement,e=n._self._c||t;return e("div",{attrs:{id:"app"}},[e("router-view")],1)},staticRenderFns:[]}},,,function(n,t){},function(n,t,e){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var o=e(0),a=e(4),c=e.n(a),u=e(1),i=e(5),r=e.n(i),p=e(3),h=e.n(p),l=e(2);e.n(l);o.default.use(r.a),o.default.use(h.a),o.default.config.debug=!0,o.default.config.productionTip=!1,o.default.http.options.emulateJSON=!0,o.default.http.interceptors.push(function(n,t){var e=sessionStorage.getItem("access_token",null);null!==e&&n.headers.set("Authorization",e),t(function(n){401===n.body.error&&this.$router.push("/login"),403===n.body.error&&this.$router.go(-1),n.ok||this.$Notice.error({title:"服务器似乎不在状态",desc:"错误代码:"+n.status+"<br>错误信息:"+n.statusText})})});var s=new o.default({router:u.a,render:function(n){return n(c.a)}}).$mount("#app",s)}],[14]);
//# sourceMappingURL=app.b4c1b8f7f661ae98d20c.js.map