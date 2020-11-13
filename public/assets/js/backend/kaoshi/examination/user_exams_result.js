define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'kaoshi/examination/user_exams_result/index' + location.search,
                    edit_url: 'kaoshi/examination/user_exams_result/edit',
                    table: 'user_exams_result',
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
                        {field: 'id', title: __('Id')},
                        {field: 'exams.exam_name', title: __('exam_name')},
                        {field: 'user.nickname', title: __('nickname')},
                        {field: 'score', title: __('Score')},
                        {field: 'status', title: __('Status'), searchList: {"0":__('Status 0'),"1":__('Status 1'),"2":'未交卷'}, formatter: Table.api.formatter.status,operate: false},
                        {field: 'usetime', title: __('usetime'), operate:'RANGE'},
                        {field: 'starttime', title: __('Starttime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'endtime', title: __('endtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {
                            field: 'buttons',
                            width: "120px",
                            title: __('操作'),
                            operate:false,
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: '_detail',
                                    text: '查看',
                                    classname: 'btn btn-xs btn-success btn-dialog',
                                    extend: 'data-area=\'["65%", "80%"]\'',
                                    icon:'fa fa-wpforms',
                                    url: 'kaoshi/examination/user_exams_result/edit',
                                    visible: function (row) {
                                        //返回true时按钮显示,返回false隐藏
                                        if(row.status=='1' || row.status=='2'){
                                            return true;
                                        }
                                    },
                                },
                                {
                                    name: '_edit',
                                    text: __('批卷'),
                                    classname: 'btn btn-xs btn-danger btn-dialog',
                                    extend: 'data-area=\'["65%", "80%"]\'',
                                    icon: 'fa fa-check',
                                    url: 'kaoshi/examination/user_exams_result/edit',
                                    visible: function (row) {
                                        //返回true时按钮显示,返回false隐藏
                                        if(row.status=='0'){
                                            return true;
                                        }
                                    },
                                },
                            ],formatter: Table.api.formatter.buttons
                        },
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            table.on('post-body.bs.table', function (e, settings, json, xhr) {
                $(".btn-editone").data("area", ["80%","80%"]);
            });
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});