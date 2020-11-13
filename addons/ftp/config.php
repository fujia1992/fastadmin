<?php

return array (
  0 => 
  array (
    'name' => 'ftp_usernmae',
    'title' => 'FTP用户名',
    'type' => 'string',
    'content' => 
    array (
    ),
    'value' => '',
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  1 => 
  array (
    'name' => 'ftp_password',
    'title' => 'FTP密码',
    'type' => 'string',
    'content' => 
    array (
    ),
    'value' => '',
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  2 => 
  array (
    'name' => 'ftp_host',
    'title' => 'FTP地址',
    'type' => 'string',
    'content' => 
    array (
    ),
    'value' => '',
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  3 => 
  array (
    'name' => 'ftp_port',
    'title' => 'FTP端口',
    'type' => 'string',
    'content' => 
    array (
    ),
    'value' => '21',
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  4 => 
  array (
    'name' => 'ftp_ssh',
    'title' => '加密传输',
    'type' => 'select',
    'content' => 
    array (
      0 => '否',
      1 => '是',
    ),
    'value' => '0',
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  5 => 
  array (
    'name' => 'ftp_pasv',
    'title' => '传输模式',
    'type' => 'select',
    'content' => 
    array (
      0 => '主动',
      1 => '被动',
    ),
    'value' => '1',
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  6 => 
  array (
    'name' => 'ftp_path',
    'title' => 'FTP目录',
    'type' => 'string',
    'content' => 
    array (
    ),
    'value' => '/',
    'rule' => 'required',
    'msg' => '',
    'tip' => 'FTP上传目录路径',
    'ok' => '',
    'extend' => '',
  ),
  7 => 
  array (
    'name' => 'delete_source',
    'title' => '是否删除源文件',
    'type' => 'select',
    'content' => 
    array (
      0 => '否',
      1 => '是',
    ),
    'value' => '1',
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  8 => 
  array (
    'name' => 'cdn_url',
    'title' => 'CDN地址',
    'type' => 'string',
    'content' => 
    array (
    ),
    'value' => 'http://admin.fastadmin.com',
    'rule' => 'required',
    'msg' => '',
    'tip' => '显示域名',
    'ok' => '',
    'extend' => '',
  ),
);
