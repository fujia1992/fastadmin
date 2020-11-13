define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'kaoshi/examination/user_plan/index' + location.search,
                    add_url: 'kaoshi/examination/user_plan/add',
                    edit_url: 'kaoshi/examination/user_plan/edit',
                    del_url: 'kaoshi/examination/user_plan/del',
                    multi_url: 'kaoshi/examination/user_plan/multi',
                    table: 'user_plan',
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
                showColumns: false,
                showExport: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'user.username', title: __('Username'),operate: 'LIKE'},
                        {field: 'plan.plan_name', title: __('Plan_name'),operate: 'LIKE'},
                        {field: 'exams.exam_name', title: __('exam_name'),operate: 'LIKE'},
                        {field: 'plan.type', title: __('type'), searchList: {"0":__('Type 0'),"1":__('Type 1')}, formatter: Table.api.formatter.normal},
                    ]
                ],
                queryParams:function (params) {
                    if(!Fast.api.query('plan_id')){
                        return params;
                    }  
                    var filter = JSON.parse(params.filter);
                    var op = JSON.parse(params.op);
                                     
                    filter.plan_id = Fast.api.query('plan_id');
                    op.company_id = '=';
                    params.filter = JSON.stringify(filter);
                    params.op = JSON.stringify(op);
                    return params;

                },
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