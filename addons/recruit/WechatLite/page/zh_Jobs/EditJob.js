var app = getApp();
const Toptips = require('../../assets/libs/zanui/toptips/index');
const Toast = require('../../assets/libs/zanui/toast/toast');
Page({

  /**
   * 页面的初始数据
   */
  data: {
    city: [],
    content: 'toptips',
    duration: 3000,
    $zanui: {
      toptips: {
        show: false
      }
    },
    id:null,
    multiArray: [
      ['3000', '3500', '4000', '4500', '5000', '5500', '6000', '6500', '7000', '7500', '8000', '8500', '9000', '9500', '10000'],
      ['3000', '3500', '4000', '4500', '5000', '5500', '6000', '6500', '7000', '7500', '8000', '8500', '9000', '9500', '10000'],
    ],
    multiIndex: [0, 0],
    josdata:[],
    canSendData: true
  },
  showTopTips(mes) {
    this.setData({
      content: mes,
      $zanui: {
        toptips: {
          show: true
        }
      }
    });

    setTimeout(() => {
      this.setData({
        $zanui: {
          toptips: {
            show: false
          }
        }
      });
    }, this.data.duration);
  },
  getObjectKey(object,values)
    {
      for(var property in object){
        if (object[property] == values){
          return property;
         }
      }
      return -1;
    },
  onLoad: function (options) {
    this.getcitydata();
    this.setData({
      id: options.id
    })
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
    var that = this;
    app.Log_after_fun(function (ret) {
      //获得用户的职位列表
      app.request('/Companyjob/get_c_job', { id: that.data.id}, function (data, ret) {
        console.log(data);
        that.data.multiIndex[0] = that.getObjectKey(that.data.multiArray[0], data.gold1.toString());
        that.data.multiIndex[1] = that.getObjectKey(that.data.multiArray[1], data.gold2.toString());
        that.setData({
          josdata: data,
          multiIndex: that.data.multiIndex
        })
      }, function (data, ret) {
        app.error(ret.msg);
      });
    });
  },
  getcitydata: function () {
    var that = this;
    if (app.globalData.city == [] || app.globalData.city == null || app.globalData.city.length == 0) {
      setTimeout(function () {
        that.getcitydata();
      }, 800)
    } else {
      that.setData({
        city: app.globalData.city
      })
    }
  },
  bindMultiPickerChange: function (e) {
    console.log('picker发送选择改变，携带值为', e.detail.value)
    this.setData({
      multiIndex: e.detail.value
    })
  },
  bindMultiPickerColumnChange: function (e) {
    console.log('修改的列为', e.detail.column, '，值为', e.detail.value);
    var TmpData = this.data.multiIndex;
    TmpData[e.detail.column] = e.detail.value;

    //如果当第一项大于第二项时
    if (TmpData[0] > TmpData[1]) {
      if (e.detail.column == 1) {
        TmpData[1] = TmpData[0]
      }
    }
    this.setData({
      multiIndex: TmpData
    })
  },
  formSubmit: function (e) {
    var that = this;
    var ShowErr_Me = "";
    let Outdata = e.detail.value;

    Outdata['id'] = this.data.josdata.Id;
    Outdata['gold1'] = this.data.multiArray[0][this.data.multiIndex[0]];
    Outdata['gold2'] = this.data.multiArray[1][this.data.multiIndex[1]];
    //console.log(Outdata);

    //这里判断下数据
    if (Outdata.name.length < 2 || Outdata.name.length > 30) {
      ShowErr_Me = "请正确填写职位名称";
    }
    if (Outdata.neednum.length < 1 || Outdata.neednum.length > 5) {
      ShowErr_Me = "正确填写岗位人数";
    }
    if (Outdata.content == null) {
      Outdata.content = "";
    }
    if (ShowErr_Me != "") {
      that.showTopTips(ShowErr_Me);
      console.log(ShowErr_Me);
      return false;
    }
    if (!that.data.canSendData) {
      return;
    }
    that.data.canSendData = false;
    
    console.log(Outdata);
    app.request('/Companyjob/edit_job', Outdata, function (data, ret) {
      console.log(data);
      app.success('职位信息锡膏成功!');
      wx.navigateBack({});
      //wx.redirectTo({
      // url: '/page/zh_Jobs/Myjobs'
     // })
      setTimeout(function () { that.data.canSendData = true; }, 1000)

    }, function (data, ret) {
      app.error(ret.msg);

      setTimeout(function () { that.data.canSendData = true; }, 1000)
    });
  },
})