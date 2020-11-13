<?php

return [
    [
        'name' => 'rewrite',
        'title' => '伪静态',
        'type' => 'array',
        'content' =>[],
        'value' =>
            [
                'index/index' => '/kaoshi$',
                'user/logout' => '/kaoshi/logout$',
                'user/login' => '/kaoshi/login$',
                'user/changepwd' => '/kaoshi/changepwd$',
                'user/index' => '/kaoshi/user$',
                'exams/answercard' => '/kaoshi/answercard$',
                'exams/score' => '/kaoshi/score$',
                'exams/getquestion' => '/kaoshi/start$',
                'exams/rank' => '/kaoshi/rank$',
                'user_plan/study' => '/kaoshi/study$',
                'user_plan/exam' => '/kaoshi/exam$',
                'user_plan/studyhistory' => '/kaoshi/studyhistory$',
                'user_plan/examhistory' => '/kaoshi/examhistory$',
            ],
        'rule' => 'required',
        'msg' => '',
        'tip' => '',
        'ok' => '',
        'extend' => '',
    ],

    [
        'name' => 'domain',
        'title' => '绑定二级域名前缀',
        'type' => 'string',
        'content' =>[],
        'value' =>"",
        'rule' => 'required',
        'msg' => '',
        'tip' => '',
        'ok' => '',
        'extend' => '',
    ],
];
