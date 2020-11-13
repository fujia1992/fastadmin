var isMobile, number_format, refresh_data;
$(function () {
    // 是否移动端
    isMobile = !!("ontouchstart" in window);

    // 格式化数字
    number_format = function (text) {
        return text.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
    };

    // 刷新数字
    refresh_data = function (elem) {
        var elem = typeof elem != "undefined" ? $(elem) : $('.number-count');
        // 动画数字
        elem.each(function () {
            var $this = $(this);
            $({from: 0, to: $this.data("to"), elem: $this}).animate({from: $this.data("to")}, {
                duration: 1000,
                easing: 'swing',
                step: function () {
                    $this.text(number_format(Math.ceil(this.from)));
                },
                complete: function () {
                    if (number_format(this.to) != this.elem.text()) {
                        this.elem.text(number_format(this.to));
                    }
                }
            });
        });
    };

    // 统计信息
    if ($("#statistics").size() > 0) {
        var flashed = false;
        $(window).on("scroll", function () {
            if ($(window).scrollTop() > $("#statistics").position().top - $(window).height() && !flashed) {
                flashed = true;
                refresh_data();
            } else if ($(window).scrollTop() == 0) {
                flashed = false;
            }
        });
    }

    // 回到顶部
    $('#back-to-top').on('click', function (e) {
        e.preventDefault();
        $('html,body').animate({
            scrollTop: 0
        }, 700);
    });

    // 开始投票
    $(document).on("click", ".btn-startvote", function () {
        $('html,body').animate({
            scrollTop: $("#players").offset().top - 50
        }, 700);
    });

    // 点击投票
    $(document).on("click", ".btn-vote", function () {
        var that = this;
        VOTE.api.ajax({
            url: "/addons/vote/index/vote",
            data: {player_id: $(that).data("id")}
        }, function (data, ret) {
            var elem = $(that).closest(".player-item").find("[data-to]");
            elem.data("to", parseInt(elem.data("to")) + 1);
            refresh_data(elem);
            //添加禁用
            if (typeof data.disabled != 'undefined' && data.disabled) {
                $(that).addClass("disabled");
            }
        });
    });

    // 发表评论
    if ($("#postform").size() > 0) {
        VOTE.api.form("#postform", function (data, ret) {
            VOTE.api.msg(ret.msg, function () {
                location.reload();
                return false;
            });
            return false;
        });
    }

    //如果是PC则移除navbar的dropdown点击事件
    if (!/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|Mobi/i.test(navigator.userAgent)) {
        $("#navbar-collapse [data-toggle='dropdown']").removeAttr("data-toggle");
    } else {
        $(".navbar-nav ul li:not(.dropdown-submenu):not(.dropdown) a").removeAttr("data-toggle");
    }

    //分享参数配置
    var shareConfig = {
        title: $("meta[property='og:title']").attr("content") || document.title,
        description: $("meta[property='og:description']").attr("content") || "",
        url: $("meta[property='og:url']").attr("content") || location.href,
        image: $("meta[property='og:image']").attr("content") || ""
    };

    // 点击分享
    $(document).on("click", ".btn-share", function () {
        var that = this;
        var type = $(that).data("type");
        if (typeof wx != 'undefined') {
            layer.open({
                type: 1,
                title: false,
                closeBtn: 0,
                shade: 0.75,
                offset: 'rt',
                skin: 'layui-layer-nobg',
                shadeClose: true,
                content: template("wxsharetpl", shareConfig)
            });
        } else {
            layer.open({
                type: 1,
                area: isMobile ? 'auto' : ["450px", "380px"],
                zIndex: 1031,
                title: '分享', //不显示标题
                btn: ["关闭"],
                btnAlign: "c",
                content: template("sharetpl", shareConfig)
            });
        }
    });

    // 复制到剪贴板
    var clipboard = new ClipboardJS('.btn-copylink');
    clipboard.on('success', function (e) {
        layer.msg("链接已复制到剪贴板!");
        e.clearSelection();
    });

    // 搜索按钮
    $(document).on("click", "#searchbtn", function () {
        $(this).closest("form").trigger("submit");
    });

    // 搜索表单
    $(document).on("submit", "#searchform", function () {
        if (parseInt($(this).data("pagesize")) > 0) {
            return true;
        }
        $(".player-item").show();
        var q = $("#searchinput").val();
        if (q != '') {
            $(".player-item:not([data-" + (isNaN(q) ? "nickname" : "id") + "*='" + q + "'])").hide();
        }
        return false;
    });

    //如果是微信内
    if (typeof wx != 'undefined') {
        shareConfig.url = location.href;
        VOTE.api.ajax({
                url: "/addons/vote/index/share",
                data: {url: shareConfig.url}
            }, function (data, ret) {
                try {
                    wx.config({
                        appId: data.appId,
                        timestamp: data.timestamp,
                        nonceStr: data.nonceStr,
                        signature: data.signature,
                        jsApiList: [
                            "onMenuShareTimeline", //分享给好友
                            "onMenuShareAppMessage", //分享到朋友圈
                            "onMenuShareQQ", //分享到QQ
                            "onMenuShareWeibo" //分享到微博
                        ]
                    });
                    var shareData = {
                        title: shareConfig.title,
                        desc: shareConfig.description,
                        link: shareConfig.url,
                        imgUrl: shareConfig.image,
                        success: function () {
                            layer.closeAll();
                        },
                        cancel: function () {
                            layer.closeAll();
                        }
                    };
                    wx.ready(function () {
                        wx.onMenuShareTimeline(shareData);
                        wx.onMenuShareAppMessage(shareData);
                        wx.onMenuShareQQ(shareData);
                        wx.onMenuShareWeibo(shareData);
                    });

                } catch (e) {

                }
                return false;
            }
        );
    }

    if (!isMobile) {
        $('body').tooltip({selector: '[data-toggle="tooltip"]'});
    }
});
