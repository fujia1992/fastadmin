define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'vote/subject/index' + location.search,
                    add_url: 'vote/subject/add',
                    edit_url: 'vote/subject/edit',
                    del_url: 'vote/subject/del',
                    multi_url: 'vote/subject/multi',
                    table: 'vote_subject',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'title', title: __('Title')},
                        {field: 'image', title: __('Image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {
                            field: 'players', title: __('Players'), sortable: true, formatter: function (value, row, index) {
                                return '<a href="vote/player/index/subject_id/' + row['id'] + '" class="btn-dialog" title="参赛者管理[' + row['title'] + ']" data-area=\'["95%","95%"]\'>' + value + '</a>';
                            }
                        },
                        {field: 'votes', title: __('Votes'), sortable: true},
                        {field: 'voters', title: __('Voters'), sortable: true},
                        {field: 'views', title: __('Views'), sortable: true},
                        {field: 'pervotenums', title: __('Pervotenums'), visible: false},
                        {field: 'pervotelimit', title: __('Pervotelimit'), visible: false},
                        {field: 'needlogin', title: __('Needlogin'), table: table, formatter: Table.api.formatter.toggle, visible: false},
                        {field: 'onlywechat', title: __('Onlywechat'), table: table, formatter: Table.api.formatter.toggle, visible: false},
                        {field: 'iscomment', title: __('Iscomment'), table: table, formatter: Table.api.formatter.toggle, visible: false},
                        {field: 'diyname', title: __('Diyname'), visible: false},
                        {field: 'playername', title: __('Playername')},
                        {
                            field: 'url', title: __('Url'), operate: false, formatter: function (value, row, index) {
                                return '<a href="' + value + '" class="btn btn-xs btn-info" target="_blank"><i class="fa fa-link"></i></a>';
                            }
                        },
                        {field: 'begintime', title: __('Begintime'), operate: 'RANGE', addclass: 'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'endtime', title: __('Endtime'), operate: 'RANGE', addclass: 'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'createtime', title: __('Createtime'), operate: 'RANGE', addclass: 'datetimerange', formatter: Table.api.formatter.datetime, visible: false},
                        {field: 'updatetime', title: __('Updatetime'), operate: 'RANGE', addclass: 'datetimerange', formatter: Table.api.formatter.datetime, visible: false},
                        {field: 'status', title: __('Status'), searchList: {"normal": __('Normal'), "hidden": __('Hidden'), "expired": __('Expired')}, formatter: Table.api.formatter.status},
                        {
                            field: 'statistics', title: __('报表'), operate: false, formatter: function (value, row, index) {
                                return '<a href="' + "vote/statistics/index/subject_id/" + row['id'] + '" class="btn btn-xs btn-info btn-dialog" title="统计报表[' + row['title'] + ']" data-area=\'["95%","95%"]\'><i class="fa fa-bar-chart"></i></a>';
                            }
                        },
                        {
                            field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate, buttons: [
                                {
                                    'name': 'player',
                                    'title': function (row) {
                                        return '参赛者管理[' + row.title + ']';
                                    },
                                    'icon': 'fa fa-pencil',
                                    'text': '参赛者管理',
                                    'classname': 'btn btn-xs btn-info btn-dialog',
                                    'url': 'vote/player/index/subject_id/{ids}',
                                    'extend': 'data-area=\'["95%","95%"]\''
                                },
                                {
                                    'name': 'category',
                                    'title': function (row) {
                                        return '投票分类管理[' + row.title + ']';
                                    },
                                    'icon': 'fa fa-list',
                                    'text': '投票分类管理',
                                    'classname': 'btn btn-xs btn-success btn-dialog',
                                    'url': 'vote/category/index/subject_id/{ids}',
                                    'extend': 'data-area=\'["95%","95%"]\''
                                },
                                {
                                    'name': 'apply',
                                    'title': function (row) {
                                        return '报名字段管理[' + row.title + ']';
                                    },
                                    'icon': 'fa fa-edit',
                                    'text': '报名字段管理',
                                    'classname': 'btn btn-xs btn-warning btn-dialog',
                                    'url': 'vote/fields/index/subject_id/{ids}',
                                    'extend': 'data-area=\'["95%","95%"]\''
                                }
                            ]
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
                                url: 'vote/subject/check_element_available',
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
