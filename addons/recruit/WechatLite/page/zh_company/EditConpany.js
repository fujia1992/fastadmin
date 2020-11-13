var app = getApp();
const Toptips = require('../../assets/libs/zanui/toptips/index');
const Toast = require('../../assets/libs/zanui/toast/toast');

Page({

  data: {
    comD:[],
    imglist: [],
    upendImgList: [],
    content: 'toptips',
    duration: 3000,
    $zanui: {
      toptips: {
        show: false
      }
    },
    qiye_img: '/assets/img/add.png',
    upend_qiye_img: null,
    imgMaxCount: 8,
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
  onLoad: function (options) {
      //在登陆后，拖取企业数据
      var that = this;
      app.Log_after_fun(function (ret) {
        app.request('/Companyjob/get_my_company', {}, function (data, ret) {
          console.log(data);
          //data.cimage = app.Domain_Public + data.cimage;
          let tmparrpic = [];
          if (data.cimages != "") {
            data.cimages.split(",").forEach(function (item) {
             // that.data.upendImgList.push(item);
              item = app.Domain_Public + item;
              tmparrpic.push(item);
            });
          }
          that.setData({
            comD: data,
            imglist: tmparrpic,
            qiye_img: app.Domain_Public + data.cimage,
            upend_qiye_img: data.cimage,
          })

        }, function (data, ret) {
          app.error(ret.msg);
          if (data == null && ret.msg == "不存在企业") {
            //跳转到
            wx.navigateBack({});
            //wx.redirectTo({
           //   url: '/page/zh_company/MyCompany'
            //})
          }
          console.log(data);
          console.log(ret);
        });
      });
  },

  onShow: function () {
  
  },
  del_qiye_img_fun: function (e) {
    this.setData({
      qiye_img: '/assets/img/add.png',
      upend_qiye_img: null
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
  //点击预览图片
  ylimg: function (e) {
    wx.previewImage({
      current: e.target.dataset.src,
      urls: this.data.imglist // 需要预览的图片http链接列表
    })
  },
  del_img_fun: function (e) {
    console.log('del:', e.currentTarget.dataset.id);
    var Tmpimglist = this.data.imglist;
    Tmpimglist.splice(e.currentTarget.dataset.id, 1);
    this.setData({
      imglist: Tmpimglist
    })
  },
  //点击选择图片
  checkimg: function () {
    self = this
    if (this.data.imglist.length >= this.data.imgMaxCount) {
      wx.showModal({
        title: '图片选择过多',
        content: '您最多上传' + this.data.imgMaxCount + '张图片。',
        showCancel: false
      })
      return;
    }
    var lastlen = this.data.imgMaxCount - this.data.imglist.length;
    wx.chooseImage({
      count: lastlen, // 默认9
      sizeType: ['compressed'], // 可以指定是原图还是压缩图，默认二者都有
      sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
      success: function (res) {
        // 返回选定照片的本地文件路径列表，tempFilePath可以作为img标签的src属性显示图片
        var tempFilePaths = self.data.imglist.concat(res.tempFilePaths);
        self.setData({
          imglist: tempFilePaths
        })
      }
    })
  },
  formSubmit: function (e) {
    //console.log(e);
    var that = this;
    //首先验证表单
    var ShowErr_Me = "";
    let Outdata = e.detail.value;
    //console.log(Outdata);
    if (Outdata.name.length < 2 || Outdata.name.length > 30) {
      ShowErr_Me = "请正确填写企业名称";
    }
    if (Outdata.tel.length < 6 || Outdata.tel.length > 20) {
      ShowErr_Me = "正确填写企业电话";
    }
    if (Outdata.no.length < 10 || Outdata.no.length > 20) {
      ShowErr_Me = "正确填写企业工商注册号";
    }
    if (Outdata.adress.length < 6 || Outdata.adress.length > 100) {
      ShowErr_Me = "正确填写企业地址";
    }

    if (this.data.upend_qiye_img == null) {
      ShowErr_Me = "请上传企业头像照片";
    }
    Outdata.cimage = this.data.upend_qiye_img;

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
    //这里上传 企业其他展示图片
    var ai = this.data.imglist;
    that.data.upendImgList = [];
    if (ai.length > 0) {
      let sdata = [];
      if (app.globalData.userInfo) {
        sdata['user_id'] = app.globalData.userInfo.id;
        sdata['token'] = app.globalData.userInfo.token;
      }
      Outdata['cimages'] = '';
      var int_ii = 0;
      let NoupNum = 0;
      for (var i = 0; i < ai.length; i++) {
        Toast.loading({
          message: '第' + (i + 1) + '张图片上传中',
          selector: '#zan-toast-test',
          timeout: 0
        });
        //这里判断这幅图片是否已经在云端
        if (that.data.imglist[i].indexOf(app.Domain_Public) != -1){
          NoupNum++;
          int_ii++;
          var sty = that.data.imglist[i].replace(app.Domain_Public, "");
          that.data.upendImgList.push(sty);
          if (NoupNum == ai.length){
            Toast.clear();
            app.success('图片都已上传!');
            that.data.upendImgList.forEach(function (item) {
              Outdata['cimages'] += item + ',';
            });
            Outdata['cimages'] = Outdata['cimages'].slice(0, Outdata['cimages'].length - 1);
            that.UpdataAllData(Outdata);
          }
        }else{
          wx.uploadFile({
            url: app.Domain_Public + "addons/recruit/Companyjob/upload",
            filePath: that.data.imglist[i],
            name: 'files',
            formData: sdata,
            complete: function () {
              Toast.clear();
              console.log('上传完成');
              int_ii++;
              if (int_ii == ai.length) {
                console.log('上传图片结束');
                app.success('所有图片上传完毕!');
                that.data.upendImgList.forEach(function (item) {
                  Outdata['cimages'] += item + ',';
                });
                Outdata['cimages'] = Outdata['cimages'].slice(0, Outdata['cimages'].length - 1);
                that.UpdataAllData(Outdata);
              }
            },
            success: function (res) {
              console.log(res);
              var b_s_ad = JSON.parse(res.data);
              //that.data.upendImgList[int_ii] = b_s_ad.data.url;
              that.data.upendImgList.push(b_s_ad.data.url);
              if (int_ii == ai.length) {
                console.log('图片上传成功');
              }
            },
            fail: function (res) {
              console.log('上传失败');
              console.log(res);
            },
          })
        }
        
      }

    } else {
      //开始提交
      this.UpdataAllData(Outdata);
    }
  },
  UpdataAllData: function (Outdata) {
    var that = this;
    //that.data.canSendData = true;
    console.log(Outdata);
    //return;
    app.request('/Companyjob/edit_com', Outdata, function (data, ret) {
      console.log(data);
      app.success('企业信息修改成功!');
      wx.navigateBack({});
      //wx.redirectTo({
      //  url: '/page/zh_company/MyCompany'
      //})
      setTimeout(function () { that.data.canSendData = true; }, 1000)

    }, function (data, ret) {
      app.error(ret.msg);

      setTimeout(function () { that.data.canSendData = true; }, 1000)
    });
  },
})