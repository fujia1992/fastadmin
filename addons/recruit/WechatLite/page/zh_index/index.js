const util = require('../../utils/util');
var app = getApp();

Page({

  data: {
    bannerList: [],
    companyList:[],
    JobsList:[],
    show: false,
    cancelWithMask: true,
    actions: [],
    chose_city:{name:'',index:0},
    cancelText: '关闭',

  },


  selcity() {
    this.setData({
      'show': true
    });
  },
  closeActionSheet() {
    this.setData({
      'show': false
    });
  },
  handleActionClick({ detail }) {
    var that = this;
    // 获取被点击的按钮 index
    const { index } = detail;
    console.log(index);
    if (index == this.data.actions.length-1){
    }else{
      let chose_city = { name: this.data.actions[index].name, index: index };
      //this.setData({
      //  chose_city: chose_city
      //});
      app.globalData.locData['ShowCity'] = that.data.actions[index].name
      app.globalData.locData['ShowCity_id'] = app.globalData.city[index].id
      that.get_index_all_data(chose_city);
      this.closeActionSheet();
    }
  },
  MakelocCity: function (cb) {
    var that = this;
    if (!util.isEmptyObject(app.globalData.locData)){
      let tmp_index = 0
      app.globalData.city.forEach(function (item, index, arr) {
        let onei = {
          name: item.city,
          subname: '',
          loading: false
        };
        that.data.actions.push(onei);

        //这里根据前面获得的数据 来抓取 当下在的城市
        if (app.globalData.locData['ShowCity'] == item.city) {
          tmp_index = index;
          //app.globalData.locData['ShowCity_id'] = item.id
          console.log(index);
        }
      });
      let onei = {
        name: '',
        subname: '更多城市，暂未开通，敬请期待',
        loading: false
      };
      that.data.actions.push(onei);
      /*
      if (tmp_index == 0){
        app.globalData.locData['ShowCity'] = that.data.actions[tmp_index].name
        app.globalData.locData['ShowCity_id'] = app.globalData.city[tmp_index].id
      }
      */
      let chose_city = { name: that.data.actions[tmp_index].name, index: tmp_index };

      that.get_index_all_data(chose_city);
      return;
    }
    setTimeout(function () { that.MakelocCity(cb); }, 500);
  },
  onLoad: function (options) {
    var that = this;

    //这里间隔获得
    app.Log_after_fun(function () {
      //这里把app中的城市数据整理出来
      that.MakelocCity();
    });
  },
  get_index_all_data: function (chose_city) {
    var that = this;
    app.request('/index/get_index_all_data', { city_id: app.globalData.locData['ShowCity_id'] }, function (data, ret) {
      console.log(data);

      data.bannerList.forEach(function (item) {
        item.url = "/page/zh_news/index?id=" + item.id;
      });
      that.setData({
        bannerList: data.bannerList,
        companyList: data.companyList,
        JobsList: data.JobsList,

        actions: that.data.actions,
        chose_city: chose_city
      });

    }, function (data, ret) {
      app.error(ret.msg);
    });
  },

  onShow: function () {
  
  },

  onPullDownRefresh: function () {
    this.data.actions=[];
    this.onLoad();
    /*
    var that = this;
    //这里间隔获得
    app.Log_after_fun(function () {
      app.request('/index/get_index_all_data', {}, function (data, ret) {
        console.log(data);
        data.bannerList.forEach(function (item) {
          item.url = "/page/zh_news/index?id=" + item.id;
        });
        that.setData({
          bannerList: data.bannerList,
          companyList: data.companyList,
          JobsList: data.JobsList,
          actions: that.data.actions
        });

      }, function (data, ret) {
        app.error(ret.msg);
      });
    });
    */
  },

  onShareAppMessage: function () {
  
  },

  gotto_jobslist: function () {
    wx.switchTab({
      url: '/page/zh_Jobs/JobsList'
    })
  },
})