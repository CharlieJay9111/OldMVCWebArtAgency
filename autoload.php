<?php

define("ROOT_PATH", __DIR__);
define("APP_PATH", ROOT_PATH . "/app");
define("VENDOR_PATH", ROOT_PATH . "/../../../vendor");


spl_autoload_register(function($class)
{
    $class = str_replace("\\", "/", $class);
    $path = (!preg_match("/^app/", $class)) ? VENDOR_PATH . "/$class.php" : ROOT_PATH . "/$class.php";
    
    if(file_exists($path))
    {
        require $path;
    } 
});

?>