define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    $(document).on('click',"input[name='row[answer0]']",function(){
        //var a = $(this).parent().prev().prev().html();
        var answer = $(this).parent().prev().siblings().find('input').val();
        if(!answer){
            Layer.alert("请先输入选项" , {title: "提示"});
            $(this).attr("checked",false);
            return false;
        }else{
            $(this).val(answer);
        }
    })
    $(document).on('click',"input[name='row[answer1][]']",function(){
        var answer = $(this).parent().prev().siblings().find('input').val();
        if(!answer){
            Layer.alert("请先输入选项" , {title: "提示"});
            $(this).attr("checked",false);
            return false;
        }else{
            $(this).val(answer);
        }
    })
    //插入括号
    $(document).on('click',".inserFill",function(){
        var ele = document.getElementById("c-question");
        ele.value = ele.value + "(_)";
    })

    $('#c-type').on('change',function (obj) {
        var select_type = $(this).val();
        switch(select_type){
            case "1":
                $('.fieldlist2').addClass('hidden');
                $('.fieldlist0').removeClass('hidden');
                $('.Answer').removeClass('hidden');
                $('.Answer').show();
                $('.fieldlist1').addClass('hidden');
                $('.fieldlist3').addClass('hidden');
                $('.fieldlist4').addClass('hidden');
                break;
            case "2":
                $('.fieldlist2').addClass('hidden');
                $('.Answer').removeClass('hidden');
                $('.Answer').show();
                $('.fieldlist0').addClass('hidden');
                $('.fieldlist3').addClass('hidden');
                $('.fieldlist1').removeClass('hidden');
                $('.fieldlist4').addClass('hidden');
                break;
            case "3":
                $('.fieldlist2').removeClass('hidden');
                $('.Answer').removeClass('hidden');
                $('.Answer').show();
                $('.fieldlist0').addClass('hidden');
                $('.fieldlist1').addClass('hidden');
                $('.fieldlist3').addClass('hidden');
                $('.fieldlist4').addClass('hidden');
                break;
            case "4":
                $('.fieldlist3').removeClass('hidden');
                $('.Answer').removeClass('hidden');
                $('.Answer').show();
                $('.fieldlist2').addClass('hidden');
                $('.fieldlist0').addClass('hidden');
                $('.fieldlist1').addClass('hidden');
                $('.fieldlist4').addClass('hidden');
                break;
            case "5":
                $('.Answer').addClass('hidden');
                $('.Answer').hide();
                $('.fieldlist3').addClass('hidden');
                $('.fieldlist2').addClass('hidden');
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
                        {field: 'type', title: __('Type'), searchList: {"1":__('Type 1'),"2":__('Type 2'),"3":__('Type 3'),"4":__('Type 4'),"5":__('Type 5')}, formatter: Table.api.formatter.normal},
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
                        {field: 'annex', title: __('annex'),formatter:function(value, row, index, field){
                                    if(row.annex){
                                        var img = 'http://admin.fastadmin.com/index/index/img?src='+row.annex;
                                        return '<img src='+img+' style="max-height:90px;max-width:120px">';
                                    }
                                }
                            },
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
        choosesubject: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    'dragsort_url': ''
                }
            });

            var table = $("#table");
            var subjectids = Fast.api.query('subjectids');
            var subject_arr = subjectids.split(',');
            // 初始化表格
            table.bootstrapTable({
                url: 'kaoshi/examination/questions/choosesubject/subjectid/'+Fast.api.query('subjectid'),
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true,formatter:function(value, row, index){
                                for (var j in subject_arr) {
                                    if (subject_arr[j] == row['id']) {
                                        return {checked:true};
                                    }
                                }
                        }},
                        {field: 'subject.subject_name', title: __('Subject_name'),formatter:Table.api.formatter.search},
                        {field: 'type', title: __('Type'), searchList: {"1":__('Type 1'),"2":__('Type 2'),"3":__('Type 3'),"4":__('Type 4'),"5":__('Type 5')}, formatter: Table.api.formatter.normal},
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
                        {field: 'level', title: __('Level'), searchList: {"1":__('Level 1'),"2":__('Level 2'),"3":__('Level 3')}, formatter: Table.api.formatter.normal},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {
                            field: 'operate',
                            width: '130px',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'click',
                                    text: '选择',
                                    title: __('点击执行事件'),
                                    classname: 'btn btn-xs btn-info btn-click',
                                    icon: 'fa fa-leaf',
                                    // dropdown: '更多',//如果包含dropdown，将会以下拉列表的形式展示
                                    click: function (data) {
                                        var data = table.bootstrapTable('getSelections');
                                        Fast.api.close(data);//在这里
                                    }
                                },
                            ],
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ]
            });
            //选中的值
            $(document).on('click',".ChooseAll",function(){
                var data = table.bootstrapTable('getSelections');
                //给表单绑定新的回调函数 接收 控制器 success(msg,url,data)或者error(msg,url,data)
                Fast.api.close(data);//在这里
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