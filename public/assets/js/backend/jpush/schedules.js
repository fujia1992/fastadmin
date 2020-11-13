define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'jpush/schedules/index',
                    del_url: 'jpush/schedules/del',
                    table: 'schedules',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'schedule_id',
                search: false,
                commonSearch: false,
                pageSize: 50,
                pageList: [50],
                columns: [
                    [
                        {checkbox: true},
                        {field: 'schedule_id', title: __('Id'), visible: false},
                        {field: 'name', title: __('Name')},
                        {field: 'trigger.single.time', title: __('Scheduled time')},
                        {
                            field: 'content',
                            title: __('Content'),
                            formatter: function (value, row, index) {
                                if (row.push.hasOwnProperty("message")) {
                                    return __('Message') + ":" + row.push.message.msg_content;
                                } else if (row.push.hasOwnProperty("notification")) {
                                    return __('Notification') + ":" + row.push.notification.alert;
                                }
                            }
                        },
                        {field: 'push.platform', title: __('Platform')},
                        {
                            field: 'push.audience', title: __('Crowd'), formatter: function (value, row, index) {
                                if (value === "all") {
                                    return __('All');
                                }
                                var aud = Array();
                                if (value.hasOwnProperty("tag")) {
                                    aud.push(__('Tag'));
                                }
                                if (value.hasOwnProperty("alias")) {
                                    aud.push(__('Alias'));
                                }
                                if (value.hasOwnProperty("registration_id")) {
                                    aud.push("reg.ID");
                                }
                                return aud;
                            }
                        },
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
        }
    };
    return Controller;
});