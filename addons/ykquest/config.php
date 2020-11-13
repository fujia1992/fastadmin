<?php

return array(
    0 =>
    array(
        'name' => 'AppID',
        'title' => 'AppID',
        'type' => 'string',
        'content' =>
        array(
        ),
        'value' => '***',
        'rule' => 'required',
        'msg' => '',
        'tip' => '',
        'ok' => '',
        'extend' => '',
    ),
    1 =>
    array(
        'name' => 'AppSecret',
        'title' => 'AppSecret',
        'type' => 'string',
        'content' =>
        array(
        ),
        'value' => '*****',
        'rule' => 'required',
        'msg' => '',
        'tip' => '',
        'ok' => '',
        'extend' => '',
    ),
    2 =>
    array(
        'name' => 'rewrite',
        'title' => '伪静态',
        'type' => 'array',
        'content' =>
        array(
        ),
        'value' =>
        array(
            'index/index' => '/ykquest$',
            'index/detail' => '/ykquest/detail$',
            'index/answer' => '/ykquest/answer$',
        ),
        'rule' => 'required',
        'msg' => '',
        'tip' => '',
        'ok' => '',
        'extend' => '',
    ),
);
