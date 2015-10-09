<?php
//Gọi cấu hình, thư viện được sử dụng
include 'libs/config.php';
include 'libs/functions.php';

//Khai báo các class sử dụng
use libs\classes\DBAccess;
use libs\classes\FlashMessage;
use libs\classes\DBPagination;
use libs\classes\HttpException;
use libs\classes\Validator;

//Tạo các đối tượng cần dùng
$oFlashMessage = new FlashMessage();
$oDBAccess = new DBAccess();

//Khai báo tiêu đề và module cho page
$pageAliasName = 'user';
$pageTitle = 'Xác nhận thay đổi mật khẩu';

$token = '';
$process = false;
if(isset($_GET['token'])) {
	$token = trim($_GET['token']);
}

if($token != '') {
	$sql= "SELECT * FROM user WHERE (UNIX_TIMESTAMP(NOW()) - reset_timeout < 60*60) AND reset_token='".$oDBAccess->real_escape_string($token)."'";
	if ($result = $oDBAccess->query($sql)) {
		//Trả về kết quả dưới dạng object
		$record = $result->fetch_object();
		$result->close();
	} else {
		throw new HttpException($oDBAccess->error, 500);
	}

	if(null === $record) {
		//Ghi flash message
		$oFlashMessage->setFlashMessage('success', 'Mã token của bạn không tồn tại hoặc đã hết hạn');
	} else {
		$password = stringRandom(6);
		$attributes = array();
		$attributes['id'] = $record->id;
		$attributes['reset_token'] = '';
		$attributes['reset_timeout'] = '';
		$attributes['passwd'] = generateUserPassword($record->username, $password);
		$attributes['updated_at'] = date('Y-m-d H:i:s');
		$record = $oDBAccess->save('user', $attributes, 'id');
		
		//Send email
		$subject = "Xác nhận thay đổi mật khẩu thành công từ ".APP_NAME;
		$filename = __DIR__.'/libs/templates/email/reset_password_success.html';
		$params = array(
			'fullname' => $record->fullname,
			'app_name' => APP_NAME,
			'password' => $password,
		);
		$body = getTemplate($filename, $params);
		sendEmail($record->email, $subject, $body, WEBMASTER_EMAIL, WEBMASTER_NAME);
		
		//Ghi flash message
		$oFlashMessage->setFlashMessage('success', 'Hệ thống đã gửi một email đến hòm thư của bạn. Hãy kiểm tra email để nhận mật khẩu mới.');
		$process = true;
	}
}
?>

<?php include 'libs/includes/admin/header.inc.php'; ?>
<h2 id="pageTitle"><?= $pageTitle ?></h2>

<?php include "libs/includes/admin/flash_message.inc.php"; ?>

<p>
	<?php if($process): ?>
	<a href="admin_login.php">Đăng nhập</a>
	<?php else: ?>
	<a href="admin_forgot_password.php">Quên mật khẩu</a>
	<?php endif; ?>
</p>
<?php include 'libs/includes/admin/footer.inc.php'; ?>
