define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {
    $('[name="row[type]"]').on('change',function (e) {
        var type = $(this).val();
        if(type == 0){
            $('.time0').removeClass('hide');
            // $('.time1').addClass('hide');
        }else{
            // $('.time1').removeClass('hide');
            $('.time0').addClass('hide');
        }
    })

    $('[name="row[exam_id]').data("params", function (obj) {
        return {custom: {subject_id: $('[name="row[subject_id]').val()}};
    });
    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'kaoshi/examination/plan/index' + location.search,
                    add_url: 'kaoshi/examination/plan/add',
                    edit_url: 'kaoshi/examination/plan/edit',
                    del_url: 'kaoshi/examination/plan/del',
                    multi_url: 'kaoshi/examination/plan/multi',
                    table: 'plan',
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
                        {field: 'exams.exam_name', title: __('Exam_name')},
                        {field: 'subject.subject_name', title: __('Subject_name')},
                        {field: 'plan_name', title: __('Plan_name')},
                        {field: 'type', title: __('Type'), searchList: {"0":__('Type 0'),"1":__('Type 1')}, formatter: Table.api.formatter.normal},
                        {
                            field: 'buttons',
                            width: "120px",
                            title: __('查看'),
                            operate:false,
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'contact',
                                    text: __('参与学生'),
                                    title: __('学生名单'),
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: 'fa fa-group',
                                    url: 'kaoshi/examination/user_plan/index/plan_id/{id}'
                                },],
                            formatter: Table.api.formatter.buttons
                        },
                        {field: 'times', title: __('Times')},
                        {field: 'hours', title: __('Hours')},

                        {field: 'starttime', title: __('Starttime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'endtime', title: __('Endtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        study: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'kaoshi/examination/plan/study' + location.search,
                    multi_url: 'kaoshi/examination/plan/multi',
                    table: 'plan',
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
                        {field: 'id', title: __('Id')},
                        {field: 'plan_name', title: __('plan_name')},
                        {field: 'exam_name', title: __('exam_name')},
                        {field: 'subject_name', title: __('subject_name')},
                        {field: 'student_num', title: __('student_num')},
                        {field: 'real_num', title: __('real_num'),operate:false},
                        {
                            field: 'buttons',
                            width: "120px",
                            title: __('查看'),
                            operate:false,
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'contact',
                                    text: __('查看详情'),
                                    title: __('参与人员'),
                                    classname: 'btn btn-xs btn-warning btn-dialog',
                                    icon: 'fa fa-address-book-o',
                                    url: 'kaoshi/examination/user_exams/users'
                                }
                            ],
                            formatter: Table.api.formatter.buttons
                        },
                        {field: 'type', title: __('type'), searchList: {"0":__('type 0'),"1":__('type 1')}, formatter: Table.api.formatter.status},
                        {field: 'starttime', title: __('Starttime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'endtime', title: __('endtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        exam: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'kaoshi/examination/plan/exam' + location.search,
                    multi_url: 'kaoshi/examination/plan/multi',
                    table: 'plan',
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
                        {field: 'id', title: __('Id')},
                        {field: 'plan_name', title: __('plan_name')},
                        {field: 'exam_name', title: __('exam_name')},
                        {field: 'subject_name', title: __('subject_name')},
                        {field: 'student_num', title: __('student_num')},
                        {field: 'real_num', title: __('real_num'),operate:false},
                        {
                            field: 'buttons',
                            width: "120px",
                            title: __('查看'),
                            operate:false,
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'contact',
                                    text: __('查看详情'),
                                    title: __('参与人员'),
                                    classname: 'btn btn-xs btn-warning btn-dialog',
                                    icon: 'fa fa-address-book-o',
                                    url: 'kaoshi/examination/user_exams/users'
                                }
                            ],
                            formatter: Table.api.formatter.buttons
                        },
                        {field: 'type', title: __('type'), searchList: {"0":__('type 0'),"1":__('type 1')}, formatter: Table.api.formatter.status},
                        {field: 'starttime', title: __('Starttime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'endtime', title: __('endtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
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
                url: 'kaoshi/examination/plan/recyclebin' + location.search,
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
                                    url: 'kaoshi/examination/plan/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'kaoshi/examination/plan/destroy',
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
            }
        }
    };
    return Controller;
});