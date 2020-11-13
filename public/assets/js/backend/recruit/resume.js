define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'recruit/resume/index',
                    add_url: 'recruit/resume/add',
                    edit_url: 'recruit/resume/edit',
                    del_url: 'recruit/resume/del',
                    multi_url: 'recruit/resume/multi',
                    table: 'recruit_resume',
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
                        {field: 'name', title: __('Name')},
                        /*{field: 'birthday', title: __('Birthday'), operate:'RANGE', addclass:'datetimerange'},*/
                        {field: 'age', title: __('Age'), operate:false,formatter: Controller.api.age_formatter },
                        {field: 'tel', title: '电话'},
                        {field: 'sex', title: __('Sex'), visible:false, searchList: {"0":__('Sex 0'),"1":__('Sex 1')}},
                        {field: 'sex_text', title: __('Sex'), operate:false},
                        {field: 'education', title: __('Education'), visible:false, searchList: {"0":__('Education 0'),"1":__('Education 1'),"2":__('Education 2'),"3":__('Education 3'),"4":__('Education 4'),"5":__('Education 5'),"6":__('Education 6')}},
                        {field: 'education_text', title: __('Education'), operate:false},
                        {field: 'native_place', title: __('Native_place')},
                        {field: 'gold1', title:'薪资标准', formatter: Controller.api.gold_formatter},
                        {field: 'recruitopencity.city', title: __('work_city')},
                        {
                            field: 'work_city', 
                            title: __('work_city'),
                            visible: false,
                            addclass: 'selectpage',
                            extend: 'data-source="recruit/opencity/index" data-field="city" ',
                            formatter: Table.api.formatter.search
                        },

                        {field: 'c_avatar', title: __('C_avatar'),formatter: Table.api.formatter.image},
                        {field: 'user.username', title: __('User.username')},
                        {field: 'user.nickname', title: __('User.nickname')},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
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
            age_formatter: function (value, row, index) {
                                    function GetAgeByBrithday(birthday){
                                     var age=-1;
                                     var today=new Date();
                                     var todayYear=today.getFullYear();
                                     var todayMonth=today.getMonth()+1;
                                     var todayDay=today.getDate();
                                     birthday = new Date(birthday);
                                     birthdayYear=birthday.getFullYear();
                                     birthdayMonth=birthday.getMonth();
                                     birthdayDay=birthday.getDate();
                                     if(todayYear-birthdayYear<0)
                                     {
                                      alert("出生日期选择错误!");
                                     }
                                     else
                                     {
                                      if(todayMonth*1-birthdayMonth*1<0)
                                      {
                                        age = (todayYear*1-birthdayYear*1)-1;
                                      }
                                      else
                                      {
                                        if(todayDay-birthdayDay>=0)
                                        {//alert(thisDay+'-'+brithd+"_ddd");
                                          age = (todayYear*1-birthdayYear*1);
                                        }
                                        else
                                        {
                                          age = (todayYear*1-birthdayYear*1)-1;
                                        }
                                      }
                                     }
                                     return age*1;
                                    }

                return GetAgeByBrithday(row.birthday);
            },
        }
    };
    return Controller;
});