var app = getApp();
const Toptips = require('../../assets/libs/zanui/toptips/index');
const Toast = require('../../assets/libs/zanui/toast/toast');



Page({


  data: {
    comjob: [],
    ShowNoCom:false,
    Company:[]
  },

  onLoad: function (options) {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
    var that = this;
    app.Log_after_fun(function (ret) {
      //获得用户的职位列表
      app.request('/Companyjob/get_all_myjob', {}, function (data, ret) {
        that.setData({
          comjob: data.JobsList,
          Company: data.Company,
          ShowNoCom: false
        })

      }, function (data, ret) {
        //app.error(ret.msg);
        if (data == null && ret.msg == "不存在企业") {
          that.setData({
            ShowNoCom: true
          })
        }

      });
    });
  },
  zhuandaozj: function (e) {
    var that = this;
    wx.navigateTo({
      url: '/page/zh_Jobs/AddJob?com=' + that.data.Company.Id + '&name='+ that.data.Company.name 
    })
  },
  
})