var myConfirm=function(info,okcallback,cancelcallback){
	this.info=info||"你确定要执行这项操作吗？";
	this.ok=okcallback;
	this.cancel=cancelcallback;
	this.init();
}
myConfirm.prototype = {
	init:function(){
		var confirmtpl='<div class="confirmWrap">\
					<div class="confirm_body">\
					</div>\
					<div class="confirm_bottom">\
						<a href="javascript:;" class="okbtn">确定</a>\
						<a href="javascript:;" class="cancelbtn">取消</a>\
					</div>\
				</div>';
		var bgtpl='<div class="confirmBg"></div>';
		if($(".confirmBg").length<1){
			$("body").append(bgtpl);
		}
		var confirmwrap=$(confirmtpl);
		$("body").append(confirmwrap);
		this.contain=confirmwrap;
		this.body=confirmwrap.find(".confirm_body");
		this.okbtn=confirmwrap.find(".okbtn");
		this.canbtn=confirmwrap.find(".cancelbtn");
		this.body.html(this.info);
		this.bindEvent();
	},
	bindEvent:function(){
		var t=this;
		t.okbtn.on("click",function(){
			if(t.ok){
				t.ok.call(t)
			}
		});
		t.canbtn.on("click",function(){
			if(t.cancel){
				t.cancel.call(t)
			}
			t.hide();
		});
	},
	show:function(){
		var t=this;
		t.contain.show();
		$(".confirmBg").show();
		var h=t.contain.height();
		t.contain.css("margin-top",-h/2+"px");
	},
	hide:function(){
		var t=this;
		t.contain.hide();
		setTimeout(function(){
			$(".confirmBg").hide();
		},50)
	}
}

$(function(){
	//下拉框选择
	$(".hidden_select_ctrl").each(function(){
		var inputobj=$(this).find(".control-detail");
		var opttext=$(this).find("select option").not(function(){ return !this.selected }).text();
		inputobj.html(opttext);
	});
	$(".hidden_select_ctrl").on("change","select",function(){
		var inputobj=$(this).siblings(".control-detail");
		var opttext=$(this).find("option").not(function(){ return !this.selected }).text();
		inputobj.html(opttext);
	});
	//表单提交
	$("a[data-subform]").on("click",function(){
		var target=$(this).attr("data-subform");
		$("#"+target).submit();
	});
	//后退按钮绑定
	$(".J_historyback").on("click",function(){
		window.history.back(-1)
	});
	//密码切换
	$(".toggle_switch").on("click",function(){
		var inputobj=$(this).siblings("input");
		if($(this).hasClass("open")){
			$(this).removeClass("open");
			inputobj.attr("type","password");
		}else{
			$(this).addClass("open");
			inputobj.attr("type","text");
		}
	});
	//单选复选框
	// $(".select_input").on("click",function(){
	// 	var t=$(this).find(".select_input_emulation");
	// 	var inputobj=t.siblings("input");
	// 	var checked=inputobj.prop("checked");
	// 	var inputtype=inputobj.attr("type");
	// 	if(inputtype=="checkbox"){
	// 		if(inputobj.is(":checked")){
	// 			t.addClass("check");
	// 		}else{
	// 			t.removeClass("check");
	// 		}
	// 	}else{
	// 		var samename=inputobj.attr("name");
	// 		var sameinput=$("input[name='"+samename+"']");
	// 		if(inputobj.is(":checked")){
	// 			$.each(sameinput,function(){
	// 				$(this).siblings(".select_input_emulation").removeClass("check");
	// 			});
	// 			t.addClass("check");
	// 		}
	// 	}
	// });
});
