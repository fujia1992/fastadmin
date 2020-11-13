define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'echarts', 'echarts-theme'], function ($, undefined, Backend, Table, Form, Echarts) {

    var Controller = {
        index: function () {
            var option1 = {
                title: {
                    text: '投票统计',
                    subtext: ''
                },
                tooltip: {
                    trigger: 'axis',
                },
                legend: {
                    data: ['投票数']
                },
                toolbox: {
                    show: true,
                    feature: {
                        dataView: {show: true, readOnly: false},
                        magicType: {show: true, type: ['line', 'bar']},
                        restore: {show: true},
                        saveAsImage: {show: true}
                    }
                },
                calculable: true,
                xAxis: [
                    {
                        type: 'category',
                        data: Config.voteListCategory
                    }
                ],
                yAxis: [
                    {
                        type: 'value'
                    }
                ],
                series: [
                    {
                        name: '投票数',
                        type: 'line',
                        data: Config.voteListData,
                    },
                ]
            };

            var myChart1 = Echarts.init($('#echarts1')[0], 'walden');
            myChart1.setOption(option1);

            var option2 = {
                title: {
                    text: '今日各分类占比',
                    subtext: '',
                    x: 'center'
                },
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                    orient: 'vertical',
                    left: 'left',
                    data: Config.cateListCategory
                },
                series: [
                    {
                        name: '投票数',
                        type: 'pie',
                        data: Config.cateListData,
                        itemStyle: {
                            emphasis: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        }
                    },
                ]
            };

            var myChart2 = Echarts.init($('#echarts2')[0], 'walden');
            myChart2.setOption(option2);

            $(window).on("resize", function () {
                myChart1.resize();
                myChart2.resize();
            });

            // 基于准备好的dom，初始化echarts实例
            var myChart3 = Echarts.init($('#echarts3')[0], 'walden');

            // 指定图表的配置项和数据
            var option3 = {
                title: {
                    text: '',
                    subtext: ''
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {},
                toolbox: {
                    show: true,
                    feature: {
                        dataView: {show: true, readOnly: false},
                        magicType: {show: true, type: ['line', 'bar']},
                        restore: {show: true},
                        saveAsImage: {show: true}
                    }
                },
                calculable: true,
                xAxis: {
                    type: 'category',
                    boundaryGap: false,
                    data: Config.voteListCategory
                },
                yAxis: {},
                grid: [{
                    left: 'left',
                    top: 'top',
                    right: '10',
                    bottom: 30
                }],
                series: [
                    {
                        name: "投票数",
                        type: 'line',
                        smooth: true,
                        areaStyle: {
                            normal: {}
                        },
                        lineStyle: {
                            normal: {
                                width: 1.5
                            }
                        },
                        data: Config.voteListData
                    }]
            };

            // 使用刚指定的配置项和数据显示图表。
            myChart3.setOption(option3);

            $(window).resize(function () {
                myChart3.resize();
            });

            $(".datetimerange").data("callback", function (start, end) {
                var date = start.format(this.locale.format) + " - " + end.format(this.locale.format);
                $(this.element).val(date);
                refresh_echart($(this.element).data("type"), date);
            });

            Form.api.bindevent($("#form1"));

            var si = {};
            var refresh_echart = function (type, date) {
                si[type] && clearTimeout(si[type]);
                si[type] = setTimeout(function () {
                    Fast.api.ajax({
                        data: {date: date, type: type},
                        loading: false
                    }, function (data) {
                        if (type == 'vote') {
                            option1.xAxis.data = data.category;
                            option1.series[0].data = data.data;
                            myChart1.clear();
                            myChart1.setOption(option1, true);
                        } else if (type == 'cate') {
                            option2.legend.data = data.category;
                            option2.series[0].data = data.data;
                            myChart2.clear();
                            myChart2.setOption(option2, true);
                        } else if (type == 'trend') {
                            option3.xAxis.data = data.category;
                            option3.series[0].data = data.data;
                            myChart3.clear();
                            myChart3.setOption(option3, true);
                        }
                        return false;
                    });
                }, 50);
            };

            //点击按钮
            $(document).on("click", ".btn-filter", function () {
                var label = $(this).text();
                var obj = $(this).closest("form").find(".datetimerange").data("daterangepicker");
                var dates = obj.ranges[label];
                obj.startDate = dates[0];
                obj.endDate = dates[1];

                obj.clickApply();
            });

            //点击刷新
            $(document).on("click", ".btn-refresh", function () {
                if ($(this).data("type")) {
                    refresh_echart($(this).data("type"), "");
                } else {
                    var input = $(this).closest("form").find(".datetimerange");
                    var type = $(input).data("type");
                    var date = $(input).data("date");
                    refresh_echart(type, date);
                }
            });
            //每隔一分钟定时刷新图表
            setInterval(function () {
                $(".btn-refresh").trigger("click");
            }, 60000);

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
