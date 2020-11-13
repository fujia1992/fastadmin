const Dialog = require('../../assets/libs/zanui/dialog/dialog');
var app = getApp();
Page({
  data: {
    isWxapp: true,
    userInfo: {
      id: 0,
      avatar: '/assets/img/avatar.png',
      nickname: '游客',
      balance: 0,
      score: 0,
      level: 0,
      group_id:1,
    },
    com_name:'',
    jobs_num:0,
    resum_num:0,
    JobfairCount:0,
  },
  onLoad: function () {

  },
  onShow: function () {
    var that = this;
    if (app.globalData.userInfo) {
      that.setData({ userInfo: app.globalData.userInfo, isWxapp: that.isWxapp() });
      //如果是企业 获得企业数据
     // if (app.globalData.userInfo.group_id==2){
        app.request('/Companyjob/get_my_companyName_jobsnum', {}, function (data, ret) {
          console.log(data);
          that.setData({
            com_name: data.name,
            jobs_num: data.jobCount,
            resum_num: data.resum_num,
            JobfairCount: data.JobfairCount,
          })
        }, function (data, ret) {
          console.log(data);
          console.log(ret);
        });
        
     // }
    }
  },
  login: function () {
    var that = this;
    app.login(function () {
      that.setData({ userInfo: app.globalData.userInfo, isWxapp: that.isWxapp() });
    });
  },
  isWxapp: function () {
    return  true;
    //return app.globalData.userInfo ? app.globalData.userInfo.username.match(/^u\d+$/) : true;
  },
  showTips: function (event) {
    var tips = {
      balance: '余额通过插件的出售获得',
      score: '积分可以通过回答问题获得',
      level: '等级通过官网活跃进行升级',
    };
    var type = event.currentTarget.dataset.type;
    var content = tips[type];
    wx.showModal({
      title: '温馨提示',
      content: content,
      showCancel: false
    });
  },
  //点击头像上传
  uploadAvatar: function () {
    var that = this;
    //var formData = app.globalData.config.upload.multipart;
    //var token = wx.getStorageSync('token') || '';
    let formData = [];
    if (app.globalData.userInfo) {
      formData['user_id'] = app.globalData.userInfo.id;
      formData['token'] = app.globalData.userInfo.token;
    }
    //formData.token = token;
    wx.chooseImage({
      success: function (res) {
        var tempFilePaths = res.tempFilePaths;
        wx.uploadFile({
          url: app.globalData.config.upload.uploadurl,
          filePath: tempFilePaths[0],
          name: 'file',
          formData: formData,
          success: function (res) {
            var data = JSON.parse(res.data);
            if (data.code == 1) {
              app.request("/user/avatar", { avatar: data.data.url }, function (data, ret) {
                app.success('头像上传成功!');
                app.globalData.userInfo = data.userInfo;
                that.setData({ userInfo: data.userInfo, isWxapp: that.isWxapp() });
              }, function (data, ret) {
                app.error(ret.msg);
              });
            }
          }, error: function (res) {
            app.error("上传头像失败!");
          }
        });
      }
    });
  },
  onGotUserInfo: function (e) {
    console.log(e.detail.errMsg);
    console.log(e.detail.userInfo);
    console.log(e.detail.rawData);
    if (e.detail.userInfo == undefined){
      return;
    }
    //这里先本地赋值;
    app.globalData.userInfo.avatar = e.detail.userInfo.avatarUrl;
    app.globalData.userInfo.nickname = e.detail.userInfo.nickName;
    app.globalData.userInfo.mobile = app.globalData.userInfo.mobile == "NoLoginData" ? '' : app.globalData.userInfo.mobile;
    //提交到 服务器
    var token = wx.getStorageSync('token') || '';
    var that = this;
    wx.request({
      url: app.apiUrl + 'user/Updata_user_hawk',
      data: {
        userInfo: e.detail.rawData,
        mobile: app.globalData.userInfo.mobile,
        token: token
      },
      method: 'post',
      header: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      success: function (lres) {
        console.log(lres);
        /* wx.redirectTo({
           url: 'index'
         })*/
        that.onShow();
      }
    });
  },
  change_js: function () {
    var that = this;
    Dialog({
      title: '选择您的角色',
      message: '“找活”，帮企业找人，帮蓝领找活，请选择您的角色',
      selector: '#zan-dialog-test',
      buttonsShowVertical: true,
      buttons: [{
        // 按钮文案
        text: '我是企业，找人才',
        // 按钮文字颜色
        color: 'red',
        // 按钮类型，用于在 then 中接受点击事件时，判断是哪一个按钮被点击
        type: 2
      }, {
        text: '我是求职者，找工作',
        color: '#3CC51F',
        type: 3
      }, {
          text: '取消',
          type: 'cancel'
       }]
    }).then((res) => {
      console.log(res.type);
      if (res.type =='cancel'){
        return;
      }
      //数据库更新，用户类别
      app.request('/user/group', res, function (data) {
        app.success('角色选择成功!', function () {
          app.globalData.userInfo.group_id = res.type;
          that.setData({ userInfo: app.globalData.userInfo });
         // wx.reLaunch({
         //   url: '/page/index/index'
         // })
        });
      }, function (data, ret) {
        app.error(ret.msg);
      });

    })
  }
})
