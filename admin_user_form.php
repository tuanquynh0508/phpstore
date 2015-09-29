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

//Kiểm tra xem có phải là super admin không
if(getUserAttrSession('is_admin') != 1) {
	header("Location: admin.php");
	exit;
}

//Tạo các đối tượng cần dùng
$oFlashMessage = new FlashMessage();
$oDBAccess = new DBAccess();

//Khai báo tiêu đề và module cho page
$pageAliasName = 'user';
$pageTitle = 'Quản lý Người dùng';

$isAddNew = true;
$id = 0;

//Khởi tạo đối tượng đầu tiên cho form, các trường của đối tượng là các trường của form
$record = new stdClass();
$record->username = '';
$record->passwd = '';
$record->fullname = '';
$record->email = '';
$record->is_admin = 0;
$record->is_active = 1;

//Kiểm tra xem id có tồn tại trên URL hay không, nếu tồn tại có nghĩa là form đang
//ở trạng thái cập nhật. Còn không thì là trạng thái thêm mới
if(isset($_GET['id'])) {
	$isAddNew = false;
	$id = $_GET['id'];
	$record = $oDBAccess->findOneById('user', $id);
}

//Khai báo mảng danh sách kiểm tra
$validates = array(
	array('type'=>'required', 'field'=>'username', 'message'=>'Cần nhập Tài khoản'),
	array('type'=>'required', 'field'=>'passwd', 'message'=>'Cần nhập Mật khẩu'),
	array('type'=>'required', 'field'=>'fullname', 'message'=>'Cần nhập Họ và tên'),
	array('type'=>'required', 'field'=>'email', 'message'=>'Cần nhập Email'),
	array('type'=>'length', 'field'=>'username', 'min'=>4, 'max'=>20, 'message'=>'Độ dài Tài khoản tối thiểu là 4, lớn nhất là 20 ký tự'),
	array('type'=>'length', 'field'=>'passwd', 'min'=>6, 'max'=>20, 'message'=>'Độ dài Mật khẩu tối thiểu là 6, lớn nhất là 20 ký tự'),
	array('type'=>'length', 'field'=>'fullname', 'min'=>5, 'max'=>25, 'message'=>'Độ dài Tài khoản tối thiểu là 5, lớn nhất là 25 ký tự'),
	array('type'=>'email', 'field'=>'email', 'message'=>'Sai định dạng Email'),
	array('type'=>'unique','field'=>'username','table'=>'user', 'message'=>'Tài khoản này đã tồn tại trong hệ thống'),
	array('type'=>'unique','field'=>'email','table'=>'user', 'message'=>'Email này đã tồn tại trong hệ thống'),
);
$oValidator = new Validator($validates, $oDBAccess);

//Xử lý khi có một POST form từ client lên
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$attributes = $_POST;

	if(!isset($attributes['is_admin'])) {
		$attributes['is_admin'] = 0;
	}

	if(!isset($attributes['is_active'])) {
		$attributes['is_active'] = 0;
	}

	//Truyền lại giá trị cho đối tượng form
	foreach($attributes as $key => $value){
		$record->$key = $value;
	}

	if(trim($attributes['passwd']) == '' && $isAddNew === false) {
		unset($attributes['passwd']);
	}

	//Đẩy giá trị vào cho đối tượng kiểm tra
	$oValidator->bindData($attributes);

	//Nếu việc kiểm tra không có lỗi thì thực hiện ghi hoặc cập nhật dữ liệu vào database
	if($oValidator->validate()) {
		if(trim($attributes['passwd']) != '') {
			$attributes['passwd'] = generateUserPassword($attributes['username'], $attributes['passwd']);
		} else {
			unset($attributes['passwd']);
		}

		if($isAddNew) {
			//Trường hợp thêm mới
			$attributes['created_at'] = date('Y-m-d H:i:s');
			$record = $oDBAccess->save('user', $attributes);
			//Ghi flash message
			$oFlashMessage->setFlashMessage('success', 'Thêm mới bản ghi thành công');
		} else {
			//Trường hợp cập nhật
			$attributes['updated_at'] = date('Y-m-d H:i:s');
			$record = $oDBAccess->save('user', $attributes, 'id');
			//Ghi flash message
			$oFlashMessage->setFlashMessage('success', 'Cập nhật bản ghi thành công');
		}
		header("Location: admin_{$pageAliasName}_form.php?id={$record->id}");
		exit;
	}
}
?>

<?php include 'libs/includes/admin/header.inc.php'; ?>
<h2 id="pageTitle"><?= $pageTitle ?> - <?= ($isAddNew)?'Thêm mới':'Cập nhật' ?></h2>

<?php include "libs/includes/admin/flash_message.inc.php"; ?>

<form action="" method="POST">
	<?php if(!$isAddNew): ?>
	<input type="hidden" name="id" value="<?= $id ?>"/>
	<?php endif; ?>

	<div class="form-row clearfix">
		<label class="form-label">Tài khoản <span class="required">*</span>:</label>
		<div class="form-control">
			<input type="text" name="username" value="<?= $record->username ?>" class="input-md <?= $oValidator->checkError('username')?'invalid':'' ?>"/>
			<?= $oValidator->fieldError('username') ?>
		</div>
	</div><!-- /.form-row clearfix -->

	<div class="form-row clearfix">
		<label class="form-label">Mật khẩu<?php if($isAddNew == true): ?> <span class="required">*</span><?php endif; ?>:</label>
		<div class="form-control">
			<input type="password" name="passwd" value="" class="input-md <?= $oValidator->checkError('passwd')?'invalid':'' ?>"/>
			<?= $oValidator->fieldError('passwd') ?>
			<?php if($isAddNew == false): ?><span class="help-block">Để trống mật khẩu, nếu bạn không muốn thay đổi</span><?php endif; ?>
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
		<label class="form-label">Quyền:</label>
		<div class="form-control">
			<label><input type="checkbox" name="is_admin" value="1" <?= ($record->is_admin==1)?'checked="checked"':'' ?>/> Quản trị</label>
		</div>
	</div><!-- /.form-row clearfix -->

	<div class="form-row clearfix">
		<label class="form-label">Trạng thái:</label>
		<div class="form-control">
			<label><input type="checkbox" name="is_active" value="1" <?= ($record->is_active==1)?'checked="checked"':'' ?>/> Hoạt động</label>
		</div>
	</div><!-- /.form-row clearfix -->

	<?php if(!$isAddNew): ?>
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
	<?php endif; ?>

	<div class="form-row clearfix">
		<label class="form-label">&nbsp;</label>
		<div class="form-control">
			<p>
				<button type="submit"><?= ($isAddNew)?'Thêm mới':'Cập nhật' ?></button>
				<button type="reset">Nhập lại</button>
				<?php if(!$isAddNew): ?>
				- <a href="admin_<?= $pageAliasName ?>_form.php">Thêm mới</a>
				<?php endif; ?>
				- <a href="admin_<?= $pageAliasName ?>.php">Danh sách</a>
			</p>
			<p>
				Các trường có dấu <span class="required">*</span> là các trường bắt buộc cần nhập.
			</p>
		</div>
	</div><!-- /.form-row clearfix -->
</form>
<?php include 'libs/includes/admin/footer.inc.php'; ?>
