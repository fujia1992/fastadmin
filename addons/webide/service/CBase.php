<?php
namespace addons\webide\service;

use app\admin\library\Auth;
use think\Request;
use think\Response;
use think\exception\HttpResponseException;
use think\Env;

/**
 * 基类
 * @author: vace(ocdo@qq.com)
 * @description: 提供缓存，执行和标准化输出
 */
class CBase {
  /**
   * [$request 请求实例]
   * @var [type]
   */
  protected $request;

  /**
   * [$dataType 返回数据类型]
   * @var string
   */
  protected $dataType = 'json';

  /**
   * [$auth 用户认证]
   * @var [type]
   */
  protected $auth;

  /**
   * [$path 当前请求操作文件路径]
   * @var [type]
   */
  protected $path;

  /**
   * [$root 运行的根目录]
   * @var [type]
   */
  protected $root;

  /**
   * [$dirty 用于缓存数据]
   * @var boolean
   */
  protected $dirty = false;

  public function __construct () {
    $this->auth = Auth::instance();
    $this->root = ROOT_PATH;
    $this->request = Request::instance();
    $this->path = static::cleanPath(input('path', ''));
    $this->_initialize();
  }

  /**
   * [_initialize 初始化]
   * @return [type] [description]
   */
  protected function _initialize () {}

  /**
   * [execute 执行]
   * @return [type] [description]
   */
  public static function execute () {
    $service = new static;
    $action = $service->request->get('action');
    if (!method_exists($service, $action)) {
      return $service->error("action [{$action}] not existed");
    }
    try {
      $data = call_user_func([$service, $action]); 
    } catch (\Exception $e) {
      if ($e instanceof HttpResponseException || Env::get('app.debug')) {
        throw $e;
      }
      return $service->error("action [{$action}] execute error");
    }
    $response = Response::create($data, $service->dataType, 200);
    throw new HttpResponseException($response);
  }

  /**
   * [success 返回成功数据]
   * @param  array  $data [description]
   * @return [type]       [description]
   */
  public function success ($data = []) {
    return $this->response('success', $data, 'ok');
  }

  /**
   * [condition 不符合条件终止]
   * @param  [type] $condition [条件表达式]
   * @param  [type] $reason    [原因]
   * @param  [type] $data      [数据]
   * @return [type]            [description]
   */
  protected function _condition ($condition, $reason, $data = null) {
    if ($condition) $this->error(__($reason), $data);
  }

  /**
   * [error 输出错误]
   * @param  [type] $message [description]
   * @param  array  $data    [description]
   * @return [type]          [description]
   */
  public function error ($message, $data = []) {
    return $this->response('error', $data, $message);
  }

  /**
   * [response 输出数据]
   * @param  string $status [description]
   * @param  array  $data   [description]
   * @param  string $message    [description]
   * @return [type]         [description]
   */
  public function response ($status = 'success', $data = [], $message = 'ok') {
    $response = Response::create(compact('status', 'data', 'message'), 'json', 200);
    throw new HttpResponseException($response);
  }

  /**
   * [cache 缓存]
   * @param  [type] $key  [description]
   * @param  string $value [description]
   * @return [type]        [description]
   */
  public function cache ($key, $value = '') {
    // 文件永久存储缓存
    $options = ['type' => 'File', 'prefix' => 'ADDON_WEBIDE_', 'expire' => 0];
    if (CSetting::$isIsolationSetting) {
      $key .= 'USER_' . $this->auth->id;
    }
    return cache($key, $value, $options);
  }

  /**
   * [isAbsPath 是否为绝对路径]
   * @param  [type]  $path [description]
   * @return boolean       [description]
   */
  public function isAbsPath( $path ) {
    return $path[0] === '/' || $path[1] === ':';
  }


  /**
   * [cleanPath 路径清理过滤]
   * @param  [type] $path [description]
   * @return [type]       [description]
   */
  public static function cleanPath($path)
  {
      // replace backslash with slash
      $path = str_replace('\\', '/', $path);
      // replace two slash
      $path = str_replace('//', '/', $path);

      // allow only valid chars in paths$
      // $path = preg_replace('/[^\r\n]/', '', $path);
      // maybe this is not needed anymore
      // prevent Poison Null Byte injections
      $path = str_replace(chr(0), '', $path);

      // prevent go out of the workspace
      while (strpos($path, '../') !== false) {
          $path = str_replace('../', '', $path);
      }
      return $path;
  }
}
