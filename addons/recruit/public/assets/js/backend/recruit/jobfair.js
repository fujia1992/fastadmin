define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'recruit/jobfair/index',
                    add_url: 'recruit/jobfair/add',
                    edit_url: 'recruit/jobfair/edit',
                    del_url: 'recruit/jobfair/del',
                    multi_url: 'recruit/jobfair/multi',
                    table: 'recruit_jobfair',
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
                        {field: 'block_id',visible:false, title: __('Block_id'),searchable:false},
                        {
                            field: 'block_id', 
                            title: '招聘会筛选',
                            visible: false,
                            addclass: 'selectpage',
                            extend: 'data-source="recruit/news/index" data-field="title" ',
                            formatter: Table.api.formatter.search
                        },
                        {field: 'block_title', title: __('Block_title')},
                        {field: 'user_id',visible:false, title: __('User_id')},
                        {field: 'tname', title: __('Tname')},
                        {field: 'ttel', title: __('Ttel')},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'recruitnews.title', visible:false,title: __('recruitnews.title')},
                        {field: 'user.username', title: __('User.username')},
                        {field: 'user.nickname', title: __('User.nickname')},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
            console.log('add');
            Controller.api.selectpage_after();
        },
        edit: function () {
            Controller.api.bindevent();
            Controller.api.selectpage_after();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
            selectpage_after: function () {
                //绑定selectpage元素事件
                require(['selectpage'], function () {
                    $('#c-block_id').selectPage({
                        eAjaxSuccess: function (data) {
                            data.list = typeof data.rows !== 'undefined' ? data.rows : (typeof data.list !== 'undefined' ? data.list : []);
                            data.totalRow = typeof data.total !== 'undefined' ? data.total : (typeof data.totalRow !== 'undefined' ? data.totalRow : data.list.length);
                            return data;
                        },
                         eSelect:function(data){
                            console.log(data.title);
                            $('#c-block_title').val(data.title);
                        }
                    });
                });
            },
        }
    };
    return Controller;
});
