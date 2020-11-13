<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:83:"E:\fastadmin\public/../application/admin\view\kaoshi\examination\exams\addword.html";i:1605240115;s:55:"E:\fastadmin\application\admin\view\layout\default.html";i:1605241810;s:52:"E:\fastadmin\application\admin\view\common\meta.html";i:1605241810;s:54:"E:\fastadmin\application\admin\view\common\script.html";i:1605241810;}*/ ?>
<!DOCTYPE html>
<html lang="<?php echo $config['language']; ?>">
    <head>
        <meta charset="utf-8">
<title><?php echo (isset($title) && ($title !== '')?$title:''); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">

<link rel="shortcut icon" href="/assets/img/favicon.ico" />
<!-- Loading Bootstrap -->
<link href="/assets/css/backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">

<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
  <script src="/assets/js/html5shiv.js"></script>
  <script src="/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
    var require = {
        config:  <?php echo json_encode($config); ?>
    };
</script>
    </head>

    <body class="inside-header inside-aside <?php echo defined('IS_DIALOG') && IS_DIALOG ? 'is-dialog' : ''; ?>">
        <div id="main" role="main">
            <div class="tab-content tab-addtabs">
                <div id="content">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <section class="content-header hide">
                                <h1>
                                    <?php echo __('Dashboard'); ?>
                                    <small><?php echo __('Control panel'); ?></small>
                                </h1>
                            </section>
                            <?php if(!IS_DIALOG && !\think\Config::get('fastadmin.multiplenav')): ?>
                            <!-- RIBBON -->
                            <div id="ribbon">
                                <ol class="breadcrumb pull-left">
                                    <li><a href="dashboard" class="addtabsit"><i class="fa fa-dashboard"></i> <?php echo __('Dashboard'); ?></a></li>
                                </ol>
                                <ol class="breadcrumb pull-right">
                                    <?php foreach($breadcrumb as $vo): ?>
                                    <li><a href="javascript:;" data-url="<?php echo $vo['url']; ?>"><?php echo $vo['title']; ?></a></li>
                                    <?php endforeach; ?>
                                </ol>
                            </div>
                            <!-- END RIBBON -->
                            <?php endif; ?>
                            <div class="content">
                                <style>
    ins{
        text-align: center;
    }
</style>
<form id="add-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="kaoshi/examination/exams/save">

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Subject_id'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-subject_id" data-rule="required" data-field="name" data-source="zj_class_class/index" class="form-control selectpage" name="row[subject_id]" type="text" value="">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Exam_name'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-exam_name" data-rule="required" class="form-control" name="row[exam_name]" type="text">
        </div>
    </div>
    <div class="form-group">
        <label for="c-img" class="control-label col-xs-12 col-sm-2"><?php echo __("Img"); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="input-group">
                <input id="c-img" data-rule="" class="form-control" size="50" name="row[img]" type="text" >
                <div class="input-group-addon no-border no-padding">
                    <span><button type="button" id="plupload-img" class="btn btn-danger plupload" data-input-id="c-img" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/bmp" data-multiple="false" data-preview-id="p-img"><i class="fa fa-upload"></i> 上传</button></span>
                    <span><button type="button" id="fachoose-img" class="btn btn-primary fachoose" data-input-id="c-img" data-mimetype="image/*" data-multiple="false"><i class="fa fa-list"></i> 选择</button></span>
                </div>
                <span class="msg-box n-right" for="c-img"></span>
            </div>
            <ul class="row list-inline plupload-preview" id="p-img"></ul>
        </div>
    </div>
	<div class="form-group uploadword">
        <label for="c-local" class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button id="plupload-local" class="btn btn-primary plupload" data-input-id="c-local" data-url="<?php echo url('/kaoshi/examination/exams/upload'); ?>" data-mimetype="docx"><i class="fa fa-upload"></i>上传Word</button>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Score'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-score" data-rule="required" class="form-control" name="row[score]" type="number" value="0" readonly="true">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Pass'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-pass" data-rule="required" class="form-control" name="row[pass]" type="number" step="1" min="0" value="0">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2">考试时间:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-hours" data-rule="required"  min='1' step="1"  class="form-control" name="row[hours]" type="number" value="60" onkeyup="value=value.replace(/[^\d]/g,'')">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Starttime'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-starttime" data-rule="required" class="form-control datetimepicker" data-date-format="YYYY-MM-DD HH:mm:ss" data-use-current="true" name="row[starttime]" type="text" value="<?php echo date('Y-m-d H:i:s'); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Endtime'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-endtime" data-rule="required" class="form-control datetimepicker" data-date-format="YYYY-MM-DD HH:mm:ss" data-use-current="true" name="row[endtime]" type="text" value="<?php echo date('Y-m-d H:i:s',strtotime('+1 day')); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Keyword'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-keyword"  class="form-control" name="row[keyword]" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2">选项打乱顺序:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="radio">
                <label for="row[chaos_status]-normal"><input id="row[chaos_status]-normal" name="row[chaos_status]" type="radio" value="0"> 关闭</label>
                <label for="row[chaos_status]-hidden"><input id="row[chaos_status]-hidden" name="row[chaos_status]" type="radio" value="1"> 开启</label>
            </div>
        </div>
    </div>
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-success btn-embossed disabled"><?php echo __('OK'); ?></button>
            <button type="reset" class="btn btn-default btn-embossed"><?php echo __('Reset'); ?></button>
        </div>
    </div>
</form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo htmlentities($site['version']); ?>"></script>
    </body>
</html>