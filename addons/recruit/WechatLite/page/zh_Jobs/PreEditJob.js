const Dialog = require('../../assets/libs/zanui/dialog/dialog');
var app = getApp();

Page({

  data: {
    id: null,
    comjob: [],
    PicAllData: [],
  },
  onShow: function () {
    var that = this;
    app.Log_after_fun(function (ret) {
      //post 拖取数据
      app.request('/Companyjob/get_c_job', { id: that.data.id }, function (data, ret) {
        console.log(data);
        data.recruitcompany.cimage = app.Domain_Public + data.recruitcompany.cimage;
        if (data.content != '') {
          data.content = data.content.replace(/[\n\r]/g, '<br>');
          data.content = app.towxml.toJson(data.content.replace(/: /g, ':'), 'html', that);
          that.traverse(data.content);
        }
        if (data.recruitcompany.content != '') {
          data.recruitcompany.content = app.towxml.toJson(data.recruitcompany.content.replace(/: /g, ':'), 'html', that);
          that.traverse(data.recruitcompany.content);
        }
        let tmparrpic = [];
        if (data.recruitcompany.cimages != "") {
          data.recruitcompany.cimages.split(",").forEach(function (item) {
            item = app.Domain_Public + item;
            tmparrpic.push(item);
          });
        }
        that.setData({
          comjob: data,
          PicAllData: tmparrpic
        })

      }, function (data, ret) {
        app.error(ret.msg);
      });

    });
  },
  onLoad: function (options) {
    var that = this;
    console.log(options);
    if (options.id != undefined) {
      that.setData({
        id: options.id
      })
    }
    that.setData({
      QrcodeImg: (app.Isdebug ? app.QrPngUrl : app.apiUrl) + "Usewechat/get_Job_QrPng?id=" + that.data.id,
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
  telmake: function (options) {
    var that = this;
    wx.makePhoneCall({
      phoneNumber: that.data.comjob.recruitcompany.tel
    })
  },
  onShareAppMessage: function () {
    var that = this;
    return {
      title: '职位详情：' + that.data.comjob.name,
      path: '/page/zh_Jobs/ShowOneJob?id=' + that.data.comjob.Id
    }
  },
  editziliao: function () {
    var that = this;
    wx.navigateTo({
      url: '/page/zh_Jobs/EditJob?id=' + that.data.comjob.Id
    })
  },
  delZiLiao: function (options) {
    var that = this;
    Dialog({
      title: '删除',
      message: '确认删除:\n职位名称:' + that.data.comjob.name,
      selector: '#zan-base-dialog',
      showCancelButton: true
    }).then(() => {
      app.request('/Companyjob/del_my_job', { 'Id': that.data.comjob.Id }, function (data, ret) {
        
        wx.navigateBack({})
        //wx.reLaunch({
        //  url: '/page/zh_Jobs/Myjobs'
       // })
      }, function (data, ret) {
        app.error(ret.msg);
        console.log(data);
        console.log(ret);
      });

    }).catch(() => {
      console.log('=== dialog reject ===', 'type: cancel');
    });
  }
})