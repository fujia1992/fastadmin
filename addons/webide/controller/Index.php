<?php

namespace addons\webide\controller;
use think\addons\Controller;

/**
 * 前台页面渲染
 * @author: vace(ocdo@qq.com)
 * @description: 全屏化编辑器
 */
class Index extends Controller {

  public function index () {
    return redirect(url('admin/webide/index'));
  }
}
