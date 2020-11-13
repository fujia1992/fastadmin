var app = getApp();
const util = require('../../utils/util');
Page({

  data: {
    comjob: [],
    nodata: false,
  },
  onShow: function () {
    var that = this;
    app.Log_after_fun(function (ret) {
      //获得用户的职位列表
      app.request('/Resume/my_workforce', {}, function (data, ret) {
        console.log(data);
        data.forEach(function (e) {
          e.showtime = util.getTime(e.updatetime);
        })
        that.setData({
          comjob: data,
          nodata: data.length == 0 ? true : false
        })

      }, function (data, ret) {
        app.error(ret.msg);
      });
    });
  },
  
  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {

  },

  zhuandaozj: function (e) {
    wx.navigateTo({
      url: '/page/zh_workforce/Add'
    })
  },
  
})