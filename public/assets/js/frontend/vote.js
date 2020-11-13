define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        post: function () {
            $.validator.config({
                rules: {
                    diyname: function (element) {
                        if (element.value.toString().match(/^\d+$/)) {
                            return __('Can not be digital');
                        }
                        return $.ajax({
                            url: 'vote/check_element_available',
                            type: 'POST',
                            data: {id: $(element).data("id"), name: element.name, value: element.value},
                            dataType: 'json'
                        });
                    }
                }
            });

            require(['cityselect', 'citydata'], function (undefined, cityData) {
                var mcs = $('#mcs').citySelect({
                    dataJson: cityData,         //数据源
                    convert: false,
                    multiSelect: true,          //多选
                    multiMaximum: 30,            //可以选择的个数
                    search: true,              //关闭搜索
                    hotCity: ['北京市', '上海市', '广州市', '深圳市', '南京市', '杭州市', '天津市', '重庆市', '成都市', '青岛市', '苏州市', '无锡市', '常州市', '温州市', '武汉市', '长沙市', '石家庄市', '南昌市', '三亚市', '合肥市'],
                    onInit: function () {       //初始化回调
                    },
                    onForbid: function () {     //禁止后点击的回调
                    },
                    onTabsAfter: function (target) {    //切换tab回调
                    },
                    onCallerAfter: function (target, values) {  //选择后回调
                        $("#c-limitarea").val(values.id.join(","));
                    }
                });
                mcs.setCityVal($("#c-limitarea").val());
            });
            //不可见的元素不验证
            $("form#add-form").data("validator-options", {ignore: ':hidden'});
            $(document).on("change", "#c-type", function () {
                $(".tf").addClass("hidden");
                $(".tf.tf-" + $(this).val()).removeClass("hidden");

            });

            //切换显示隐藏变量字典列表
            $(document).on("change", "select[name$='[type]']", function (e) {
                $(this).closest(".iteminner").find(".multiple textarea").prop("readonly", ['select', 'selects', 'checkbox', 'radio'].indexOf($(this).val()) > -1 ? false : true);
            });
            $(document).on("click", ".btn-delone", function () {
                $(this).closest(".iteminner").remove();
            });
            $(document).on("click", ".btn-append", function () {
                var index = parseInt($(this).data("index"));
                var count = $(".iteminner").size();
                $(Template('itemtpl', {i: index, typelist: typelist, content: 'key1|value1\nkey2|value2', count: count})).insertBefore($("#itemcontent > .col-xs-6:last-child"));
                $(this).data("index", index + 1);
                return false;
            });
            $.each(contentlist, function (i, j) {
                $(Template('itemtpl', $.extend(j, {i: i, typelist: typelist, count: i}))).insertBefore($("#itemcontent > .col-xs-6:last-child"));
            });
            $(".btn-append").data("index", contentlist.length);
            //不可见的元素不验证
            $("form[role='form']").data("validator-options", {
                invalid: function (form, errors) {
                    $.each(errors, function (i, j) {
                        Toastr.error(j);
                    });
                },
                target: '#errtips',
                ignore: 'textarea[readonly]'
            });
            // 拖拽排序
            require(['sortable'], function (Sortable) {
                //拖动排序
                new Sortable($("#itemcontent")[0], {draggable: 'div.iteminner', handle: 'a.btn-dragsort', animation: 500});
            });

            Form.api.bindevent($("form[role=form]"), function (data, ret) {
                Toastr.success(ret.msg);
                setTimeout(function () {
                    location.href = ret.url;
                }, 2000);
                return false;
            });
            $("#c-type").trigger("change");
        }
    };
    return Controller;
});