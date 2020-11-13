var app = getApp();
const util = require('../../utils/util');
Page({

  data: {
    ResumeD: [],
    id:null,
    QrcodeImg:null
  },

  onLoad: function (options) {
    var that = this;
    console.log(options);
    if (options.id != undefined){
      that.setData({
        id: options.id
      })
    }
    if (options.scene != undefined){
      var scene = decodeURIComponent(options.scene);
      that.setData({
        id: scene
      })
    }
    that.setData({
      QrcodeImg: (app.Isdebug ? app.QrPngUrl : app.apiUrl) + "Usewechat/get_resume_QrPng?id=" + that.data.id,
    }) 
  },

  onShow: function () {
    var that = this;
    app.Log_after_fun(function (ret) {
      app.request('/Resume/get_resumeByID', {id:that.data.id}, function (data, ret) {
        console.log(data);
        data.updatetime = util.getTime(data.updatetime);
        data.c_avatar = app.Domain_Public + data.c_avatar;
        if (data.content != '') {
          data.content = data.content.replace(/[\n\r]/g, '<br>');
          data.content = app.towxml.toJson(data.content.replace(/: /g, ':'), 'html', that);
          that.traverse(data.content);
        }

        that.setData({
          ResumeD: data,
          NoResume: false
        })

      }, function (data, ret) {
        if (data == null && ret.msg == "不存在简历") {
          that.setData({
            NoResume: true
          })
        }
        console.log(data);
        console.log(ret);
      });
    });
  },
  traverse: function (obj) {
    for (var a in obj) {
      if (typeof (obj[a]) == "object") {
        this.traverse(obj[a]);
      } else {
        if (a == 'src' && obj[a].indexOf("/uploads/") == 0) {
          obj[a] = app.Domain_Public + obj[a];
        }
      }
    }
  },
  onShareAppMessage: function () {
    var that = this;
    return {
      title: that.data.ResumeD.name + '的简历',
      path: '/page/zh_resume/ShowOneResume?id=' + that.data.ResumeD.id
    }
  },
  telmake: function (options) {
    var that = this;
    wx.makePhoneCall({
      phoneNumber: that.data.ResumeD.tel
    })
  },
})