<?php

use app\common\model\Category;
use fast\Form;
use fast\Tree;
use think\Db;

if (!function_exists('NumToLetter')) {
    //阿拉伯数字转字母
    function NumToLetter($num = 0)
    {
        $num = $num % 26;
        $cns = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        return isset($cns[$num]) ? $cns[$num] : $num;
    }
}