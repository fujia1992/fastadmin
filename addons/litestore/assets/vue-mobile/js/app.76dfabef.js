(function (e) {
    function n(n) {
        for (var c, o, u = n[0], i = n[1], s = n[2], f = 0, h = []; f < u.length; f++) o = u[f], a[o] && h.push(a[o][0]), a[o] = 0;
        for (c in i) Object.prototype.hasOwnProperty.call(i, c) && (e[c] = i[c]);
        l && l(n);
        while (h.length) h.shift()();
        return r.push.apply(r, s || []), t()
    }

    function t() {
        for (var e, n = 0; n < r.length; n++) {
            for (var t = r[n], c = !0, o = 1; o < t.length; o++) {
                var u = t[o];
                0 !== a[u] && (c = !1)
            }
            c && (r.splice(n--, 1), e = i(i.s = t[0]))
        }
        return e
    }

    var c = {}, o = {app: 0}, a = {app: 0}, r = [];

    function u(e) {
        return i.p + "js/" + ({}[e] || e) + "." + {
            "chunk-0e455c8c": "e6bc1143",
            "chunk-291f569a": "3df6760f",
            "chunk-32ea458a": "6875721f",
            "chunk-3cd027a4": "251a00d9",
            "chunk-585ea9bb": "6019f519",
            "chunk-683fd508": "67efcbec",
            "chunk-72fb80f6": "0fb8fbea",
            "chunk-76f88e57": "b23592ca",
            "chunk-7c352045": "458f60ce",
            "chunk-854219ba": "87b21345",
            "chunk-a1e0e73c": "02529220",
            "chunk-c01442dc": "24c8627c",
            "chunk-c0734d00": "0ca64864"
        }[e] + ".js"
    }

    function i(n) {
        if (c[n]) return c[n].exports;
        var t = c[n] = {i: n, l: !1, exports: {}};
        return e[n].call(t.exports, t, t.exports, i), t.l = !0, t.exports
    }

    i.e = function (e) {
        var n = [], t = {
            "chunk-0e455c8c": 1,
            "chunk-291f569a": 1,
            "chunk-32ea458a": 1,
            "chunk-3cd027a4": 1,
            "chunk-585ea9bb": 1,
            "chunk-683fd508": 1,
            "chunk-72fb80f6": 1,
            "chunk-7c352045": 1,
            "chunk-854219ba": 1,
            "chunk-a1e0e73c": 1,
            "chunk-c01442dc": 1,
            "chunk-c0734d00": 1
        };
        o[e] ? n.push(o[e]) : 0 !== o[e] && t[e] && n.push(o[e] = new Promise(function (n, t) {
            for (var c = "css/" + ({}[e] || e) + "." + {
                "chunk-0e455c8c": "e227af02",
                "chunk-291f569a": "86ff993e",
                "chunk-32ea458a": "40817dda",
                "chunk-3cd027a4": "90025fe8",
                "chunk-585ea9bb": "65e9d18a",
                "chunk-683fd508": "85c45e3b",
                "chunk-72fb80f6": "a6c6ff5d",
                "chunk-76f88e57": "31d6cfe0",
                "chunk-7c352045": "1d8301ee",
                "chunk-854219ba": "f049a183",
                "chunk-a1e0e73c": "d630cf18",
                "chunk-c01442dc": "8e8beb53",
                "chunk-c0734d00": "1f5b6216"
            }[e] + ".css", a = i.p + c, r = document.getElementsByTagName("link"), u = 0; u < r.length; u++) {
                var s = r[u], f = s.getAttribute("data-href") || s.getAttribute("href");
                if ("stylesheet" === s.rel && (f === c || f === a)) return n()
            }
            var h = document.getElementsByTagName("style");
            for (u = 0; u < h.length; u++) {
                s = h[u], f = s.getAttribute("data-href");
                if (f === c || f === a) return n()
            }
            var l = document.createElement("link");
            l.rel = "stylesheet", l.type = "text/css", l.onload = n, l.onerror = function (n) {
                var c = n && n.target && n.target.src || a,
                    r = new Error("Loading CSS chunk " + e + " failed.\n(" + c + ")");
                r.request = c, delete o[e], l.parentNode.removeChild(l), t(r)
            }, l.href = a;
            var d = document.getElementsByTagName("head")[0];
            d.appendChild(l)
        }).then(function () {
            o[e] = 0
        }));
        var c = a[e];
        if (0 !== c) if (c) n.push(c[2]); else {
            var r = new Promise(function (n, t) {
                c = a[e] = [n, t]
            });
            n.push(c[2] = r);
            var s, f = document.createElement("script");
            f.charset = "utf-8", f.timeout = 120, i.nc && f.setAttribute("nonce", i.nc), f.src = u(e), s = function (n) {
                f.onerror = f.onload = null, clearTimeout(h);
                var t = a[e];
                if (0 !== t) {
                    if (t) {
                        var c = n && ("load" === n.type ? "missing" : n.type), o = n && n.target && n.target.src,
                            r = new Error("Loading chunk " + e + " failed.\n(" + c + ": " + o + ")");
                        r.type = c, r.request = o, t[1](r)
                    }
                    a[e] = void 0
                }
            };
            var h = setTimeout(function () {
                s({type: "timeout", target: f})
            }, 12e4);
            f.onerror = f.onload = s, document.head.appendChild(f)
        }
        return Promise.all(n)
    }, i.m = e, i.c = c, i.d = function (e, n, t) {
        i.o(e, n) || Object.defineProperty(e, n, {enumerable: !0, get: t})
    }, i.r = function (e) {
        "undefined" !== typeof Symbol && Symbol.toStringTag && Object.defineProperty(e, Symbol.toStringTag, {value: "Module"}), Object.defineProperty(e, "__esModule", {value: !0})
    }, i.t = function (e, n) {
        if (1 & n && (e = i(e)), 8 & n) return e;
        if (4 & n && "object" === typeof e && e && e.__esModule) return e;
        var t = Object.create(null);
        if (i.r(t), Object.defineProperty(t, "default", {
            enumerable: !0,
            value: e
        }), 2 & n && "string" != typeof e) for (var c in e) i.d(t, c, function (n) {
            return e[n]
        }.bind(null, c));
        return t
    }, i.n = function (e) {
        var n = e && e.__esModule ? function () {
            return e["default"]
        } : function () {
            return e
        };
        return i.d(n, "a", n), n
    }, i.o = function (e, n) {
        return Object.prototype.hasOwnProperty.call(e, n)
    }, i.p = "/assets/addons/litestore/vue-mobile/", i.oe = function (e) {
        throw console.error(e), e
    };
    var s = window["webpackJsonp"] = window["webpackJsonp"] || [], f = s.push.bind(s);
    s.push = n, s = s.slice();
    for (var h = 0; h < s.length; h++) n(s[h]);
    var l = f;
    r.push([0, "chunk-vendors"]), t()
})({
    0: function (e, n, t) {
        e.exports = t("56d7")
    }, "034f": function (e, n, t) {
        "use strict";
        var c = t("64a9"), o = t.n(c);
        o.a
    }, "56d7": function (e, n, t) {
        "use strict";
        t.r(n);
        t("cadf"), t("551c"), t("f751"), t("097d");
        var c = t("2b0e"), o = function () {
                var e = this, n = e.$createElement, t = e._self._c || n;
                return t("div", {attrs: {id: "app"}}, [t("router-view")], 1)
            }, a = [], r = t("f499"), u = t.n(r), i = t("5176"), s = t.n(i), f = t("c290"), h = {
                created: function () {
                    var e = this;
                    localStorage.getItem("Gconfig") && this.$store.replaceState(s()(this.$store.state, JSON.parse(localStorage.getItem("Gconfig")))), window.addEventListener("beforeunload", function () {
                        localStorage.setItem("Gconfig", u()(e.$store.state))
                    });
                    var n = "/addons/litestore/api.wxapp/base", t = this;
                    f["a"](n, {}, function (e) {
                        t.$store.commit("setCFG", e["data"]["wxapp"])
                    })
                }
            }, l = h, d = (t("034f"), t("2877")), p = Object(d["a"])(l, o, a, !1, null, null, null), m = p.exports,
            b = t("b970"), g = (t("157a"), t("8c4f"));
        c["a"].use(g["a"]);
        var k = new g["a"]({
            routes: [{
                path: "/", name: "Index", component: function (e) {
                    return t.e("chunk-854219ba").then(function () {
                        var n = [t("86d6")];
                        e.apply(null, n)
                    }.bind(this)).catch(t.oe)
                }, children: [{
                    path: "goods", component: function (e) {
                        return t.e("chunk-683fd508").then(function () {
                            var n = [t("ff5b")];
                            e.apply(null, n)
                        }.bind(this)).catch(t.oe)
                    }
                }, {
                    path: "news", component: function (e) {
                        return t.e("chunk-291f569a").then(function () {
                            var n = [t("e761")];
                            e.apply(null, n)
                        }.bind(this)).catch(t.oe)
                    }
                }]
            }, {
                path: "/goods", name: "Goods", component: function (e) {
                    return t.e("chunk-683fd508").then(function () {
                        var n = [t("ff5b")];
                        e.apply(null, n)
                    }.bind(this)).catch(t.oe)
                }
            }, {
                path: "/news", name: "News", component: function (e) {
                    return t.e("chunk-291f569a").then(function () {
                        var n = [t("e761")];
                        e.apply(null, n)
                    }.bind(this)).catch(t.oe)
                }
            }, {
                path: "/pageList", name: "PageList", component: function (e) {
                    return t.e("chunk-32ea458a").then(function () {
                        var n = [t("000d")];
                        e.apply(null, n)
                    }.bind(this)).catch(t.oe)
                }, children: [{
                    path: "relist", component: function (e) {
                        return t.e("chunk-0e455c8c").then(function () {
                            var n = [t("96e1")];
                            e.apply(null, n)
                        }.bind(this)).catch(t.oe)
                    }, children: [{
                        path: "goods", component: function (e) {
                            return t.e("chunk-683fd508").then(function () {
                                var n = [t("ff5b")];
                                e.apply(null, n)
                            }.bind(this)).catch(t.oe)
                        }
                    }]
                }]
            }, {
                path: "/SetToken", name: "SetToken", component: function (e) {
                    return t.e("chunk-76f88e57").then(function () {
                        var n = [t("40a1")];
                        e.apply(null, n)
                    }.bind(this)).catch(t.oe)
                }
            }, {
                path: "/my", name: "My", component: function (e) {
                    return t.e("chunk-c0734d00").then(function () {
                        var n = [t("c612")];
                        e.apply(null, n)
                    }.bind(this)).catch(t.oe)
                }, children: [{
                    path: "myorder", component: function (e) {
                        return t.e("chunk-72fb80f6").then(function () {
                            var n = [t("1d8e")];
                            e.apply(null, n)
                        }.bind(this)).catch(t.oe)
                    }, children: [{
                        path: "detail", component: function (e) {
                            return t.e("chunk-7c352045").then(function () {
                                var n = [t("dfd9")];
                                e.apply(null, n)
                            }.bind(this)).catch(t.oe)
                        }
                    }]
                }]
            }, {
                path: "/cartindex", name: "CartIndex", component: function (e) {
                    return t.e("chunk-3cd027a4").then(function () {
                        var n = [t("37a9")];
                        e.apply(null, n)
                    }.bind(this)).catch(t.oe)
                }, children: [{
                    path: "goods", component: function (e) {
                        return t.e("chunk-683fd508").then(function () {
                            var n = [t("ff5b")];
                            e.apply(null, n)
                        }.bind(this)).catch(t.oe)
                    }
                }, {
                    path: "cartcheck", component: function (e) {
                        return t.e("chunk-a1e0e73c").then(function () {
                            var n = [t("e793")];
                            e.apply(null, n)
                        }.bind(this)).catch(t.oe)
                    }, children: [{
                        path: "adresslist", component: function (e) {
                            return t.e("chunk-585ea9bb").then(function () {
                                var n = [t("7631")];
                                e.apply(null, n)
                            }.bind(this)).catch(t.oe)
                        }
                    }, {
                        path: "adressedit", component: function (e) {
                            return t.e("chunk-c01442dc").then(function () {
                                var n = [t("8b33")];
                                e.apply(null, n)
                            }.bind(this)).catch(t.oe)
                        }
                    }]
                }]
            }, {
                path: "/adresslist", name: "AdressList", component: function (e) {
                    return t.e("chunk-585ea9bb").then(function () {
                        var n = [t("7631")];
                        e.apply(null, n)
                    }.bind(this)).catch(t.oe)
                }
            }, {
                path: "/adressedit", name: "AdressEdit", component: function (e) {
                    return t.e("chunk-c01442dc").then(function () {
                        var n = [t("8b33")];
                        e.apply(null, n)
                    }.bind(this)).catch(t.oe)
                }
            }]
        }), v = t("2f62");
        c["a"].use(v["a"]);
        var y = {gcfg: null}, O = {
            getGcfg: function () {
                return y.gcfg
            }
        }, w = {
            setCFG: function (e, n) {
                e.gcfg = n
            }
        }, T = new v["a"].Store({state: y, getters: O, mutations: w}), x = T, S = t("fe3c"), R = t.n(S);
        c["a"].use(b["b"]), R.a.attach(document.body), c["a"].use(b["d"]), c["a"].config.productionTip = !1, c["a"].component("remote-script", {
            render: function (e) {
                var n = this;
                return e("script", {
                    attrs: {type: "text/javascript", src: this.src}, on: {
                        load: function (e) {
                            n.$emit("load", e)
                        }, error: function (e) {
                            n.$emit("error", e)
                        }, readystatechange: function (e) {
                            "complete" == this.readyState && n.$emit("load", e)
                        }
                    }
                })
            }, props: {src: {type: String, required: !0}}
        }), new c["a"]({
            router: k, store: x, render: function (e) {
                return e(m)
            }, data: {eventHub: new c["a"]}
        }).$mount("#app")
    }, "64a9": function (e, n, t) {
    }, "679b": function (e, n, t) {
        var c, o = "";
        c = o, e.exports.PROXYROOT = o, e.exports.ROOT = c, e.exports = {PROXYROOT: o, ROOT: c}
    }, c290: function (e, n, t) {
        "use strict";
        t.d(n, "b", function () {
            return h
        }), t.d(n, "a", function () {
            return l
        });
        t("4917"), t("cadf"), t("551c"), t("f751"), t("097d");
        var c = t("bc3a"), o = t.n(c), a = t("4328"), r = t.n(a), u = t("b970"), i = t("679b"),
            s = navigator.userAgent.toLowerCase(), f = !1;

        function h(e, n, t, c, a) {
            n.token = d("token"), console.log(i.ROOT + e);
            o.a.post(i.ROOT + e, r.a.stringify(n), {headers: {"Content-Type": "application/x-www-form-urlencoded"}}).then(function (e) {
                console.log(e), 0 == e.data.code ? u["a"].alert({title: "温馨提示", message: e.data.msg}).then(function () {
                }) : t && t(e.data), a && a()
            }).catch(function (e) {
                console.log(e), e.response && (401 == e.response.data.code && (window.location.href = f ? i.PROXYROOT + "/addons/litestore/api.uservue/connect?platform=wechat&url=" + window.location.href : i.ROOT + "/index/user/login"), console.log(e.response)), c && c(e), a && a()
            })
        }

        function l(e, n, t, c, a) {
            n.token = d("token"), console.log(i.ROOT + e);
            o.a.get(i.ROOT + e, {params: n}).then(function (e) {
                console.log(e), 0 == e.data.code ? u["a"].alert({title: "温馨提示", message: e.data.msg}).then(function () {
                }) : t && t(e.data), a && a()
            }).catch(function (e) {
                console.log(e), e.response && (401 == e.response.data.code && (window.location.href = f ? i.PROXYROOT + "/addons/litestore/api.uservue/connect?platform=wechat&url=" + window.location.href : i.ROOT + "/index/user/login"), console.log(e.response)), c && c(e), a && a()
            })
        }

        function d(e) {
            if (document.cookie.length > 0) {
                var n = document.cookie.indexOf(e + "=");
                if (-1 != n) {
                    n = n + e.length + 1;
                    var t = document.cookie.indexOf(";", n);
                    return -1 == t && (t = document.cookie.length), unescape(document.cookie.substring(n, t))
                }
            }
            return ""
        }

        "micromessenger" == s.match(/MicroMessenger/i) && (f = !0)
    }
});
//# sourceMappingURL=app.76dfabef.js.map