define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'vote/fields/index/subject_id/' + Config.subject_id + '/' + location.search,
                    add_url: 'vote/fields/add/subject_id/' + Config.subject_id,
                    edit_url: 'vote/fields/edit/subject_id/' + Config.subject_id,
                    del_url: 'vote/fields/del/subject_id/' + Config.subject_id,
                    multi_url: 'vote/fields/multi/subject_id/' + Config.subject_id,
                    table: 'vote_fields',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'weigh',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', sortable: true, title: __('Id')},
                        {field: 'subject_id', visible: false, operate: false, title: __('Subject_id')},
                        {
                            field: 'name', title: __('Name')
                        },
                        {
                            field: 'type', title: __('Type')
                        },
                        {
                            field: 'title', title: __('Title')
                        },
                        {field: 'weigh', title: __('Weigh'), visible: false},
                        {field: 'createtime', title: __('Createtime'), visible: false, operate: 'RANGE', addclass: 'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), visible: false, operate: 'RANGE', addclass: 'datetimerange', formatter: Table.api.formatter.datetime},
                        {
                            field: 'status', title: __('Status'), formatter: Table.api.formatter.status
                        },
                        {
                            field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                $.validator.config({
                    rules: {
                        diyname: function (element) {
                            if (element.value.toString().match(/^\d+$/)) {
                                return __('Can not be digital');
                            }
                            return $.ajax({
                                url: 'vote/fields/check_element_available',
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
                Form.api.bindevent($("form[role=form]"));
                $("#c-type").trigger("change");
            }
        }
    };
    return Controller;
});