<?php

return array (
  'autoload' => false,
  'hooks' => 
  array (
    'app_init' => 
    array (
      0 => 'cms',
      1 => 'epay',
    ),
    'view_filter' => 
    array (
      0 => 'cms',
      1 => 'vote',
    ),
    'user_sidenav_after' => 
    array (
      0 => 'cms',
      1 => 'vote',
    ),
    'xunsearch_config_init' => 
    array (
      0 => 'cms',
    ),
    'xunsearch_index_reset' => 
    array (
      0 => 'cms',
    ),
    'upload_config_init' => 
    array (
      0 => 'ftp',
    ),
    'upload_after' => 
    array (
      0 => 'ftp',
    ),
    'upload_delete' => 
    array (
      0 => 'ftp',
    ),
    'action_begin' => 
    array (
      0 => 'geetest',
    ),
    'config_init' => 
    array (
      0 => 'geetest',
    ),
    'testhook' => 
    array (
      0 => 'recruit',
      1 => 'ykquest',
    ),
    'run' => 
    array (
      0 => 'voicenotice',
    ),
  ),
  'route' => 
  array (
    '/cms/$' => 'cms/index/index',
    '/cms/t/[:name]$' => 'cms/tags/index',
    '/cms/p/[:diyname]$' => 'cms/page/index',
    '/cms/s$' => 'cms/search/index',
    '/cms/d/[:diyname]' => 'cms/diyform/index',
    '/cms/special/[:diyname]' => 'cms/special/index',
    '/cms/a/[:diyname]$' => 'cms/archives/index',
    '/cms/c/[:diyname]$' => 'cms/channel/index',
    '/u/[:id]' => 'cms/user/index',
    '/example$' => 'example/index/index',
    '/example/d/[:name]' => 'example/demo/index',
    '/example/d1/[:name]' => 'example/demo/demo1',
    '/example/d2/[:name]' => 'example/demo/demo2',
    '/kaoshi$' => 'kaoshi/index/index',
    '/kaoshi/logout$' => 'kaoshi/user/logout',
    '/kaoshi/login$' => 'kaoshi/user/login',
    '/kaoshi/changepwd$' => 'kaoshi/user/changepwd',
    '/kaoshi/user$' => 'kaoshi/user/index',
    '/kaoshi/answercard$' => 'kaoshi/exams/answercard',
    '/kaoshi/score$' => 'kaoshi/exams/score',
    '/kaoshi/start$' => 'kaoshi/exams/getquestion',
    '/kaoshi/rank$' => 'kaoshi/exams/rank',
    '/kaoshi/study$' => 'kaoshi/user_plan/study',
    '/kaoshi/exam$' => 'kaoshi/user_plan/exam',
    '/kaoshi/studyhistory$' => 'kaoshi/user_plan/studyhistory',
    '/kaoshi/examhistory$' => 'kaoshi/user_plan/examhistory',
    '/vote/$' => 'vote/index/index',
    '/vote/subject/[:diyname]' => 'vote/subject/index',
    '/vote/player/[:id]' => 'vote/player/index',
    '/vote/rank/[:diyname]' => 'vote/rank/index',
    '/vote/apply/[:diyname]' => 'vote/apply/index',
    '/ykquest$' => 'ykquest/index/index',
    '/ykquest/detail$' => 'ykquest/index/detail',
    '/ykquest/answer$' => 'ykquest/index/answer',
  ),
);