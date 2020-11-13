define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'recruit/resumedelivery/index',
                    add_url: 'recruit/resumedelivery/add',
                    edit_url: 'recruit/resumedelivery/edit',
                    del_url: 'recruit/resumedelivery/del',
                    multi_url: 'recruit/resumedelivery/multi',
                    table: 'recruit_resumedelivery',
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
                        {field: 're_id', title: __('Re_id')},
                        {field: 're_name', title: __('Re_name')},
                        {field: 're_tel', title: __('Re_tel')},
                        {field: 'job_id', operate:false, visible:false, title: __('Job_id')},
                        {field: 'com_name', title: __('Com_name')},
                        {field: 'job_name', title: __('Job_name')},
                        {field: 'user_id', operate:false, visible:false, title: __('User_id')},
                        {field: 'createtime', title: '投递时间', operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},

                        {field: 're_id', title: __('简历详情'), table: table, buttons: [
                                {name: 'detail', text: '查看', title: '简历详情', icon: 'fa fa-list', classname: 'btn btn-xs btn-primary btn-dialog', 
                                url: 'recruit/resumedelivery/detail/re_id/{re_id}/re_tel/{re_tel}/job_id/{job_id}/user_id/{user_id}'},
                            ], operate:false, formatter: Table.api.formatter.buttons
                        },

                        {field: 'user.username', operate:false, title: __('User.username')},
                        {field: 'user.nickname', operate:false, title: __('User.nickname')},
                        {field: 'job.name', operate:false, visible:false, title: __('job.name')}, 
                        {
                            field: 'job_id', 
                            title: '职位筛选',
                            visible: false,
                            addclass: 'selectpage',
                            extend: 'data-source="recruit/job/selectpage_job_com?type=2" data-field="name" ',
                            formatter: Table.api.formatter.search
                        },
                        {field: 'resume.name', operate:false, visible:false, title: __('resume.name')},
                        {field: 'resume.tel', operate:false, visible:false, title: __('resume.tel')},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
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
                    $('#c-re_id').selectPage({
                        eAjaxSuccess: function (data) {
                            data.list = typeof data.rows !== 'undefined' ? data.rows : (typeof data.list !== 'undefined' ? data.list : []);
                            data.totalRow = typeof data.total !== 'undefined' ? data.total : (typeof data.totalRow !== 'undefined' ? data.totalRow : data.list.length);
                            return data;
                        },
                        formatItem: function(data){
                            return data.name + '(' + data.tel + ')';
                        },
                        eSelect:function(data){
                            console.log(data);
                            $('#c-re_name').val(data.name);
                            $('#c-re_tel').val(data.tel);
                        }
                    });

                    $('#c-job_id').selectPage({
                        eAjaxSuccess: function (data) {
                            data.list = typeof data.rows !== 'undefined' ? data.rows : (typeof data.list !== 'undefined' ? data.list : []);
                            data.totalRow = typeof data.total !== 'undefined' ? data.total : (typeof data.totalRow !== 'undefined' ? data.totalRow : data.list.length);
                            return data;
                        },
                        formatItem: function(data){
                           return data.name + '(' + data.comname + ')';
                        },
                        eSelect:function(data){
                            console.log(data);
                            $('#c-com_name').val(data.comname);
                            $('#c-job_name').val(data.name);
                        }
                    });
                });
            },
        }
    };
    return Controller;
});