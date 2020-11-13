const Dialog = require('../../assets/libs/zanui/dialog/dialog');
var app = getApp();
Page({

  data: {
  
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
  
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
    var that = this;
    //登录成功后，判断是否 选择了角色： 企业、求职者
    app.Log_after_fun(function (ret) { 
      if (app.globalData.userInfo.group_id == 1) {
        Dialog({
          title: '选择您的角色',
          message: '您第一次登录“找活”，请选择您的角色',
          selector: '#zan-dialog-test',
          buttonsShowVertical: true,
          buttons: [{
            // 按钮文案
            text: '我是企业，找人才',
            // 按钮文字颜色
            color: 'red',
            // 按钮类型，用于在 then 中接受点击事件时，判断是哪一个按钮被点击
            type: '2'
          }, {
            text: '我是求职者，找工作',
            color: '#3CC51F',
            type: '3'
          }]
        }).then((res) => {
          console.log(res.type);
          //数据库更新，用户类别
          app.request('/user/group', res, function (data) {
            app.success('角色选择成功!', function () {
              app.globalData.userInfo.group_id == res.type;
              that.drect_page();
            });
          }, function (data, ret) {
            app.error(ret.msg);
          });

        })
      }else{
        that.drect_page();
      }
    });

  },

  drect_page: function(){
    console.log(app.globalData.userInfo.group_id);
    if (app.globalData.userInfo.group_id == 2){
      wx.switchTab({
        url: '/page/zh_index/index'
      })
    }
    if (app.globalData.userInfo.group_id == 3){
      wx.switchTab({
        url: '/page/zh_index/index'
      })
    }
  },
  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
  
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
  
  },
})