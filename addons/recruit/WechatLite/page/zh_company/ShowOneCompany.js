var app = getApp();
Page({

  data: {
    comD: [],
    PicAllData: [],
    article: {},
    id:null
  },

  onLoad: function (options) {
    var that = this;
    that.setData({
      id: options.id
    })
    app.Log_after_fun(function (ret) {
      app.request('/Companyjob/get_companyById', {id: options.id}, function (data, ret) {
        console.log(data);
        data.cimage = app.Domain_Public + data.cimage;
        if (data.content != '') {
          var content = data.content;
          console.log(content);
          content = content.replace(/[\n\r]/g, '<br>');
          content = app.towxml.toJson(content.replace(/: /g, ':'), 'html');
          console.log(content);
          that.traverse(content);
          that.setData({ article: content });
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
          PicAllData: tmparrpic
        })

      }, function (data, ret) {
        app.error(ret.msg);
        if (data == null && ret.msg == "不存在企业") {
          //跳转到
          wx.navigateBack({})
        }
        console.log(data);
        console.log(ret);
      });
    });
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
  onShow: function () {
  
  },
  onShareAppMessage: function () {
    var that = this;
    return {
      title: '企业详情：'+that.data.comD.name,
      path: '/page/zh_company/ShowOneCompany?id=' + that.data.comD.Id
    }
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
      phoneNumber: that.data.comD.tel
    })
  },
})