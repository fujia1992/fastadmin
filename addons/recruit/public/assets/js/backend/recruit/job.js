define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'recruit/job/index',
                    add_url: 'recruit/job/add',
                    edit_url: 'recruit/job/edit',
                    del_url: 'recruit/job/del',
                    multi_url: 'recruit/job/multi',
                    table: 'recruit_job',
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
                        {field: 'age', title: __('Age'), visible:false, searchList: {"0":__('Age 0'),"1":__('Age 1'),"2":__('Age 2'),"3":__('Age 3'),"4":__('Age 4')}},
                        {field: 'age_text', title: __('Age'), operate:false},
                        {field: 'stay', title: __('Stay'), visible:false, searchList: {"0":__('Stay 0'),"1":__('Stay 1'),"2":__('Stay 2')}},
                        {field: 'stay_text', title: __('Stay'), operate:false},
                        {field: 'food', title: __('Food'), visible:false, searchList: {"0":__('Food 0'),"1":__('Food 1'),"2":__('Food 2'),"3":__('Food 3')}},
                        {field: 'food_text', title: __('Food'), operate:false},
                        {field: 'safe', title: __('Safe'), visible:false, searchList: {"0":__('Safe 0'),"1":__('Safe 1'),"2":__('Safe 2'),"3":__('Safe 3')}},
                        {field: 'safe_text', title: __('Safe'), operate:false},
                        {field: 'gold1', title:'薪资标准', formatter: Controller.api.gold_formatter},
                        //{field: 'neednum', title: __('Neednum')},
                        //{field: 'education', title: __('Education'), visible:false, searchList: {"0":__('Education 0'),"1":__('Education 1'),"2":__('Education 2'),"3":__('Education 3'),"4":__('Education 4'),"5":__('Education 5'),"6":__('Education 6')}},
                        //{field: 'education_text', title: __('Education'), operate:false},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'recruitcompany.name', title: __('company.name')},
                        {field: 'recruitopencity.city', title: __('opencity.city'),searchable:false},
                        {
                            field: 'city_id', 
                            title: __('opencity.city'),
                            visible: false,
                            addclass: 'selectpage',
                            extend: 'data-source="recruit/opencity/index" data-field="city" ',
                            formatter: Table.api.formatter.search
                        },
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
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
            gold_formatter: function (value, row, index) {
                return row.gold1+"-"+row.gold2;
            },
        }
    };
    return Controller;
});