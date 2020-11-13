define([], function () {
    require.config({
    paths: {
        'jquery-colorpicker': '../addons/cms/js/jquery.colorpicker.min',
        'jquery-autocomplete': '../addons/cms/js/jquery.autocomplete',
        'jquery-tagsinput': '../addons/cms/js/jquery.tagsinput',
        'clipboard': '../addons/cms/js/clipboard.min',
    },
    shim: {
        'jquery-colorpicker': {
            deps: ['jquery'],
            exports: '$.fn.extend'
        },
        'jquery-autocomplete': {
            deps: ['jquery'],
            exports: '$.fn.extend'
        },
        'jquery-tagsinput': {
            deps: ['jquery', 'jquery-autocomplete', 'css!../addons/cms/css/jquery.tagsinput.min.css'],
            exports: '$.fn.extend'
        }
    }
});

require.config({
    paths: {
        'async': '../addons/example/js/async',
        'BMap': ['//api.map.baidu.com/api?v=2.0&ak=mXijumfojHnAaN2VxpBGoqHM'],
    },
    shim: {
        'BMap': {
            deps: ['jquery'],
            exports: 'BMap'
        }
    }
});

require.config({
    paths: {
        'geetest': '../addons/geetest/js/geetest.min'
    }
});

require(['geetest'], function (Geet) {
    var geetInit = false;
    window.renderGeetest = function () {
        $("input[name='captcha']:visible").each(function () {
            var obj = $(this);
            var form = obj.closest('form');
            obj.parent()
                .removeClass('input-group')
                .html('<div class="embed-captcha"><input type="hidden" name="captcha" class="form-control" data-msg-required="请完成验证码验证" data-rule="required" /> </div> <p class="wait show" style="min-height:44px;line-height:44px;">正在加载验证码...</p>');

            Fast.api.ajax("/addons/geetest/index/start", function (data) {
                // 参数1：配置参数
                // 参数2：回调，回调的第一个参数验证码对象，之后可以使用它做appendTo之类的事件
                initGeetest({
                    gt: data.gt,
                    https: true,
                    challenge: data.challenge,
                    new_captcha: data.new_captcha,
                    product: Config.geetest.product, // 产品形式，包括：float，embed，popup。注意只对PC版验证码有效
                    width: '100%',
                    offline: !data.success // 表示用户后台检测极验服务器是否宕机，一般不需要关注
                }, function (captchaObj) {
                    // 将验证码加到id为captcha的元素里，同时会有三个input的值：geetest_challenge, geetest_validate, geetest_seccode
                    geetInit = captchaObj;
                    captchaObj.appendTo($(".embed-captcha", form));
                    captchaObj.onReady(function () {
                        $(".wait", form).remove();
                    });
                    captchaObj.onSuccess(function () {
                        var result = captchaObj.getValidate();
                        if (result) {
                            $('input[name="captcha"]', form).val('ok');
                        }
                    });
                    captchaObj.onError(function () {
                        geetInit.reset();
                    });
                });
                // 监听表单错误事件
                form.on("error.form", function (e, data) {
                    geetInit.reset();
                });
                return false;
            });
        });
    };
    renderGeetest();
});

require.config({
    paths: {
        'slider': '../addons/recruit/js/bootstrap-slider.min'
    },
    shim:{
        'slider': ['css!../addons/recruit/css/bootstrap-slider.min.css'],
    }
});
require(['form', 'upload'], function (Form, Upload) {
    var _bindevent = Form.events.bindevent;
    Form.events.bindevent = function (form) {
        _bindevent.apply(this, [form]);
        try {
            if ($(".slider", form).size() > 0) {
                    require(['slider'], function () {
                     //   console.log($(document).find(".slider-horizontal").css("margin-left",'%5'));
                    //$(this).slider();
                    /*
                        $(this).slider({  
                            formatter: function (value) {  
                                return 'Current value: ' + value;  
                            }  
                        }).on('slide', function (slideEvt) {  
                            console.info(slideEvt);  
                        }).on('change', function (e) {  
                            console.info(e.value.oldValue + '--' + e.value.newValue);  
                        });  
                    */
                    });
                }
        } catch (e) {

        }

    };
});

require.config({
    paths: {
        'voicenotice': '../addons/voicenotice/js/voicenotice'
    }
});

if (window.Config.actionname == "index" && window.Config.controllername == "index") {
    require(['voicenotice'], function f(voicenotice) {
        voicenotice.start();
    })
}
require.config({
    paths: {
        'cityselect': '../addons/vote/js/cityselect',
        'citydata': '../addons/vote/js/citydata',
    },
    shim: {
        'citydata': {
            exports: 'cityData'
        },
        'cityselect': {
            deps: ['jquery', 'css!../addons/vote/css/cityselect.css'],
            exports: '$.fn.extend'
        },
    }
});
});