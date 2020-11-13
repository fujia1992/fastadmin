<?php
namespace addons\jpush\library\jpush\Exceptions;

class APIConnectionException extends JPushException {

    function __toString() {
        return "\n" . __CLASS__ . " -- {$this->message} \n";
    }
}
