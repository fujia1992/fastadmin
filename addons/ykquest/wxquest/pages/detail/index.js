// pages/detail/index.js
var app = getApp();
Page({

    /**
     * 页面的初始数据
     */
    data: {
        pages: 1,
        lists: [],
        info: [],
        isGet: false,
        count: 0,
        eid: null,
        btnshwo: false,
        btnsucess: false,
        msg: "加载中"
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (e) {
        var that = this;
        var id = e.id;
        if (id) {
            that.setData({
                "eid": id
            });
            // that.getData(id);
        } else {
            that.setData({
                "msg": "暂无数据"
            });
            wx.navigateTo({
                url: '../index/index'
            })
        }
    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function () {

    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function () {
        var that = this;
        that.getData(that.data.eid);
    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function () {

    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function () {

    },

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function () {

    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function () {
        var that = this;
        var pages = that.data.pages;
        var count = that.data.count;
        var tempCount = pages * 10;
        // that.getData(1);
        if (tempCount >= count) {
            //加载完毕
            if (!that.data.btnshwo) {
                that.jscount();
            }
        } else {
            that.setData({
                "pages": pages + 1
            });
            that.loadmore(that.data.eid)
        }
    },

    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function () {
        var that = this;
        var id = that.data.eid
        if (res.from === 'button') {
        }
        return {
            title: '转发',
            path: '/pages/detail/index?id=' + id,
            success: function (res) {}
        }

    },
    getData: function (id) {
        var unid = wx.getStorageSync("openid");
        var that = this;
        var pages = that.data.pages;
        console.log(pages)
        if (unid) {
            wx.request({
                url: app.globalData.urlAjax + "survey_api/index", //
                data: {
                    openid: unid,
                    page: pages,
                    ids: id
                },
                header: {
                    'content-type': 'application/json' // 默认值
                },
                success(res) {
                    console.log(res.data.data.list)
                    if (res.data.code == 1) {
                        that.setData({
                            "lists": res.data.data.list,
                            'info': res.data.data.info,
                            "isGet": true,
                            "count": res.data.data.count
                        });
                        if (!that.data.btnshwo) {
                            that.jscount();
                        }
                    } else {
                        console.log(res.data.msg)
                        if (res.data.msg == "未注册") {
                            app.getInfo();
                        } else {
                            that.setData({
                                "msg": res.data.msg
                            })
                        }
                    }
                }
            })
        } else {
            app.getInfo();
        }
    },
    formSubmit: function (e) {
        var questlist = e.detail.value;
        var that = this;
        var arr = [];
        console.log(questlist)
        for (var index in questlist) {
            if (questlist[index] == "") {
                wx.showModal({
                    title: '警告',
                    content: '请填写完整',
                    success: function (res) {

                    }
                })
                return false;
            }
        }
        var unid = wx.getStorageSync("openid");
        if (unid) {
            console.log(unid)
            wx.request({
                url: app.globalData.urlAjax + "survey_api/setReplay", //仅为示例，并非真实的接口地址
                data: {
                    openid: unid,
                    quest: questlist,
                    ser_id: that.data.info.id
                },
                header: {
                    'content-type': 'application/json' // 默认值
                },
                success(res) {
                    console.log(res)
                    if (res.data.code == 1) {
                        that.setData({
                            "btnsucess": true
                        });
                    } else {
                        if (res.data.msg == "未注册") {
                            app.getInfo();
                        } else {
                            wx.showModal({
                                title: '警告',
                                content: res.data.msg,
                                success: function (res) {

                                }
                            })
                        }
                    }
                }
            })
        } else {
            app.getInfo();
        }
    },
    jscount: function () {
        var that = this;
        var show = false;
        var pages = that.data.pages;
        var count = that.data.count;
        if (pages * 10 >= count && that.data.lists != "") {
            show = true;
        }
        that.setData({
            "btnshwo": show
        });

    },
    loadmore: function (id) {
        var unid = wx.getStorageSync("openid");
        var that = this;
        var pages = that.data.pages;
        console.log(pages)
        if (unid) {
            wx.request({
                url: app.globalData.urlAjax + "survey_api/index", //仅为示例，并非真实的接口地址
                data: {
                    openid: unid,
                    page: pages,
                    ids: id
                },
                header: {
                    'content-type': 'application/json' // 默认值
                },
                success(res) {
                    if (res.data.code == 1) {
                        var temp = that.data.lists;
                        var tempData = res.data.data.list;
                        tempData.forEach(function (item, index) {
                            temp.push(item);
                        })
                        that.setData({
                            "lists": temp,
                            'info': res.data.data.info,
                            "isGet": true,
                            "count": res.data.data.count
                        });
                        if (!that.data.btnshwo) {
                            that.jscount();
                        }
                    } else {
                        console.log(res.data.msg)
                        if (res.data.msg == "未注册") {
                            app.getInfo();
                        } else {
                            var msg = res.data.msg;
                            if (res.data.msg == "") {
                                msg = "提交失败";
                            }
                            that.setData({
                                "msg": msg
                            })
                        }
                    }
                }
            })
        } else {
            app.getInfo();
        }
    },
})

function pushdArray(arr, valueArr) {
    if (valueArr.length) {
        for (var i = 0; i < arr.length; i++) {
            if (valueArr[i]) {
                arr[i].push(valueArr[i]);
            }
        }
    }
    return arr;
}