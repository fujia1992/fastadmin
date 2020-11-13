require.config({
    paths: {
        'slider': '../addons/recruit/js/bootstrap-slider.min'
    },
    shim:{
        'slider': ['css!../addons/recruit/css/bootstrap-slider.min.css'],
    }
});
require(['form', 'upload'], function (Form, Upload) {
    var _bindevent = Form.events.bindevent;
    Form.events.bindevent = function (form) {
        _bindevent.apply(this, [form]);
        try {
            if ($(".slider", form).size() > 0) {
                    require(['slider'], function () {
                     //   console.log($(document).find(".slider-horizontal").css("margin-left",'%5'));
                    //$(this).slider();
                    /*
                        $(this).slider({  
                            formatter: function (value) {  
                                return 'Current value: ' + value;  
                            }  
                        }).on('slide', function (slideEvt) {  
                            console.info(slideEvt);  
                        }).on('change', function (e) {  
                            console.info(e.value.oldValue + '--' + e.value.newValue);  
                        });  
                    */
                    });
                }
        } catch (e) {

        }

    };
});
