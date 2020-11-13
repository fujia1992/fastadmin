<?php
namespace addons\webide\service;

/**
 * 项目管理
 * @author: vace(ocdo@qq.com)
 * @description: 获取项目信息，以及命令配置
 */
class CProject extends CBase {

  protected $commands = [
    ['key' => '_1', 'name' => 'Update From Git', 'path' => 'git pull', 'system' => 1],
    ['key' => '_2', 'name' => 'Compack All Resuource', 'path' => 'php think min -m all -r all', 'system' => 1],
    ['key' => '_3', 'name' => 'Compack Backend Resuource', 'path' => 'php think min -m backend -r all', 'system' => 1],
    ['key' => '_4', 'name' => 'Compack Frontend Resuource', 'path' => 'php think min -m frontend -r all', 'system' => 1],
    ['key' => '_5', 'name' => 'Generation Api Documents', 'path' => 'php think api --force=true', 'system' => 1],
    ['key' => '_6', 'name' => 'Generation All Menu', 'path' => 'php think menu -c all-controller', 'system' => 1],
  ];

  protected $localCommands = [];

  protected function _initialize () {
    $cache = $this->cache('project', '', false);
    if ($cache && is_array($cache)) {
      $this->localCommands = $cache;
      $this->commands = array_merge($this->commands, $cache);
    }
    // i18n
    foreach ($this->commands as &$value) {
      $value['name'] = __($value['name']);
    }
  }

  /**
   * [getCurrent 获取当前项目]
   * @return [type] [description]
   */
  public function getCurrent () {
    return $this->success(['name' => CSetting::$projectRootName, 'path' => '/']);
  }

  /**
   * [getCommands 获取命令集合]
   * @return [type] [description]
   */
  public function getCommands () {
    return $this->commands;
  }

  /**
   * [findCommand 查找命令]
   * @param  [type] $key [description]
   * @return [type]      [description]
   */
  public function findCommand ($key) {
    foreach ($this->commands as $value) {
      if ($value['key'] === $key) {
        return $value;
      }
    }
    return null;
  }

  /**
   * [execCommand 执行命令]
   * @param  [type] $key [description]
   * @return [type]      [description]
   */
  public function execCommand ($key) {
    $command = $this->findCommand($key);
    $this->_condition(!$command, 'Command Not Found');
    $this->_condition(!function_exists('shell_exec'), 'Shell_exec() Command Not Enabled.');
    chdir($this->root);
    $command['output'] = shell_exec($command['path']);
    return $command;
  }

  /**
   * [create 创建命令]
   * @return [type] [description]
   */
  public function create () {
    $this->localCommands[] = $command = ['key' => uniqid(), 'name' => input('projectName'), 'path' => input('projectPath')];
    $this->cache('project', $this->localCommands, false);
    return $this->success($command);
  }

  /**
   * [delete 删除命令]
   * @return [type] [description]
   */
  public function delete () {
    $key = input('key');
    foreach ($this->localCommands as $i => $value) {
      if ($value['key'] === $key) {
        unset($this->localCommands[$i]);
        $this->cache('project', $this->localCommands, false);
        return $this->success('Deleted Success');
      }
    }
    $this->error('Command Not Found');
  }
}
