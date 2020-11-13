define(['jquery', 'bootstrap', 'backend', 'form'], function ($, undefined, Backend, Form) {

    var Controller = {
        notification: function () {
            Form.api.bindevent($("form[role=form]"), function(data, ret){
            }, function(data, ret){
            }, function(success, error){
                Layer.confirm(__("Is push ok"), function (index) {
                    Form.api.submit($("#form"), success, error);
                    Layer.close(index);
                });
                return false;
            });
            $("#crowd input").click(function () {
                var value = $(this).val();
                if ($(this).prop("checked")) {
                    if (value === "all") {
                        $("#crowd .other").prop("checked", false);
                        $(".crowd_type").addClass("hidden");
                    } else {
                        $("#crowd input").eq(0).prop("checked", false);
                        $("#" + value).removeClass("hidden");
                    }
                } else if (value !== "all") {
                    $("#" + value).addClass("hidden");
                }
            });
            $("#tag input").click(function () {
                var value = $(this).val();
                $(this).prop("checked") ? $("#" + value).removeClass("hidden") : $("#" + value).addClass("hidden");
            });
            $("#when input").click(function () {
                var value = $(this).val();
                value === "timing" ? $("#timing").removeClass("hidden") : $("#timing").addClass("hidden");
            });
            $("#speedBtn").click(function () {
                $(this).prop("checked") ? $(this).next().removeClass("hidden") : $(this).next().addClass("hidden");
            });
        }
    };
    return Controller;
});