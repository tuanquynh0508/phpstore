<?php
session_start();

define("APP_NAME", "PHPStore");
define("APP_URL", "http://localhost/phpstore/");
define("WEBMASTER_EMAIL", 'tuanquynh0508@gmail.com');
define("WEBMASTER_NAME", 'Web Master');
define("ORDER_EMAIL", 'tuanquynh0508@gmail.com');

//Mã bí mật
define("SECRET_CODE", "phpstoresecretcodeabc123");

//Cấu hình database
define("DB_HOST", "localhost");
define("DB_NAME", "phpstore");
define("DB_USER", "root");
define("DB_PASS", "123456");
define("DB_PORT", 3306);

//Upload file
define("UPLOAD_DIR", "uploads/");
define("UPLOAD_QUANTITY", 80);
define("UPLOAD_W", 800);
define("UPLOAD_H", 800);
define("UPLOAD_THUMB_W", 200);
define("UPLOAD_THUMB_H", 200);

//Hàm __autoload, tự động load các thư viện class vào chương trình chạy
//Tham khảo tài liệu tại http://php.net/manual/en/language.oop5.autoload.php
function __autoload($class)
{
    $parts = explode('\\', $class);
    require __DIR__.'/classes/'.end($parts) . '.php';
}
