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

//Kiểm tra đăng nhập, chưa đăng nhập thì chuyển đến trang đăng nhập
if(!checkAuthentication()) {
	header("Location: admin_login.php");
	exit;
}

//Tạo các đối tượng cần dùng
$oFlashMessage = new FlashMessage();
$oDBAccess = new DBAccess();

//Khai báo tiêu đề và module cho page
$pageAliasName = 'user';
$pageTitle = 'Hồ sơ người dùng';

//Khởi tạo đối tượng đầu tiên cho form, các trường của đối tượng là các trường của form
$record = new stdClass();
$record->passwd = '';
$record->fullname = '';
$record->email = '';

//Lấy thông tin của người dùng hiện tại
$record = $oDBAccess->findOneById('user', getUserAttrSession('id'));

//Khai báo mảng danh sách kiểm tra
$validates = array(
	array('type'=>'required', 'field'=>'passwd', 'message'=>'Cần nhập Mật khẩu'),
	array('type'=>'required', 'field'=>'email', 'message'=>'Cần nhập Email'),
	array('type'=>'length', 'field'=>'passwd', 'min'=>6, 'max'=>20, 'message'=>'Độ dài Mật khẩu tối thiểu là 6, lớn nhất là 20 ký tự'),
	array('type'=>'length', 'field'=>'fullname', 'min'=>5, 'max'=>25, 'message'=>'Độ dài Tài khoản tối thiểu là 5, lớn nhất là 25 ký tự'),
	array('type'=>'email', 'field'=>'email', 'message'=>'Sai định dạng Email'),
	array('type'=>'unique','field'=>'email','table'=>'user', 'message'=>'Email này đã tồn tại trong hệ thống'),
);
$oValidator = new Validator($validates, $oDBAccess);

//Xử lý khi có một POST form từ client lên
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$attributes = $_POST;

	$attributes['id'] = $record->id;

	//Truyền lại giá trị cho đối tượng form
	foreach($attributes as $key => $value){
		$record->$key = $value;
	}

	if(trim($attributes['passwd']) == '') {
		unset($attributes['passwd']);
	}

	//Đẩy giá trị vào cho đối tượng kiểm tra
	$oValidator->bindData($attributes);

	//Nếu việc kiểm tra không có lỗi thì thực hiện ghi hoặc cập nhật dữ liệu vào database
	if($oValidator->validate()) {
		if(trim($attributes['passwd']) != '') {
			$attributes['passwd'] = md5($attributes['passwd']);
		} else {
			unset($attributes['passwd']);
		}

		//Trường hợp cập nhật
		$attributes['updated_at'] = date('Y-m-d H:i:s');
		$record = $oDBAccess->save('user', $attributes, 'id');
		setUserSession($record);
		//Ghi flash message
		$oFlashMessage->setFlashMessage('success', 'Cập nhật hồ sơ thành công');
		header("Location: admin_profile.php");
		exit;
	}
}
?>

<?php include 'libs/includes/admin/header.inc.php'; ?>
<h2 id="pageTitle"><?= $pageTitle ?></h2>

<?php include "libs/includes/admin/flash_message.inc.php"; ?>

<form action="" method="POST">

	<div class="form-row clearfix">
		<label class="form-label">Mật khẩu:</label>
		<div class="form-control">
			<input type="password" name="passwd" value="" class="input-md <?= $oValidator->checkError('passwd')?'invalid':'' ?>"/>
			<?= $oValidator->fieldError('passwd') ?>
			<span class="help-block">Để trống mật khẩu, nếu bạn không muốn thay đổi</span>
		</div>
	</div><!-- /.form-row clearfix -->

	<div class="form-row clearfix">
		<label class="form-label">Họ và tên <span class="required">*</span>:</label>
		<div class="form-control">
			<input type="text" name="fullname" value="<?= $record->fullname ?>" class="input-md <?= $oValidator->checkError('fullname')?'invalid':'' ?>"/>
			<?= $oValidator->fieldError('fullname') ?>
		</div>
	</div><!-- /.form-row clearfix -->

	<div class="form-row clearfix">
		<label class="form-label">Email <span class="required">*</span>:</label>
		<div class="form-control">
			<input type="text" name="email" value="<?= $record->email ?>" class="input-md <?= $oValidator->checkError('email')?'invalid':'' ?>"/>
			<?= $oValidator->fieldError('email') ?>
		</div>
	</div><!-- /.form-row clearfix -->

	<div class="form-row clearfix">
		<label class="form-label">Ngày tạo:</label>
		<div class="form-control">
			<?= $record->created_at ?>
		</div>
	</div><!-- /.form-row clearfix -->

	<div class="form-row clearfix">
		<label class="form-label">Cập nhật:</label>
		<div class="form-control">
			<?= $record->updated_at ?>
		</div>
	</div><!-- /.form-row clearfix -->

	<div class="form-row clearfix">
		<label class="form-label">&nbsp;</label>
		<div class="form-control">
			<p>
				<button type="submit">Cập nhật</button>
				<button type="reset">Nhập lại</button>
			</p>
			<p>
				Các trường có dấu <span class="required">*</span> là các trường bắt buộc cần nhập.
			</p>
		</div>
	</div><!-- /.form-row clearfix -->
</form>
<?php include 'libs/includes/admin/footer.inc.php'; ?>
