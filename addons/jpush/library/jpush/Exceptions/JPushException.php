<?php
namespace addons\jpush\library\jpush\Exceptions;

class JPushException extends \Exception {

    function __construct($message) {
        parent::__construct($message);
    }
}
