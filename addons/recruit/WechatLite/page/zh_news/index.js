var app = getApp();
const util = require('../../utils/util');
Page({

  data: {
    userInfo: null,
    archivesInfo: { article: {} },
    Jobfair:null,
    loading: false,
    nodata: false,
    nomore: true,
    canSendData: true,
    form: { quotepid: 0, message: '', focus: false },

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
    var that = this;
    //这里判断是二维码的值 还是 传递的值
    let tmpid = 0;
    if (options.id != undefined) {
      tmpid = options.id;
    }
    if (options.scene != undefined) {
      tmpid = options.scene;
    }
    app.Log_after_fun(function (ret) {
      that.setData({ userInfo: app.globalData.userInfo });
      app.request('/archives/BannerDetail', { id: tmpid }, function (data, ret) {
        var content = data.archivesInfo.content;
        //console.log(content);
        data.archivesInfo.article = app.towxml.toJson(content.replace(/: /g, ':'), 'html');
        //console.log(data.archivesInfo.article);
        that.traverse(data.archivesInfo.article);

        //格式化时间
        data.Jobfair.forEach(function (value, index, array) {
          var updatetime = util.getTime(value['updatetime']);
          data.Jobfair[index]['showtime'] = updatetime;
        })

        that.setData({ 
            archivesInfo: data.archivesInfo,
            Jobfair: data.Jobfair,

          QrcodeImg: (app.Isdebug ? app.QrPngUrl : app.apiUrl) + "Usewechat/get_News_QrPng?id=" + tmpid,
          });

      }, function (data, ret) {
        app.error(ret.msg);
      });
    });
  },
  traverse: function (obj) {  
      for(var a in obj) {
        if (typeof (obj[a]) == "object") {
          this.traverse(obj[a]); //递归遍历  
        } else {
          if (a == 'src' && obj[a].indexOf("/uploads/")==0 ){
            //console.log(a + "=" + obj[a]); 
            obj[a] = app.Domain_Public + obj[a];
          }
        }
      }
  },
  onReachBottom: function () {
  },
  share: function () {
    wx.showShareMenu({});
  },
  onShareAppMessage: function () {
    return {
      title: this.data.archivesInfo.title,
      desc: this.data.archivesInfo.updatetime,
      path: 'page/zh_news/index?id=' + this.data.archivesInfo.id
    }
  },
  getPhoneNumber: function (e) {
    console.log(e.detail.errMsg);
    if (e.detail.iv == undefined){
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
    Outdata.block_id = that.data.archivesInfo.id;
    Outdata.block_title = that.data.archivesInfo.title;

    console.log(Outdata);
    if (Outdata.tname.length < 2 || Outdata.tname.length > 20) {
      ShowErr_Me = "请正确填写您的姓名";
    }
    if (!(/^1[34578]\d{9}$/.test(Outdata.ttel))) {
      ShowErr_Me = "正确输入正确的手机号";
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
    app.request('/My/add_baoming', Outdata, function (data, ret) {
      console.log(data);
      app.success('报名提交成功!');
      /*

      */
      setTimeout(function () { 
        wx.redirectTo({
          url: '/page/zh_news/index?id=' + that.data.archivesInfo.id
        });
        that.data.canSendData = true; 
        }, 1500)

    }, function (data, ret) {
      app.error(ret.msg);

      setTimeout(function () { that.data.canSendData = true; }, 1000)
    });


    
  },
})