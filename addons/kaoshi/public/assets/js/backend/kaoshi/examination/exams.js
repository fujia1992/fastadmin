define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {
    $(document).on("fa.event.appendfieldlist", ".btn-append", function(){
        Form.events.selectpicker($(".fieldlist"));
    });
    $(function () {
        $("body").delegate(".scoreset","input",function(){
            var score = getscore();
            if(isNaN(score)){
                score = 0;
            }
            $('[name="row[score]"]').val(score);
        });


    });
    function getscore(length) {
        var score = 0;
        $('.mark').each(function () {

            score+=$(this).val() * $(this).parent().prev().children('.number').val();
        });

        return score;
    }
    var Controller = {

        index: function () {

            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'kaoshi/examination/exams/index' + location.search,
                    add_url: 'kaoshi/examination/exams/add',
                    edit_url: 'kaoshi/examination/exams/edit',
                    del_url: 'kaoshi/examination/exams/del',
                    multi_url: 'kaoshi/examination/exams/multi',
                    getquestion_url: 'kaoshi/examination/exams/getquestion',
                    table: 'exams',
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
                        {
                            field: 'buttons',
                            width: "120px",
                            title: __('预览'),
                            operate:false,
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'questions',
                                    text: __('预览'),
                                    title: __('随机考题预览'),
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: 'fa fa-eye',
                                    url: 'kaoshi/examination/exams/getquestion'
                                },],
                            formatter: Table.api.formatter.buttons
                        },
                        {field: 'id', title: __('Id')},
                        {field: 'admin.username', title: __('username')},
                        {field: 'subject.subject_name', title: __('Subject_name')},
                        {field: 'exam_name', title: __('Exam_name')},
                        {field: 'score', title: __('Score')},
                        {field: 'pass', title: __('Pass')},
                        {field: 'type', title: __('Type'), searchList: {"1":__('Type 1'),"2":__('Type 2')}, formatter: Table.api.formatter.normal},
                        {field: 'keyword', title: __('Keyword')},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            table.on('post-body.bs.table', function (e, settings, json, xhr) {
                $(".btn-editone").data("area", ["900px","600px"]);
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
                url: 'kaoshi/examination/exams/recyclebin' + location.search,
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
                                    url: 'kaoshi/examination/exams/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'kaoshi/examination/exams/destroy',
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
        getquestion:function () {
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
