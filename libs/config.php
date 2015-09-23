<?php
session_start();

//Cấu hình database
define("DB_HOST", "localhost");
define("DB_NAME", "phpstore");
define("DB_USER", "root");
define("DB_PASS", "123456");
define("DB_PORT", 3306);

//Hàm __autoload, tự động load các thư viện class vào chương trình chạy
//Tham khảo tài liệu tại http://php.net/manual/en/language.oop5.autoload.php
function __autoload($class)
{
    $parts = explode('\\', $class);
    require __DIR__.'/classes/'.end($parts) . '.php';
}
