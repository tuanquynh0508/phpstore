<?php
session_start();

// Constant Variable
define("DB_HOST", "localhost");
define("DB_NAME", "phpstore");
define("DB_USER", "root");
define("DB_PASS", "123456");
define("DB_PORT", 3306);

//Auto load classes
function __autoload($class)
{
    $parts = explode('\\', $class);
    require __DIR__.'/classes/'.end($parts) . '.php';
}
