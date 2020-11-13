//index.js
//获取应用实例
const app = getApp()
Page({
    data: {
        motto: 'Hello World',
        userInfo: {},
        hasUserInfo: false,
        canIUse: wx.canIUse('button.open-type.getUserInfo'),
        lists: [],
        islists: false,
        page: 1,
        count: 0,
    },
    //事件处理函数
    bindViewTap: function () {
        wx.navigateTo({
            url: '../logs/logs'
        })
    },
    onLoad: function () {
        // this.getList()
    },
    getUserInfo: function (e) {
        console.log(app.globalData.userInfo)
        app.globalData.userInfo = e.detail.userInfo
        this.setData({
            userInfo: e.detail.userInfo,
            hasUserInfo: true,
        })
    },
    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function () {
        var a = wx.getStorage("openid");
        this.getList()
    },
    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function () {
        // this.getList()
    },
    loadmore: function () {
        var unid = wx.getStorageSync("openid");

        if (!unid) {
            app.getInfo();
        } else {
            var that = this;
            wx.request({
                url: app.globalData.urlAjax + "survey_api/serList",
                data: {
                    openid: unid,
                    page: that.data.page
                },
                header: {
                    'content-type': 'application/json' // 默认值
                },
                success(res) {
                    if (res.data.code == 1) {
                        var temp = that.data.lists;
                        if (res.data.data.list != "") {
                            that.setData({
                                "islists": true
                            })
                            var tempArr = res.data.data.list;
                            tempArr.forEach(function (item, index) {
                                temp.push(item);
                            })
                        }
                        that.setData({
                            "lists": temp
                        })
                    } else {
                        if (res.data.msg == "未注册") {
                            wx.removeStorage("openid");
                            wx.showModal({
                                title: '警告',
                                content: '尚未进行授权，请点击确定跳转到授权页面进行授权。',
                                success: function (res) {
                                    if (res.confirm) {
                                        console.log('用户点击确定')
                                        wx.navigateTo({
                                            url: '../auth/index',
                                        })
                                    }
                                }
                            })
                        } else {
                            wx.showModal({
                                title: '警告',
                                content: res.data.msg,
                                success: function (res) {
                                    if (res.confirm) {

                                    }
                                }
                            })
                        }
                    }
                }
            })
        }
    },
    edit: function (e) {
        var id = e.currentTarget.dataset.id;
        // 跳转 navigateTo
        wx.navigateTo({
            url: '../detail/index?id=' + id
        })
    },
    getList: function () {
        var unid = wx.getStorageSync("openid");
        if (unid == "") {
            console.log(111)
            this.onLoad();
        }
        if (!unid) {
            app.getInfo();
        } else {
            var that = this;
            wx.request({
                url: app.globalData.urlAjax + "survey_api/serList",
                data: {
                    openid: unid,
                    page: that.data.page
                },
                header: {
                    'content-type': 'application/json' // 默认值
                },
                success(res) {
                    if (res.data.code == 1) {
                        if (res.data.data.list != "") {
                            that.setData({
                                "islists": true
                            })
                        } else {
                            that.setData({
                                "islists": false
                            })
                        }
                        that.setData({
                            "lists": res.data.data.list,
                            'count': res.data.data.count
                        })
                    } else {
                        if (res.data.msg == "未注册") {
                            wx.removeStorage("openid");
                            wx.showModal({
                                title: '警告',
                                content: '尚未进行授权，请点击确定跳转到授权页面进行授权。',
                                success: function (res) {
                                    if (res.confirm) {
                                        console.log('用户点击确定')
                                        wx.navigateTo({
                                            url: '../auth/index',
                                        })
                                    }
                                }
                            })
                        } else {
                            wx.showModal({
                                title: '警告',
                                content: res.data.msg,
                                success: function (res) {
                                    if (res.confirm) {

                                    }
                                }
                            })
                        }
                    }
                }
            })
        }
    },
    onReachBottom: function () {
        var that = this;
        var tempCount = that.data.page * 6;
        if (tempCount < that.data.count) {
            that.setData({
                "page": that.data.page + 1
            })
            that.loadmore();
        }
    }
})
