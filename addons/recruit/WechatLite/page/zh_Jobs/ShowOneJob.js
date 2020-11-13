var app = getApp();
const Dialog = require('../../assets/libs/zanui/dialog/dialog');
const util = require('../../utils/util');
Page({

  data: {
    id:null,
    comjob:[],
    PicAllData: [],

    ResumeNum: 0,
    ResumedeliveryData: null,
    canSendData: true,
  },
  onShow: function () {
    //post 拖取数据
    var that = this;
    app.request('/Companyjob/get_c_job', { id: that.data.id }, function (data, ret) {
      console.log(data);
      data.recruitcompany.cimage = app.Domain_Public + data.recruitcompany.cimage;
      if (data.content != '') {
        data.content = data.content.replace(/[\n\r]/g, '<br>');
        data.content = app.towxml.toJson(data.content.replace(/: /g, ':'), 'html', that);
        //data.content = app.towxml.toJson(data.content, 'html', that);
        that.traverse(data.content);
      }
      if (data.recruitcompany.content != '') {
        data.recruitcompany.content = data.recruitcompany.content.replace(/[\n\r]/g, '<br>');
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

    //这里拖取 本人的简历投递情况 和 简历是否存在的情况
    app.Log_after_fun(function (ret) {
      app.request('/my/Job_re_stat', { id: that.data.id }, function (data, ret) {
        console.log(data);
        //格式化时间
        if (data.red_D != null) {
          var updatetime = util.getTime(data.red_D['updatetime']);
          data.red_D['showtime'] = updatetime;
        }

        that.setData({
          ResumeNum: data.ResumeNum,
          ResumedeliveryData: data.red_D
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
    if (options.scene != undefined) {
      var scene = decodeURIComponent(options.scene);
      that.setData({
        id: scene
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
  add_re: function (e) {
    var that = this;
    if (this.data.ResumeNum == 0) {
      Dialog({
        title: '暂时无法投递简历',
        message: '您未提交简历，请增加您的简历先。',
        selector: '#zan-dialog-test',
        buttons: [{
          // 按钮文案
          text: '增加简历',
          // 按钮文字颜色
          color: 'red',
          // 按钮类型，用于在 then 中接受点击事件时，判断是哪一个按钮被点击
          type: 'add'
        }, {
          text: '取消',
          type: 'cancel'
        }]
      }).then((res) => {
        console.log(res);
        if (res.type == 'add') {
          //这里跳转  首先关闭 所有的页面。然后打开增加简历的页面
          wx.navigateTo({
            url: '/page/zh_resume/AddResume'
          })
        }
      })

      return;
    } else {
      if (!that.data.canSendData) {
        return;
      }
      that.data.canSendData = false;
      //这里做投递的交互
      app.request('/my/add_resume_resumedelivery', { id: that.data.id, com_name: this.data.comjob.recruitcompany.name, job_name: this.data.comjob.name }, function (data, ret) {
        console.log(data);
        app.success('简历投递成功!');

        setTimeout(function () {
          wx.redirectTo({
            url: '/page/zh_Jobs/ShowOneJob?id=' + that.data.id
          });
          that.data.canSendData = true;
        }, 180)
      }, function (data, ret) {
        app.error(ret.msg);
        setTimeout(function () { that.data.canSendData = true; }, 1000)
      });
    }
  },
  onShareAppMessage: function () {
    var that = this;
    return {
      title: '职位详情：' + that.data.comjob.name,
      path: '/page/zh_Jobs/ShowOneJob?id=' + that.data.comjob.Id
    }
  }
})