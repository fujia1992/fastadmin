const util = require('../../utils/util');

var app = getApp();

Page({
  cash_model: [0, 3000, 4000, 5000, 6000, 7000, 8000, 9000, 10000],
  data: {
    inputValue: '',
    YueXin: '月薪范围',
    XueLi: '学历要求',
    ZhuSu: '住宿安排',
    YueXin_num: 0,
    XueLi_num: 0,
    ZhuSu_num: 0,
    currentTab: -1,

    comjob:[],
    loading: false,
    nodata: false,
    nomore: false,
  },
  page: 1,
  page_block: 10,

  onLoad: function (options) {
    this.page = 1;
  },

  onShow: function () {
    /*
    this.page = 1;
    var that = this;
    app.Log_after_fun(function (ret) {
      that.loadJobs();
    });
    */
    this.page = 1;
    this.setData({ nodata: false, nomore: false });
    this.loadJobs(function () {
      wx.stopPullDownRefresh();
    });
  },
  loadJobs: function (cb) {
    let that = this;
    if (that.data.nomore == true || that.data.loading == true) {
      return;
    }
    if (!util.isEmptyObject(app.globalData.locData)) {
      this.setData({ loading: true });
      app.request('/Companyjob/get_JobList_resrch', {
        page: that.page,
        page_block: that.page_block,
        YueXin: that.cash_model[that.data.YueXin_num],
        XueLi_num: that.data.XueLi_num,
        ZhuSu_num: that.data.ZhuSu_num,
        Re_input: that.data.inputValue,
        ShowCity_id:app.globalData.locData['ShowCity_id']
      }, function (data, ret) {
        console.log(data);
        that.setData({
          loading: false,
          nodata: that.page == 1 && data.length == 0 ? true : false,
          nomore: (that.page > 1 && data.length == 0) ||
            (that.page == 1 && data.length < that.page_block && data.length > 0) ? true : false,
          comjob: that.page > 1 ? that.data.comjob.concat(data) : data
        })
        that.page++;
        typeof cb == 'function' && cb(data);
      }, function (data, ret) {
        app.error(ret.msg);
      });
    }
    setTimeout(function () { that.loadJobs(cb); }, 500);
  },
  onPullDownRefresh: function () {
    this.page = 1;
    this.setData({ nodata: false, nomore: false });
    this.loadJobs(function () {
      wx.stopPullDownRefresh();
    });
  },
  onReachBottom: function () {
    var that = this;
    this.loadJobs(function (data) {
      if (data.length == 0) {
        app.info("暂无更多数据");
      }
    });
  },
  onShareAppMessage: function () {
  
  },
  searchChange(e) {
    this.setData({
      inputValue: e.detail.value
    });
  },

  searchDone(e) {
    //console.error('search', e.detail.value)
    this.onPullDownRefresh();
  },

  handleCancel() {
    //console.error('cancel')
    this.onPullDownRefresh();
  },
  // 下拉切换
  hideNav: function () {
    this.setData({
      displays: "none",
      currentTab: -1,
    })
  },
  // 区域
  tabNav: function (e) {
    console.log('出问题' + e.target.dataset.current);
    if (e.target.dataset.current == undefined){
      return;
    }
    this.setData({
      displays: "block"
    })
  
    if (this.data.currentTab === e.target.dataset.current) {
      return false;
    } else {

      var showMode = e.target.dataset.current == 0;

      this.setData({
        currentTab: e.target.dataset.current,
        isShow: showMode
      })
    }
  },
  go_do_resrch: function() {

  },
  clickYueXinNum: function (e) {
    console.log(e.target.dataset.num)
    if (e.target.dataset.num == this.data.YueXin_num){
      return;
    }

    this.setData({
      YueXin_num: e.target.dataset.num
    })
    this.setData({
      YueXin: e.target.dataset.name
    })
    this.setData({
      displays: "none"
    })
    console.log(e.target.dataset.name)

    this.onPullDownRefresh();
  },
  clickXueLiNum: function (e) {
    console.log(e.target.dataset.num)
    if (e.target.dataset.num == this.data.XueLi_num) {
      return;
    }
    this.setData({
      XueLi_num: e.target.dataset.num
    })
    this.setData({
      XueLi: e.target.dataset.name
    })
    this.setData({
      displays: "none"
    })
    console.log(e.target.dataset.name)
    this.onPullDownRefresh();
  },
  clickZhuSuNum: function (e) {
    console.log(e.target.dataset.num)
    if (e.target.dataset.num == this.data.ZhuSu_num) {
      return;
    }
    this.setData({
      ZhuSu_num: e.target.dataset.num
    })
    this.setData({
      ZhuSu: e.target.dataset.name
    })
    this.setData({
      displays: "none"
    })
    console.log(e.target.dataset.name)
    this.onPullDownRefresh();
  },
  Cnagetabfun: function (e) {
    let num = e.detail.current;
    //console.log('自家家的+'+num);
    this.setData({
      currentTab: num
    })
  }
})