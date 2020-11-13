var Towxml = require('/assets/libs/towxml/main.js');
App({
  //请不要修改 addons/recruit这部分,只允许修改域名部分
  apiUrl: 'http://zhao.addnos.com/addons/recruit/',
  Domain_Public:'http://zhao.addnos.com/',
  Isdebug:true,
  si: 0,
  //小程序启动
  onLaunch: function () {
    if (this.Isdebug){
      this.apiUrl = 'http://zhao.addnos.com/addons/recruit/';
      this.QrPngUrl = 'https://lite.her-family.com/addons/cms/wxapp.';
      this.Domain_Public = 'http://zhao.addnos.com/';
    }
    var that = this;
    that.request('/common/init', {}, function (data, ret) {
      console.log(data);
      that.globalData.config = data.config;
      that.globalData.city = data.city;

      //这里获得位置
      that.GetLoc();      
      //如果需要一进入小程序就要求授权登录,可在这里发起调用
      that.check(function (ret) { });
    }, function (data, ret) {
      that.error(ret.msg);
    });
  },
  //获得地址
  GetLoc: function (){
    var that = this;
    wx.getLocation({
      type: 'wgs84',
      success: function (lb) {
        console.log(lb);
        var latitude = lb.latitude
        var longitude = lb.longitude
        var speed = lb.speed
        var accuracy = lb.accuracy

        console.log("地理位置")
        wx.request({ // ②百度地图API，将微信获得的经纬度传给百度，获得城市等信息
          url: 'https://api.map.baidu.com/geocoder/v2/?ak=dHVrlsy6da3XV9Hm7tFySXie2VMwlH4I&location=' + lb.latitude + ',' + lb.longitude + '&output=json&coordtype=wgs84ll',
          data: {},
          header: {
            'Content-Type': 'application/json'
          },
          success: function (res) {
            console.log(res.data.result);
            console.log(res.data.result.addressComponent.province + '/' + res.data.result.addressComponent.city );

            that.globalData.locData = res.data.result.addressComponent;
            that.Makeloc(res.data.result.addressComponent);
            //that.globalData.locData['ShowCity'] = res.data.result.addressComponent.province + '/' + res.data.result.addressComponent.city;
          },
          fail: function () {
            that.Makeloc('no');
          },
          complete: function () {}
        })
      },
      fail: function (res) {
        console.log(res);
        that.Makeloc('no');
      }
    })
  },
  //制造loc数据
  Makeloc: function (L_data) {
    var that = this;
    if (L_data!='no'){
      that.globalData.locData['ShowCity'] = L_data.province + '/' + L_data.city;
      let tmp_index = 0
      that.globalData.city.forEach(function (item, index, arr) {
        //这里根据前面获得的数据 来抓取 当下在的城市
        if (that.globalData.locData['ShowCity'] == item.city) {
          tmp_index = index;
          that.globalData.locData['ShowCity_id'] = item.id
        }
      });
      if (tmp_index == 0) {
        that.globalData.locData['ShowCity'] = that.globalData.city[tmp_index].name
        that.globalData.locData['ShowCity_id'] = that.globalData.city[tmp_index].id
      }
    }else{
      that.globalData.locData['ShowCity'] = that.globalData.city[0].name
      that.globalData.locData['ShowCity_id'] = that.globalData.city[0].id
    }

  },
  //投票
  vote: function (event, cb) {
    var that = this;
    var id = event.currentTarget.dataset.id;
    var type = event.currentTarget.dataset.type;
    var vote = wx.getStorageSync("vote") || [];
    if (vote.indexOf(id)>-1){
      that.info("你已经发表过意见了,请勿重复操作");
      return;
    }
    vote.push(id);
    wx.setStorageSync("vote", vote);
    this.request('/archives/vote', { id: id, type: type }, function (data, ret) {
      typeof cb == "function" && cb(data);
    }, function (data, ret) {
      that.error(ret.msg);
    });
  },
  //判断是否登录
  check: function (cb) {
    var that = this;
    if (this.globalData.userInfo) {
      typeof cb == "function" && cb(this.globalData.userInfo);
    } else {
      this.login(cb);
    }
  },
  //登录
  login: function (cb) {
    var that = this;
    var token = wx.getStorageSync('token') || '';
    //调用登录接口
    wx.login({
      success: function (res) {
        if (res.code) {
          //发起网络请求
              wx.request({
                url: that.apiUrl + 'user/login_hawk',
                //url: that.apiUrl + 'user/login_debug',
                data: {
                  code: res.code,
                  token: token
                },
                method: 'post',
                header: {
                  "Content-Type": "application/x-www-form-urlencoded",
                },
                success: function (lres) {
                  console.log(lres);
                  var response = lres.data
                  if (response.code == 1) {
                    that.globalData.userInfo = response.data.userInfo;
                    wx.setStorageSync('token', response.data.userInfo.token);
                    typeof cb == "function" && cb(that.globalData.userInfo);
                  } else {
                    wx.setStorageSync('token', '');
                    console.log("用户登录失败");
                    if (response.data.errcode == 40125) {
                      console.log("前后台设置的appid必须一致，且后台的appid和appsecret也必须一致")
                    }
                    //that.showLoginModal(cb);
                  }
                }
              });
        } else {
          console.log("用户失败")
          //that.showLoginModal(cb);
        }
      }
    });
  },
  //显示登录或授权提示
  showLoginModal: function (cb) {
    var that = this;
    if (!that.globalData.userInfo) {
      //获取用户信息
      wx.getSetting({
        success: function (sres) {
          if (sres.authSetting['scope.userInfo']) {
            wx.showModal({
              title: '温馨提示',
              content: '当前无法获取到你的个人信息，部分操作可能受到限制',
              confirmText: "重新登录",
              cancelText: "暂不登录",
              success: function (res) {
                if (res.confirm) {
                  that.login(cb);
                } else {
                  console.log('用户暂不登录');
                }
              }
            });
          } else {
            wx.showModal({
              title: '温馨提示',
              content: '当前无法获取到你的个人信息，部分操作可能受到限制',
              confirmText: "去授权",
              cancelText: "暂不授权",
              success: function (res) {
                if (res.confirm) {
                  wx.navigateTo({
                    url: '/page/my/setting?type=getuserinfo',
                  });
                  return false;
                  wx.openSetting({
                    success: function (sres) {
                      that.check(cb);
                    }
                  });
                } else {
                  console.log('用户暂不授权');
                }
              }
            });
          }
        }
      });
    } else {
      typeof cb == "function" && cb(that.globalData.userInfo);
    }
  },
  //发起网络请求
  request: function (url, data, success, error) {
    var that = this;
    if (typeof data == 'function') {
      success = data;
      error = success;
      data = {};
    }
    if (this.globalData.userInfo) {
      data['user_id'] = this.globalData.userInfo.id;
      data['token'] = this.globalData.userInfo.token;
    }
    //移除最前的/
    while (url.charAt(0) === '/')
      url = url.slice(1);
    this.loading(true);
    console.log(data);
    wx.request({
      url: this.apiUrl + url,
      data: data,
      method: 'post',
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      success: function (res) {
        that.loading(false);
        var code, msg, json;
        if (res.statusCode === 200) {
          json = res.data;
          if (json.code === 1) {
            typeof success === 'function' && success(json.data, json);
          } else {
            typeof error === 'function' && error(json.data, json);
          }
        } else {
          json = typeof res.data === 'object' ? res.data : { code: 0, msg: '发生一个未知错误', data: null };
          typeof error === 'function' && error(json.data, json);
        }
      },
      fail: function (res) {
        that.loading(false);
        console.log("fail:", res);
        typeof error === 'function' && error(null, { code: 0, msg: '', data: null });
      }
    });
  },
  //构造CDN地址
  cdnurl:function(url){
    return url.toString().match(/^https?:\/\/(.*)/i) ? url : this.globalData.config.upload.cdnurl + url;
  },
  //文本提示
  info: function (msg, cb) {
    wx.showToast({
      title: msg,
      icon: 'none',
      duration: 2000,
      complete: function () {
        typeof cb == "function" && cb();
      }
    });
  },
  //成功提示
  success: function (msg, cb) {
    wx.showToast({
      title: msg,
      icon: 'success',
      image: '/assets/images/ok.png',
      duration: 2000,
      complete: function () {
        typeof cb == "function" && cb();
      }
    });
  },
  //错误提示
  error: function (msg, cb) {
    wx.showToast({
      title: msg,
      image: '/assets/images/error.png',
      duration: 2000,
      complete: function () {
        typeof cb == "function" && cb();
      }
    });
  },
  //警告提示
  warning: function (msg, cb) {
    wx.showToast({
      title: msg,
      image: '/assets/images/warning.png',
      duration: 2000,
      complete: function () {
        typeof cb == "function" && cb();
      }
    });
  },
  Log_after_fun: function (cb){
    var that = this;
    if (this.globalData.userInfo) {
      typeof cb == "function" && cb(this.globalData.userInfo);
    } else {
     //这里循环等待出数据
      setTimeout(function () { that.Log_after_fun(cb); }, 500);
    }
  },
  //Loading
  loading: function (msg) {
    if (typeof msg == 'boolean') {
      if (!msg) {
        if (!this.si) {
          return;
        }
        clearTimeout(this.si);
        wx.hideLoading({});
        return;
      }
    }
    msg = typeof msg == 'undefined' || typeof msg == 'boolean' ? '加载中' : msg;
    this.globalData.loading = true;
    if (this.si) {
      return;
    }
    this.si = setTimeout(function () {
      wx.showLoading({
        title: msg
      });
    }, 300);

  },
  towxml: new Towxml(),
  //全局信息
  globalData: {
    userInfo: null,
    config: null,
    indexTabList: [],
    newsTabList: [],
    productTabList: [],
    city:[],
    locData:[]
  }
})
