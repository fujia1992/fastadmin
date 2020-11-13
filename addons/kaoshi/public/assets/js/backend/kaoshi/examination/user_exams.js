define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'kaoshi/examination/user_exams/index' + location.search,
                    add_url: 'kaoshi/examination/user_exams/add',
                    edit_url: 'kaoshi/examination/user_exams/edit',
                    del_url: 'kaoshi/examination/user_exams/del',
                    multi_url: 'kaoshi/examination/user_exams/multi',
                    table: 'user_exams',
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
                        {field: 'user_plan_id', title: __('User_plan_id')},
                        {field: 'answers', title: __('Answers')},
                        {field: 'scorelist', title: __('Scorelist')},
                        {field: 'score', title: __('Score')},
                        {field: 'status', title: __('Status'), searchList: {"0":__('Status 0'),"1":__('Status 1')}, formatter: Table.api.formatter.status},
                        {field: 'starttime', title: __('Starttime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'lasttime', title: __('Lasttime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        users: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'kaoshi/examination/user_exams/users/ids/'+Fast.api.query('ids') + location.search,
                    multi_url: 'kaoshi/examination/user_exams/multi',
                    table: 'user_exams',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',

                search:false,
                showToggle: false,
                showExport: false,
                commonSearch: false,
                
                columns: [
                    [
                        {field: 'id', title: __('Id')},
                        {field: 'nickname', title: __('nickname')},
                        {field: 'status', title: __('status'), searchList: {"0":__('status 0'),"1":__('status 1'),"":__('未开始')}, formatter: Table.api.formatter.status},
                        {field: 'score', title: __('score')},
                        {field: 'starttime', title: __('Starttime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'lasttime', title: __('endtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        studyrank: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'kaoshi/examination/user_exams/studyrank' + location.search,
                    add_url: 'kaoshi/examination/user_exams/add',
                    edit_url: 'kaoshi/examination/user_exams/edit',
                    del_url: 'kaoshi/examination/user_exams/del',
                    multi_url: 'kaoshi/examination/user_exams/multi',
                    table: 'user_exams',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                search:false,
                showToggle: false,
                showExport: false,
                commonSearch: false,
                columns: [
                    [
                        {field: 'nickname', title: __('nickname')},
                        {field: 'score', title: __('score')},
                        {field: 'ranking', title: __('ranking')},
                        
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        examrank: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'kaoshi/examination/user_exams/examrank' + location.search,
                    table: 'user_exams',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                search:false,
                showToggle: false,
                showExport: false,
                commonSearch: false,
                columns: [
                    [
                        {field: 'nickname', title: __('nickname')},
                        {field: 'score', title: __('score')},
                        {field: 'ranking', title: __('ranking')},
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