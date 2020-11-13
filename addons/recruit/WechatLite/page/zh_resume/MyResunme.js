const Dialog = require('../../assets/libs/zanui/dialog/dialog');
const util = require('../../utils/util');
const Toptips = require('../../assets/libs/zanui/toptips/index');
var app = getApp();
Page({

  data: {
    NoResume:true,
    ResumeD:[],
    content: 'toptips',
    duration: 3000,
    QrcodeImg: null,
    $zanui: {
      toptips: {
        show: false
      }
    },
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
  showTopTips(mes) {
    this.setData({
      content: mes,
      $zanui: {
        toptips: {
          show: true
        }
      }
    });

    setTimeout(() => {
      this.setData({
        $zanui: {
          toptips: {
            show: false
          }
        }
      });
    }, this.data.duration);
  },
  onLoad: function (options) {
  
  },

  onShow: function () {
    var that = this;
    app.Log_after_fun(function (ret) {
      app.request('/Resume/get_my_resume', {}, function (data, ret) {
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
          NoResume: false,
          QrcodeImg: (app.Isdebug ? app.QrPngUrl : app.apiUrl) + "Usewechat/get_resume_QrPng?id=" + data.id,
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

  zhuandaozj: function (e) {
    wx.navigateTo({
      url: '/page/zh_resume/AddResume'
    })
  },
  editziliao: function (e) {
    let that = this;
    wx.navigateTo({
      url: '/page/zh_resume/EditResume?id=' + that.data.ResumeD.id
    })
  },
  telmake: function (options) {
    var that = this;
    wx.makePhoneCall({
      phoneNumber: that.data.ResumeD.tel
    })
  },
  delZiLiao: function (options) {
    var that = this;
    Dialog({
      title: '删除',
      message: '确认删除:\n您的简历？',
      selector: '#zan-base-dialog',
      showCancelButton: true
    }).then(() => {
      app.request('/Resume/del_my_Resume', { 'id': that.data.ResumeD.id }, function (data, ret) {
        that.showTopTips('删除成功');
        that.setData({
          ResumeD: [],
          NoResume: true
        })
      }, function (data, ret) {
        that.showTopTips('删除失败');
        app.error(ret.msg);
        console.log(data);
        console.log(ret);
      });

    }).catch(() => {
      console.log('=== dialog reject ===', 'type: cancel');
    });
  },
  onShareAppMessage: function (res) {
    var that = this;
    return {
      title: that.data.ResumeD.name+'的简历',
      path: '/page/zh_resume/ShowOneResume?id=' + that.data.ResumeD.id
    }
  }
})