const Dialog = require('../../assets/libs/zanui/dialog/dialog');
const Toptips = require('../../assets/libs/zanui/toptips/index');
var app = getApp();

Page({

  data: {
    comD:[],
    PicAllData:[],
    NoCom:true,
    content: 'toptips',
    duration: 3000,
    $zanui: {
      toptips: {
        show: false
      }
    },
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
  traverse: function (obj) {
    for (var a in obj) {
      if (typeof (obj[a]) == "object") {
        this.traverse(obj[a]); //递归遍历  
      } else {
        if (a == 'src' && obj[a].indexOf("/uploads/") == 0) {
          //console.log(a + "=" + obj[a]); 
          obj[a] = app.Domain_Public + obj[a];
        }
      }
    }
  },
  onShow: function (options) {
    var that = this;
    app.Log_after_fun(function (ret) {
      app.request('/Companyjob/get_my_company', {}, function (data, ret) {
        console.log(data);
        data.cimage = app.Domain_Public + data.cimage;
        if (data.content != '') {
          data.content = data.content.replace(/[\n\r]/g, '<br>');
          data.content = app.towxml.toJson(data.content.replace(/: /g, ':'), 'html', that);
          that.traverse(data.content);
        }
        let tmparrpic = [];
        if (data.cimages != "") {
          data.cimages.split(",").forEach(function (item) {
            item = app.Domain_Public + item;
            tmparrpic.push(item);
          });
        }
        that.setData({
          comD: data,
          PicAllData: tmparrpic,
          NoCom:false
        })

      }, function (data, ret) {
        //app.error(ret.msg);
        if (data == null && ret.msg == "不存在企业") {
          //跳转到
          //wx.navigateTo({
          //  url: '/page/zh_company/AddCompany'
          //})
          that.setData({
            NoCom: true
          })
        }
        console.log(data);
        console.log(ret);
      });
    });
  },
  editziliao: function (options) {
    wx.navigateTo({
        url: '/page/zh_company/EditConpany'
    })
  },
  onLoad: function (options) {
    var that = this;
    app.Log_after_fun(function (ret) {
        app.request('/Companyjob/get_my_company', {}, function (data, ret) {
          console.log(data);
          data.cimage = app.Domain_Public + data.cimage;
          if (data.content != '') {
            data.content = app.towxml.toJson(data.content.replace(/: /g, ':'), 'html', that);
            that.traverse(data.content);
          }
          let tmparrpic = [];
          if (data.cimages != "") {
            data.cimages.split(",").forEach(function (item) {
              item = app.Domain_Public + item;
              tmparrpic.push(item);
            });
          }
          that.setData({
            comD: data,
            PicAllData: tmparrpic,
            NoCom: false
          })

        }, function (data, ret) {
          //app.error(ret.msg);
          if (data == null && ret.msg=="不存在企业"){
            //跳转到
            //wx.navigateTo({
            //  url: '/page/zh_company/AddCompany'
            //})
            that.setData({
              NoCom: true
            })
          }
          console.log(data);
          console.log(ret);
        });
     });
  },
  zhuandaozj: function (e) {
    wx.navigateTo({
      url: '/page/zh_company/AddCompany'
     })
  },
  pic_click: function (e) {
    var that = this;
    wx.previewImage({
      current: e.currentTarget.dataset.src,
      urls: that.data.PicAllData,
      success: function (res) {
        console.log(res);
      },
      fail: function () {
        console.log('fail')
      }
    });
  },
  telmake: function (options) {
    var that = this;
    wx.makePhoneCall({
      phoneNumber: that.data.comD.tel
    })
  },
  delZiLiao: function (options) {
    var that = this;
    Dialog({
      title: '删除',
      message: '确认删除:\n企业名称:' + that.data.comD.name,
      selector: '#zan-base-dialog',
      showCancelButton: true
    }).then(() => {
      app.request('/Companyjob/del_my_company', { 'Id': that.data.comD.Id}, function (data, ret) {
        that.showTopTips('删除成功');
        that.setData({
          comD: [],
          PicAllData: [],
          NoCom: true
        })
      }, function (data, ret) {
        app.error(ret.msg);
        console.log(data);
        console.log(ret);
      });

    }).catch(() => {
      console.log('=== dialog reject ===', 'type: cancel');
    });
  },
  onShareAppMessage: function (options) {
    var that = this;
    return {
      title: '企业详情：' + that.data.comD.name,
      path: '/page/zh_company/ShowOneCompany?id=' + that.data.comD.Id
    }
  }
})