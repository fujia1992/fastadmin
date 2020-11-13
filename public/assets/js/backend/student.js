define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'student/index' + location.search,
                    add_url: 'student/add',
                    edit_url: 'student/edit',
                    del_url: 'student/del',
                    multi_url: 'student/multi',
                    table: 'student',
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
                        {field: 'sex',title: __('判断测试'),formatter:function(value,row){
                                console.log(row);
                                if (value == 1) {
                                    return '<a href="/shSjpTOLDc.php/student/edit/ids/16" class="btn btn-xs btn-success btn-dialog"  data-table-id="table" data-field-index="10" data-row-index="0" data-button-index="0" data-original-title="编辑"><i class="fa fa-pencil"></i></a>';
                                } else if (value == 0) {
                                    return '<a href="/shSjpTOLDc.php/student/edit/ids/16" class="btn btn-xs btn-primary btn-dialog"  data-table-id="table" data-field-index="10" data-row-index="0" data-button-index="0"><i class="fa fa-list"></i> 弹出窗口打开</a>';
                                }
                            },operate:false},
                        {field: 'name', title: __('Name')},
                        {field: 'sex', title: __('Sex'), searchList: {"0":__('Sex 0'),"1":__('Sex 1')}, formatter: Table.api.formatter.normal},
                        {field: 'phone', title: __('Phone')},
                        {field: 'code', title: __('Code')},
                        {field: 'avatar', title: __('Avatar'), events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'activitytime', title: __('Activitytime'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate},
                        {
                            field: 'buttons',
                            width: "120px",
                            title: __('按钮组'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'detail',
                                    text: __('弹出窗口打开'),
                                    title: __('弹出窗口打开'),
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: 'fa fa-list',
                                    url: 'example/bootstraptable/detail',
                                    callback: function (data) {
                                        Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                    },
                                    visible: function (row) {
                                        //console.log(row.id);
                                        if(row.id=='16'){
                                            return true;
                                        }else{
                                            return false;
                                        }
                                        //返回true时按钮显示,返回false隐藏
                                        return true;
                                    }
                                },
                                {
                                    name: 'ajax',
                                    text: __('发送Ajax'),
                                    title: __('发送Ajax'),
                                    classname: 'btn btn-xs btn-success btn-magic btn-ajax',
                                    icon: 'fa fa-magic',
                                    url: 'example/bootstraptable/detail',
                                    confirm: '确认发送',
                                    success: function (data, ret) {
                                        Layer.alert(ret.msg + ",返回数据：" + JSON.stringify(data));
                                        //如果需要阻止成功提示，则必须使用return false;
                                        //return false;
                                    },
                                    error: function (data, ret) {
                                        console.log(data, ret);
                                        Layer.alert(ret.msg);
                                        return false;
                                    }
                                },
                                {
                                    name: 'addtabs',
                                    text: __('新选项卡中打开'),
                                    title: __('新选项卡中打开'),
                                    classname: 'btn btn-xs btn-warning btn-addtabs',
                                    icon: 'fa fa-folder-o',
                                    url: 'example/bootstraptable/detail'
                                }
                            ],
                            formatter: Table.api.formatter.buttons
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
$("#city-picker").on("cp:updated", function() {
    var citypicker = $(this).data("citypicker");
    var code = citypicker.getCode("district") || citypicker.getCode("city") || citypicker.getCode("province");
    $("#code").val(code);
    layer.closeAll('iframe');
});