define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    $('#c-type').on('change',function (obj) {
        var select_type = $(this).val();
        switch(select_type){
            case "1":
                $('.fieldlist2').addClass('hidden');
                $('.fieldlist0').removeClass('hidden');
                $('.fieldlist1').addClass('hidden');
                break;
            case "2":
                $('.fieldlist2').addClass('hidden');
                $('.fieldlist0').addClass('hidden');
                $('.fieldlist1').removeClass('hidden');
                break;
            case "3":
                case "2":
                $('.fieldlist2').removeClass('hidden');
                $('.fieldlist0').addClass('hidden');
                $('.fieldlist1').addClass('hidden');
                break;
        }
    });
    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'kaoshi/examination/questions/index' + location.search,
                    add_url: 'kaoshi/examination/questions/add',
                    edit_url: 'kaoshi/examination/questions/edit',
                    del_url: 'kaoshi/examination/questions/del',
                    multi_url: 'kaoshi/examination/questions/multi',
                    import_url: 'kaoshi/examination/questions/import',
                    table: 'questions',
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
                        {field: 'admin.username', title: __('username'),formatter:Table.api.formatter.search},
                        {field: 'subject.subject_name', title: __('Subject_name'),formatter:Table.api.formatter.search},
                        {field: 'type', title: __('Type'), searchList: {"1":__('Type 1'),"2":__('Type 2'),"3":__('Type 3')}, formatter: Table.api.formatter.normal},
                        {field: 'selectnumber', title: __('Selectnumber'),formatter:Table.api.formatter.search},
                        {field: 'answer', title: __('Answer')},
                        {field: 'question', title: __('question'),cellStyle : function(value, row, index, field){
                            return {
                                css: {"min-width": "100px",
                                    "white-space": "nowrap",
                                    "text-overflow": "ellipsis",
                                    "overflow": "hidden",
                                    "max-width":"250px"
                                }
                            };
                        }},
                        {field: 'annex', title: __('annex'),formatter: Controller.api.formatter.thumb, operate: false},
                        {field: 'level', title: __('Level'), searchList: {"1":__('Level 1'),"2":__('Level 2'),"3":__('Level 3')}, formatter: Table.api.formatter.normal},
                        {field: 'status', title: __('status'), searchList: {"1":__('status 1'),"2":__('status 2')}, formatter: Table.api.formatter.normal},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            table.on('post-body.bs.table', function (e, settings, json, xhr) {
                $(".btn-editone").data("area", ["800px","800px"]);
            });
        },
        recyclebin: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    'dragsort_url': ''
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: 'kaoshi/examination/questions/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {
                            field: 'deletetime',
                            title: __('Deletetime'),
                            operate: 'RANGE',
                            addclass: 'datetimerange',
                            formatter: Table.api.formatter.datetime
                        },
                        {
                            field: 'operate',
                            width: '130px',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'Restore',
                                    text: __('Restore'),
                                    classname: 'btn btn-xs btn-info btn-ajax btn-restoreit',
                                    icon: 'fa fa-rotate-left',
                                    url: 'kaoshi/examination/questions/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'kaoshi/examination/questions/destroy',
                                    refresh: true
                                }
                            ],
                            formatter: Table.api.formatter.operate
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
            },
            formatter: {
                thumb: function (value, row, index) {
                    if(typeof (value) == 'string' && value.length > 0){
                        return '<a href="' + value + '" target="_blank"><img src="' + value +'" alt="" style="max-height:90px;max-width:120px"></a>';
                    }else{
                        return "";
                    }
                },
                url: function (value, row, index) {
                    return '<a href="' + value + '" target="_blank" class="label bg-green">' + value + '</a>';
                },
            }
        }
    };

    return Controller;
});