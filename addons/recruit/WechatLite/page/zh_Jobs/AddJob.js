var app = getApp();
const Toptips = require('../../assets/libs/zanui/toptips/index');
const Toast = require('../../assets/libs/zanui/toast/toast');

Page({

  data: {
    city:[],
    comId:null,
    comname: null,
    multiArray: [
      ['3000', '3500', '4000', '4500', '5000', '5500', '6000', '6500', '7000', '7500', '8000', '8500', '9000', '9500', '10000'],
      ['3000', '3500', '4000', '4500', '5000', '5500', '6000', '6500', '7000', '7500', '8000', '8500', '9000', '9500', '10000'],
    ],
    multiIndex: [0, 0],
    canSendData: true,
    content: 'toptips',
    duration: 3000,
    $zanui: {
      toptips: {
        show: false
      }
    },
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
  onLoad: function (options) {
    this.getcitydata();

    //这里获得公司信息
    this.setData({
      comId: options.com,
      comname: options.name
      
    })
  },
  getcitydata: function(){
    var that= this;
    if (app.globalData.city == [] || app.globalData.city == null || app.globalData.city.length == 0){
      setTimeout(function () {
        that.getcitydata();
      }, 800)
    }else{
      that.setData({
        city: app.globalData.city
      })
    }
  },
  onShow: function () {
  
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
   // console.log(e);
    var that = this;
    var ShowErr_Me = "";
    let Outdata = e.detail.value;

    Outdata['c_id'] = this.data.comId;
    Outdata['gold1'] = this.data.multiArray[0][this.data.multiIndex[0]];
    Outdata['gold2'] = this.data.multiArray[1][this.data.multiIndex[1]];
    console.log(Outdata);
    
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
    app.request('/Companyjob/add_job', Outdata, function (data, ret) {
      console.log(data);
      app.success('职位信息提交成功!');
      wx.navigateBack({});
      //wx.redirectTo({
      //  url: '/page/zh_Jobs/Myjobs'
      //})
      setTimeout(function () { that.data.canSendData = true; }, 1000)

    }, function (data, ret) {
      app.error(ret.msg);

      setTimeout(function () { that.data.canSendData = true; }, 1000)
    });
  },
})