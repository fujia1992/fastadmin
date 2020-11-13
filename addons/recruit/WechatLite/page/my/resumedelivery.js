var app = getApp();
const util = require('../../utils/util');
Page({

  data: {
    comjob: [],
    nodata: false,
  },

  onLoad: function (options) {

  },
  onShow: function () {
    var that = this;
    app.Log_after_fun(function (ret) {
      //获得用户的职位列表
      app.request('/my/My_resumedelivery', {}, function (data, ret) {
        console.log(data);
        data.ResumedeliveryList.forEach(function (e) {
          e.showtime = util.getTime(e.updatetime);
        })
        //console.log(data.ResumedeliveryList.length);
        that.setData({
          comjob: data.ResumedeliveryList,
          nodata: data.ResumedeliveryList.length == 0 ? true : false
        })

      }, function (data, ret) {
        app.error(ret.msg);
      });
    });
  },

})