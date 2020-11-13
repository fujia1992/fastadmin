define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'ykquest/detail/index' + location.search,
                    add_url: 'ykquest/detail/add',
                    edit_url: 'ykquest/detail/edit',
                    del_url: 'ykquest/detail/del',
                    detail_url: 'ykquest/detail/detail2',
                    detail_url1: 'ykquest/detail/detail1',
                    multi_url: 'ykquest/detail/multi',
                    dragsort_url: "",
                    table: 'ykquest_problem',
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
                        {field: 'id', title: __('Id')},
                        {field: 'title', title: __('Title')},
                        {field: 'option_type', title: __('Option_type'), searchList: {"0": __('Option_type 0'), "1": __('Option_type 1'), "2": __('Option_type 2'), "3": __('Option_type 3')}, formatter: Table.api.formatter.normal},
                        {field: 'count', title: __('Count')},
                        {field: 'survey.name', title: __('Survey.name')},
//                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                        //操作栏,默认有编辑、删除或排序按钮,可自定义配置buttons来扩展按钮
                        {
                            field: 'operate',
                            width: "150px",
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'detail',
                                    title: __('option detail'),
                                    text: __('option detail'),
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: 'fa fa-pencil-square-o',
                                    url: $.fn.bootstrapTable.defaults.extend.detail_url,
                                    visible: function (row) {
                                        if (row.option_type > 1) {
                                            return true;
                                        }
                                        return false;
                                    }
                                },
                                {
                                    name: 'detail1',
                                    title: __('option detail'),
                                    text: __('option detail'),
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: 'fa fa-pencil-square-o',
                                    url: $.fn.bootstrapTable.defaults.extend.detail_url1,
                                    visible: function (row) {
                                        if (row.option_type <= 1) {
                                            return true;
                                        }
                                        return false;
                                    }
                                },
                            ],
                            formatter: Table.api.formatter.operate
                        },
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
                url: 'ykquest/detail/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'title', title: __('Title'), align: 'left'},
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
                                    url: 'ykquest/detail/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'ykquest/detail/destroy',
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
        },
        detail2: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    table: 'ykquest_reply',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: 'ykquest/detail/detail2?ids=' + ids + location.search,
                pk: 'id',
                showExport: false,
                search: false,
                showColumns: false,
                showToggle: false,
                commonSearch: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), operate: false},
                        {field: 'content', title: __('Content')},
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        detail1: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    table: 'ykquest_reply',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: 'ykquest/detail/detail1?ids=' + ids + location.search,
                pk: 'id',
                showExport: false,
                search: false,
                showColumns: false,
                showToggle: false,
                commonSearch: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), operate: false},
                        {field: 'content', title: __('Content')},
                        {field: 'count', title: __('Count')},
                        {field: 'bl', title: __('Bl')},
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        }
    };
    return Controller;
});
