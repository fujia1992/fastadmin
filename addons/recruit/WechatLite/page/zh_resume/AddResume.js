var app = getApp();
const Toptips = require('../../assets/libs/zanui/toptips/index');
const Toast = require('../../assets/libs/zanui/toast/toast');

Page({


  data: {
    city: [], 
    content: 'toptips',
    duration: 3000,
    $zanui: {
      toptips: {
        show: false
      }
    },
    multiArray: [
      ['3000', '3500', '4000', '4500', '5000', '5500', '6000', '6500', '7000', '7500', '8000', '8500', '9000', '9500', '10000'],
      ['3000', '3500', '4000', '4500', '5000', '5500', '6000', '6500', '7000', '7500', '8000', '8500', '9000', '9500', '10000'],
    ],
    multiIndex: [0, 0],
    canSendData: true,
    birthday:'1980-01-01',
    region: ['安徽省', '宿州市', ''],
    qiye_img: '/assets/img/add.png',
    upend_qiye_img: null,
  },
  showTopTips(mes) {
    this.setData({
      content:mes,
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
  onLoad: function (options) {
    this.getcitydata();
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
  bindRegionChange: function (e) {
    console.log('picker发送选择改变，携带值为', e.detail.value)
    this.setData({
      region: e.detail.value
    })
  },
  bindDateChange: function (e) {
    console.log('picker发送选择改变，携带值为', e.detail.value)
    this.setData({
      birthday: e.detail.value
    })
  },
  qiye_img: function (e) {
    self = this
    if (e.target.dataset.src == "/assets/img/add.png") {
      wx.chooseImage({
        count: 1, // 默认9
        sizeType: ['compressed'], // 可以指定是原图还是压缩图，默认二者都有
        sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
        success: function (res) {

          // 返回选定照片的本地文件路径列表，tempFilePath可以作为img标签的src属性显示图片
          var tempFilePaths = res.tempFilePaths[0]
          if (res.tempFiles[0].size >= 2 * 1024 * 1024) {
            wx.showModal({
              title: '图片选择失败',
              content: '图片过大，请选择小一些的照片[小于2MB]',
              showCancel: false
            })
            return;
          }

          Toast.loading({
            message: '上传中',
            selector: '#zan-toast-test',
            timeout: 0
          });
          let sdata = [];
          if (app.globalData.userInfo) {
            sdata['user_id'] = app.globalData.userInfo.id;
            sdata['token'] = app.globalData.userInfo.token;
          }
          wx.uploadFile({
            url: app.Domain_Public + "addons/recruit/Companyjob/upload",
            filePath: tempFilePaths,
            name: 'files',
            formData: sdata,
            complete: function () {
              console.log('上传完成');
              Toast.clear();
            },
            success: function (res) {
              console.log(res);
              var b_s_ad = JSON.parse(res.data);
              //console.log(b_s_ad);
              self.setData({
                qiye_img: tempFilePaths,
                upend_qiye_img: b_s_ad.data.url
              })
            },
            fail: function (res) {
              console.log('上传失败');
              console.log(res);
            },
          })
        }
      })
    } else {
      var urls = [];
      urls[0] = e.target.dataset.src;
      wx.previewImage({
        current: e.target.dataset.src,
        urls: urls
      })
    }
  },
  del_qiye_img_fun: function (e) {
    this.setData({
      qiye_img: '/assets/img/add.png',
      upend_qiye_img: null
    })
  },

  formSubmit: function (e) {
    
    var that = this;
    //首先验证表单
    var ShowErr_Me = "";
    let Outdata = e.detail.value;
    console.log(Outdata);
    if (Outdata.name.length < 2 || Outdata.name.length > 20) {
      ShowErr_Me = "请正确填写您的姓名";
    }
    if (!(/^1[34578]\d{9}$/.test(Outdata.tel))) {
      ShowErr_Me = "正确输入正确的手机号";
    } 

    if (this.data.upend_qiye_img == null) {
      ShowErr_Me = "请上传个人照片";
    }
    Outdata.c_avatar = this.data.upend_qiye_img;

    if (Outdata.content == null) {
      Outdata.content = "";
    }
    Outdata['gold1'] = this.data.multiArray[0][this.data.multiIndex[0]];
    Outdata['gold2'] = this.data.multiArray[1][this.data.multiIndex[1]];

    Outdata['native_place'] = this.data.region[0] + '/' + this.data.region[1];

    if (ShowErr_Me != "") {
      that.showTopTips(ShowErr_Me);
      console.log(ShowErr_Me);
      return false;
    }
    if (!that.data.canSendData) {
      return;
    }
    that.data.canSendData = false;
    app.request('/Resume/add_Resume', Outdata, function (data, ret) {
      console.log(data);
      app.success('简历提交成功!');
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
})