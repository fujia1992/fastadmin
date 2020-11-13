define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'recruit/company/index',
                    add_url: 'recruit/company/add',
                    edit_url: 'recruit/company/edit',
                    del_url: 'recruit/company/del',
                    multi_url: 'recruit/company/multi',
                    table: 'recruit_company',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'Id',
                sortName: 'Id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'Id', title: __('Id')},
                        {field: 'name', title: __('Name')},
                        {field: 'tel', title: __('Tel')},
                        {field: 'no', title: __('No')},
                        {field: 'xinzhi', title: __('Xinzhi'), visible:false, searchList: {"0":__('Xinzhi 0'),"1":__('Xinzhi 1'),"2":__('Xinzhi 2'),"3":__('Xinzhi 3'),"4":__('Xinzhi 4')}},
                        {field: 'xinzhi_text', title: __('Xinzhi'), operate:false},
                        {field: 'adress', title: __('Adress'),formatter: Controller.api.formatter.adress},
                        {field: 'cimage', title: __('Cimage'), formatter: Table.api.formatter.image},
                        {field: 'cimages', title: __('Cimages'), formatter: Table.api.formatter.images},
                        {field: 'user.username', title: __('User.username')},
                        {field: 'user.nickname', title: __('User.nickname')},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
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
            },
            formatter:{
                adress:function (value, row, index) {
                    //console.log(row);
                    var length = value.length;
                    var count = 16;
                    if(length&&length>count){
                      return "<span title ='"+value+"'>"+value.substring(0,count)+"...</span>";
                    }else{
                      return value;
                    }
                }
            }
        }
    };
    return Controller;
});