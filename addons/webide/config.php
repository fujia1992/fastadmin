<?php

return [
    [
        //配置唯一标识
        'name'    => 'baseAcePath',
        //显示的标题
        'title'   => 'Ace.js资源目录',
        //类型
        'type'    => 'string',
        //值
        'value'   => '//cdnjs.cloudflare.com/ajax/libs/ace/1.3.3/',
        //验证规则 
        'rule'    => 'required',
        //错误消息
        'msg'     => '',
        //提示消息
        'tip'     => '如果安装在本地，可以设置相对路径。',
        //成功消息
        'ok'      => '',
        'extend'  => ''
    ],

    [
        'name'    => 'isIsolationSetting',
        'title'   => '用户配置隔离',
        'type'    => 'radio',
        'content' => [
            0  => '不隔离',
            1  => '隔离配置'
        ],
        'value'   => '1',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '配置文件包含已打开文件、命令行、编辑器配置等',
        'ok'      => '',
        'extend'  => '',
    ],

    [
        //配置唯一标识
        'name'    => 'projectRootName',
        //显示的标题
        'title'   => '项目根目录名称',
        //类型
        'type'    => 'string',
        //值
        'value'   => 'Workspace',
        //验证规则 
        'rule'    => 'required',
        //错误消息
        'msg'     => '',
        //提示消息
        'tip'     => '目录第一层级名称',
        //成功消息
        'ok'      => '',
        'extend'  => ''
    ],

    [
        'name'    => 'fileIgnoreList',
        'title'   => '忽略目录',
        'type'    => 'string',
        'value'   => '/.git|/runtime',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '多个请使用|分隔，如/.git|/runtime',
        'ok'      => '',
        'extend'  => ''
    ],

    [
        'name'    => 'fileIgnoreFileExt',
        'title'   => '忽略文件后缀',
        'type'    => 'string',
        'value'   => 'map|cache|ttf|DS_Store',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '多个请使用|分隔',
        'ok'      => '',
        'extend'  => ''
    ]
    
];
