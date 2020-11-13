var app = getApp();
const Toptips = require('../../assets/libs/zanui/toptips/index');
const Toast = require('../../assets/libs/zanui/toast/toast');


Page({

  data: {
    content: 'toptips',
    duration: 3000,
    canSendData: true,
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

  },

  onShow: function () {

  },
  getPhoneNumber: function (e) {
    console.log(e.detail.errMsg);
    if (e.detail.iv == undefined) {
      return;
    }
    console.log(e.detail.iv);
    console.log(e.detail.encryptedData);

    let dataTMp = [];
    dataTMp.encryptedData = e.detail.encryptedData;
    dataTMp.iv = e.detail.iv;
    var that = this;
    app.request('/Usewechat/get_PhoneNum', dataTMp, function (data, ret) {
      console.log(data);
      app.success('成功获取手机号');
      //data = data.replace(/(^\s*)|(\s*$)/g, "");
      that.setData({
        phoneNumber: data.phoneNumber
      });
    }, function (data, ret) {
      //app.error(ret.msg);
    });
  },
  formSubmit: function (e) {
    var that = this;
    //首先验证表单
    var ShowErr_Me = "";
    let Outdata = e.detail.value;
    //console.log(Outdata);
    if (Outdata.name.length < 2 || Outdata.name.length > 20) {
      ShowErr_Me = "请正确填写您的姓名";
    }
    if (!(/^1[34578]\d{9}$/.test(Outdata.tel))) {
      ShowErr_Me = "正确输入正确的手机号";
    } 
    if (!(/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X|x)$/.test(Outdata.sfzno))) {
      ShowErr_Me = "正确输入正确身份证号";
    } 
    if (Outdata.content == null) {
      Outdata.content = "";
    }
    if (Outdata.village.length < 2 || Outdata.village.length > 20) {
      ShowErr_Me = "请正确填写村名";
    }

    if (ShowErr_Me != "") {
      that.showTopTips(ShowErr_Me);
      //console.log(ShowErr_Me);
      return false;
    }

    if (!that.data.canSendData) {
      return;
    }
    that.data.canSendData = false;

    app.request('/Resume/add_workforce', Outdata, function (data, ret) {
      console.log(data);
      app.success('资料提交成功!');
      wx.navigateBack({});
      setTimeout(function () { that.data.canSendData = true; }, 1000)
    }, function (data, ret) {
      app.error(ret.msg);
      setTimeout(function () { that.data.canSendData = true; }, 1000)
    });
    //console.log(Outdata);
  }
})