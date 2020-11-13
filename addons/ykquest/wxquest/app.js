//app.js
App({
    onLaunch: function () {
        // 展示本地存储能力
        var logs = wx.getStorageSync('logs') || []
        logs.unshift(Date.now())
        wx.setStorageSync('logs', logs)
        // var unid=wx.getStorageSync("openid");
        // if(!unid){
        //   this.getInfo()
        // }

    },
    globalData: {
        userInfo: null,
        iv: null,
        encryptedData: null,
        rowData: null,
        urlAjax: "https://****/addons/ykquest/"
    },
    login: function () {
        // 登录
        wx.login({
            success: res => {
                // 发送 res.code 到后台换取 openId, sessionKey, unionId
                var iv = getApp().globalData.iv;
                var encryptedData = getApp().globalData.encryptedData;
                var rowData = getApp().globalData.rowData;
                console.log(rowData)
                var code = res.code;
                if (code) {
                    wx.request({
                        method: 'GET',
                        url: this.globalData.urlAjax + "user_api/userlogin",
                        dataType: 'json',
                        header: {'content-type': 'application/x-www-form-urlencoded'},
                        data: {code: code, rawData: rowData},
                        success: function (res) {
                            console.log(res.data)
                            if (res.data.code == 0) {
                                wx.showModal({
                                    title: '警告',
                                    content: "服务器出现异常，请联系站长",
                                    success: function (res) {
                                    }
                                })
                            } else {
                                console.log(res.data.data.openid)
                                var openid = res.data.data.openid;
                                try {
                                    wx.setStorageSync('openid', openid)
                                    wx.setStorage('openid', openid)
                                    wx.navigateBack({
                                        delta: 1
                                    })
                                } catch (e) {
                                    Console.log(333)
                                }
                            }
                        }, fail: function () {
                            wx.showModal({
                                title: '警告',
                                content: '出现异常，请联系站长',
                                success: function (res) {
                                }
                            })
                        }
                    });
                } else {
                    console.log('获取用户登录态失败：' + res.errMsg);
                }
            }
        })
    },
    getInfo: function (e) {
        wx.getUserInfo({
            success: res => {
                this.globalData.userInfo = res.userInfo
                this.globalData.iv = res.iv
                this.globalData.encryptedData = res.encryptedData
                this.globalData.rowData = res.rawData
                this.login();
            }, fail: function () {
                //  获取用户信息失败后。请跳转授权页面
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
            }
        })
    }
})