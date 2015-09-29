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

if(checkAuthentication()) {
	header("Location: admin.php");
	exit;
}

//Tạo các đối tượng cần dùng
$oFlashMessage = new FlashMessage();
$oDBAccess = new DBAccess();

//Khai báo tiêu đề và module cho page
$pageAliasName = 'user';
$pageTitle = 'Lấy lại mật khẩu';

//Khởi tạo đối tượng đầu tiên cho form, các trường của đối tượng là các trường của form
$record = new stdClass();
$record->email = '';

//Khai báo mảng danh sách kiểm tra
$validates = array(
	array('type'=>'required', 'field'=>'email', 'message'=>'Cần nhập Email'),
	array('type'=>'email', 'field'=>'email', 'message'=>'Sai định dạng email'),
);
$oValidator = new Validator($validates, $oDBAccess);

//Xử lý khi có một POST form từ client lên
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$attributes = $_POST;

	if(!isset($attributes['is_active'])) {
		$attributes['is_active'] = 0;
	}

	//Truyền lại giá trị cho đối tượng form
	foreach($attributes as $key => $value){
		$record->$key = $value;
	}

	if($record->email != '') {
		$sql= "SELECT * FROM user WHERE email='".$oDBAccess->real_escape_string($record->email)."'";
		if ($result = $oDBAccess->query($sql)) {
			//Trả về kết quả dưới dạng object
			$record = $result->fetch_object();
			$result->close();
		} else {
			throw new HttpException($oDBAccess->error, 500);
		}

		if(null === $record) {
			$oValidator->addError('email', 'Email này không tồn tại');
		}
	}

	//Đẩy giá trị vào cho đối tượng kiểm tra
	$oValidator->bindData($attributes);

	//Nếu đăng nhập thành công thì chuyển đến trang admin
	if($oValidator->validate()) {

		unset($attributes['email']);
		$attributes['id'] = $record->id;
		
		$attributes['reset_token'] = generateUserResetToken($record->username);
		$attributes['reset_timeout'] = time();
		
		//Trường hợp cập nhật
		$attributes['updated_at'] = date('Y-m-d H:i:s');
		$record = $oDBAccess->save('user', $attributes, 'id');
		
		//Send email
		$subject = "Xác nhận thay đổi mật khẩu từ ".APP_NAME;
		$filename = __DIR__.'/libs/templates/email/reset_password.html';
		$params = array(
			'fullname' => $record->fullname,
			'app_name' => APP_NAME,
			'link' => APP_URL.'admin_confirm_password.php?token='.$attributes['reset_token'],
		);
		$body = getTemplate($filename, $params);
		sendEmail($record->email, $subject, $body, WEBMASTER_EMAIL, WEBMASTER_NAME);

		//Ghi flash message
		$oFlashMessage->setFlashMessage('success', 'Hệ thống đã gửi một email đến hòm thư của bạn. Hãy kiểm tra email và bấm vào link xác nhận thay đổi mật khẩu.');

		header("Location: admin_forgot_password.php");
		exit;
	}
}
?>

<?php include 'libs/includes/admin/header.inc.php'; ?>
<h2 id="pageTitle"><?= $pageTitle ?></h2>

<?php include "libs/includes/admin/flash_message.inc.php"; ?>

<form action="" method="POST">

	<div class="form-row clearfix">
		<label class="form-label">Email <span class="required">*</span>:</label>
		<div class="form-control">
			<input type="text" name="email" value="<?= $record->email ?>" class="input-md <?= $oValidator->checkError('email')?'invalid':'' ?>"/>
			<?= $oValidator->fieldError('email') ?>
			<span class="help-block">Hãy nhập email của bạn để lấy lại mật khẩu.</span>
		</div>
	</div><!-- /.form-row clearfix -->

	<div class="form-row clearfix">
		<label class="form-label">&nbsp;</label>
		<div class="form-control">
			<p>
				<button type="submit">Gửi mật khẩu cho tôi</button>
				- <a href="admin_login.php">Đăng nhập</a>
			</p>
			<p>
				Các trường có dấu <span class="required">*</span> là các trường bắt buộc cần nhập.
			</p>
		</div>
	</div><!-- /.form-row clearfix -->
</form>
<?php include 'libs/includes/admin/footer.inc.php'; ?>
