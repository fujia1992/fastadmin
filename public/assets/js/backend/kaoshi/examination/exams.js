define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {
    $(document).on("fa.event.appendfieldlist", ".btn-append", function(){
        Form.events.selectpicker($(".fieldlist"));
    });
    $(document).on("change",'#c-type',function(){
        var _val = $(this).val();
        switch(_val) {
            case "1":
                $("#ChooseSubject").removeClass('hidden');
                $("#ChooseSubject1").addClass('hidden');
                $("#settingdata1").addClass('hidden');
                break;
            case "2":
                $("#settingdata1").removeClass('hidden');
                $("#ChooseSubject").addClass('hidden');
                $("#ChooseSubject1").removeClass('hidden');
                break;
        }
    });
    $(document).on('click', "#AddSubject", function(){
        var subject_id = $("#c-subject_id").val();
        if(!subject_id){
            Layer.alert("请先选择科目ID" , {title: "提示"});
            return false;
        }
        var subject_ids = $("#subject_id").val();
        //弹出层回调
        Fast.api.open('kaoshi/examination/questions/choosesubject/subjectid/'+subject_id+'/subjectids/'+subject_ids, '选择题目',{callback:function(data){
            //Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
            var tbody = subject_ids = '';
            var gogo = true;
            var subject_id = $("#subject_id").val();
            var subject_arr = subject_id.split(',');
            for(var i=0,l=data.length;i<l;i++){
                for(var j in subject_arr){
                    if(subject_arr[j] == data[i]['id']){
                        gogo = false;
                        break;
                    }else{
                        gogo = true;
                    }
                }
                if(!gogo){
                    continue;
                }
                subject_ids += data[i]['id'] + ',';
                tbody += '<dd class="form-inline">' +
                    '<input type="hidden"name="row[questions_id][]" value="'+data[i]['id']+'">\n' +
                    '<ins><input type="text" class="form-control" readonly="true" size="10" value="'+data[i]['question']+'"></ins>\n' +
                    '<ins><input type="text" class="form-control" readonly="true" value="'+data[i]['type_text']+'"></ins>\n' +
                    '<ins><input type="text" name="row[onescore][]" class="form-control onescore" placeholder="分数值" onkeyup="value=value.replace(/[^\\d]/g,\'\')"></ins>\n' +
                    '<!--下面的两个按钮务必保留-->\n' +
                    '<span class="btn btn-sm btn-danger btn-remove remove_subject" subject-value="'+data[i]['id']+'"><i class="fa fa-times"></i></span>\n' +
                    '<span class="btn btn-sm btn-primary btn-dragsort"><i class="fa fa-arrows"></i></span></dd>';
            }
            subject_ids = subject_ids.slice(0,-1);
            if(subject_id && subject_ids){
                $("#subject_id").val(subject_id+','+subject_ids);
            }else if(subject_ids){
                $("#subject_id").val(subject_ids);
            }
            $("#settingdata1").before(tbody);
           // alert(tbody);
        }});
    });
    //删除后可以添加
    $(document).on('click', ".remove_subject", function(){
        var _ids = '';
        var _id = $(this).attr('subject-value');
        var subject_id = $("#subject_id").val();
        var subject_arr = subject_id.split(',');
        for(var j in subject_arr){
            if(subject_arr[j] == _id){
                continue;
            }
            _ids += subject_arr[j] + ',';
        }
        _ids = _ids.slice(0,-1);
        $("#subject_id").val(_ids);
    });
    $(function () {
        $("body").delegate(".scoreset","input",function(){
            var score = getscore();
            if(isNaN(score)){
                score = 0;
            }
            $('[name="row[score]"]').val(score);
        });
        $("body").delegate(".onescore","input",function(){
            var score = 0;
            $('.onescore').each(function () {
                if($(this).val()){
                    score += parseInt($(this).val());
                }
            });
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
    $(document).on("click", ".btn-addword", function () {
        Fast.api.open("kaoshi/examination/exams/addword", 'Word上传',{area:['1000px', '800px']});
    });

    var Controller = {

        index: function () {

            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'kaoshi/examination/exams/index' + location.search,
                    add_url: 'kaoshi/examination/exams/add',
                    import_url: 'kaoshi/examination/exams/import',
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
                                    url: 'kaoshi/examination/exams/getquestion/'
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
                        {field: 'img', title: __('Img'),formatter: Controller.api.formatter.thumb, operate: false},
                        {field: 'keyword', title: __('Keyword')},
                        {field: 'starttime', title: __('Starttime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'endtime', title: __('Endtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
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
        addword: function () {
            $("#plupload-local").data("upload-success", function(data, ret){
                //这里进行后续操作
                Fast.api.ajax({
                    url:"kaoshi/examination/exams/read",
                    data:{path:ret.data.url},
                    loading:false
                }, function(data, ret){
                    $(".uploadword").hide();
                    //Form.events.fieldlist("form");
                    $(".uploadword").before(ret.data);
                    //Form.api.bindevent("form")
                    Form.events.fieldlist("form");
                    //成功回调
                });
            });
            $(document).on('click',".AnswerXz",function(){
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
            $(document).on('click',".AnswerDx",function(){
                var answer = $(this).parent().prev().siblings().find('input').val();
                if(!answer){
                    Layer.alert("请先输入选项" , {title: "提示"});
                    $(this).attr("checked",false);
                    return false;
                }else{
                    $(this).val(answer);
                }
            })
            $("body").delegate(".keyscore","input",function(){
                setscore();
            });
            function setscore(){
                var score = 0;
                $('.keyscore').each(function () {
                    score+= $(this).val()*1;
                });
                if(isNaN(score)){
                    score = 0;
                }
                $('[name="row[score]"]').val(score);
            }
            //删除小题
            $(document).on("click", ".closeexam",function () {
                $(this).parent().parent().remove();
                var _ids = '';
                var _id = $(this).attr('data-key');
                var keyid = $("#keyhtml").val();
                var keyid = keyid.split(',');
                for(var j in keyid){
                    if(keyid[j] == _id){
                        continue;
                    }
                    _ids += keyid[j] + ',';
                }
                _ids = _ids.slice(0,-1);
                $("#keyhtml").val(_ids);
                setscore();
            })
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
