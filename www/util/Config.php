<?php
namespace util;

define("__CONFIG_PATH__", dirname(dirname(dirname(__FILE__))) . "/config/omnomnom-tasks/");

abstract class Config
{

    public static function configFile($val): string
    {
        return __CONFIG_PATH__ . $val;
    }
}

?>