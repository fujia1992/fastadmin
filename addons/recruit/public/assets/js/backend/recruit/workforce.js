define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'recruit/workforce/index',
                    add_url: 'recruit/workforce/add',
                    edit_url: 'recruit/workforce/edit',
                    del_url: 'recruit/workforce/del',
                    multi_url: 'recruit/workforce/multi',
                    table: 'recruit_workforce',
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
                        {field: 'village', title: __('Village')},
                        {field: 'name', title: __('Name'),operate:'LIKE'},
                        {field: 'sex', title: __('Sex'), visible:false, searchList: {"0":__('Sex 0'),"1":__('Sex 1')}},
                        {field: 'sex_text', title: __('Sex'), operate:false},
                        {field: 'sfzno', title: __('Sfzno'),operate:'LIKE'},
                        {field: 'education', title: __('Education'), visible:false, searchList: {"0":__('Education 0'),"1":__('Education 1'),"2":__('Education 2'),"3":__('Education 3'),"4":__('Education 4'),"5":__('Education 5'),"6":__('Education 6'),"7":__('Education 7')}},
                        {field: 'education_text', title: __('Education'), operate:false},
                        {field: 'place', title: __('Place'),operate:'LIKE'},
                        {field: 'salary', title: __('Salary')},
                        {field: 'tel', title: __('Tel'),operate:'LIKE'},
                        {field: 'collect', title: __('Collect'),operate:'LIKE'},
                        {field: 'skill', title: __('Skill'),operate:'LIKE'},
                        {field: 'intent', title: __('Intent'),operate:'LIKE'},
                        {field: 'content', title: __('Content'),operate:'LIKE',formatter: Controller.api.text_formatter},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'user.nickname', title: __('User.nickname')},
                        {field: 'user.avatar', title: __('User.avatar'),formatter: Table.api.formatter.image},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ],
              exportOptions: {
                mso:
                    {
                        // fileFormat:        'xlsx',
                        //修复导出数字不显示为科学计数法
                        onMsoNumberFormat: function (cell, row, col) {
                            return !isNaN($(cell).text())?'\\@':'';
                        }
                    }
              }
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

            var submitForm = function (ids, layero) {
                var options = table.bootstrapTable('getOptions');
                console.log(options);
                var columns = [];
                $.each(options.columns[0], function (i, j) {
                    if (j.field && !j.checkbox && j.visible && j.field != 'operate') {
                        columns.push(j.field);
                    }
                });
                var search = options.queryParams({});
                $("input[name=search]", layero).val(options.searchText);
                $("input[name=ids]", layero).val(ids);
                $("input[name=filter]", layero).val(search.filter);
                $("input[name=op]", layero).val(search.op);
                $("input[name=columns]", layero).val(columns.join(','));
                $("form", layero).submit();
            };
            $(document).on("click", ".btn-export", function () {
                var ids = Table.api.selectedids(table);
                var page = table.bootstrapTable('getData');
                var all = table.bootstrapTable('getOptions').totalRows;
                console.log(ids, page, all);
                Layer.confirm("请选择导出的选项[当数据大于1万，请筛选后处理，不然会造成数据库的卡死]<form action='" + Fast.api.fixurl("recruit/workforce/export") + "' method='post' target='_blank'><input type='hidden' name='ids' value='' /><input type='hidden' name='filter' ><input type='hidden' name='op'><input type='hidden' name='search'><input type='hidden' name='columns'></form>", {
                    title: '导出数据',
                    btn: ["选中项(" + ids.length + "条)", "本页(" + page.length + "条)", "全部(" + all + "条)"],
                    success: function (layero, index) {
                        $(".layui-layer-btn a", layero).addClass("layui-layer-btn0");
                    }
                    , yes: function (index, layero) {
                        submitForm(ids.join(","), layero);
                        return false;
                    }
                    ,
                    btn2: function (index, layero) {
                        var ids = [];
                        $.each(page, function (i, j) {
                            ids.push(j.id);
                        });
                        submitForm(ids.join(","), layero);
                        return false;
                    }
                    ,
                    btn3: function (index, layero) {
                        submitForm("all", layero);
                        return false;
                    }
                })
            });
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
            text_formatter: function (value, row, index) {
               String.prototype.stripHTML = function() {
                var reTag = /<(?:.|\s)*?>/g;
                return this.replace(reTag,"");
              }
              function HTMLDecode(text) { 
                var temp = document.createElement("div"); 
                temp.innerHTML = text; 
                var output = temp.innerText || temp.textContent; 
                temp = null; 
                return output; 
                } 
              return HTMLDecode(value).stripHTML();
            },
        }
    };
    return Controller;
});