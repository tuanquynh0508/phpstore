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
$pageTitle = 'Đăng nhập hệ thống';

//Khởi tạo đối tượng đầu tiên cho form, các trường của đối tượng là các trường của form
$record = new stdClass();
$record->username = '';
$record->password = '';

//Khai báo mảng danh sách kiểm tra
$validates = array(
	array('type'=>'required', 'field'=>'username', 'message'=>'Cần nhập Tài khoản'),
	array('type'=>'required', 'field'=>'password', 'message'=>'Cần nhập Mật khẩu'),
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

	if($record->username != '') {
		$sql= "SELECT * FROM user WHERE username='".$oDBAccess->real_escape_string($record->username)."'";
		if ($result = $oDBAccess->query($sql)) {
			//Trả về kết quả dưới dạng object
			$user = $result->fetch_object();
			$result->close();
		} else {
			throw new HttpException($oDBAccess->error, 500);
		}

		if(null === $user) {
			$oValidator->addError('username', 'Tài khoản này không tồn tại');
		} elseif($user->is_active == 0) {
			$oValidator->addError('username', 'Tài khoản này đang bị khóa');
		} elseif($user->passwd != generateUserPassword($record->username, $record->password)) {
			$oValidator->addError('password', 'Nhập sai mật khẩu');
		}
	}

	//Đẩy giá trị vào cho đối tượng kiểm tra
	$oValidator->bindData($attributes);

	//Nếu đăng nhập thành công thì chuyển đến trang admin
	if($oValidator->validate()) {
		setUserSession($user);
		header("Location: admin.php");
		exit;
	}
}
?>

<?php include 'libs/includes/admin/header.inc.php'; ?>
<h2 id="pageTitle"><?= $pageTitle ?></h2>

<?php include "libs/includes/admin/flash_message.inc.php"; ?>

<form action="" method="POST">

	<div class="form-row clearfix">
		<label class="form-label">Tài khoản <span class="required">*</span>:</label>
		<div class="form-control">
			<input type="text" name="username" value="<?= $record->username ?>" class="input-md <?= $oValidator->checkError('username')?'invalid':'' ?>"/>
			<?= $oValidator->fieldError('username') ?>
		</div>
	</div><!-- /.form-row clearfix -->

	<div class="form-row clearfix">
		<label class="form-label">Mật khẩu <span class="required">*</span>:</label>
		<div class="form-control">
			<input type="password" name="password" value="<?= $record->password ?>" class="input-md <?= $oValidator->checkError('password')?'invalid':'' ?>"/>
			<?= $oValidator->fieldError('password') ?>
		</div>
	</div><!-- /.form-row clearfix -->

	<div class="form-row clearfix">
		<label class="form-label">&nbsp;</label>
		<div class="form-control">
			<p>
				<button type="submit">Đăng nhập</button>
				- <a href="admin_forgot_password.php">Quên mật khẩu?</a>
			</p>
			<p>
				Các trường có dấu <span class="required">*</span> là các trường bắt buộc cần nhập.
			</p>
		</div>
	</div><!-- /.form-row clearfix -->
</form>
<?php include 'libs/includes/admin/footer.inc.php'; ?>
