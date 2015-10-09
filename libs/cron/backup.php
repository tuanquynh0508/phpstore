<?php
//Gọi cấu hình, thư viện được sử dụng
include '../config.php';
include '../functions.php';

//Khai báo các class sử dụng
use libs\classes\DBAccess;

//Tạo các đối tượng cần dùng
$oDBAccess = new DBAccess();

//Copy thư mục upload
$uploadDir = realpath(__DIR__.'/../../'.UPLOAD_DIR);
$backupUploadDir = realpath(__DIR__.'/backup/').'/'.UPLOAD_DIR;
$backupUploadDir = substr($backupUploadDir, 0, -1);
if(is_dir($backupUploadDir)) {
	deleteDirectory($backupUploadDir);
}
copyFolder($uploadDir,$backupUploadDir);

//DUMP DATABASE
$backupDBFile = realpath(__DIR__.'/backup/').'/DB-'.date('Ymd').'.sql';
if(file_exists($backupDBFile)) {
	unlink($backupDBFile);
}
exec("mysqldump -h ".DB_HOST." -P ".DB_PORT." -u ".DB_USER." -p".DB_PASS." ".DB_NAME." > $backupDBFile");
?>