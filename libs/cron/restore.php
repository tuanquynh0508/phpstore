<?php
//Gọi cấu hình, thư viện được sử dụng
include '../config.php';
include '../functions.php';

//Khai báo các class sử dụng
use libs\classes\DBAccess;

//Tạo các đối tượng cần dùng
$oDBAccess = new DBAccess();

if(!isset($argv[1])) {
	die("Hay nhap ngay can khoi phuc YYYYMMDD (Vi du: php restore.php 20151009).\n");
}
$dateRestore = $argv[1];
$backupDBFile = realpath(__DIR__.'/backup/').'/DB-'.$dateRestore.'.sql';
if(!file_exists($backupDBFile)) {
	die("Khong co du lieu ngay ".$dateRestore."\n");
}

//Copy thư mục upload
$uploadDir = realpath(__DIR__.'/../../'.UPLOAD_DIR);
$backupUploadDir = realpath(__DIR__.'/backup/').'/'.UPLOAD_DIR;
$backupUploadDir = substr($backupUploadDir, 0, -1);
if(is_dir($uploadDir)) {
	deleteDirectory($uploadDir);
}
copyFolder($backupUploadDir, $uploadDir);

//RESTORE DATABASE
exec("mysql -h ".DB_HOST." -P ".DB_PORT." -u ".DB_USER." -p".DB_PASS." ".DB_NAME." < $backupDBFile");
?>