var storage = window.localStorage;
var initData = {};
var formData = {};
var submitPaperConfirm = new myConfirm("确定要交卷吗？", function() {
	submitPaper();
	this.hide();
});
var countdown = function(userOptions) {
	var h, m, s, t;
	var init = function() {
		userOptions.time = userOptions.time * 60 - userOptions.lefttime;
		s = userOptions.time % 60;
		m = parseInt(userOptions.time % 3600 / 60);
		h = parseInt(userOptions.time / 3600);
	}

	var setval = function() {
		if (s >= 10)
			userOptions.sbox.html(s);
		else
			userOptions.sbox.html('0' + s.toString());
		if (m >= 10)
			userOptions.mbox.html(m);
		else
			userOptions.mbox.html('0' + m);
		if (h >= 10)
			userOptions.hbox.html(h);
		else
			userOptions.hbox.html('0' + h);
	}

	var step = function() {
		if (s > 0) {
			s--;
		} else {
			if (m > 0) {
				m--;
				s = 60;
				s--;
			} else {
				if (h > 0) {
					h--;
					m = 60;
					m--;
					s = 60;
					s--;
				} else {
					clearInterval(interval);
					userOptions.finish();
					return;
				}
			}
		}
		setval();
	}
	init();
	interval = setInterval(step, 1000);
};

function set(k, v) {
	var _this = this;
	if (typeof(_this) == "object" && Object.prototype.toString.call(_this).toLowerCase() == "[object object]" && !_this.length) {
		_this[k] = v;
		storage.setItem('questions', JSON.stringify(formData));
	}
}

function clearStorage() {
	storage.removeItem('questions');
}

function submitPaper() {
	clearStorage();
	$('#form1').submit();
}

function refreshRecord() {
	$('#form1 input[type=text]').each(function() {
		var _ = this;
		var _this = $(this);
		var p = [];
		p.push(_.name);
		p.push(_.value);
		set.apply(formData, p);
		markQuestion(_this.attr('rel'), true);
	});
	$('#form1 textarea').each(function() {
		var _ = this;
		var _this = $(this);
		var p = [];
		for (instance in CKEDITOR.instances)
			CKEDITOR.instances[instance].updateElement();
		p.push(_.name);
		p.push(_.value);
		set.apply(formData, p);
		markQuestion(_this.attr('rel'), true);
	});
}

function saveanswer() {
	var params = $("#form1").serialize();
    if(document.URL.indexOf('getquestion')!=-1 || document.URL.indexOf('start')!=-1) {

        $.ajax({
			url: saveanswer_url,
			async: false,
			type: 'post',
			dataType: 'json',
			data: params
		});
    }
}

function markQuestion(rel, isTextArea) {
	var t = 0;
	var f = false;
	try {
		f = $('#form1 input[rel="' + rel + '"]');
	} catch (e) {
		f = false;
	}
	if (!f) return false;
	if (isTextArea) {
		if ($('#form1 input[rel="' + rel + '"]').val() && $('#form1 input[rel="' + rel + '"]').val() != '' && $('#form1 input[rel="' + rel + '"]').val() != '<p></p>') t++;
	} else
		$('#form1 input[rel="' + rel + '"]').each(function() {
			if ($(this).is(':checked') && $(this).val() && $(this).val() != '' && $(this).val() != '<p></p>') t++;
		});
	if (t > 0) {
		if (!$('#sign_' + rel).hasClass("badge-info")) $('#sign_' + rel).addClass("badge-info");
	} else {
		$('#sign_' + rel).removeClass("badge-info");
	}
	$('.yesdonumber').html($('.exp_answer_list .badge-info').length);
}

function batmark(rel, value) {
	if (value && value != '') {
		if (!$('#sign_' + rel).hasClass("badge-info")) $('#sign_' + rel).addClass("badge-info");
	} else
		$('#sign_' + rel).removeClass("badge-info");
	$('.yesdonumber').html($('.exp_answer_list .badge-info').length);
}

function _markQuestion(rel) {
	if (!$('#sign_' + rel).hasClass("badge-info")) $('#sign_' + rel).addClass("badge-info");
	$('.yesdonumber').html($('.exp_answer_list .badge-info').length);
}


function gotoquestion(questid, questypeid) {
	$(".questionpanel").hide();
	$(".paperexamcontent").hide();
	$("#panel-type-" + questypeid).show();
	$("#question_" + questid).show();
	$("body").css("overflow","visible");
	$("#expanswerPanel").hide();
	 //$('#modal').modal('hide');
}

function gotoindexquestion(index) {
	$(".questionpanel").hide();
	$(".paperexamcontent").hide();
	$(".paperexamcontent").eq(index).show();
	$(".paperexamcontent").eq(index).parents(".questionpanel").show();
}
$(document).ready(function() {
	$(".J_submit").on("click",function(){
		submitPaperConfirm.show();
	});
    if(document.URL.indexOf('getquestion')!=-1 || document.URL.indexOf('start')!=-1) {

        setInterval(saveanswer, 60000);
    }
	$('.allquestionnumber').html($('.paperexamcontent').length);
	$('.yesdonumber').html($('.exp_answer_list .badge-info').length);

	if (window.JSON) $.parseJSON = JSON.parse;

	initData = $.parseJSON(storage.getItem('questions'));

	if (initData) {
		for (var p in initData) {
			if (p != 'set')
				formData[p] = initData[p];
		}
		var textarea = $('#form1 textarea');
		$.each(textarea, function() {
			var _this = $(this);
			_this.val(initData[_this.attr('name')]);
			CKEDITOR.instances[_this.attr('id')].setData(initData[_this.attr('name')]);
			if (initData[_this.attr('name')] && initData[_this.attr('name')] != '')
				batmark(_this.attr('rel'), initData[_this.attr('name')]);
		});

		var texts = $('#form1 input[type=text]');
		$.each(texts, function() {
			var _this = $(this);
			_this.val(initData[_this.attr('name')]);
			if (initData[_this.attr('name')] && initData[_this.attr('name')] != '')
				batmark(_this.attr('rel'), initData[_this.attr('name')]);
		});


	}

	$('#form1 input[type=text]').change(function() {
		var _this = $(this);
		var p = [];
		p.push(_this.attr('name'));
		p.push(_this.val());
		set.apply(formData, p);
		markQuestion(_this.attr('rel'));
	});

	$('#form1 input[type=radio]').change(function() {
		var _ = this;
		var _this = $(this);
		var p = [];
		p.push(_.name);
		if (_.checked) {
			p.push(_.value);
			set.apply(formData, p);
		} else {
			p.push('');
			set.apply(formData, p);
		}
		markQuestion(_this.attr('rel'));
	});

	$('#form1 textarea').change(function() {
		var _ = this;
		var _this = $(this);
		var p = [];
		p.push(_.name);
		p.push(_.value);
		set.apply(formData, p);
		markQuestion(_this.attr('rel'));
	});

	$('#form1 input[type=checkbox]').change(function() {
		var _ = this;
		var _this = $(this);
		var p = [];
		p.push(_.name);
		if (_.checked) {
			p.push(_.value);
			set.apply(formData, p);
		} else {
			p.push('');
			set.apply(formData, p);
		}
		markQuestion(_this.attr('rel'));
	});
	$("#goBtn").on("click",function(){
		submitPaper();
	})
});