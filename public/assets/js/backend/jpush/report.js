define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        received: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'jpush/report/received',
                    del_url: 'jpush/report/del',
                    table: 'jpush_log',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                search: false,
                commonSearch: false,
                pageSize: 50,
                pageList: [50],
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), visible: false},
                        {field: 'sendno', title: __('Sendno')},
                        {field: 'msg_id', title: __('Msg_id')},
                        {
                            field: 'push_type', title: __('Push_type'),
                            formatter: function (value, row, index) {
                                if (value === "now") {
                                    return __('Now');
                                } else if (value === "timing") {
                                    return __('Timing');
                                }
                            }
                        },
                        {field: 'receiver', title: __('Receiver')},
                        {field: 'content', title: __('Content')},
                        {field: 'platform', title: __('Platform')},
                        {field: 'createtime', title: __('Createtime')},
                        {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [{
                                name: 'detail',
                                text: __('Detail'),
                                icon: 'fa fa-list',
                                classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                                url: 'jpush/report/recDetail'
                            }],
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ]
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        messages: function () {
            Form.api.bindevent($("form[role=form]"), function(data, ret){
                var result = data.hasOwnProperty("body") ? data.body : [];
                var str = "";
                for(var i in result){
                    switch (result[i].status) {
                        case 0:
                            str += i + " : " + __("Message_status_0") + "<br />";
                            break;
                        case 1:
                            str += i + " : " + __("Message_status_1") + "<br />";
                            break;
                        case 2:
                            str += i + " : " + __("Message_status_2") + "<br />";
                            break;
                        case 3:
                            str += i + " : " + __("Message_status_3") + "<br />";
                            break;
                        case 4:
                            str += i + " : " + __("Message_status_4") + "<br />";
                            break;
                        default:
                            str += i + " : " + __("Message_status_unknown") + "<br />";
                    }
                }
                $("#result").html(str);
            });
        }
    };
    return Controller;
});