define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'ykquest/myanswer/index' + location.search,
                    add_url: 'ykquest/myanswer/add',
                    edit_url: 'ykquest/myanswer/edit',
                    del_url: 'ykquest/myanswer/del',
                    multi_url: 'ykquest/myanswer/multi',
                    detail_url: 'ykquest/myanswer/detail',
                    table: 'ykquest_myanswer',
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
                        {field: 'name', title: __('Name')},
                        {field: 'count', title: __('Count')},
//                        {
//                            field: 'operate',
//                            width: "150px",
//                            title: __('Operate'),
//                            table: table,
//                            events: Table.api.events.operate,
//                            buttons: [
//                               {
//                                    name: 'detail',
//                                    title: __('Answer details'),
//                                    classname: 'btn btn-xs btn-primary btn-dialog',
//                                    icon: 'fa fa-list',
//                                    url: $.fn.bootstrapTable.defaults.extend.detail_url,
//                                     
//                                }
//                            ],
//                            
//                            // formatter: Table.api.formatter.operate
//                            formatter: function (value, row, index) {
//                                var that = $.extend({}, this);
//                                var table = $(that.table).clone(true);
//
//                                that.table = table;
//                                return Table.api.formatter.operate.call(that, value, row, index);
//                              }
//                          }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        recyclebin: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    'dragsort_url': ''
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: 'ykquest/myanswer/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {
                            field: 'deletetime',
                            title: __('Deletetime'),
                            operate: 'RANGE',
                            addclass: 'datetimerange',
                            formatter: Table.api.formatter.datetime
                        },
                        {
                            field: 'operate',
                            width: '130px',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'Restore',
                                    text: __('Restore'),
                                    classname: 'btn btn-xs btn-info btn-ajax btn-restoreit',
                                    icon: 'fa fa-rotate-left',
                                    url: 'ykquest/myanswer/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'ykquest/myanswer/destroy',
                                    refresh: true
                                }
                            ],
                            formatter: Table.api.formatter.operate
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
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});