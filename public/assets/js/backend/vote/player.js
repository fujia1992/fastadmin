define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'vote/player/index/subject_id/' + Config.subject_id + location.search,
                    add_url: 'vote/player/add/subject_id/' + Config.subject_id,
                    edit_url: 'vote/player/edit/subject_id/' + Config.subject_id,
                    del_url: 'vote/player/del/subject_id/' + Config.subject_id,
                    multi_url: 'vote/player/multi/subject_id/' + Config.subject_id,
                    import_url: 'vote/player/import/subject_id/' + Config.subject_id,
                    table: 'vote_player',
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
                        {field: 'subject_id', title: __('Subject_id'), formatter: Table.api.formatter.search},
                        {field: 'subject.title', title: __('Subject_title'), formatter: Table.api.formatter.search},
                        {field: 'user_id', title: __('User_id'), formatter: Table.api.formatter.search},
                        {field: 'user.nickname', title: __('Nickname'), formatter: Table.api.formatter.search},
                        {field: 'category_id', title: __('Category_id'), formatter: Table.api.formatter.search},
                        {field: 'category.name', title: __('Category_name'), formatter: Table.api.formatter.search},
                        {field: 'number', title: __('Number')},
                        {field: 'nickname', title: __('Player_Nickname')},
                        {field: 'image', title: __('Image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'bgcolor', title: __('Bgcolor')},
                        {field: 'banner', title: __('Banner'), operate: false, formatter: Table.api.formatter.image},
                        {field: 'votes', title: __('Votes'), sortable: true},
                        {field: 'views', title: __('Views'), sortable: true},
                        {field: 'comments', title: __('Comments'), sortable: true},
                        {
                            field: 'url', title: __('Url'), operate: false, formatter: function (value, row, index) {
                                return '<a href="' + value + '" class="btn btn-xs btn-info" target="_blank"><i class="fa fa-link"></i></a>';
                            }
                        },
                        {
                            field: 'apply', title: __('ApplyData'), operate: 'like', table: table, formatter: Table.api.formatter.buttons, buttons: [
                                {
                                    name: "applydetail",
                                    title: function (row) {
                                        return "报名数据" + "[" + row.nickname + "]";
                                    },
                                    icon: "fa fa-list-ul",
                                    text: "报名数据",
                                    classname: "btn btn-xs btn-success btn-dialog",
                                    url: "vote/player/detail"
                                }
                            ]
                        },
                        {
                            field: 'apply', title: __('Recorddata'), operate: false, table: table, formatter: Table.api.formatter.buttons, buttons: [
                                {
                                    name: "recorddata",
                                    title: function (row) {
                                        return "获得投票数据" + "[" + row.nickname + "]";
                                    },
                                    icon: "fa fa-list-ul",
                                    text: "投票数据",
                                    classname: "btn btn-xs btn-warning btn-dialog",
                                    url: "vote/record/index?player_id={ids}",
                                    'extend': 'data-area=\'["95%","95%"]\''
                                }
                            ]
                        },
                        {
                            field: 'comment', title: __('Commentdata'), operate: false, table: table, formatter: Table.api.formatter.buttons, buttons: [
                                {
                                    name: "commentdata",
                                    title: function (row) {
                                        return "获得评论数据" + "[" + row.nickname + "]";
                                    },
                                    icon: "fa fa-list-ul",
                                    text: "评论数据",
                                    classname: "btn btn-xs btn-info btn-dialog",
                                    url: "vote/comment/index?player_id={ids}",
                                    'extend': 'data-area=\'["95%","95%"]\''
                                }
                            ]
                        },
                        {field: 'createtime', title: __('Createtime'), operate: 'RANGE', addclass: 'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate: 'RANGE', addclass: 'datetimerange', formatter: Table.api.formatter.datetime, visible: false},
                        {field: 'votetime', title: __('Votetime'), operate: 'RANGE', addclass: 'datetimerange', formatter: Table.api.formatter.datetime, visible: false},
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
                $("#c-category_id").data("params", function (obj) {
                    return {custom: {subject_id: $("#c-subject_id").val()}};
                });
                $("#c-subject_id").data("eSelect", function (obj) {
                    //主题变更时需要重置分类
                    $("#c-category_id").selectPageClear();
                    $("#c-category_id_text").data("selectPageObject").option.data = "vote/category/index/subject_id/" + $("#c-subject_id").val();
                });
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});
