<?php
//Gọi cấu hình, thư viện được sử dụng
include 'libs/config.php';
include 'libs/functions.php';

removeUserSession();
header("Location: admin_login.php");
exit;
?>
