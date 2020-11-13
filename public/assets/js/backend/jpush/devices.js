define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        get_all_attr: function () {
            Form.api.bindevent($("form[role=form]"), function (data, ret) {
                var str = "";
                for (var i in data) {
                    str += __(i) + " : " + data[i] + "<br />";
                }
                $("#result").html(str);
            });
        },
        set_all_attr: function () {
            Form.api.bindevent($("form[role=form]"));
        },
        get_rid_by_alias: function () {
            Form.api.bindevent($("form[role=form]"), function (data, ret) {
                var rid = data.registration_ids;
                var len = rid.length;
                var str = "";
                for (var i = 0; i < len; i++) {
                    str += rid[i] + "<br />";
                }
                $("#result").html(str);
            });
        },
        del_alias: function () {
            Form.api.bindevent($("form[role=form]"));
        },
        get_tags: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'jpush/devices/get_tags',
                    del_url: 'jpush/devices/del_tag',
                    edit_url: 'jpush/devices/edit_tag',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                search: false,
                commonSearch: false,
                pageSize: 100,
                columns: [
                    [
                        {field: 'id', title: __('Name')},
                        {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ],
                onLoadSuccess: function (data) {
                    if (data.hasOwnProperty("code") && data.code === 0) {
                        Toastr.error(__(data.msg));
                    }
                }
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        edit_tag: function () {
            Form.api.bindevent($("form[role=form]"));
        }
    };
    return Controller;
});