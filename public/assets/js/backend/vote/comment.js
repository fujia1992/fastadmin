define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'vote/comment/index' + location.search,
                    add_url: 'vote/comment/add',
                    edit_url: 'vote/comment/edit',
                    del_url: 'vote/comment/del',
                    multi_url: 'vote/comment/multi',
                    table: 'vote_comment',
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
                        {field: 'user_id', title: __('User_id'), formatter: Table.api.formatter.search},
                        {field: 'user.nickname', title: __('Nickname'), formatter: Table.api.formatter.search},
                        {field: 'subject_id', title: __('Subject_id'), formatter: Table.api.formatter.search},
                        {field: 'subject.title', title: __('Title'), formatter: Table.api.formatter.search},
                        {field: 'player_id', title: __('Player_id'), formatter: Table.api.formatter.search},
                        {field: 'player.nickname', title: __('Player_nickname'), formatter: Table.api.formatter.search},
                        {field: 'content', title: __('Content')},
                        {field: 'ip', title: __('Ip'), formatter: Table.api.formatter.search},
                        {field: 'createtime', title: __('Createtime'), operate: 'RANGE', addclass: 'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate: 'RANGE', addclass: 'datetimerange', formatter: Table.api.formatter.datetime, visible: false},
                        {field: 'status', title: __('Status'), searchList: {"normal": __('Normal'), "hidden": __('Hidden')}, formatter: Table.api.formatter.status},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
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